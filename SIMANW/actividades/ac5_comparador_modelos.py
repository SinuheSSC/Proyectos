from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.metrics.pairwise import cosine_similarity
import numpy as np


class ComparadorModelos:
    """
    AC-5: Compara formalmente modelo booleano vs. vectorial.
    Implementa ambos modelos sobre el mismo corpus y los evalúa
    con las mismas consultas y juicios de relevancia.
    """

    def __init__(self, documentos):
        self.documentos = documentos
        self.textos = [f"{d['titulo']} {d['cuerpo']}" for d in documentos]
        self.vectorizer = TfidfVectorizer(ngram_range=(1, 2))
        self.matriz = self.vectorizer.fit_transform(self.textos)

        self.indice = {}
        for i, texto in enumerate(self.textos):
            for palabra in texto.lower().split():
                if palabra not in self.indice:
                    self.indice[palabra] = set()
                self.indice[palabra].add(i)

    def busqueda_booleana(self, consulta):
        """Modelo booleano: AND de todos los términos."""
        terminos = consulta.lower().split()
        if not terminos:
            return []
        resultado = self.indice.get(terminos[0], set()).copy()
        for t in terminos[1:]:
            resultado &= self.indice.get(t, set())
        return sorted(resultado)

    def busqueda_vectorial(self, consulta, top_k=None):
        """Modelo vectorial: ranking por similitud coseno."""
        if top_k is None:
            top_k = len(self.documentos)
        q_vec = self.vectorizer.transform([consulta])
        sims = cosine_similarity(q_vec, self.matriz)[0]
        indices = sims.argsort()[::-1][:top_k]
        return [(i, round(sims[i], 4)) for i in indices if sims[i] > 0]

    def evaluar_ambos(self, consulta, relevantes):
        """Evalúa ambos modelos con la misma consulta y juicio de relevancia."""
        bool_result = self.busqueda_booleana(consulta)
        bool_precision = len(set(bool_result) & set(relevantes)) / max(len(bool_result), 1)
        bool_recall = len(set(bool_result) & set(relevantes)) / max(len(relevantes), 1)

        vec_result = [idx for idx, _ in self.busqueda_vectorial(consulta)]
        vec_top_k = vec_result[:len(bool_result)] if bool_result else vec_result[:3]
        vec_precision = len(set(vec_top_k) & set(relevantes)) / max(len(vec_top_k), 1)
        vec_recall = len(set(vec_top_k) & set(relevantes)) / max(len(relevantes), 1)

        return {
            'consulta': consulta,
            'booleano': {
                'recuperados': len(bool_result),
                'precision': round(bool_precision, 4),
                'recall': round(bool_recall, 4),
                'docs': bool_result,
            },
            'vectorial': {
                'recuperados': len(vec_top_k),
                'precision': round(vec_precision, 4),
                'recall': round(vec_recall, 4),
                'docs': vec_result,
            },
        }

    def mostrar_ranking_vectorial(self, consulta, top_k=3):
        """Muestra los top resultados vectoriales con su score."""
        resultados = self.busqueda_vectorial(consulta, top_k)
        print(f"  Ranking vectorial para \"{consulta}\":")
        for idx, score in resultados:
            doc = self.documentos[idx]
            print(f"    [{score:.3f}] {doc['titulo'][:60]}")


