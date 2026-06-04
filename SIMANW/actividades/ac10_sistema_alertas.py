import json
import os
import time
from datetime import datetime
from collections import defaultdict
from difflib import SequenceMatcher


class SistemaAlertas:
    """
    AC-10: Sistema de alertas por consulta guardada.
    Permite definir consultas permanentes y registrar alertas
    cuando entran noticias nuevas que las satisfacen.
    """

    def __init__(self, archivo_alertas=None):
        if archivo_alertas is None:
            archivo_alertas = os.path.join(
                os.path.dirname(__file__), '..', 'alertas.json'
            )
        self.archivo_alertas = archivo_alertas
        self.consultas = {}
        self.historial = []
        self.noticias_indexadas = set()
        self._alertas_emitidas = set()
        self._cargar()

    def _cargar(self):
        """Restaura estado desde archivo JSON."""
        try:
            with open(self.archivo_alertas, encoding='utf-8') as f:
                data = json.load(f)
            self.consultas = data.get('consultas', {})
            self.historial = data.get('historial', [])
            self._alertas_emitidas = {
                (a['consulta'], a['url']) for a in self.historial
                if 'url' in a
            }
        except (FileNotFoundError, json.JSONDecodeError):
            pass

    def guardar_estado(self):
        """Persiste el estado completo a JSON."""
        with open(self.archivo_alertas, 'w', encoding='utf-8') as f:
            json.dump({
                'consultas': self.consultas,
                'historial': self.historial,
            }, f, ensure_ascii=False, indent=2)

    def agregar_consulta(self, nombre, terminos):
        """Guarda una consulta permanente con nombre único."""
        if nombre in self.consultas:
            return False, f"La consulta '{nombre}' ya existe"
        self.consultas[nombre] = {
            'terminos': terminos,
            'fecha_creacion': datetime.now().strftime('%Y-%m-%d %H:%M:%S'),
            'ultima_activacion': None,
        }
        self.guardar_estado()
        return True, f"Consulta '{nombre}' guardada"

    def eliminar_consulta(self, nombre):
        if nombre in self.consultas:
            del self.consultas[nombre]
            self.guardar_estado()
            return True
        return False

    def _texto_coincide(self, texto, terminos):
        """Verifica si TODOS los términos aparecen en el texto."""
        tl = texto.lower()
        return all(t.lower() in tl for t in terminos.split())

    def evaluar_noticia(self, noticia):
        """Evalúa una noticia contra todas las consultas guardadas.
        Retorna lista de consultas que se activan."""
        texto = f"{noticia.get('titulo', '')} {noticia.get('resumen', '')} {noticia.get('cuerpo', '')}"
        url = noticia.get('url', '')
        activadas = []
        for nombre, q in self.consultas.items():
            par = (nombre, url)
            if par in self._alertas_emitidas:
                continue
            if self._texto_coincide(texto, q['terminos']):
                alerta = {
                    'consulta': nombre,
                    'terminos': q['terminos'],
                    'titulo': noticia.get('titulo', ''),
                    'url': url,
                    'timestamp': datetime.now().strftime('%Y-%m-%d %H:%M:%S'),
                }
                self.historial.append(alerta)
                self._alertas_emitidas.add(par)
                q['ultima_activacion'] = alerta['timestamp']
                activadas.append(alerta)
        return activadas

    def procesar_lote(self, noticias_nuevas):
        """Procesa un lote de noticias nuevas y retorna las alertas generadas."""
        alertas_generadas = []
        for n in noticias_nuevas:
            url = n.get('url', '')
            if url in self.noticias_indexadas:
                continue
            self.noticias_indexadas.add(url)
            activadas = self.evaluar_noticia(n)
            alertas_generadas.extend(activadas)
        if alertas_generadas:
            self.guardar_estado()
        return alertas_generadas

    def resumen_consultas(self):
        """Muestra el estado de todas las consultas guardadas."""
        if not self.consultas:
            return "No hay consultas guardadas."
        lineas = []
        for nombre, q in sorted(self.consultas.items()):
            ultima = q['ultima_activacion'] or 'nunca'
            lineas.append(
                f"  [{nombre}] '{q['terminos']}' "
                f"(creada: {q['fecha_creacion']}, "
                f"última activación: {ultima})"
            )
        return '\n'.join(lineas)

    def historial_reciente(self, n=10):
        """Últimas n alertas generadas."""
        if not self.historial:
            return "Sin alertas registradas."
        lineas = []
        for a in reversed(self.historial[-n:]):
            lineas.append(
                f"  [{a['timestamp']}] '{a['consulta']}' -> "
                f"'{a['titulo'][:55]}'"
            )
        return '\n'.join(lineas)

    def total_alertas(self):
        return len(self.historial)

    def documentar_prevencion_duplicados(self):
        """Explica cómo se evitan alertas duplicadas."""
        return (
            "Prevención de alertas duplicadas:\n"
            "  El sistema mantiene un conjunto en memoria (_alertas_emitidas) "
            "con pares (consulta, url) que ya han generado una alerta.\n"
            "  Antes de emitir una nueva alerta, se verifica que el par "
            "(nombre_consulta, url_noticia) no exista en ese conjunto.\n"
            "  Además, al procesar lotes, las URLs ya indexadas "
            "(noticias_indexadas) se ignoran, evitando reprocesar.\n"
            "  El estado se persiste en alertas.json, por lo que "
            "la prevención sobrevive a reinicios de la aplicación.\n"
            "  Esto garantiza que cada combinación consulta-noticia "
            "genere exactamente una alerta en todo el ciclo de vida."
        )


