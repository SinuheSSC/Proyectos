from collections import Counter
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.metrics.pairwise import cosine_similarity


class MotorBusquedaLocal:
    """Motor de búsqueda vectorial mínimo para usar dentro del chatbot."""

    def __init__(self, noticias):
        self.noticias = noticias
        self.textos = [f"{n['titulo']} {n['cuerpo']}" for n in noticias]
        self.vectorizer = TfidfVectorizer(ngram_range=(1, 2), max_features=2000)
        self.matriz = self.vectorizer.fit_transform(self.textos)

    def buscar_vectorial(self, consulta, top_k=5):
        q_vec = self.vectorizer.transform([consulta])
        sims = cosine_similarity(q_vec, self.matriz)[0]
        indices = sims.argsort()[::-1][:top_k]
        resultados = []
        for idx in indices:
            if sims[idx] > 0:
                n = self.noticias[idx]
                resultados.append({
                    'doc_id': idx,
                    'titulo': n['titulo'],
                    'relevancia': float(round(sims[idx], 4)),
                    'categoria': n.get('categoria', 'general'),
                    'snippet': n['cuerpo'][:80] + '...',
                })
        return resultados


class ChatbotContextual:
    """
    AC-6: Chatbot con memoria de contexto.
    Recuerda la conversación anterior y usa ese contexto
    para mejorar sus respuestas (no responde de forma aislada).
    """

    def __init__(self, noticias, motor_busqueda):
        self.noticias = noticias
        self.motor = motor_busqueda
        self.historial = []
        self.contexto_temas = Counter()
        self.ultima_respuesta = None

    def _detectar_temas(self, texto):
        """Identifica qué temas aparecen en el texto del usuario."""
        t = texto.lower()
        temas = {
            'tecnologia': ['tecnología', 'ia', 'inteligencia artificial',
                           'python', 'programación', 'software',
                           'algoritmo', 'digital', 'startup'],
            'economia':   ['economía', 'mercado', 'finanzas', 'dinero',
                           'inversión', 'bolsa', 'inflación', 'bancos'],
            'ciencia':    ['ciencia', 'clima', 'investigación', 'laboratorio',
                           'cambio climático', 'estudio', 'científico'],
            'educacion':  ['educación', 'escuela', 'universidad', 'aprendizaje',
                           'estudiantes', 'curso', 'formación'],
        }
        for tema, keywords in temas.items():
            for kw in keywords:
                if kw in t:
                    return tema
        return None

    def actualizar_contexto(self, pregunta, respuesta_tipo):
        """Actualiza el contexto basándose en la interacción."""
        self.historial.append({
            'pregunta': pregunta,
            'tipo': respuesta_tipo,
        })
        tema = self._detectar_temas(pregunta)
        if tema:
            self.contexto_temas[tema] += 1

    def responder(self, pregunta):
        """Genera respuesta considerando el contexto previo."""
        pregunta_lower = pregunta.lower()

        # Detectar referencias al contexto anterior
        es_referencia = any(
            ref in pregunta_lower
            for ref in ['eso', 'esa', 'ese', 'anterior', 'más sobre',
                        'otra', 'lo mismo', 'similar']
        )

        if es_referencia and self.historial:
            ultimo = self.historial[-1]
            pregunta_expandida = f"{ultimo['pregunta']} {pregunta}"
            resultados = self.motor.buscar_vectorial(pregunta_expandida, top_k=2)
            if resultados:
                r = resultados[0]
                resp = (f"Basándome en nuestra conversación anterior, "
                        f"encontré: {r['titulo']}. {r['snippet']}")
                self.actualizar_contexto(pregunta, 'contextual')
                return resp, 'contextual', r['relevancia']

        resultados = self.motor.buscar_vectorial(pregunta, top_k=5)
        if not resultados:
            self.actualizar_contexto(pregunta, 'fallback')
            return ("No encontré algo específico. "
                    "¿Puedes darme más detalles?"), 'fallback', 0.0

        if self.contexto_temas:
            tema_favorito = self.contexto_temas.most_common(1)[0][0]
            for r in resultados:
                if r['categoria'] == tema_favorito:
                    resp = (f"Como veo que te interesa {tema_favorito}, "
                            f"te recomiendo: {r['titulo']}. {r['snippet']}")
                    self.actualizar_contexto(pregunta, 'personalizada')
                    self.ultima_respuesta = r
                    return resp, 'personalizada', r['relevancia']

        mejor = resultados[0]
        resp = f"{mejor['titulo']}. {mejor['snippet']}"
        self.actualizar_contexto(pregunta, 'directa')
        self.ultima_respuesta = mejor
        return resp, 'directa', mejor['relevancia']

    def estadisticas_sesion(self):
        return {
            'interacciones': len(self.historial),
            'temas_interes': dict(self.contexto_temas.most_common()),
            'tipos_respuesta': dict(
                Counter(h['tipo'] for h in self.historial).most_common()
            ),
        }


