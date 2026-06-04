import numpy as np
from sklearn.naive_bayes import MultinomialNB
from sklearn.svm import LinearSVC
from sklearn.linear_model import LogisticRegression
from sklearn.ensemble import RandomForestClassifier, VotingClassifier
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.model_selection import cross_val_score, StratifiedKFold
from sklearn.metrics import classification_report


class SelectorModelo:
    """
    AC-3: Entrena múltiples clasificadores y selecciona
    automáticamente el mejor según los datos con validación cruzada.
    """

    def __init__(self):
        self.modelos = {
            'Naive Bayes': MultinomialNB(alpha=0.1),
            'SVM Lineal': LinearSVC(max_iter=3000, C=1.0),
            'Logistic Regression': LogisticRegression(max_iter=1000, C=1.0),
            'Random Forest': RandomForestClassifier(n_estimators=100, random_state=42),
        }
        self.ensemble = VotingClassifier(
            estimators=[
                ('nb', MultinomialNB(alpha=0.1)),
                ('lr', LogisticRegression(max_iter=1000, C=1.0)),
                ('rf', RandomForestClassifier(n_estimators=100, random_state=42)),
            ],
            voting='soft',
        )
        self.vectorizer = TfidfVectorizer(max_features=3000, ngram_range=(1, 2))
        self.mejor_modelo = None
        self.resultados = {}
        self.categorias = []

    def evaluar_todos(self, textos, etiquetas, cv_folds=5):
        """Evalúa todos los modelos con validación cruzada."""
        X = self.vectorizer.fit_transform(textos)
        self.categorias = sorted(set(etiquetas))
        cv = StratifiedKFold(n_splits=cv_folds, shuffle=True, random_state=42)

        for nombre, modelo in self.modelos.items():
            try:
                scores = cross_val_score(
                    modelo, X, etiquetas, cv=cv, scoring='accuracy'
                )
                self.resultados[nombre] = {
                    'accuracy_mean': scores.mean(),
                    'accuracy_std': scores.std(),
                    'scores': scores.tolist(),
                }
            except Exception as e:
                self.resultados[nombre] = {'error': str(e)}

        try:
            scores_ens = cross_val_score(
                self.ensemble, X.toarray(), etiquetas, cv=cv, scoring='accuracy'
            )
            self.resultados['Voting Ensemble'] = {
                'accuracy_mean': scores_ens.mean(),
                'accuracy_std': scores_ens.std(),
                'scores': scores_ens.tolist(),
            }
        except Exception as e:
            self.resultados['Voting Ensemble'] = {'error': str(e)}

        validos = {
            k: v for k, v in self.resultados.items() if 'accuracy_mean' in v
        }
        if validos:
            mejor_nombre = max(
                validos, key=lambda k: validos[k]['accuracy_mean']
            )
            self.mejor_modelo = (mejor_nombre, self.modelos.get(
                mejor_nombre, self.ensemble
            ))
            self.mejor_modelo[1].fit(X, etiquetas)

        return self.resultados

    def predecir(self, textos):
        """Predice usando el mejor modelo seleccionado."""
        if not self.mejor_modelo:
            raise ValueError("Primero ejecuta evaluar_todos()")
        X = self.vectorizer.transform(textos)
        return self.mejor_modelo[1].predict(X)

    def reporte_detallado(self, textos, etiquetas):
        """Reporte con precisión, recall y F1 para cada categoría."""
        if not self.mejor_modelo:
            return "Ejecuta evaluar_todos() primero"
        X = self.vectorizer.transform(textos)
        preds = self.mejor_modelo[1].predict(X)
        return classification_report(
            etiquetas, preds, target_names=self.categorias
        )

    def reporte(self):
        """Genera reporte comparativo de modelos."""
        lineas = [
            "Modelo                  | Accuracy   | Std Dev",
            "-" * 50,
        ]
        for nombre, res in sorted(
            self.resultados.items(),
            key=lambda x: x[1].get('accuracy_mean', 0),
            reverse=True,
        ):
            if 'accuracy_mean' in res:
                marca = " *" if (
                    self.mejor_modelo and nombre == self.mejor_modelo[0]
                ) else ""
                lineas.append(
                    f"{nombre:<23} | {res['accuracy_mean']:.4f}     "
                    f"| {res['accuracy_std']:.4f}{marca}"
                )
            else:
                lineas.append(
                    f"{nombre:<23} | ERROR      | "
                    f"{res.get('error', '')[:20]}"
                )
        return "\n".join(lineas)


