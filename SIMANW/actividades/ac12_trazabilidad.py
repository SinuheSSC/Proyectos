"""
AC-12: Trazabilidad y reproducibilidad del pipeline (Fases 1-7)
Manifiesto de ejecución, log estructurado, procedimiento de
reproducción y anexo de limitaciones conocidas.
"""

import json
import os
import platform
import subprocess
import sys
from datetime import datetime


class PipelineTracker:
    """
    Registra cada etapa del pipeline SIMANW y genera un manifiesto
    de ejecución que permite reproducir el proceso completo.
    """

    def __init__(self, proyecto_dir='.'):
        self.proyecto_dir = os.path.abspath(proyecto_dir)
        self.inicio = datetime.now()
        self.etapas = []
        self.version = self._detectar_version()
        self.config = {}

    def _detectar_version(self):
        """Intenta leer versión desde git o usa fecha como fallback."""
        try:
            r = subprocess.run(
                ['git', 'describe', '--always', '--dirty'],
                capture_output=True, text=True, cwd=self.proyecto_dir,
                timeout=5,
            )
            if r.returncode == 0:
                return r.stdout.strip()
        except Exception:
            pass
        return f"SIMANW-{datetime.now().strftime('%Y%m%d')}"

    def registrar_etapa(self, nombre, descripcion, archivos_entrada=None,
                        archivos_salida=None, metadatos=None):
        """Registra una etapa completada del pipeline."""
        etapa = {
            'etapa': nombre,
            'descripcion': descripcion,
            'timestamp': datetime.now().strftime('%Y-%m-%d %H:%M:%S'),
            'archivos_entrada': archivos_entrada or [],
            'archivos_salida': archivos_salida or [],
            'metadatos': metadatos or {},
        }
        self.etapas.append(etapa)
        return etapa

    def generar_manifiesto(self):
        """Genera el manifiesto completo de la ejecución."""
        duracion = datetime.now() - self.inicio
        return {
            'manifiesto_simanw': {
                'proyecto': 'SIMANW - Sistema Inteligente de Monitoreo '
                            'y Análisis de Noticias Web',
                'version': self.version,
                'fecha_ejecucion': self.inicio.strftime('%Y-%m-%d %H:%M:%S'),
                'duracion_total_seg': round(duracion.total_seconds(), 1),
                'sistema': {
                    'plataforma': platform.platform(),
                    'python': platform.python_version(),
                    'maquina': platform.node(),
                },
                'etapas': self.etapas,
                'total_etapas': len(self.etapas),
            }
        }

    def exportar_manifiesto(self, archivo='manifiesto_ejecucion.json'):
        """Guarda el manifiesto a JSON."""
        m = self.generar_manifiesto()
        ruta = os.path.join(self.proyecto_dir, archivo)
        with open(ruta, 'w', encoding='utf-8') as f:
            json.dump(m, f, ensure_ascii=False, indent=2)
        return ruta

    def resumen_etapas(self):
        """Tabla resumen de etapas."""
        lineas = []
        lineas.append(f"{'Etapa':<30} {'Registros':<12} {'Archivos salida':<30}")
        lineas.append("-" * 75)
        for e in self.etapas:
            n_reg = e['metadatos'].get('n_registros', '')
            salida = ', '.join(
                os.path.basename(f) for f in e['archivos_salida']
            )[:28]
            lineas.append(f"{e['etapa']:<30} {str(n_reg):<12} {salida:<30}")
        return '\n'.join(lineas)


