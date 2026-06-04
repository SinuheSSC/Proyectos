import json
import os
import re
from datetime import datetime
from collections import Counter


class ValidadorCorpus:
    """
    AC-8: Control de calidad del corpus rastreado.
    Valida registros, detecta duplicados y genera un corpus depurado
    utilizable en las fases posteriores del pipeline.
    """

    def __init__(self, reglas=None):
        self.reglas = reglas or self._reglas_por_defecto()
        self.rechazados = []
        self.aceptados = []
        self.duplicados_exactos = []
        self.duplicados_cercanos = []

    @staticmethod
    def _reglas_por_defecto():
        return {
            'campos_obligatorios': ['titulo', 'url', 'fuente'],
            'titulo_min_length': 5,
            'titulo_max_length': 300,
            'cuerpo_min_length': 0,
            'url_debe_ser_absoluta': True,
            'url_pattern': r'^https?://.+',
            'max_duplicados_cercanos': 0.85,
        }

    def validar_registro(self, noticia, idx):
        """Valida un registro contra las reglas definidas. Retorna (valido, [errores])."""
        errores = []

        for campo in self.reglas['campos_obligatorios']:
            if campo not in noticia or not noticia[campo]:
                errores.append(f"Campo obligatorio '{campo}' ausente o vacío")

        titulo = (noticia.get('titulo') or '').strip()
        if titulo:
            if len(titulo) < self.reglas['titulo_min_length']:
                errores.append(f"Título demasiado corto "
                               f"({len(titulo)} < {self.reglas['titulo_min_length']})")
            if len(titulo) > self.reglas['titulo_max_length']:
                errores.append(f"Título demasiado largo "
                               f"({len(titulo)} > {self.reglas['titulo_max_length']})")

        cuerpo = noticia.get('resumen') or noticia.get('cuerpo') or ''
        if len(cuerpo.strip()) < self.reglas['cuerpo_min_length']:
            errores.append(f"Cuerpo/resumen demasiado corto "
                           f"({len(cuerpo.strip())} < {self.reglas['cuerpo_min_length']})")

        url = noticia.get('url', '')
        if url and self.reglas['url_debe_ser_absoluta']:
            if not re.match(self.reglas['url_pattern'], url):
                errores.append(f"URL no es absoluta o formato inválido: {url[:60]}")

        return len(errores) == 0, errores

    def detectar_duplicados(self, noticias):
        """Identifica duplicados exactos y cercanos en la lista."""
        exactos = []
        cercanos = []
        vistos_url = {}
        vistos_titulo = {}

        for i, n in enumerate(noticias):
            url = n.get('url', '').strip().lower()
            titulo = (n.get('titulo') or '').strip().lower()

            if url:
                if url in vistos_url:
                    exactos.append((vistos_url[url], i, 'URL duplicada'))
                else:
                    vistos_url[url] = i

            if titulo:
                for idx_prev, prev in enumerate(noticias[:i]):
                    tit_prev = (prev.get('titulo') or '').strip().lower()
                    if titulo == tit_prev and url != prev.get('url', '').strip().lower():
                        cercanos.append((idx_prev, i, 'Mismo título, diferente URL'))
                        break
                else:
                    vistos_titulo[titulo] = i

        return exactos, cercanos

    def validar_corpus(self, noticias, fuente_nombre=""):
        """Ejecuta la validación completa sobre el corpus."""
        self.rechazados = []
        self.aceptados = []
        self.duplicados_exactos = []
        self.duplicados_cercanos = []
        self._indices_aceptados = []
        self._indices_rechazar_dup = set()

        for i, n in enumerate(noticias):
            valido, errores = self.validar_registro(n, i)
            if valido:
                self.aceptados.append(n)
                self._indices_aceptados.append(i)
            else:
                self.rechazados.append({
                    'indice': i,
                    'titulo': n.get('titulo', '(sin título)')[:60],
                    'motivos': errores,
                })

        exactos, cercanos = self.detectar_duplicados(noticias)
        self.duplicados_exactos = exactos
        self.duplicados_cercanos = cercanos

        for a, b, _ in exactos:
            self._indices_rechazar_dup.add(b)

        return self.generar_informe(fuente_nombre)

    def generar_informe(self, fuente_nombre=""):
        """Genera el reporte automático de calidad del corpus."""
        total = len(self.aceptados) + len(self.rechazados)
        duplicados_exactos_ids = set()
        for a, b, _ in self.duplicados_exactos:
            duplicados_exactos_ids.update([a, b])
        duplicados_cercanos_ids = set()
        for a, b, _ in self.duplicados_cercanos:
            duplicados_cercanos_ids.update([a, b])

        informe = {
            'fuente': fuente_nombre,
            'fecha_analisis': datetime.now().strftime('%Y-%m-%d %H:%M:%S'),
            'resumen': {
                'total_registros': total,
                'registros_aceptados': len(self.aceptados),
                'registros_rechazados': len(self.rechazados),
                'duplicados_exactos': len(self.duplicados_exactos),
                'duplicados_cercanos': len(self.duplicados_cercanos),
                'tasa_aceptacion': round(len(self.aceptados) / max(total, 1) * 100, 1),
            },
            'reglas_aplicadas': self.reglas,
            'registros_rechazados': self.rechazados,
            'duplicados_exactos': [
                {'indice_a': a, 'indice_b': b, 'motivo': m}
                for a, b, m in self.duplicados_exactos
            ],
            'duplicados_cercanos': [
                {'indice_a': a, 'indice_b': b, 'motivo': m}
                for a, b, m in self.duplicados_cercanos
            ],
            'estadisticas_campos': self._estadisticas_campos(),
        }
        return informe

    def _estadisticas_campos(self):
        """Analiza cobertura de campos en el corpus (solo datos reales)."""
        campos_noticia = {'titulo', 'url', 'fuente', 'resumen', 'cuerpo',
                          'fecha', 'autor', 'categoria'}
        campos = Counter()
        for n in self.aceptados:
            for k in n:
                if k in campos_noticia and n[k]:
                    campos[k] += 1
        total = len(self.aceptados)
        return {k: {'completos': v, 'cobertura_pct': round(v / max(total, 1) * 100, 1)}
                for k, v in sorted(campos.items())}

    def corpus_depurado(self):
        """Retorna solo los registros válidos, descartando duplicados."""
        return [
            n for i, n in zip(self._indices_aceptados, self.aceptados)
            if i not in self._indices_rechazar_dup
        ]

    def parrafo_explicativo(self, fuente_nombre=""):
        """Genera el párrafo de 200 palabras exigido por la actividad."""
        inf = self.generar_informe(fuente_nombre)['resumen']
        n_rechazo = inf['registros_rechazados']
        n_exactos = inf['duplicados_exactos']
        n_cercanos = inf['duplicados_cercanos']
        motivos = Counter()
        for r in self.rechazados:
            for m in r['motivos']:
                motivos[m] += 1
        razones = ', '.join(f"{m} ({c})" for m, c in motivos.most_common(3))

        parrafo = (
            f"Se analizaron {inf['total_registros']} registros del corpus "
            f"{'de ' + fuente_nombre if fuente_nombre else ''}."
            f" Fueron aceptados {inf['registros_aceptados']} "
            f"({inf['tasa_aceptacion']}%) y rechazados {n_rechazo}."
        )
        if n_exactos or n_cercanos:
            parrafo += (
                f" Se detectaron {n_exactos} duplicados exactos (misma URL)"
                f" y {n_cercanos} duplicados cercanos (mismo título)."
            )
        if n_rechazo:
            parrafo += f" Los rechazos se debieron a: {razones}."
        parrafo += (
            f" El corpus depurado final contiene {len(self.corpus_depurado())} "
            f"registros listos para las fases de procesamiento."
        )
        return parrafo

    def guardar_informe(self, archivo):
        """Guarda el informe completo en JSON."""
        informe = self.generar_informe()
        with open(archivo, 'w', encoding='utf-8') as f:
            json.dump(informe, f, ensure_ascii=False, indent=2)
        return archivo


