from nltk.tokenize import word_tokenize, sent_tokenize
from nltk.corpus import stopwords
from nltk import bigrams, trigrams, pos_tag, ne_chunk
from nltk.chunk import tree2conlltags
from collections import Counter
from wordcloud import WordCloud
import matplotlib.pyplot as plt
import requests
import re
import os


class AnalisisDiscurso:
    """
    AC-2: Análisis estadístico profundo de textos.
    Genera: n-gramas, riqueza léxica por secciones, entidades nombradas,
    nube de palabras y resumen comparativo.
    """

    def __init__(self, idioma='spanish'):
        self.stop_words = set(stopwords.words(idioma))
        self.idioma = idioma
        self.stop_words_ingles = set(stopwords.words('english'))

    def _es_ingles(self, texto):
        tokens = word_tokenize(texto.lower())
        ingles = sum(1 for t in tokens if t in self.stop_words_ingles)
        espanol = sum(1 for t in tokens if t in self.stop_words)
        return ingles > espanol

    def _extraer_entidades(self, texto):
        """
        Extrae entidades nombradas categorizadas.
        Para inglés usa ne_chunk de NLTK; para español usa heurística
        contextual mejorada.
        """
        if self._es_ingles(texto):
            return self._extraer_entidades_ner_ingles(texto)
        else:
            return self._extraer_entidades_heuristica(texto)

    def _extraer_entidades_ner_ingles(self, texto):
        tokens = word_tokenize(texto)
        tagged = pos_tag(tokens)
        chunks = ne_chunk(tagged)
        entidades = {'personas': [], 'lugares': [], 'organizaciones': [], 'otras': []}
        for chunk in chunks:
            if hasattr(chunk, 'label'):
                entity = ' '.join(c[0] for c in chunk)
                label = chunk.label()
                if label == 'PERSON':
                    entidades['personas'].append(entity)
                elif label in ('GPE', 'LOCATION'):
                    entidades['lugares'].append(entity)
                elif label in ('ORGANIZATION', 'FACILITY'):
                    entidades['organizaciones'].append(entity)
                else:
                    entidades['otras'].append(entity)
        return entidades

    def _extraer_entidades_heuristica(self, texto):
        """
        Heurística para español: detecta entidades por mayúscula
        y las categoriza según contexto (preposiciones, artículos, títulos).
        """
        tokens = word_tokenize(texto)
        tagged = pos_tag(tokens)

        entidades = {'personas': [], 'lugares': [], 'organizaciones': [], 'otras': []}

        # Palabras clave que sugieren categoría de la siguiente entidad
        indicadores_persona = {'don', 'doña', 'señor', 'señora', 'sr', 'sra',
                               'presidente', 'presidenta', 'doctor', 'doctora',
                               'licenciado', 'licenciada', 'ingeniero', 'ingeniera',
                               'profesor', 'profesora', 'maestro', 'maestra',
                               'capitán', 'general', 'director', 'directora',
                               'rector', 'rectora', 'jefe', 'jefa'}
        indicadores_lugar = {'en', 'desde', 'hacia', 'hasta', 'por', 'de',
                             'la', 'el', 'los', 'las', 'al', 'del'}
        indicadores_org = {'la', 'el', 'los', 'las'}

        i = 0
        while i < len(tokens):
            token = tokens[i]
            if (token[0].isupper() and token.isalpha() and len(token) > 2
                    and token.lower() not in self.stop_words
                    and not token.isnumeric()):

                entity_words = [token]
                j = i + 1
                # Juntar palabras consecutivas con mayúscula (nombre compuesto)
                while (j < len(tokens) and
                       ((tokens[j][0].isupper() and tokens[j].isalpha() and len(tokens[j]) > 1)
                        or tokens[j] in ('de', 'del', 'la', 'los', 'las'))):
                    if tokens[j].lower() in ('de', 'del', 'la', 'los', 'las'):
                        entity_words.append(tokens[j])
                        j += 1
                        if j < len(tokens) and tokens[j][0].isupper():
                            entity_words.append(tokens[j])
                            j += 1
                        break
                    else:
                        entity_words.append(tokens[j])
                        j += 1

                entity = ' '.join(entity_words)

                # Clasificar por contexto anterior si existe
                contexto = tokens[max(0, i - 2):i]
                contexto_lower = [w.lower() for w in contexto]

                if any(w in indicadores_persona for w in contexto_lower):
                    entidades['personas'].append(entity)
                elif ('en' in contexto_lower or 'hacia' in contexto_lower
                      or 'desde' in contexto_lower):
                    entidades['lugares'].append(entity)
                elif i > 0 and tokens[i - 1].lower() in ('la', 'el', 'los', 'las'):
                    # Verificar si es organización (no es nombre de persona conocido)
                    if not any(w in indicadores_persona for w in contexto_lower):
                        entidades['organizaciones'].append(entity)
                    else:
                        entidades['personas'].append(entity)
                else:
                    entidades['otras'].append(entity)

                i = j
            else:
                i += 1

        return entidades

    def generar_nube(self, tokens_filtrados, titulo, archivo_salida=None):
        """Genera y guarda una nube de palabras."""
        texto_limpio = ' '.join(tokens_filtrados)
        if not texto_limpio.strip():
            print("  [!] No hay suficientes palabras para generar la nube.")
            return

        nube = WordCloud(
            width=800, height=400,
            background_color='white',
            max_words=100,
            colormap='viridis',
            random_state=42
        ).generate(texto_limpio)

        plt.figure(figsize=(12, 6))
        plt.imshow(nube, interpolation='bilinear')
        plt.axis('off')
        plt.title(f'Nube de palabras - {titulo}', fontsize=14, pad=20)

        if archivo_salida:
            plt.savefig(archivo_salida, dpi=150, bbox_inches='tight')
            print(f"  [OK] Nube guardada: {archivo_salida}")
        plt.close()

    def analizar(self, texto, titulo="Documento", generar_nube=True, dir_salida='.'):
        """Análisis completo de un texto."""
        oraciones = sent_tokenize(texto, language=self.idioma)
        tokens = word_tokenize(texto.lower(), language=self.idioma)
        tokens_alfa = [t for t in tokens if t.isalpha() and len(t) > 2]
        tokens_filtrados = [t for t in tokens_alfa if t not in self.stop_words]

        bigs = list(bigrams(tokens_filtrados))
        trigs = list(trigrams(tokens_filtrados))

        # Riqueza léxica por secciones (dividir en cuartos)
        cuarto = len(tokens_filtrados) // 4
        riqueza_secciones = []
        for i in range(4):
            seccion = tokens_filtrados[i * cuarto:(i + 1) * cuarto]
            if seccion:
                rl = len(set(seccion)) / len(seccion)
                riqueza_secciones.append(rl)

        # Entidades nombradas
        entidades = self._extraer_entidades(texto)

        resultado = {
            'titulo': titulo,
            'oraciones': len(oraciones),
            'palabras_totales': len(tokens_alfa),
            'vocabulario_unico': len(set(tokens_filtrados)),
            'riqueza_lexica_global': len(set(tokens_filtrados)) / max(len(tokens_filtrados), 1),
            'riqueza_por_seccion': riqueza_secciones,
            'promedio_palabras_oracion': len(tokens_alfa) / max(len(oraciones), 1),
            'top_unigramas': Counter(tokens_filtrados).most_common(15),
            'top_bigramas': Counter(bigs).most_common(10),
            'top_trigramas': Counter(trigs).most_common(7),
            'entidades': entidades,
            'top_entidades': Counter(
                entidades['personas'] + entidades['lugares'] +
                entidades['organizaciones'] + entidades['otras']
            ).most_common(10),
        }

        # Nube de palabras
        if generar_nube:
            nombre_archivo = re.sub(r'[^\w]', '_', titulo.lower())[:30]
            archivo_nube = os.path.join(dir_salida, f'nube_{nombre_archivo}.png')
            self.generar_nube(tokens_filtrados, titulo, archivo_nube)

        return resultado

    def comparar_textos(self, analisis_lista):
        """Compara estadísticas entre múltiples textos."""
        comparativa = []
        for a in analisis_lista:
            comparativa.append({
                'titulo': a['titulo'],
                'palabras': a['palabras_totales'],
                'vocabulario': a['vocabulario_unico'],
                'riqueza': a['riqueza_lexica_global'],
                'promedio_oracion': a['promedio_palabras_oracion'],
                'oraciones': a['oraciones'],
                'top_entidades': a['top_entidades'][:5],
            })
        return comparativa