# ============================================================================
# Corpus de noticias para la comparación
# ============================================================================
NOTICIAS = [
    {
        "titulo": "La inteligencia artificial transforma la industria tecnológica",
        "cuerpo": "Los avances en inteligencia artificial y deep learning están revolucionando la industria tecnológica. Las empresas invierten en modelos de lenguaje y redes neuronales para mejorar sus productos y servicios digitales.",
    },
    {
        "titulo": "Mercados financieros en recuperación tras volatilidad económica",
        "cuerpo": "Los mercados de valores muestran signos de recuperación después de un período de alta volatilidad. Los inversores siguen de cerca las decisiones de los bancos centrales sobre tasas de interés y política monetaria.",
    },
    {
        "titulo": "Científicos advierten sobre el acelerado cambio climático global",
        "cuerpo": "Un grupo de científicos publicó un estudio sobre el calentamiento global y sus efectos en los ecosistemas. El cambio climático avanza más rápido de lo previsto y requiere acción inmediata de los gobiernos.",
    },
    {
        "titulo": "Nuevos algoritmos de machine learning para análisis de datos",
        "cuerpo": "Investigadores desarrollaron nuevos algoritmos de machine learning que mejoran el análisis de grandes volúmenes de datos. La inteligencia artificial aplicada a la ciencia de datos abre nuevas posibilidades en investigación.",
    },
    {
        "titulo": "Gobierno anuncia política de datos abiertos para transparencia",
        "cuerpo": "El gobierno presentó una nueva política de datos abiertos que permitirá a los ciudadanos acceder a información pública. La iniciativa busca fortalecer la transparencia y la rendición de cuentas.",
    },
    {
        "titulo": "Startups de tecnología educativa crecen en América Latina",
        "cuerpo": "Las startups de tecnología educativa están transformando la forma de aprender en América Latina. La inversión en edtech crece impulsada por la demanda de educación digital y plataformas de aprendizaje en línea.",
    },
]

# ============================================================================
# Consultas de evaluación con juicios de relevancia manuales
# ============================================================================
CONSULTAS_EVAL = [
    {"consulta": "inteligencia artificial", "relevantes": [0, 3]},
    {"consulta": "mercados economía finanzas", "relevantes": [1]},
    {"consulta": "datos abiertos gobierno", "relevantes": [4]},
    {"consulta": "cambio climático científico", "relevantes": [2]},
    {"consulta": "tecnología educación startups", "relevantes": [5]},
]


# ============================================================================
# Demostración
# ============================================================================
if __name__ == '__main__':
    print("=" * 65)
    print("  AC-5: Comparación Booleano vs. Vectorial")
    print("=" * 65)

    print(f"\nCorpus: {len(NOTICIAS)} documentos")
    for i, doc in enumerate(NOTICIAS):
        print(f"  [{i}] {doc['titulo'][:65]}")

    comparador = ComparadorModelos(NOTICIAS)

    print(f"\n{'Consulta':<35} | {'Modelo':<10} | {'Recup':>5} | {'Prec':>7} | {'Recall':>7}")
    print("-" * 85)

    sum_bool_p, sum_vec_p = 0, 0
    sum_bool_r, sum_vec_r = 0, 0
    n = len(CONSULTAS_EVAL)

    for ce in CONSULTAS_EVAL:
        resultado = comparador.evaluar_ambos(ce['consulta'], ce['relevantes'])
        b = resultado['booleano']
        v = resultado['vectorial']
        sum_bool_p += b['precision']
        sum_vec_p += v['precision']
        sum_bool_r += b['recall']
        sum_vec_r += v['recall']
        print(f"{ce['consulta']:<35} | {'Booleano':<10} | {b['recuperados']:>5} "
              f"| {b['precision']:>7.3f} | {b['recall']:>7.3f}")
        print(f"{'':35} | {'Vectorial':<10} | {v['recuperados']:>5} "
              f"| {v['precision']:>7.3f} | {v['recall']:>7.3f}")
        print()

    print("-" * 85)
    print(f"{'PROMEDIO':<35} | {'Booleano':<10} | {'':>5} "
          f"| {sum_bool_p / n:>7.3f} | {sum_bool_r / n:>7.3f}")
    print(f"{'':35} | {'Vectorial':<10} | {'':>5} "
          f"| {sum_vec_p / n:>7.3f} | {sum_vec_r / n:>7.3f}")

    mejor = 'vectorial' if sum_vec_p > sum_bool_p else 'booleano'
    print(f"\n  Conclusión: El modelo {mejor} tiene mejor precisión promedio.")

    print(f"\n  --- Ejemplo de ranking ---")
    comparador.mostrar_ranking_vectorial("inteligencia artificial datos", top_k=4)