# ============================================================================
# Carga de datos reales (AC-1) + registros adulterados para demostrar validación
# ============================================================================
def cargar_corpus_real(ruta=None):
    if ruta is None:
        ruta = os.path.join(os.path.dirname(__file__), '..', 'noticias_hackernews.json')
    """Carga el corpus generado por el rastreador de AC-1."""
    try:
        with open(ruta, encoding='utf-8') as f:
            data = json.load(f)
        return data['noticias'], data.get('fuente', 'desconocida')
    except FileNotFoundError:
        return [], ""


def inyectar_registros_invalidos(noticias):
    """Agrega registros con errores para demostrar la detección."""
    return noticias + [
        {"titulo": "", "url": "", "fuente": ""},
        {"titulo": "AB", "url": "no-es-url", "fuente": ""},
        {"titulo": "Normal" * 100, "url": "https://ejemplo.com/largo",
         "fuente": "test"},
        {"titulo": "Duplicado exacto",
         "url": noticias[3]["url"] if len(noticias) > 3 else "https://x.com",
         "fuente": "test"},
        {"titulo": noticias[4]["titulo"] if len(noticias) > 4 else "Test",
         "url": "https://otra-url-diferente.com",
         "fuente": "test"},
    ]


# ============================================================================
# Demostración
# ============================================================================
if __name__ == '__main__':
    print("=" * 65)
    print("  AC-8: Control de Calidad del Corpus Rastreado")
    print("=" * 65)

    corpus, fuente = cargar_corpus_real()
    if not corpus:
        print("\n  [!] No se encontró noticias_hackernews.json")
        print("  Ejecuta primero AC-1 para generar el corpus.")
    else:
        corpus_total = corpus + inyectar_registros_invalidos([])
        print(f"\n  Corpus original: {len(corpus)} noticias de {fuente}")
        print(f"  Registros de prueba inválidos añadidos: "
              f"{len(inyectar_registros_invalidos([]))}")

        corpus_a_validar = inyectar_registros_invalidos(corpus)
        print(f"  Total a validar: {len(corpus_a_validar)}")

        validador = ValidadorCorpus()
        informe = validador.validar_corpus(corpus_a_validar, fuente)

        r = informe['resumen']
        print(f"\n  --- Resumen de Validación ---")
        print(f"  Total registros:       {r['total_registros']}")
        print(f"  Aceptados:             {r['registros_aceptados']}")
        print(f"  Rechazados:            {r['registros_rechazados']}")
        print(f"  Duplicados exactos:    {r['duplicados_exactos']}")
        print(f"  Duplicados cercanos:   {r['duplicados_cercanos']}")
        print(f"  Tasa de aceptación:    {r['tasa_aceptacion']}%")

        if informe['registros_rechazados']:
            print(f"\n  --- Registros Rechazados ---")
            for rr in informe['registros_rechazados']:
                print(f"  [{rr['indice']}] {rr['titulo'][:50]}")
                for m in rr['motivos']:
                    print(f"       -> {m}")

        if informe['duplicados_exactos']:
            print(f"\n  --- Duplicados Exactos ---")
            for d in informe['duplicados_exactos']:
                print(f"  #{d['indice_a']} y #{d['indice_b']}: {d['motivo']}")

        if informe['duplicados_cercanos']:
            print(f"\n  --- Duplicados Cercanos ---")
            for d in informe['duplicados_cercanos']:
                print(f"  #{d['indice_a']} y #{d['indice_b']}: {d['motivo']}")

        print(f"\n  --- Cobertura de Campos ---")
        for campo, stats in informe['estadisticas_campos'].items():
            print(f"  {campo:<20} {stats['completos']:>5}/{r['total_registros']} "
                  f"({stats['cobertura_pct']}%)")

        print(f"\n  --- Corpus Depurado ---")
        depurado = validador.corpus_depurado()
        print(f"  {len(depurado)} registros listos para fases siguientes")

        print(f"\n  --- Párrafo Explicativo ---")
        print(f"  {validador.parrafo_explicativo(fuente)}")

        archivo_informe = "informe_calidad_corpus.json"
        validador.guardar_informe(archivo_informe)
        print(f"\n  Informe guardado en: {archivo_informe}")
