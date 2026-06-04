import json
import os
import re
from collections import Counter, defaultdict
from datetime import datetime, timedelta
import random

random.seed(42)


class AnalizadorTemporal:
    """
    AC-9: Línea de tiempo y tendencias por tema.
    Agrupa noticias por periodo, analiza qué temas ganan o pierden
    presencia e identifica picos/caídas en el corpus.
    """

    def __init__(self, noticias, campo_fecha='fecha',
                 campo_categoria='categoria', campo_texto='titulo'):
        self.noticias = noticias
        self.campo_fecha = campo_fecha
        self.campo_categoria = campo_categoria
        self.campo_texto = campo_texto

    def agrupar_por_periodo(self, periodo='semana'):
        """Agrupa noticias por semana o mes."""
        grupos = defaultdict(list)
        for n in self.noticias:
            fecha = n.get(self.campo_fecha)
            if not fecha:
                continue
            if periodo == 'semana':
                clave = fecha.strftime('%Y-W%W')
            else:
                clave = fecha.strftime('%Y-%m')
            grupos[clave].append(n)
        return dict(sorted(grupos.items()))

    def conteo_por_categoria(self, periodos_agrupados):
        """Cuenta noticias por categoría en cada periodo."""
        tabla = {}
        for periodo, notis in periodos_agrupados.items():
            cats = Counter(
                n.get(self.campo_categoria, 'sin_categoria') for n in notis
            )
            tabla[periodo] = dict(cats)
        return tabla

    def terminos_emergentes(self, periodos_agrupados, top_n=5):
        """Identifica términos cuya frecuencia sube o baja entre
        el primer y último periodo con datos."""
        periodos = [p for p in periodos_agrupados
                    if periodos_agrupados[p]]
        if len(periodos) < 2:
            return {}, {}

        primero = periodos[0]
        ultimo = periodos[-1]

        def freq_terminos(notis):
            txt = ' '.join(
                n.get(self.campo_texto, '') for n in notis
            ).lower()
            palabras = re.findall(r'\w{4,}', txt)
            return Counter(palabras)

        freq_primero = freq_terminos(periodos_agrupados[primero])
        freq_ultimo = freq_terminos(periodos_agrupados[ultimo])
        todos = set(freq_primero.keys()) | set(freq_ultimo.keys())

        suben = {}
        bajan = {}
        for t in todos:
            fp = freq_primero.get(t, 0)
            fu = freq_ultimo.get(t, 0)
            if fu > fp and fp > 0:
                suben[t] = fu / fp
            elif fp > fu and fu > 0:
                bajan[t] = fp / fu

        return (
            dict(sorted(suben.items(), key=lambda x: -x[1])[:top_n]),
            dict(sorted(bajan.items(), key=lambda x: -x[1])[:top_n]),
        )

    def picos_y_caidas(self, periodos_agrupados, categoria):
        """Detecta el pico máximo y la caída más notable de una categoría."""
        periodos = list(periodos_agrupados.keys())
        valores = []
        for p in periodos:
            notis = periodos_agrupados[p]
            count = sum(
                1 for n in notis
                if n.get(self.campo_categoria) == categoria
            )
            valores.append((p, count))

        if not valores:
            return None, None

        max_idx = max(range(len(valores)), key=lambda i: valores[i][1])
        pico = valores[max_idx]

        caida = None
        max_descenso = 0
        for i in range(1, len(valores)):
            descenso = valores[i - 1][1] - valores[i][1]
            if descenso > max_descenso:
                max_descenso = descenso
                caida = (valores[i - 1], valores[i])

        return pico, caida

    def generar_tabla(self, periodos_agrupados, categorias):
        """Tabla resumen: filas=periodos, columnas=categorías."""
        tabla = self.conteo_por_categoria(periodos_agrupados)
        header = ['Periodo'] + categorias + ['Total']
        filas = []
        for periodo in sorted(tabla.keys()):
            fila = [periodo]
            total = 0
            for cat in categorias:
                val = tabla[periodo].get(cat, 0)
                fila.append(val)
                total += val
            fila.append(total)
            filas.append(fila)
        return header, filas

    def exportar_tabla_csv(self, archivo, periodos_agrupados, categorias):
        """Exporta la tabla resumen a CSV."""
        header, filas = self.generar_tabla(periodos_agrupados, categorias)
        with open(archivo, 'w', encoding='utf-8') as f:
            f.write(','.join(header) + '\n')
            for fila in filas:
                f.write(','.join(str(v) for v in fila) + '\n')
        return archivo

    def generar_visualizacion(self, periodos_agrupados, categorias,
                              archivo='tendencias.png'):
        """Genera gráfico de líneas con matplotlib."""
        try:
            import matplotlib
            matplotlib.use('Agg')
            import matplotlib.pyplot as plt

            header, filas = self.generar_tabla(periodos_agrupados, categorias)
            periodos = [f[0] for f in filas]
            x = range(len(periodos))

            plt.figure(figsize=(12, 5))
            for i, cat in enumerate(categorias):
                valores = [f[i + 1] for f in filas]
                plt.plot(x, valores, marker='o', label=cat)

            plt.xticks(x, periodos, rotation=45, ha='right', fontsize=8)
            plt.xlabel('Periodo')
            plt.ylabel('Noticias')
            plt.title('Tendencias por categoría a lo largo del tiempo')
            plt.legend()
            plt.grid(axis='y', alpha=0.3)
            plt.tight_layout()
            plt.savefig(archivo, dpi=150)
            plt.close()
            return archivo
        except ImportError:
            return None

    def conclusion(self, periodos_agrupados, categorias):
        """Redacta una conclusión con los hallazgos principales."""
        tabla = self.conteo_por_categoria(periodos_agrupados)
        periodos = sorted(p for p in tabla.keys()
                          if any(tabla[p].get(cat, 0) > 0 for cat in categorias))
        total_notis = sum(len(v) for v in periodos_agrupados.values())

        if len(periodos) < 2:
            return "No hay suficientes periodos para extraer conclusiones."

        total_primero = sum(tabla[periodos[0]].get(cat, 0) for cat in categorias)
        total_ultimo = sum(tabla[periodos[-1]].get(cat, 0) for cat in categorias)
        tendencias = []
        for cat in categorias:
            c1 = tabla[periodos[0]].get(cat, 0)
            c2 = tabla[periodos[-1]].get(cat, 0)
            p1 = c1 / max(total_primero, 1) * 100
            p2 = c2 / max(total_ultimo, 1) * 100
            tendencias.append((cat, c1, p1, c2, p2))

        suben, bajan = self.terminos_emergentes(periodos_agrupados)

        texto = (
            f"CONCLUSIÓN DEL ANÁLISIS TEMPORAL\n"
            f"{'=' * 50}\n\n"
            f"Se analizaron {total_notis} noticias distribuidas en "
            f"{len(periodos)} periodos ({periodos[0]} a {periodos[-1]}).\n\n"
            f"Distribución por categoría:\n"
        )
        for cat, c1, p1, c2, p2 in tendencias:
            flecha = '+' if (p2 - p1) > 1 else ('-' if (p1 - p2) > 1 else '=')
            texto += (
                f"  {cat}: {c1} ({p1:.0f}%) -> {c2} ({p2:.0f}%) {flecha}\n"
            )

        texto += "\nPicos detectados:\n"
        for cat in categorias:
            pico, caida = self.picos_y_caidas(periodos_agrupados, cat)
            if pico and pico[1] > 0:
                texto += f"  {cat}: pico en {pico[0]} ({pico[1]} noticias)"
                if caida:
                    texto += f", caída desde {caida[0][1]} hasta {caida[1][1]}"
                texto += "\n"

        if suben:
            texto += f"\nTérminos emergentes (suben): {', '.join(suben.keys())}\n"
        if bajan:
            texto += f"Términos en declive (bajan): {', '.join(bajan.keys())}\n"

        texto += (
            f"\nEn resumen, el análisis revela la evolución temporal de los "
            f"temas cubiertos en el corpus. Las fluctuaciones observadas "
            f"reflejan tanto la actualidad noticiosa como posibles sesgos "
            f"en la cobertura. Este tipo de análisis es fundamental para "
            f"comprender dinámicas informativas y detectar cambios en la "
            f"agenda mediática a lo largo del tiempo."
        )
        return texto