# ============================================================================
# Datos de entrenamiento: mezcla de tecnológicas, economía,
# ciencia y política (expandidos para mayor robustez)
# ============================================================================
DATOS_AC3 = {
    'textos': [
        "inteligencia artificial deep learning redes neuronales transformers",
        "programación software desarrollo aplicaciones web python javascript",
        "startup tecnológica innovación digital plataforma cloud",
        "ciberseguridad hackers vulnerabilidad protección datos privacidad",
        "nube computación servidores almacenamiento infraestructura digital",
        "blockchain criptomonedas bitcoin ethereum contrato inteligente",
        "machine learning algoritmos entrenamiento predicción datos masivos",
        "internet cosas iot sensores dispositivos conectados automatización",
        "realidad virtual aumentada metaverso experiencia inmersiva 3d",
        "código abierto open source comunidad desarrolladores contribución",
        "API integración microservicios arquitectura escalabilidad backend",
        "inflación tasas interés banco central política monetaria",
        "bolsa acciones mercado valores inversión rendimiento portafolio",
        "desempleo recesión económica crisis laboral empleo informal",
        "comercio exportaciones importaciones balanza aranceles tratado",
        "PIB producto interno bruto crecimiento económico trimestre",
        "deuda pública déficit fiscal presupuesto ingresos gasto gobierno",
        "petróleo crudo precio barril energía hidrocarburos gasolina",
        "remesas migrantes divisas economía familiar ingreso hogares",
        "tipo de cambio dólar peso moneda divisas reservas internacionales",
        "investigación científica laboratorio experimento publicación revista",
        "cambio climático emisiones carbono calentamiento temperatura global",
        "vacuna medicamento ensayo clínico pacientes tratamiento hospital",
        "espacio cohete satélite misión astronauta exploración lunar",
        "genética ADN edición genómica CRISPR biotecnología laboratorio",
        "partículas física cuántica experimento acelerador descubrimiento",
        "oceáno biología marina ecosistema conservación especies biodiversidad",
        "energía renovable solar eólica sustentable medio ambiente",
        "neurociencia cerebro neuronas cognición memoria investigación",
        "planeta marte exploración rover NASA agencia espacial",
        "elecciones presidente candidato partido campaña votación democracia",
        "congreso legisladores reforma ley aprobación dictamen senado",
        "seguridad policía crimen organizado justicia tribunal sentencia",
        "gobierno programa social presupuesto política pública decreto",
        "derechos humanos constitución garantías libertades ciudadanía",
        "municipio alcalde gobernador administración estatal federalismo",
        "diplomacia embajador tratado internacional relaciones exteriores",
        "partido político oposición coalición mayoría legislativa",
        "voto electoral padrón casilla observación proceso democrático",
        "transparencia rendición cuentas anticorrupción fiscalización",
    ],
    'etiquetas': (
        ['tecnologia'] * 11
        + ['economia'] * 9
        + ['ciencia'] * 10
        + ['politica'] * 10
    ),
}

# ============================================================================
# Noticias de prueba (simulan textos nuevos no vistos)
# ============================================================================
NUEVAS_NOTICIAS = [
    "nueva aplicación de machine learning para detectar fraudes bancarios",
    "el presidente anunció reformas al sistema de justicia y al poder judicial",
    "los mercados cerraron con pérdidas por tercer día consecutivo",
    "investigadores descubren nueva especie marina en el océano pacífico",
    "empresa de software lanza plataforma de inteligencia artificial en la nube",
    "el senado aprobó la reforma educativa con mayoría absoluta",
    "la tasa de inflación se ubicó en 4.5 por ciento anual",
    "el cambio climático acelera el derretimiento de los glaciares",
    "startup mexicana recibe inversión de capital de riesgo internacional",
    "la corte suprema emitió un fallo histórico sobre derechos laborales",
]


# ============================================================================
# Demostración
# ============================================================================
if __name__ == '__main__':
    print("=" * 65)
    print("  AC-3: Clasificador Multimodelo con Selección Automática")
    print("=" * 65)

    selector = SelectorModelo()
    resultados = selector.evaluar_todos(
        DATOS_AC3['textos'], DATOS_AC3['etiquetas'], cv_folds=5
    )

    print("\n" + selector.reporte())
    print(f"\n  Modelo seleccionado: {selector.mejor_modelo[0]}")

    print(f"\n  Predicciones con el mejor modelo:")
    predicciones = selector.predecir(NUEVAS_NOTICIAS)
    for texto, pred in zip(NUEVAS_NOTICIAS, predicciones):
        print(f"    [{pred:>10}] {texto}")