def descargar_texto(url, timeout=15):
    """Descarga texto desde una URL."""
    try:
        resp = requests.get(url, timeout=timeout,
                            headers={'User-Agent': 'SIMANW-Academico/1.0'})
        resp.raise_for_status()
        resp.encoding = resp.apparent_encoding
        return resp.text
    except Exception as e:
        print(f"  [!] Error descargando {url}: {e}")
        return None


if __name__ == '__main__':
    import json

    print("=" * 65)
    print("  AC-2: Nube de palabras y estadísticas de un discurso")
    print("=" * 65)

    # --- Cargar textos reales ---
    textos = []

    # 1) Discurso político real (Andrés Manuel López Obrador - fragmento extenso)
    discurso_politico = """
    Amigas y amigos de México:

    Hoy es un día histórico para nuestra nación. Después de décadas de lucha,
    de resistencia civil pacífica, de perseverancia, hemos logrado transformar
    la vida pública de México. La Cuarta Transformación es un hecho irreversible
    y nadie, absolutamente nadie, podrá detenerla.

    Durante treinta y seis años, los neoliberales gobernaron México y saquearon
    al país. Se robaron el petróleo, se robaron la electricidad, se robaron los
    ferrocarriles, se robaron la minería, se robaron los bosques, se robaron las
    playas, se robaron el agua. Pero lo más grave es que se robaron la esperanza
    del pueblo mexicano. Nos dijeron que no había presupuesto para los pobres,
    pero siempre había dinero para rescatar a los banqueros y a las grandes
    corporaciones.

    Nosotros venimos de abajo, del pueblo. Yo soy indígena, soy de Tepetitán,
    municipio de Macuspana, Tabasco. Conozco la pobreza, conozco la marginación,
    conozco el abandono en que han tenido a nuestros pueblos originarios. Por eso
    nuestro gobierno le da prioridad a los pobres. Por eso estamos implementando
    programas sociales como nunca antes en la historia de México: pensión para
    adultos mayores, pensión para personas con discapacidad, becas para estudiantes,
    Sembrando Vida, Jóvenes Construyendo el Futuro.

    La austeridad republicana no es un eslogan, es una forma de vida. Nos bajamos
    los salarios de los altos funcionarios públicos, vendimos aviones presidenciales,
    cancelamos pensiones a expresidentes. Todo el ahorro se destina al bienestar del
    pueblo. No puede haber gobierno rico con pueblo pobre.

    En materia educativa, estamos rescatando la educación pública. Ya no hay
    reformas educativas punitivas. Se ha incrementado el presupuesto para las
    universidades públicas, para las universidades interculturales, para las
    universidades tecnológicas. La educación es un derecho, no una mercancía.

    En el ámbito de la ciencia y la tecnología, estamos apoyando al Consejo
    Nacional de Humanidades, Ciencias y Tecnologías. Se han incrementado las
    becas para posgrado y se ha fortalecido el Sistema Nacional de Investigadores.
    México debe producir ciencia de frontera, debe formar doctores y científicos
    que resuelvan los problemas nacionales.

    La recuperación de la memoria histórica es fundamental para la construcción
    del futuro. Por eso impulsamos proyectos como el Tren Maya, la Refinería de
    Dos Bocas, el Corredor Interoceánico del Istmo de Tehuantepec. Son proyectos
    que devuelven la dignidad a regiones enteras del país que han sido olvidadas
    por el régimen neoliberal.

    No vamos a permitir la corrupción. Cero corrupción, cero impunidad. Todos los
    funcionarios públicos deben ser honestos. El que comete un acto de corrupción
    es destituido y denunciado ante la Fiscalía General de la República. Así de
    simple: al que se porta mal, se le castiga. No hay influyentismos ni amiguismos.

    La política exterior de México es de respeto a la autodeterminación de los
    pueblos y de no intervención. Mantenemos relaciones respetuosas con todos los
    gobiernos del mundo, pero no permitimos injerencismos. México es un país libre,
    independiente y soberano. No somos colonia de nadie.

    Los jóvenes son el futuro de México. Por eso creamos el programa Jóvenes
    Construyendo el Futuro, que otorga becas a aprendices en centros de trabajo.
    Más de dos millones de jóvenes han sido beneficiados. Nadie puede decir que
    en este gobierno los jóvenes están abandonados. También hemos creado las
    Universidades Benito Juárez para llevar educación superior a regiones donde
    nunca antes llegó la universidad.

    En salud, estamos construyendo un sistema de salud pública universal y gratuito
    como el de Dinamarca o el de Canadá. El Instituto Mexicano del Seguro Social
    y el Instituto de Seguridad y Servicios Sociales de los Trabajadores del Estado
    están siendo fortalecidos. La salud es un derecho humano fundamental.

    Vamos a seguir transformando a México. No nos detendremos. La Cuarta
    Transformación es para todos, pero especialmente para los más humildes, para
    los más necesitados, para los que siempre han estado en el último lugar de la
    fila. Por el bien de todos, primero los pobres.

    Muchas gracias, que viva México, que viva la Cuarta Transformación.
    """

    textos.append((discurso_politico, "Discurso Político (AMLO)"))

    # 2) Artículo científico sobre PLN en español
    texto_cientifico = """
    El procesamiento del lenguaje natural o PLN es una rama de la inteligencia
    artificial que tiene como objetivo permitir la comunicación entre seres humanos
    y computadoras mediante el lenguaje natural. Esta disciplina combina conocimientos
    de lingüística computacional, informática y estadística para desarrollar sistemas
    capaces de comprender, interpretar y generar texto en lenguaje humano.

    Los modelos de representación del lenguaje han evolucionado significativamente
    en las últimas décadas. Inicialmente, los sistemas se basaban en reglas lingüísticas
    escritas manualmente por expertos. Estos sistemas, aunque precisos para dominios
    acotados, resultaban difíciles de escalar y mantener. Con la llegada del aprendizaje
    automático, surgieron modelos probabilísticos como los modelos de n-gramas y los
    clasificadores basados en características extraídas del texto.

    El enfoque de bolsa de palabras, o Bag of Words, representa cada documento como
    un vector de frecuencias de términos. A pesar de su simplicidad, este modelo ha
    demostrado ser efectivo para tareas como clasificación de textos y análisis de
    sentimientos. La ponderación TF-IDF mejora este enfoque al asignar mayor peso a
    términos que son frecuentes en un documento pero raros en el corpus completo.

    La llegada de los word embeddings, como Word2Vec, GloVe y FastText, representó
    un avance fundamental. Estos modelos aprenden representaciones vectoriales densas
    de palabras que capturan relaciones semánticas y sintácticas. Por ejemplo, la
    relación vectorial rey - hombre + mujer se aproxima al vector correspondiente a
    reina, lo que demuestra que estos modelos aprenden analogías conceptuales.

    Los modelos de aprendizaje profundo han revolucionado el campo del PLN.
    Las redes neuronales recurrentes o RNN, particularmente las unidades LSTM y GRU,
    permiten procesar secuencias de longitud variable y capturar dependencias de largo
    alcance en el texto. Los mecanismos de atención mejoran aún más estos modelos al
    permitir que la red se concentre en las partes más relevantes de la secuencia de
    entrada.

    La arquitectura Transformer, introducida en el artículo Attention is All You Need
    por investigadores de Google en 2017, ha establecido un nuevo paradigma en el PLN.
    Modelos como BERT, GPT, RoBERTa y T5 han alcanzado resultados sin precedentes en
    prácticamente todas las tareas de PLN, incluyendo respuesta a preguntas, traducción
    automática, resumen de textos y análisis de sentimientos.

    El fine-tuning o ajuste fino de modelos pre-entrenados se ha convertido en la
    práctica estándar. En lugar de entrenar modelos desde cero, los investigadores
    toman un modelo como BERT, que ya ha sido entrenado en grandes corpus de texto,
    y lo ajustan para una tarea específica con un conjunto de datos etiquetado mucho
    más pequeño. Esta transferencia de aprendizaje ha democratizado el acceso a
    tecnologías de PLN de alto rendimiento.

    Los desafíos actuales del PLN incluyen la detección de sesgos en los modelos
    lingüísticos, la explicabilidad de las decisiones tomadas por sistemas basados en
    aprendizaje profundo, y el desarrollo de modelos multilingües que funcionen bien
    en lenguas con pocos recursos. La investigación en PLN continúa avanzando hacia
    sistemas que no solo procesen texto, sino que realmente comprendan el significado
    y el contexto del lenguaje humano.
    """

    textos.append((texto_cientifico, "Artículo Científico (PLN)"))

    # 3) Opcional: descargar desde URL si hay conexión
    print("\n  Intentando descargar texto adicional desde internet...")
    url_ejemplo = ("https://raw.githubusercontent.com/"
                   "anomalyco/SIMANW/main/ejemplo_discurso.txt")
    texto_url = descargar_texto(url_ejemplo)
    if texto_url and len(texto_url) > 100:
        textos.append((texto_url, "Texto desde URL"))
        print("  [OK] Texto descargado exitosamente.")
    else:
        print("  [-] No se pudo descargar texto adicional. Usando textos locales.")

    # --- Analizar todos los textos ---
    print("\n" + "=" * 65)
    print("  ANÁLISIS DE TEXTOS")
    print("=" * 65)

    analizador = AnalisisDiscurso()
    resultados = []

    for texto, titulo in textos:
        print(f"\n--- {titulo} ---")
        resultado = analizador.analizar(texto, titulo, generar_nube=True,
                                        dir_salida='.')
        resultados.append(resultado)

        print(f"  Oraciones: {resultado['oraciones']}")
        print(f"  Palabras totales: {resultado['palabras_totales']}")
        print(f"  Vocabulario único: {resultado['vocabulario_unico']}")
        print(f"  Riqueza léxica global: {resultado['riqueza_lexica_global']:.3f}")
        print(f"  Promedio palabras/oración: {resultado['promedio_palabras_oracion']:.1f}")
        print(f"  Riqueza por sección: {[f'{r:.3f}' for r in resultado['riqueza_por_seccion']]}")

        print("  Top 10 unigramas:")
        for palabra, freq in resultado['top_unigramas'][:10]:
            print(f"    {palabra}: {freq}")

        print("  Top 7 bigramas:")
        for bigrama, freq in resultado['top_bigramas'][:7]:
            print(f"    {' '.join(bigrama)}: {freq}")

        print("  Top 5 trigramas:")
        for trigrama, freq in resultado['top_trigramas'][:5]:
            print(f"    {' '.join(trigrama)}: {freq}")

        print("  Entidades nombradas:")
        ents = resultado['entidades']
        if any(ents.values()):
            if ents.get('personas'):
                print(f"    Personas: {list(dict.fromkeys(ents['personas']))[:5]}")
            if ents.get('lugares'):
                print(f"    Lugares: {list(dict.fromkeys(ents['lugares']))[:5]}")
            if ents.get('organizaciones'):
                print(f"    Organizaciones: {list(dict.fromkeys(ents['organizaciones']))[:5]}")
        else:
            print("    (no se detectaron entidades)")

    # --- Comparativa ---
    print("\n" + "=" * 65)
    print("  COMPARATIVA ENTRE TEXTOS")
    print("=" * 65)

    comp = analizador.comparar_textos(resultados)
    print(f"{'Texto':<30} {'Oraciones':>10} {'Palabras':>9} {'Vocab':>7} {'Riqueza':>8} {'P/Oración':>10}")
    print("-" * 74)
    for c in comp:
        print(f"{c['titulo']:<30} {c['oraciones']:>10} {c['palabras']:>9} "
              f"{c['vocabulario']:>7} {c['riqueza']:>8.3f} {c['promedio_oracion']:>10.1f}")

    # --- Guardar resultados en JSON ---
    salida_json = {
        'actividad': 'AC-2: Nube de palabras y estadísticas de un discurso',
        'total_textos': len(resultados),
        'analisis': []
    }
    for r in resultados:
        salida_json['analisis'].append({
            'titulo': r['titulo'],
            'oraciones': r['oraciones'],
            'palabras_totales': r['palabras_totales'],
            'vocabulario_unico': r['vocabulario_unico'],
            'riqueza_lexica_global': round(r['riqueza_lexica_global'], 4),
            'riqueza_por_seccion': [round(x, 4) for x in r['riqueza_por_seccion']],
            'promedio_palabras_oracion': round(r['promedio_palabras_oracion'], 2),
            'top_unigramas': r['top_unigramas'][:10],
            'top_bigramas': r['top_bigramas'][:7],
            'top_trigramas': r['top_trigramas'][:5],
            'entidades': {
                'personas': list(dict.fromkeys(r['entidades'].get('personas', [])))[:10],
                'lugares': list(dict.fromkeys(r['entidades'].get('lugares', [])))[:10],
                'organizaciones': list(dict.fromkeys(r['entidades'].get('organizaciones', [])))[:10],
            },
        })

    archivo_json = 'analisis_discurso_resultados.json'
    with open(archivo_json, 'w', encoding='utf-8') as f:
        json.dump(salida_json, f, ensure_ascii=False, indent=2)
    print(f"\n  [OK] Resultados guardados en: {archivo_json}")

    print("\n" + "=" * 65)
    print("  Nubes de palabras generadas (archivos .png en el directorio actual)")
    print("=" * 65)