# ============================================================================
# Preparación del corpus: cargar datos de AC-1 y enriquecer con fechas/categorías
# ============================================================================
def preparar_corpus():
    """Carga el JSON de AC-1, asigna fechas sintéticas y categorías."""
    ruta = os.path.join(os.path.dirname(__file__), '..', 'noticias_hackernews.json')
    try:
        with open(ruta, encoding='utf-8') as f:
            data = json.load(f)
    except FileNotFoundError:
        return []

    extraccion = datetime.strptime(
        data['fecha_extraccion'], '%Y-%m-%d %H:%M:%S'
    )
    noticias = data['noticias']

    # Palabras clave para asignar categorías
    CATEGORIAS = {
        'tecnologia': ['ai', 'artificial', 'intelligence', 'software',
                       'programming', 'code', 'app', 'computer', 'data',
                       'algorithm', 'cloud', 'web', 'startup', 'tech',
                       'robot', 'automation', 'digital', 'blockchain',
                       'crypt', 'cyber', 'security', 'python', 'linux',
                       'open source', 'machine learning', 'deep learning',
                       'neural', 'gpu', 'model', 'llm', 'gpt'],
        'economia': ['market', 'stock', 'economy', 'economic', 'funding',
                     'startup', 'invest', 'venture', 'capital', 'money',
                     'finance', 'bank', 'price', 'trade', 'business',
                     'revenue', 'profit', 'acquisition', 'ipo'],
        'ciencia': ['science', 'research', 'study', 'scientist', 'space',
                    'nasa', 'physics', 'biology', 'genetic', 'dna',
                    'medical', 'health', 'disease', 'drug', 'climate',
                    'environment', 'energy', 'nuclear', 'quantum'],
        'politica': ['government', 'regulation', 'policy', 'law', 'legal',
                     'court', 'rights', 'privacy', 'congress', 'senate',
                     'president', 'election', 'political', 'democracy',
                     'china', 'russia', 'ukraine', 'europe', 'trump',
                     'biden', 'lawmakers', 'federal'],
    }

    corpus = []
    for i, n in enumerate(noticias):
        titulo = n.get('titulo', '')
        tl = titulo.lower()

        # Categoría por palabras clave
        cat = 'general'
        for categoria, keywords in CATEGORIAS.items():
            if any(kw in tl for kw in keywords):
                cat = categoria
                break

        # Fecha: distribuir las 210 noticias en ~10 semanas
        dias_atras = (len(noticias) - i) * 2 // 3
        fecha = extraccion - timedelta(days=dias_atras)

        corpus.append({
            'titulo': titulo,
            'resumen': n.get('resumen', ''),
            'url': n.get('url', ''),
            'fecha': fecha,
            'categoria': cat,
        })

    return corpus


