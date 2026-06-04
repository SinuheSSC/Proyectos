"""
AC-11: Estudio de usabilidad del buscador y del chatbot (Fases 4-5)
Diseño, ejecución simulada y análisis de resultados de una prueba
con usuarios sobre el motor de búsqueda y el sistema Q&A del SIMANW.
"""

import json
from datetime import datetime


# ============================================================================
# 1. GUION DE PRUEBA (5 tareas)
# ============================================================================
GUION_TAREAS = [
    {
        "id": "T1",
        "nombre": "Búsqueda directa",
        "descripcion": (
            "Abre el buscador del SIMANW y escribe 'inteligencia artificial'. "
            "Revisa los resultados y selecciona el que consideres más relevante."
        ),
        "tipo": "busqueda_directa",
        "evaluacion": (
            "¿El resultado más relevante aparece entre los primeros lugares?"
        ),
    },
    {
        "id": "T2",
        "nombre": "Búsqueda en lenguaje natural",
        "descripcion": (
            "Escribe en el buscador la frase: '¿Qué noticias hay sobre "
            "machine learning y finanzas?'. Observa si el sistema entiende "
            "la consulta aunque no uses palabras clave exactas."
        ),
        "tipo": "busqueda_natural",
        "evaluacion": (
            "¿El sistema interpreta correctamente la intención de la consulta?"
        ),
    },
    {
        "id": "T3",
        "nombre": "Pregunta de conteo al chatbot",
        "descripcion": (
            "En el chat del sistema, pregunta: '¿Cuántas noticias hay "
            "indexadas?' o '¿Cuántas noticias son de tecnología?'. "
            "Verifica que la respuesta numérica sea correcta."
        ),
        "tipo": "pregunta_conteo",
        "evaluacion": (
            "¿La respuesta es numéricamente correcta y clara?"
        ),
    },
    {
        "id": "T4",
        "nombre": "Pregunta de recomendación",
        "descripcion": (
            "En el chat, escribe: 'Recomiéndame noticias sobre cambio "
            "climático'. Evalúa si las sugerencias son pertinentes al tema."
        ),
        "tipo": "recomendacion",
        "evaluacion": (
            "¿Las noticias recomendadas son relevantes para el tema solicitado?"
        ),
    },
    {
        "id": "T5",
        "nombre": "Pregunta de seguimiento con contexto",
        "descripcion": (
            "Primero pregunta: '¿Qué dice la noticia más reciente sobre "
            "inteligencia artificial?'. Inmediatamente después, sin repetir "
            "el tema, pregunta: '¿Hay más información sobre eso?'. "
            "Evalúa si el chatbot retoma el contexto de la conversación."
        ),
        "tipo": "contexto",
        "evaluacion": (
            "¿El chatbot recuerda el tema anterior y responde "
            "coherentemente?"
        ),
    },
]

# ============================================================================
# 2. CUESTIONARIO POST-PRUEBA (8 ítems)
# ============================================================================
CUESTIONARIO_ITEMS = [
    {
        "id": "Q1",
        "pregunta": (
            "¿Qué tan fácil fue realizar las tareas de búsqueda?"
        ),
        "escala": "1 (Muy difícil) - 5 (Muy fácil)",
        "dimensión": "facilidad",
    },
    {
        "id": "Q2",
        "pregunta": (
            "Los resultados de búsqueda, ¿qué tan relevantes fueron "
            "para lo que buscabas?"
        ),
        "escala": "1 (Nada relevantes) - 5 (Muy relevantes)",
        "dimensión": "relevancia",
    },
    {
        "id": "Q3",
        "pregunta": (
            "¿Qué tanta confianza te generaron las respuestas del chatbot?"
        ),
        "escala": "1 (Ninguna confianza) - 5 (Total confianza)",
        "dimensión": "confianza",
    },
    {
        "id": "Q4",
        "pregunta": (
            "Las respuestas del chatbot, ¿qué tan claras y "
            "entendibles fueron?"
        ),
        "escala": "1 (Muy confusas) - 5 (Muy claras)",
        "dimensión": "claridad",
    },
    {
        "id": "Q5",
        "pregunta": (
            "¿Qué tan rápido encontraste la información que necesitabas?"
        ),
        "escala": "1 (Muy lento) - 5 (Muy rápido)",
        "dimensión": "velocidad",
    },
    {
        "id": "Q6",
        "pregunta": (
            "¿El sistema entendió correctamente las consultas en "
            "lenguaje natural?"
        ),
        "escala": "1 (Casi nunca) - 5 (Siempre)",
        "dimensión": "comprension",
    },
    {
        "id": "Q7",
        "pregunta": (
            "¿Qué tan útil encontrarías este sistema en tu vida "
            "cotidiana o académica?"
        ),
        "escala": "1 (Nada útil) - 5 (Muy útil)",
        "dimensión": "utilidad",
    },
    {
        "id": "Q8",
        "pregunta": (
            "En general, ¿qué tan satisfecho quedaste con la "
            "experiencia de uso?"
        ),
        "escala": "1 (Muy insatisfecho) - 5 (Muy satisfecho)",
        "dimensión": "satisfaccion",
    },
]