# ============================================================================
# EJECUCIÓN COMPONENTES DEL PIPELINE (simulación basada en archivos reales)
# ============================================================================
def ejecutar_pipeline_simulado(tracker):
    """
    Recorre los artefactos generados por las AC y registra
    cada etapa del pipeline en el tracker.
    """
    base = tracker.proyecto_dir
    act = os.path.join(base, 'actividades')

    archivos_ac1 = os.path.join(base, 'noticias_hackernews.json')

    # Etapa 1: Rastreo
    n_rastreo = 0
    if os.path.exists(archivos_ac1):
        with open(archivos_ac1) as f:
            data = json.load(f)
        n_rastreo = data.get('total_noticias', 0)
    tracker.registrar_etapa(
        'F1: Rastreo Web',
        'Rastreo de Hacker News con paginación (AC-1). '
        'Verificación robots.txt, delay 3s, 7 páginas.',
        archivos_salida=[archivos_ac1] if os.path.exists(archivos_ac1) else [],
        metadatos={'n_registros': n_rastreo, 'fuente': 'news.ycombinator.com'},
    )

    # Etapa 2: NLP
    tracker.registrar_etapa(
        'F2: NLP',
        'Análisis estadístico de discursos con n-gramas, '
        'riqueza léxica por secciones y entidades (AC-2).',
        archivos_entrada=[os.path.join(act, 'ac2_analisis_discurso.py')],
        metadatos={'n_registros': 3, 'textos': 'discurso educativo, científico, político'},
    )

    # Etapa 3: Clasificación y Análisis
    n_categorias = 4
    tracker.registrar_etapa(
        'F3: Clasificación',
        'Clasificador multimodelo con selección automática '
        'mediante validación cruzada (AC-3). '
        'Evaluación: Naive Bayes 0.50, SVM 0.43, Ensemble 0.35.',
        archivos_entrada=[os.path.join(act, 'ac3_selector_modelo.py')],
        metadatos={'n_modelos': 5, 'mejor_modelo': 'Naive Bayes',
                   'n_categorias': n_categorias, 'exactitud': 0.50},
    )

    # Etapa 3b: Análisis de sentimiento en hilos
    tracker.registrar_etapa(
        'F3: Análisis Hilos',
        'Análisis de evolución de sentimiento, detección de '
        'subtemas y resumen automático de hilos (AC-4).',
        archivos_entrada=[os.path.join(act, 'ac4_analizador_hilo.py')],
        metadatos={'n_hilos': 2, 'n_mensajes': 35},
    )

    # Etapa 3c: Tendencias temporales
    tracker.registrar_etapa(
        'F3: Tendencias',
        'Línea de tiempo y tendencias por tema. Agrupación '
        'semanal, picos y caídas por categoría (AC-9).',
        archivos_entrada=[os.path.join(act, 'ac9_tendencias_temporales.py')],
        archivos_salida=[os.path.join(base, 'tendencias_temporales.csv'),
                         os.path.join(base, 'tendencias_temporales.png')],
        metadatos={'n_periodos': 21, 'categorias': 4},
    )

    # Etapa 4: Búsqueda
    tracker.registrar_etapa(
        'F4: Búsqueda',
        'Comparación modelo booleano vs. vectorial con '
        'precisión y recall. Modelo vectorial superior (AC-5). '
        'Sistema de alertas por consulta guardada (AC-10).',
        archivos_entrada=[os.path.join(act, 'ac5_comparador_modelos.py'),
                         os.path.join(act, 'ac10_sistema_alertas.py')],
        metadatos={'n_consultas_eval': 5, 'precision_vectorial': 1.0,
                   'alertas_activas': 5},
    )

    # Etapa 5: Chatbot
    tracker.registrar_etapa(
        'F5: Chatbot',
        'Chatbot con memoria de contexto que recuerda '
        'conversación y personaliza respuestas (AC-6).',
        archivos_entrada=[os.path.join(act, 'ac6_chatbot_contextual.py')],
        metadatos={'n_respuestas_tipos': 3, 'temas_detectados': 2},
    )

    # Etapa 6: Knowledge Graph
    tracker.registrar_etapa(
        'F6: Knowledge Graph',
        'Enriquecimiento del grafo con enlaces a Wikidata '
        'mediante owl:sameAs y skos:exactMatch (AC-7).',
        archivos_entrada=[os.path.join(act, 'ac7_enriquecedor_kg.py')],
        metadatos={'n_triples': 59, 'n_enlaces_externos': 3,
                   'entidades_enlazadas': 'tecnologia, economia, ciencia'},
    )

    # Etapa 7: Reporte
    tracker.registrar_etapa(
        'F7: Reporte y Validación',
        'Informe de calidad del corpus con validación de '
        'registros inválidos y duplicados (AC-8). '
        'Estudio de usabilidad con 3 usuarios (AC-11). '
        'Manifiesto de trazabilidad (AC-12).',
        archivos_salida=[os.path.join(base, 'informe_calidad_corpus.json'),
                        os.path.join(base, 'manifiesto_ejecucion.json')],
        metadatos={'n_aceptados': 212, 'n_rechazados': 3,
                   'usuarios_estudio': 3},
    )

    return tracker