# ============================================================================
# Noticias de ejemplo con categorías
# ============================================================================
NOTICIAS = [
    {"titulo": "IA Generativa revoluciona la creación de contenido digital",
     "cuerpo": "Los modelos de inteligencia artificial generativa están transformando la creación de contenido en todas las industrias. Desde texto hasta imágenes y video, las capacidades de la IA generativa avanzan rápidamente.",
     "categoria": "tecnologia"},
    {"titulo": "Mercados bursátiles alcanzan nuevos máximos históricos",
     "cuerpo": "Los principales índices bursátiles mundiales alcanzaron nuevos máximos históricos impulsados por el optimismo de los inversores ante las perspectivas económicas globales.",
     "categoria": "economia"},
    {"titulo": "Nuevo estudio revela impacto del cambio climático en océanos",
     "cuerpo": "Un estudio científico publicado hoy revela que el calentamiento global está afectando los ecosistemas marinos a un ritmo más rápido de lo estimado anteriormente.",
     "categoria": "ciencia"},
    {"titulo": "Plataforma de cursos en línea crece 200% en América Latina",
     "cuerpo": "La educación en línea sigue expandiéndose en la región. Una plataforma latinoamericana reporta un crecimiento del 200% en su número de estudiantes durante el último año.",
     "categoria": "educacion"},
    {"titulo": "Startup mexicana desarrolla asistente IA para programadores",
     "cuerpo": "Una startup de tecnología mexicana lanzó un asistente basado en inteligencia artificial que ayuda a programadores a escribir código más eficiente y detectar errores automáticamente.",
     "categoria": "tecnologia"},
    {"titulo": "Bancos centrales analizan nuevas medidas contra la inflación",
     "cuerpo": "Los bancos centrales de las principales economías evalúan nuevas estrategias para controlar la inflación sin afectar el crecimiento económico y el empleo.",
     "categoria": "economia"},
]


# ============================================================================
# Demostración
# ============================================================================
if __name__ == '__main__':
    print("=" * 65)
    print("  AC-6: Chatbot con Memoria de Contexto")
    print("=" * 65)

    motor = MotorBusquedaLocal(NOTICIAS)
    chatbot = ChatbotContextual(NOTICIAS, motor)

    conversacion = [
        "¿Qué noticias hay de tecnología?",
        "Cuéntame más sobre eso",
        "¿Hay algo sobre inteligencia artificial?",
        "¿Y algo de economía?",
        "Dame otra noticia similar a la anterior",
    ]

    for pregunta in conversacion:
        respuesta, tipo, confianza = chatbot.responder(pregunta)
        print(f"\n  Usuario: {pregunta}")
        print(f"  Bot [{tipo}][{confianza:.2f}]: {respuesta[:90]}...")

    stats = chatbot.estadisticas_sesion()
    print(f"\n  --- Estadísticas de sesión ---")
    print(f"  Interacciones:   {stats['interacciones']}")
    print(f"  Temas de interés: {stats['temas_interes']}")
    print(f"  Tipos de respuesta: {stats['tipos_respuesta']}")