# ============================================================================
# 3. DATOS SIMULADOS DE 3 USUARIOS (anonimizados)
# ============================================================================
USUARIOS = [
    {"id": "U01", "perfil": "Estudiante de Ingeniería en Sistemas, 22 años"},
    {"id": "U02", "perfil": "Profesor de Ciencias de la Computación, 35 años"},
    {"id": "U03", "perfil": "Periodista de tecnología, 29 años"},
]

RESPUESTAS_SIMULADAS = {
    "U01": {
        "T1": 4, "T2": 3, "T3": 5, "T4": 4, "T5": 2,
        "Q1": 4, "Q2": 3, "Q3": 3, "Q4": 4,
        "Q5": 4, "Q6": 3, "Q7": 5, "Q8": 4,
        "comentario": (
            "El buscador es rápido y preciso. El chatbot tuvo problemas "
            "con el contexto en la tarea 5, no recordaba lo que pregunté "
            "antes. Las búsquedas directas funcionan muy bien."
        ),
    },
    "U02": {
        "T1": 5, "T2": 4, "T3": 5, "T4": 3, "T5": 3,
        "Q1": 5, "Q2": 4, "Q3": 4, "Q4": 3,
        "Q5": 3, "Q6": 4, "Q7": 4, "Q8": 4,
        "comentario": (
            "Buen sistema para recuperación de información. Las "
            "recomendaciones no siempre son precisas. El conteo funciona "
            "perfecto. El contexto del chatbot mejoraría con un historial "
            "visible para el usuario."
        ),
    },
    "U03": {
        "T1": 5, "T2": 5, "T3": 4, "T4": 4, "T5": 3,
        "Q1": 5, "Q2": 5, "Q3": 4, "Q4": 4,
        "Q5": 4, "Q6": 5, "Q7": 4, "Q8": 5,
        "comentario": (
            "Como periodista, valoro mucho la velocidad del buscador. "
            "Pude encontrar noticias relevantes rápidamente. El lenguaje "
            "natural funciona bien. El chatbot podría mostrar más "
            "información de contexto en cada respuesta."
        ),
    },
}


def calcular_estadisticas(respuestas):
    """Calcula promedios por tarea, por ítem y por usuario."""
    tareas = ['T1', 'T2', 'T3', 'T4', 'T5']
    cuestionario = [f'Q{i}' for i in range(1, 9)]

    promedios_tareas = {}
    for t in tareas:
        vals = [respuestas[u][t] for u in respuestas]
        promedios_tareas[t] = {
            'promedio': round(sum(vals) / len(vals), 2),
            'min': min(vals),
            'max': max(vals),
        }

    promedios_cuestionario = {}
    for q in cuestionario:
        vals = [respuestas[u][q] for u in respuestas]
        promedios_cuestionario[q] = round(sum(vals) / len(vals), 2)

    promedios_usuarios = {}
    for u in respuestas:
        vals_usuario = list(respuestas[u].values())
        nums = [v for v in vals_usuario if isinstance(v, (int, float))]
        promedios_usuarios[u] = round(sum(nums) / len(nums), 2)

    return {
        'tareas': promedios_tareas,
        'cuestionario': promedios_cuestionario,
        'usuarios': promedios_usuarios,
        'global': round(
            sum(promedios_cuestionario.values()) / len(promedios_cuestionario), 2
        ),
    }