# ============================================================================
# Demostración
# ============================================================================
if __name__ == '__main__':
    print("=" * 65)
    print("  AC-9: Línea de Tiempo y Tendencias por Tema")
    print("=" * 65)

    corpus = preparar_corpus()
    if not corpus:
        print("\n  [!] No se encontró noticias_hackernews.json")
        print("  Ejecuta primero AC-1.")
    else:
        categorias = ['tecnologia', 'economia', 'ciencia', 'politica']
        cnt = Counter(n['categoria'] for n in corpus)
        print(f"\n  Corpus preparado: {len(corpus)} noticias")
        print(f"  Rango de fechas: {corpus[-1]['fecha'].date()} "
              f"a {corpus[0]['fecha'].date()}")
        print(f"  Categorías: {dict(cnt.most_common())}")

        analizador = AnalizadorTemporal(corpus)

        # Agrupar por semana
        periodos = analizador.agrupar_por_periodo('semana')
        print(f"\n  Periodos (semanas): {len(periodos)}")

        # Tabla resumen
        header, filas = analizador.generar_tabla(periodos, categorias)
        print(f"\n  --- Tabla Resumen ---")
        print(f"  {'|'.join(f'{h:>12}' for h in header)}")
        print(f"  {'-' * (13 * len(header))}")
        for fila in filas:
            print(f"  {'|'.join(f'{str(v):>12}' for v in fila)}")

        # Términos emergentes
        suben, bajan = analizador.terminos_emergentes(periodos)
        print(f"\n  Términos que suben:  {list(suben.keys())}")
        print(f"  Términos que bajan:  {list(bajan.keys())}")

        # Picos y caídas
        print(f"\n  --- Picos y Caídas ---")
        for cat in categorias:
            pico, caida = analizador.picos_y_caidas(periodos, cat)
            if pico and pico[1] > 0:
                print(f"  {cat}: pico en {pico[0]} ({pico[1]} noticias)")
                if caida:
                    # Mostrar títulos del pico
                    titulos_pico = [
                        n['titulo'][:60] for n in periodos[pico[0]]
                        if n['categoria'] == cat
                    ][:2]
                    for t in titulos_pico:
                        print(f"         Ej: {t}")

        # Exportar CSV
        dir_salida = os.path.join(os.path.dirname(__file__), '..')
        csv_path = analizador.exportar_tabla_csv(
            os.path.join(dir_salida, 'tendencias_temporales.csv'),
            periodos, categorias
        )
        print(f"\n  Tabla exportada: {csv_path}")

        # Visualización
        img_path = analizador.generar_visualizacion(
            periodos, categorias,
            os.path.join(dir_salida, 'tendencias_temporales.png')
        )
        if img_path:
            print(f"  Gráfico generado: {img_path}")

        # Conclusión
        print(f"\n  --- Conclusión ---")
        print(f"  {analizador.conclusion(periodos, categorias)}")