# ============================================================================
# PROCEDIMIENTO DE REPRODUCCIÓN
# ============================================================================
PROCEDIMIENTO_REPRODUCCION = (
    "PROCEDIMIENTO DOCUMENTADO DE REPRODUCCIÓN\n"
    "=========================================\n"
    "\n"
    "Requisitos previos:\n"
    "  1. Python 3.10+ instalado.\n"
    "  2. Clonar el repositorio del proyecto SIMANW.\n"
    "  3. Instalar dependencias:\n"
    "     pip install -r requirements.txt\n"
    "  4. Conexión a Internet (para rastreo y descarga de datos NLTK).\n"
    "\n"
    "Paso 1 - Rastreo:\n"
    "  python actividades/ac1_rastreador_paginado.py\n"
    "  Genera: noticias_hackernews.json\n"
    "\n"
    "Paso 2 - NLP:\n"
    "  python actividades/ac2_analisis_discurso.py\n"
    "\n"
    "Paso 3 - Clasificación:\n"
    "  python actividades/ac3_selector_modelo.py\n"
    "  python actividades/ac4_analizador_hilo.py\n"
    "  python actividades/ac9_tendencias_temporales.py\n"
    "  Genera: tendencias_temporales.csv, tendencias_temporales.png\n"
    "\n"
    "Paso 4 - Búsqueda:\n"
    "  python actividades/ac5_comparador_modelos.py\n"
    "  python actividades/ac10_sistema_alertas.py\n"
    "  Genera: alertas.json\n"
    "\n"
    "Paso 5 - Chatbot:\n"
    "  python actividades/ac6_chatbot_contextual.py\n"
    "\n"
    "Paso 6 - Knowledge Graph:\n"
    "  python actividades/ac7_enriquecedor_kg.py\n"
    "\n"
    "Paso 7 - Reportes:\n"
    "  python actividades/ac8_validador_corpus.py\n"
    "  python actividades/ac11_estudio_usabilidad.py\n"
    "\n"
    "Paso 8 - Trazabilidad:\n"
    "  python actividades/ac12_trazabilidad.py\n"
    "  Genera: manifiesto_ejecucion.json\n"
    "\n"
    "Nota: Todos los pasos usan datos de entrada versionados "
    "(archivos .py en el repositorio) y no dependen del HTML "
    "de demostración del tutorial original. El rastreo (paso 1) "
    "obtiene datos reales de news.ycombinator.com."
)


# ============================================================================
# CHECKLIST
# ============================================================================
CHECKLIST = [
    {"paso": "AC-1: Rastrear sitio real con paginación", "artefacto": "noticias_hackernews.json", "hecho": True},
    {"paso": "AC-2: Análisis estadístico de discursos", "artefacto": "Consola (salida texto)", "hecho": True},
    {"paso": "AC-3: Clasificador multimodelo", "artefacto": "Consola (reporte modelos)", "hecho": True},
    {"paso": "AC-4: Análisis de hilos de discusión", "artefacto": "Consola (resumen + subtemas)", "hecho": True},
    {"paso": "AC-5: Comparación booleano vs. vectorial", "artefacto": "Consola (tabla precisión/recall)", "hecho": True},
    {"paso": "AC-6: Chatbot con memoria de contexto", "artefacto": "Consola (diálogo + estadísticas)", "hecho": True},
    {"paso": "AC-7: Enriquecimiento KG con Wikidata", "artefacto": "Consola (triples + enlaces)", "hecho": True},
    {"paso": "AC-8: Control de calidad del corpus", "artefacto": "informe_calidad_corpus.json", "hecho": True},
    {"paso": "AC-9: Tendencias temporales por tema", "artefacto": "tendencias_temporales.csv + .png", "hecho": True},
    {"paso": "AC-10: Sistema de alertas por consulta", "artefacto": "alertas.json", "hecho": True},
    {"paso": "AC-11: Estudio de usabilidad", "artefacto": "Consola (tablas + reflexión)", "hecho": True},
    {"paso": "AC-12: Trazabilidad y reproducibilidad", "artefacto": "manifiesto_ejecucion.json", "hecho": True},
]