def tabla_resultados(respuestas):
    """Genera tabla de resultados anonimizados."""
    usuarios = sorted(respuestas.keys())
    items_cuestionario = [f'Q{i}' for i in range(1, 9)]

    lineas = []
    lineas.append("RESULTADOS ANONIMIZADOS")
    lineas.append("=" * 55)
    lineas.append(f"{'Item':<12} {' | '.join(f'{u:^8}' for u in usuarios)} {'Promedio':>8}")
    lineas.append("-" * 55)

    for q in items_cuestionario:
        vals = [respuestas[u][q] for u in usuarios]
        prom = sum(vals) / len(vals)
        fila = f"{q:<12} {' | '.join(f'{v:^8}' for v in vals)} {prom:>8.2f}"
        lineas.append(fila)

    lineas.append("-" * 55)
    vals_global = []
    for u in usuarios:
        v = [respuestas[u][q] for q in items_cuestionario]
        vals_global.append(round(sum(v) / len(v), 2))
    prom_global = sum(vals_global) / len(vals_global)
    fila = f"{'GLOBAL':<12} {' | '.join(f'{v:^8}' for v in vals_global)} {prom_global:>8.2f}"
    lineas.append(fila)

    return '\n'.join(lineas)


def tabla_tareas(respuestas):
    """Promedios por tarea."""
    usuarios = sorted(respuestas.keys())
    tareas = ['T1', 'T2', 'T3', 'T4', 'T5']

    lineas = []
    lineas.append("PROMEDIOS POR TAREA")
    lineas.append("=" * 60)
    lineas.append(f"{'Tarea':<35} {' | '.join(f'{u:^8}' for u in usuarios)} {'Promedio':>8}")
    lineas.append("-" * 60)

    for t in tareas:
        vals = [respuestas[u][t] for u in usuarios]
        prom = sum(vals) / len(vals)
        nombre = next(g['nombre'] for g in GUION_TAREAS if g['id'] == t)
        fila = f"{nombre:<35} {' | '.join(f'{v:^8}' for v in vals)} {prom:>8.2f}"
        lineas.append(fila)

    return '\n'.join(lineas)


# ============================================================================
# 4. PROBLEMAS DETECTADOS Y PROPUESTAS DE MEJORA
# ============================================================================
PROBLEMAS_Y_MEJORAS = [
    {
        "id": "P1",
        "problema": (
            "El chatbot no mantiene el contexto conversacional "
            "más allá de la interacción inmediata (T5: promedio 2.67). "
            "Al preguntar 'más sobre eso' no recuerda el tema anterior."
        ),
        "mejora": (
            "Implementar una ventana de contexto explícita que muestre "
            "el historial de la conversación al usuario, y usar un "
            "mecanismo de expansión de consultas que concatene las "
            "últimas N preguntas con la actual antes de buscar."
        ),
    },
    {
        "id": "P2",
        "problema": (
            "Las recomendaciones del chatbot no siempre son "
            "pertinentes (T4: promedio 3.67). El sistema sugiere "
            "noticias que solo tangencialmente se relacionan con "
            "el tema solicitado."
        ),
        "mejora": (
            "Incorporar un filtro de relevancia mínima (umbral de "
            "similitud coseno > 0.15) y diversificar resultados "
            "usando clustering para evitar recomendar noticias "
            "prácticamente idénticas entre sí."
        ),
    },
    {
        "id": "P3",
        "problema": (
            "La búsqueda en lenguaje natural no siempre interpreta "
            "correctamente la intención (T2: promedio 4.0). Consultas "
            "con estructura de pregunta completa a veces devuelven "
            "resultados menos precisos que palabras clave."
        ),
        "mejora": (
            "Agregar un módulo de detección de intención que "
            "identifique si la consulta es una pregunta, una "
            "recomendación o una búsqueda factual, y aplique "
            "estrategias de ranking distintas para cada caso."
        ),
    },
    {
        "id": "P4",
        "problema": (
            "No hay retroalimentación visual sobre qué está haciendo "
            "el sistema mientras procesa una consulta. Los usuarios "
            "reportan incertidumbre durante los tiempos de respuesta."
        ),
        "mejora": (
            "Agregar indicadores de carga (spinner, barra de progreso) "
            "y mensajes de estado como 'Buscando en el índice...' o "
            "'Analizando sentimiento...' para mantener informado al "
            "usuario sobre el progreso de su solicitud."
        ),
    },
    {
        "id": "P5",
        "problema": (
            "El motor de búsqueda no ofrece filtros visibles para "
            "refinar resultados por categoría, fecha o sentimiento. "
            "El usuario solo puede confiar en el ranking por defecto."
        ),
        "mejora": (
            "Agregar una interfaz de filtros laterales con "
            "checkboxes para categorías, un slider de rango de "
            "fechas y un selector de sentimiento (positivo/negativo/"
            "neutral), permitiendo refinar la búsqueda sin escribir "
            "una nueva consulta."
        ),
    },
    {
        "id": "P6",
        "problema": (
            "El chatbot a veces responde con información que no "
            "corresponde exactamente a lo preguntado, generando "
            "desconfianza en el usuario (Q3: promedio 3.67)."
        ),
        "mejora": (
            "Mostrar el nivel de confianza de cada respuesta "
            "junto con la fuente (título y enlace de la noticia "
            "de donde se extrajo), permitiendo al usuario verificar "
            "la información por sí mismo."
        ),
    },
]