# ============================================================================
# Noticias de ejemplo
# ============================================================================
NOTICIAS_INICIALES = [
    {"titulo": "Nueva inteligencia artificial generativa revoluciona el mercado",
     "resumen": "Los modelos de machine learning y deep learning avanzan",
     "url": "https://ejemplo.com/ia-gen"},
    {"titulo": "Mercados bursátiles alcanzan nuevos máximos históricos",
     "resumen": "La bolsa de valores rompe récord en inversión extranjera",
     "url": "https://ejemplo.com/mercados"},
    {"titulo": "Investigación científica descubre nueva terapia genética",
     "resumen": "El descubrimiento médico abre puertas a tratamiento de enfermedades",
     "url": "https://ejemplo.com/genetica"},
    {"titulo": "Gobierno anuncia reforma fiscal y nueva ley de mercado",
     "resumen": "El senado aprobó la reforma en sesión extraordinaria",
     "url": "https://ejemplo.com/reforma"},
    {"titulo": "Startup de inteligencia artificial levanta inversión récord",
     "resumen": "Machine learning aplicado a finanzas atrae capital de riesgo",
     "url": "https://ejemplo.com/startup-ia"},
]

NOTICIAS_NUEVAS = [
    {"titulo": "OpenAI presenta modelo con inteligencia artificial avanzada",
     "resumen": "El deep learning alcanza nuevo hito en razonamiento automático",
     "url": "https://ejemplo.com/openai-nuevo"},
    {"titulo": "Inflación en México cae por política del banco central",
     "resumen": "El mercado reacciona positivo a la noticia económica",
     "url": "https://ejemplo.com/inflacion"},
    {"titulo": "Científicos hacen descubrimiento: nuevo planeta en nuestra galaxia",
     "resumen": "La investigación astronómica revela hallazgo sin precedentes",
     "url": "https://ejemplo.com/exoplaneta"},
    {"titulo": "Senado aprueba ley de inteligencia artificial y machine learning",
     "resumen": "La nueva regulación del gobierno busca equilibrio ético",
     "url": "https://ejemplo.com/ley-ia"},
    {"titulo": "Bolsa mexicana cae por temor a reforma de mercado",
     "resumen": "Inversión extranjera se contrae ante incertidumbre económica",
     "url": "https://ejemplo.com/bolsa-caida"},
    {"titulo": "Deep learning aplicado a diagnóstico médico temprano",
     "resumen": "Algoritmo de machine learning detecta enfermedades con precisión",
     "url": "https://ejemplo.com/deep-learning-medicina"},
]


# ============================================================================
# Demostración
# ============================================================================
if __name__ == '__main__':
    import tempfile

    print("=" * 65)
    print("  AC-10: Sistema de Alertas por Consulta Guardada")
    print("=" * 65)

    tmp = os.path.join(tempfile.gettempdir(), 'simanw_alertas_test.json')
    sistema = SistemaAlertas(tmp)

    consultas_ejemplo = [
        ('IA y ML', 'inteligencia artificial machine learning deep'),
        ('Mercados', 'mercado bolsa acciones inversión'),
        ('Ciencia', 'científico investigación descubrimiento'),
        ('Política regulatoria', 'gobierno ley reforma senado'),
        ('Salud', 'terapia genética médico enfermedad'),
    ]

    print(f"\n  --- Guardando {len(consultas_ejemplo)} consultas ---")
    for nombre, terminos in consultas_ejemplo:
        ok, msg = sistema.agregar_consulta(nombre, terminos)
        print(f"  {msg}")

    print(f"\n  --- Consultas registradas ---")
    print(sistema.resumen_consultas())

    # Primera ejecución: sin noticias nuevas
    print(f"\n  --- Ejecución 1: SIN noticias nuevas ---")
    alertas = sistema.procesar_lote([])
    print(f"  Alertas generadas: {len(alertas)}")

    # Indexar noticias iniciales
    print(f"\n  --- Indexando {len(NOTICIAS_INICIALES)} noticias iniciales ---")
    alertas = sistema.procesar_lote(NOTICIAS_INICIALES)
    print(f"  Alertas generadas: {len(alertas)}")
    for a in alertas:
        print(f"    [{a['consulta']}] {a['titulo'][:55]}")

    # Segunda ejecución: noticias nuevas
    print(f"\n  --- Ejecución 2: {len(NOTICIAS_NUEVAS)} noticias nuevas ---")
    alertas = sistema.procesar_lote(NOTICIAS_NUEVAS)
    print(f"  Alertas generadas: {len(alertas)}")
    for a in alertas:
        print(f"    [{a['consulta']}] {a['titulo'][:55]}")

    # Reprocesar mismo lote (debe dar 0 alertas duplicadas)
    print(f"\n  --- Reprocesando mismas noticias (verificar 0 duplicados) ---")
    alertas = sistema.procesar_lote(NOTICIAS_NUEVAS)
    print(f"  Alertas generadas: {len(alertas)} (deben ser 0)")

    # Historial
    print(f"\n  --- Historial de alertas ({sistema.total_alertas()} total) ---")
    print(sistema.historial_reciente(8))

    # Prevención de duplicados
    print(f"\n  --- Documentación: Prevención de duplicados ---")
    print(f"  {sistema.documentar_prevencion_duplicados()}")

    print(f"\n  Estado persistido en: {sistema.archivo_alertas}")