# ============================================================================
# ANEXO: LIMITACIONES CONOCIDAS
# ============================================================================
ANEXO_LIMITACIONES = (
    "ANEXO: LIMITACIONES CONOCIDAS DEL SISTEMA\n"
    "==========================================\n"
    "\n"
    "1. Cambios en el sitio rastreado:\n"
    "   El rastreador de AC-1 depende de la estructura HTML de "
    "Hacker News (selectores CSS: tr.athing, .titleline a, "
    "a.morelink). Si el sitio modifica su marcado, los selectores "
    "deben actualizarse. No hay mecanismo de adaptación automática.\n"
    "\n"
    "2. Conectividad y rate limiting:\n"
    "   El rastreo real requiere conexión a Internet. El sitio "
    "Hacker News impone límites de tasa (429 Too Many Requests) "
    "si se excede un umbral de peticiones. El delay de 3s "
    "configurado mitiga parcialmente este riesgo, pero no lo elimina.\n"
    "\n"
    "3. Dependencias externas:\n"
    "   El proyecto usa NLTK (punkt, stopwords, vader_lexicon), "
    "scikit-learn, rdflib, matplotlib y requests. Los servidores "
    "de descarga de datos NLTK pueden no estar disponibles en "
    "entornos sin acceso externo. sklearn y rdflib requieren "
    "versiones específicas.\n"
    "\n"
    "4. Análisis de sentimiento en español:\n"
    "   VADER (AC-4) está optimizado para inglés. Los textos en "
    "español reciben puntuaciones menos precisas, subestimando "
    "la polaridad detectada. Una mejora futura sería incorporar "
    "un modelo específico para español (p.ej., pysentimiento).\n"
    "\n"
    "5. Fechas sintéticas en tendencias:\n"
    "   AC-9 asigna fechas sintéticas a las noticias de HN "
    "porque el sitio solo proporciona tiempos relativos "
    "('2 hours ago'). El análisis temporal es conceptualmente "
    "correcto pero no refleja la distribución real.\n"
    "\n"
    "6. Estudio de usabilidad simulado:\n"
    "   AC-11 presenta datos simulados de 3 usuarios. Un estudio "
    "real requeriría reclutar participantes, ejecutar las tareas "
    "en vivo y recopilar respuestas genuinas. Los resultados aquí "
    "mostrados son ilustrativos.\n"
    "\n"
    "7. Sin interfaz gráfica desplegada:\n"
    "   Todo el sistema se ejecuta por línea de comandos. No hay "
    "interfaz web o aplicación de escritorio para usuarios finales. "
    "Flet fue mencionado como posible frontend pero no implementado.\n"
    "\n"
    "8. Dependencia de servicios externos:\n"
    "   AC-7 incluye consultas SPARQL plantilla para Wikidata, "
    "pero no las ejecuta contra el endpoint real. El enriquecimiento "
    "del grafo se simula con datos locales.\n"
    "\n"
    "9. Volumen de datos limitado:\n"
    "   El corpus máximo alcanzado es de ~210 noticias. Para "
    "aplicaciones de producción se necesitarían órdenes de "
    "magnitud mayores (miles o millones de documentos) para "
    "entrenar modelos de clasificación más robustos.\n"
)


# ============================================================================
# DEMOSTRACIÓN
# ============================================================================
if __name__ == '__main__':
    print("=" * 65)
    print("  AC-12: Trazabilidad y Reproducibilidad del Pipeline")
    print("=" * 65)

    tracker = PipelineTracker(os.path.join(os.path.dirname(__file__), '..'))
    ejecutar_pipeline_simulado(tracker)

    m = tracker.generar_manifiesto()
    manif = m['manifiesto_simanw']

    print(f"\n  --- Manifiesto de Ejecución ---")
    print(f"  Versión:    {manif['version']}")
    print(f"  Fecha:      {manif['fecha_ejecucion']}")
    print(f"  Duración:   {manif['duracion_total_seg']}s")
    print(f"  Python:     {manif['sistema']['python']}")
    print(f"  Plataforma: {manif['sistema']['plataforma']}")
    print(f"\n  Etapas registradas: {manif['total_etapas']}")

    print(f"\n  {tracker.resumen_etapas()}")

    ruta = tracker.exportar_manifiesto()
    print(f"\n  Manifiesto exportado: {ruta}")

    print(f"\n  --- Log estructurado (extracto) ---")
    for e in manif['etapas']:
        ts = e['timestamp'][11:19]
        reg = e['metadatos'].get('n_registros', '-')
        print(f"  [{ts}] {e['etapa']:<25} registros: {reg}")

    print(f"\n  --- Procedimiento de Reproducción ---")
    print(f"  {PROCEDIMIENTO_REPRODUCCION[:300]}...")

    print(f"\n  --- Checklist de Actividades ---")
    print(f"  {'#':<3} {'Actividad':<40} {'Artefacto':<40} {'Estado':<8}")
    print(f"  {'-'*3} {'-'*40} {'-'*40} {'-'*8}")
    for i, c in enumerate(CHECKLIST, 1):
        estado = 'SI' if c['hecho'] else 'NO'
        print(f"  {i:<3} {c['paso']:<40} {c['artefacto']:<40} {estado:<8}")

    print(f"\n  --- Anexo: Limitaciones Conocidas (extracto) ---")
    for linea in ANEXO_LIMITACIONES.split('\n')[:8]:
        print(f"  {linea}")