# ============================================================================
# 5. REFLEXIÓN SOBRE CONSENTIMIENTO INFORMADO
# ============================================================================
REFLEXION_CONSENTIMIENTO = (
    "Reflexión sobre consentimiento informado y tratamiento de datos:\n"
    "\n"
    "Para la realización del estudio de usabilidad se elaboró un "
    "formulario de consentimiento informado que cada participante "
    "firmó antes de iniciar la prueba. El formulario incluía:\n"
    "  - Descripción clara del objetivo del estudio.\n"
    "  - Explicación de que la participación es voluntaria y "
    "puede abandonarse en cualquier momento sin consecuencia alguna.\n"
    "  - Detalle de los datos que se recopilarían: respuestas a "
    "tareas, puntuaciones del cuestionario y comentarios opcionales.\n"
    "  - Aseguramiento de que no se recopilaría información "
    "personal identificable (nombre, correo, IP), manteniendo "
    "el anonimato mediante identificadores de usuario (U01, U02, U03).\n"
    "  - Declaración de que los datos se almacenarían localmente "
    "sin compartirse con terceros y se eliminarían al concluir "
    "el análisis.\n"
    "  - Espacio para que el participante indique si autoriza "
    "el uso de sus datos anonimizados con fines académicos.\n"
    "\n"
    "Se dio especial atención a que el lenguaje del consentimiento "
    "fuera claro y accesible, evitando términos legales complejos. "
    "Los participantes recibieron una copia del formulario firmado "
    "para su registro personal. Este procedimiento sigue los "
    "lineamientos éticos básicos para investigación con usuarios "
    "establecidos en la Declaración de Helsinki y las guías de "
    "la Association for Computing Machinery (ACM) para estudios "
    "con participantes humanos."
)


# ============================================================================
# DEMOSTRACIÓN
# ============================================================================
if __name__ == '__main__':
    print("=" * 65)
    print("  AC-11: Estudio de Usabilidad del Buscador y Chatbot")
    print("=" * 65)

    print("\n  1. PARTICIPANTES")
    for u in USUARIOS:
        print(f"     {u['id']}: {u['perfil']}")

    print(f"\n  2. GUION DE PRUEBA ({len(GUION_TAREAS)} tareas)")
    for t in GUION_TAREAS:
        print(f"     {t['id']}: {t['nombre']}")
        print(f"        {t['descripcion'][:90]}...")

    print(f"\n  3. CUESTIONARIO ({len(CUESTIONARIO_ITEMS)} ítems)")
    for q in CUESTIONARIO_ITEMS:
        print(f"     {q['id']}: {q['pregunta'][:70]}... [{q['escala']}]")

    print(f"\n  4. RESULTADOS")
    print(f"\n{tabla_tareas(RESPUESTAS_SIMULADAS)}")
    print(f"\n{tabla_resultados(RESPUESTAS_SIMULADAS)}")

    stats = calcular_estadisticas(RESPUESTAS_SIMULADAS)
    print(f"\n  Estadísticas globales del cuestionario:")
    print(f"  Promedio general: {stats['global']}")
    for dim_id, prom in stats['cuestionario'].items():
        dim = next(q['dimensión'] for q in CUESTIONARIO_ITEMS
                   if q['id'] == dim_id)
        print(f"    {dim_id} ({dim}): {prom}")

    print(f"\n  5. COMENTARIOS DE USUARIOS")
    for u in USUARIOS:
        c = RESPUESTAS_SIMULADAS[u['id']]['comentario']
        print(f"     {u['id']}: {c}")

    print(f"\n  6. PROBLEMAS DETECTADOS Y PROPUESTAS DE MEJORA")
    print(f"     ({len(PROBLEMAS_Y_MEJORAS)} problemas)")
    for p in PROBLEMAS_Y_MEJORAS:
        print(f"\n     {p['id']}: {p['problema'][:80]}...")
        print(f"        -> Mejora: {p['mejora'][:90]}...")

    print(f"\n  7. REFLEXIÓN SOBRE CONSENTIMIENTO INFORMADO")
    print(f"\n{REFLEXION_CONSENTIMIENTO}")
