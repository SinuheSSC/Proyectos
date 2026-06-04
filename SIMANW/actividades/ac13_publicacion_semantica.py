"""
AC-13: Publicación semántica y validación del grafo (Fase 6)
Exporta el KG en múltiples serializaciones, valida con SHACL,
establece enlaces a datasets externos y genera documentación
para agentes externos.
"""

import json
import os
from datetime import datetime
from rdflib import Graph, Namespace, Literal, URIRef, RDF, RDFS, OWL, XSD
from rdflib.namespace import DC, FOAF, DCTERMS, SKOS, VOID


# ============================================================================
# Construcción del grafo de ejemplo
# ============================================================================
def construir_grafo_ejemplo():
    """Construye un KG completo con noticias, autores, categorías
    y enlaces externos para la demostración de AC-13."""
    import sys
    sys.path.insert(0, os.path.join(os.path.dirname(__file__), '..'))
    from base.knowledge_graph import KnowledgeGraphSIMANW
    from base.datos_abiertos import ConectorDatosAbiertos

    kg = KnowledgeGraphSIMANW()
    DATA = kg.DATA

    noticias = [
        {"titulo": "IA Generativa revoluciona la industria",
         "cuerpo": "Los modelos de lenguaje están transformando la creación de contenido digital en todas las industrias.",
         "fecha": "2026-05-20", "autor": "Redacción Tech",
         "categoria_original": "tecnologia", "url": "https://ejemplo.com/ia-gen"},
        {"titulo": "Mercados financieros en recuperación económica",
         "cuerpo": "Los principales índices bursátiles muestran signos de recuperación.",
         "fecha": "2026-05-19", "autor": "Redacción Economía",
         "categoria_original": "economia", "url": "https://ejemplo.com/economia"},
        {"titulo": "Estudio sobre cambio climático alerta a gobiernos",
         "cuerpo": "Investigadores advierten sobre el acelerado calentamiento global.",
         "fecha": "2026-05-18", "autor": "Redacción Ciencia",
         "categoria_original": "ciencia", "url": "https://ejemplo.com/ciencia"},
    ]

    for n in noticias:
        n['sentimiento'] = {'compound': 0.25, 'etiqueta': 'positivo'}

    for i, n in enumerate(noticias, 1):
        kg.agregar_noticia(n, i)

    # Enlazar con Wikidata (desde AC-7)
    WD = Namespace("http://www.wikidata.org/entity/")
    kg.graph.bind("wd", WD)
    kg.graph.bind("skos", SKOS)

    kg.graph.add((DATA["categoria_tecnologia"], OWL.sameAs, WD["Q11016"]))
    kg.graph.add((DATA["categoria_tecnologia"], SKOS.exactMatch, WD["Q11016"]))
    kg.graph.add((WD["Q11016"], RDFS.label, Literal("Tecnología de la información", lang="es")))

    kg.graph.add((DATA["categoria_economia"], OWL.sameAs, WD["Q159810"]))
    kg.graph.add((DATA["categoria_economia"], SKOS.exactMatch, WD["Q159810"]))
    kg.graph.add((WD["Q159810"], RDFS.label, Literal("Economía", lang="es")))

    kg.graph.add((DATA["categoria_ciencia"], OWL.sameAs, WD["Q336"]))
    kg.graph.add((DATA["categoria_ciencia"], SKOS.exactMatch, WD["Q336"]))
    kg.graph.add((WD["Q336"], RDFS.label, Literal("Ciencia", lang="es")))

    # Cargar datos abiertos
    conector = ConectorDatosAbiertos(kg)
    conector.cargar_dataset_gobierno(
        "Presupuesto TIC 2026",
        [{"monto": 1500.5, "concepto": "Infraestructura digital"}],
        "Secretaría de Hacienda", "tecnologia"
    )
    conector.cargar_dataset_gobierno(
        "Indicadores Económicos 2026",
        [{"pib": 2.8, "inflacion": 4.2}],
        "INEGI", "economia"
    )
    conector.cargar_dataset_gobierno(
        "Emisiones CO2 2026",
        [{"toneladas": 320.5, "fuente": "Transporte"}],
        "SEMARNAT", "ciencia"
    )

    return kg, DATA


# ============================================================================
# 1. EXPORTACIÓN EN MÚLTIPLES SERIALIZACIONES
# ============================================================================
def exportar_serializaciones(kg, directorio='.'):
    """Exporta el grafo en Turtle y JSON-LD."""
    archivos = {}

    turtle = kg.serializar('turtle')
    ruta_ttl = os.path.join(directorio, 'simanw_graph.ttl')
    with open(ruta_ttl, 'w', encoding='utf-8') as f:
        f.write(turtle)
    archivos['turtle'] = ruta_ttl

    jsonld = kg.serializar('json-ld')
    ruta_jsonld = os.path.join(directorio, 'simanw_graph.jsonld')
    with open(ruta_jsonld, 'w', encoding='utf-8') as f:
        f.write(jsonld)
    archivos['json-ld'] = ruta_jsonld

    xml = kg.serializar('xml')
    ruta_xml = os.path.join(directorio, 'simanw_graph.rdf')
    with open(ruta_xml, 'w', encoding='utf-8') as f:
        f.write(xml)
    archivos['xml'] = ruta_xml

    return archivos


# ============================================================================
# 2. GLOSARIO DE LA ONTOLOGÍA
# ============================================================================
GLOSARIO_ONTOLOGIA = (
    "GLOSARIO DE LA ONTOLOGÍA SIMANW\n"
    "===============================\n\n"
    "Prefijos:\n"
    "  simanw: http://simanw.org/ontology/  (ontología del SIMANW)\n"
    "  data:   http://simanw.org/data/       (instancias/recursos)\n"
    "  wd:     http://www.wikidata.org/entity/  (Wikidata)\n"
    "  wdt:    http://www.wikidata.org/prop/direct/  (propiedades Wikidata)\n"
    "  dcat:   http://www.w3.org/ns/dcat#    (DCAT: datasets)\n"
    "  gob:    http://datos.gob.mx/          (datos abiertos México)\n"
    "  schema: https://schema.org/           (Schema.org)\n"
    "  dc:     http://purl.org/dc/elements/1.1/  (Dublin Core)\n"
    "  foaf:   http://xmlns.com/foaf/0.1/    (FOAF: personas)\n"
    "  skos:   http://www.w3.org/2004/02/skos/core#  (SKOS)\n\n"
    "Clases:\n"
    "  simanw:Noticia     - Una noticia procesada por el sistema\n"
    "  simanw:Autor       - Persona que escribió la noticia\n"
    "  simanw:Categoria   - Categoría temática de la noticia\n"
    "  simanw:Fuente      - Sitio de origen de la noticia\n"
    "  dcat:Dataset       - Conjunto de datos abiertos\n\n"
    "Propiedades de objeto:\n"
    "  simanw:tieneAutor       - Relaciona una noticia con su autor\n"
    "  simanw:tieneCategoria   - Relaciona una noticia con su categoría\n"
    "  simanw:provieneDe       - Relaciona una noticia con su fuente\n"
    "  simanw:relacionadaCon   - Relaciona dos noticias entre sí\n"
    "  owl:sameAs              - Identidad entre recurso local y externo\n"
    "  skos:exactMatch         - Correspondencia exacta con concepto externo\n\n"
    "Propiedades de datos:\n"
    "  dc:title                - Título de la noticia o dataset\n"
    "  dc:description          - Resumen o cuerpo de la noticia\n"
    "  dc:date                 - Fecha de publicación\n"
    "  dc:publisher            - Publicador del dataset\n"
    "  foaf:name               - Nombre del autor\n"
    "  simanw:sentimientoScore - Puntuación numérica de sentimiento\n"
    "  simanw:sentimientoEtiqueta - Etiqueta textual de sentimiento\n"
    "  simanw:urlOriginal      - URL original de la noticia\n"
)


# ============================================================================
# 3. CONSULTAS SPARQL DE NEGOCIO
# ============================================================================
CONSULTAS_SPARQL = [
    {
        "nombre": "Noticias agrupadas por autor y categoría",
        "pregunta_negocio": (
            "¿Qué autores han escrito sobre cada categoría?"
        ),
        "query": """
        PREFIX simanw: <http://simanw.org/ontology/>
        PREFIX dc: <http://purl.org/dc/elements/1.1/>
        PREFIX foaf: <http://xmlns.com/foaf/0.1/>
        PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>

        SELECT ?autor ?categoria (COUNT(?noticia) AS ?total)
        WHERE {
            ?noticia a simanw:Noticia ;
                     simanw:tieneAutor ?autor_uri ;
                     simanw:tieneCategoria ?cat_uri .
            ?autor_uri foaf:name ?autor .
            ?cat_uri rdfs:label ?categoria .
        }
        GROUP BY ?autor ?categoria
        ORDER BY DESC(?total)
        """,
    },
    {
        "nombre": "Distribución de sentimiento por categoría",
        "pregunta_negocio": (
            "¿Cuál es el sentimiento promedio de las noticias "
            "en cada categoría?"
        ),
        "query": """
        PREFIX simanw: <http://simanw.org/ontology/>
        PREFIX dc: <http://purl.org/dc/elements/1.1/>
        PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>

        SELECT ?categoria (AVG(?score) AS ?sentimiento_promedio)
               (COUNT(?noticia) AS ?total_noticias)
        WHERE {
            ?noticia a simanw:Noticia ;
                     simanw:tieneCategoria ?cat_uri ;
                     simanw:sentimientoScore ?score .
            ?cat_uri rdfs:label ?categoria .
        }
        GROUP BY ?categoria
        ORDER BY DESC(?sentimiento_promedio)
        """,
    },
    {
        "nombre": "Enlaces entre noticias y datasets abiertos",
        "pregunta_negocio": (
            "¿Qué noticias del SIMANW se relacionan con datasets "
            "de datos abiertos del gobierno?"
        ),
        "query": """
        PREFIX simanw: <http://simanw.org/ontology/>
        PREFIX dc: <http://purl.org/dc/elements/1.1/>
        PREFIX dcat: <http://www.w3.org/ns/dcat#>
        PREFIX gob: <http://datos.gob.mx/>
        PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>

        SELECT ?noticia_titulo ?dataset_titulo ?tema
        WHERE {
            ?noticia a simanw:Noticia ;
                     dc:title ?noticia_titulo ;
                     simanw:tieneCategoria ?cat .
            ?cat rdfs:label ?cat_label .
            ?ds a dcat:Dataset ;
                dc:title ?dataset_titulo ;
                gob:tema ?tema .
            FILTER(CONTAINS(LCASE(?tema), LCASE(?cat_label)))
        }
        """,
    },
]


# ============================================================================
# 4. VALIDACIÓN SHACL
# ============================================================================
SHAPES_SHACL = """
@prefix sh: <http://www.w3.org/ns/shacl#> .
@prefix simanw: <http://simanw.org/ontology/> .
@prefix dc: <http://purl.org/dc/elements/1.1/> .
@prefix xsd: <http://www.w3.org/2001/XMLSchema#> .

simanw:NoticiaShape
    a sh:NodeShape ;
    sh:targetClass simanw:Noticia ;
    sh:property [
        sh:path dc:title ;
        sh:minCount 1 ;
        sh:maxCount 1 ;
        sh:datatype xsd:string ;
        sh:message "Toda noticia debe tener exactamente un título."
    ] ;
    sh:property [
        sh:path simanw:tieneAutor ;
        sh:minCount 1 ;
        sh:message "Toda noticia debe tener al menos un autor."
    ] ;
    sh:property [
        sh:path simanw:tieneCategoria ;
        sh:minCount 1 ;
        sh:maxCount 1 ;
        sh:message "Toda noticia debe tener exactamente una categoría."
    ] ;
    sh:property [
        sh:path simanw:sentimientoScore ;
        sh:datatype xsd:float ;
        sh:minInclusive -1.0 ;
        sh:maxInclusive 1.0 ;
        sh:message "El sentimiento debe estar entre -1.0 y 1.0."
    ] .
"""


def validar_con_shacl(kg):
    """Valida el grafo contra las reglas SHACL."""
    try:
        from pyshacl import validate
        shapes_graph = Graph().parse(data=SHAPES_SHACL, format='turtle')
        conforms, results_graph, results_text = validate(
            kg.graph, shacl_graph=shapes_graph,
            inference='rdfs', abort_on_first=False,
        )
        return {
            'conforme': conforms,
            'resultados': results_text,
        }
    except ImportError:
        # Fallback: validación manual si pyshacl no está instalado
        return validar_manual(kg)


def validar_manual(kg):
    """Validación manual de restricciones cuando pyshacl no está disponible."""
    violaciones = []
    ns = Namespace("http://simanw.org/ontology/")
    DATA = Namespace("http://simanw.org/data/")

    for s, p, o in kg.graph.triples((None, RDF.type, ns.Noticia)):
        uri = str(s)
        titulos = list(kg.graph.triples((s, DC.title, None)))
        if not titulos:
            violaciones.append(f"  - {uri}: falta dc:title (obligatorio)")

        autores = list(kg.graph.triples((s, ns.tieneAutor, None)))
        if not autores:
            violaciones.append(f"  - {uri}: falta simanw:tieneAutor (obligatorio)")

        categorias = list(kg.graph.triples((s, ns.tieneCategoria, None)))
        if not categorias:
            violaciones.append(f"  - {uri}: falta simanw:tieneCategoria (obligatorio)")

        scores = list(kg.graph.triples((s, ns.sentimientoScore, None)))
        for _, _, score in scores:
            try:
                val = float(score)
                if val < -1.0 or val > 1.0:
                    violaciones.append(
                        f"  - {uri}: sentimientoScore={val} fuera de rango [-1,1]"
                    )
            except (ValueError, TypeError):
                violaciones.append(
                    f"  - {uri}: sentimientoScore no es numérico"
                )

    return {
        'conforme': len(violaciones) == 0,
        'resultados': '\n'.join(violaciones) if violaciones else 'Sin violaciones.',
        'n_violaciones': len(violaciones),
    }


# ============================================================================
# 5. ENLACES A VOCABULARIOS EXTERNOS
# ============================================================================
ENLACES_EXTERNOS_DOC = [
    {
        "uri_local": "data:categoria_tecnologia",
        "uri_externa": "wd:Q11016",
        "vocabulario": "Wikidata",
        "propiedad": "owl:sameAs / skos:exactMatch",
        "justificación": (
            "Q11016 es 'tecnología de la información' en Wikidata, "
            "equivalente semántico directo de nuestra categoría."
        ),
    },
    {
        "uri_local": "data:categoria_economia",
        "uri_externa": "wd:Q159810",
        "vocabulario": "Wikidata",
        "propiedad": "owl:sameAs / skos:exactMatch",
        "justificación": (
            "Q159810 es 'economía' en Wikidata, "
            "equivalente directo de nuestra categoría."
        ),
    },
    {
        "uri_local": "data:categoria_ciencia",
        "uri_externa": "wd:Q336",
        "vocabulario": "Wikidata",
        "propiedad": "owl:sameAs / skos:exactMatch",
        "justificación": (
            "Q336 es 'ciencia' en Wikidata, "
            "cubre el alcance de nuestra categoría."
        ),
    },
    {
        "uri_local": "data:autor_Redacción_Tech",
        "uri_externa": "schema:Person",
        "vocabulario": "Schema.org",
        "propiedad": "rdf:type",
        "justificación": (
            "Schema.org/Person es el tipo estándar para personas "
            "en la Web Semántica, usado por buscadores y asistentes."
        ),
    },
    {
        "uri_local": "data:noticia_1 (dc:title / dc:date)",
        "uri_externa": "schema:NewsArticle",
        "vocabulario": "Schema.org",
        "propiedad": "rdf:type (proyección)",
        "justificación": (
            "schema:NewsArticle permitiría a motores de búsqueda "
            "indexar la noticia como artículo periodístico. "
            "Las propiedades dc:title y dc:date tienen "
            "correspondencia directa con schema:headline y "
            "schema:datePublished."
        ),
    },
    {
        "uri_local": "gob:dataset/Presupuesto_TIC_2026",
        "uri_externa": "dcat:Dataset",
        "vocabulario": "DCAT",
        "propiedad": "rdf:type",
        "justificación": (
            "DCAT es el vocabulario W3C para catálogos de datos "
            "públicos. Usamos dcat:Dataset para que el dataset "
            "sea interoperable con otros catálogos de datos abiertos."
        ),
    },
]


# ============================================================================
# 6. FRAGMENTO JSON-LD
# ============================================================================
FRAGMENTO_JSONLD = {
    "@context": {
        "simanw": "http://simanw.org/ontology/",
        "data": "http://simanw.org/data/",
        "dc": "http://purl.org/dc/elements/1.1/",
        "foaf": "http://xmlns.com/foaf/0.1/",
        "rdf": "http://www.w3.org/1999/02/22-rdf-syntax-ns#",
        "rdfs": "http://www.w3.org/2000/01/rdf-schema#",
        "xsd": "http://www.w3.org/2001/XMLSchema#",
        "wd": "http://www.wikidata.org/entity/",
        "schema": "https://schema.org/",
        "tieneAutor": {"@id": "simanw:tieneAutor", "@type": "@id"},
        "tieneCategoria": {"@id": "simanw:tieneCategoria", "@type": "@id"},
    },
    "@graph": [
        {
            "@id": "data:noticia_1",
            "@type": "simanw:Noticia",
            "dc:title": "IA Generativa revoluciona la industria",
            "dc:description": "Los modelos de lenguaje están transformando la creación de contenido digital en todas las industrias.",
            "dc:date": {"@value": "2026-05-20", "@type": "xsd:date"},
            "simanw:tieneAutor": "data:autor_Redacción_Tech",
            "simanw:tieneCategoria": "data:categoria_tecnologia",
            "simanw:sentimientoScore": {"@value": 0.25, "@type": "xsd:float"},
            "simanw:sentimientoEtiqueta": "positivo",
        },
        {
            "@id": "data:autor_Redacción_Tech",
            "@type": "simanw:Autor",
            "foaf:name": "Redacción Tech",
        },
        {
            "@id": "data:categoria_tecnologia",
            "@type": "simanw:Categoria",
            "rdfs:label": "tecnologia",
            "owl:sameAs": "wd:Q11016",
        },
    ],
}


# ============================================================================
# 7. DESCUBRIMIENTO Y REUSO POR AGENTES EXTERNOS
# ============================================================================
TEXTO_DESCUBRIMIENTO = (
    "DESCUBRIMIENTO Y REUSO POR AGENTES EXTERNOS\n"
    "============================================\n\n"
    "Un agente externo (aplicación, buscador semántico o "
    "web crawler) puede descubrir y reutilizar los datos "
    "del SIMANW sin acceso al código fuente mediante los "
    "siguientes mecanismos:\n\n"
    "1. Publicación en un endpoint SPARQL: Si el archivo "
    "Turtle se despliega en un servidor con Apache Jena "
    "Fuseki o similar, cualquier agente puede consultar "
    "el grafo mediante queries SPARQL HTTP GET/POST "
    "en el endpoint público. El archivo simanw_graph.ttl "
    "contiene la totalidad de los datos.\n\n"
    "2. Dereferenciación de URIs: Cada recurso usa una URI "
    "desreferenciable (http://simanw.org/data/noticia_1). "
    "Si se configura negociación de contenido, un agente "
    "que solicite ese URI recibiría una representación RDF "
    "(Turtle o JSON-LD) permitiendo navegar el grafo "
    "siguiendo enlaces.\n\n"
    "3. Enlaces a vocabularios estándar: Los enlaces "
    "owl:sameAs y skos:exactMatch hacia Wikidata (Q11016, "
    "Q159810, Q336) permiten a cualquier agente descubrir "
    "el grafo local desde Wikidata mediante consultas "
    "inversas. Los tipos rdf:type hacia schema:Person y "
    "dcat:Dataset hacen que los datos sean comprensibles "
    "por buscadores como Google Dataset Search.\n\n"
    "4. Documentación publicada: El glosario de la ontología "
    "(prefijos, clases, propiedades) sirve como punto de "
    "partida para que un desarrollador entienda la "
    "estructura de los datos sin inspeccionar el código.\n\n"
    "5. Serializaciones múltiples: Los formatos Turtle, "
    "RDF/XML y JSON-LD cubren los principales ecosistemas "
    "de procesamiento RDF. JSON-LD en particular permite "
    "integrar los datos directamente en páginas web "
    "existentes mediante etiquetas <script type=\"application/ld+json\">.\n\n"
    "6. Vocabularios justificados: DCAT para datasets "
    "gubernamentales, FOAF para autores y Dublin Core "
    "para metadatos bibliográficos son estándares "
    "ampliamente soportados que garantizan interoperabilidad."
)


# ============================================================================
# DEMOSTRACIÓN
# ============================================================================
if __name__ == '__main__':
    DIR_SALIDA = os.path.join(os.path.dirname(__file__), '..')

    print("=" * 65)
    print("  AC-13: Publicación Semántica y Validación del Grafo")
    print("=" * 65)

    # Construir grafo
    kg, DATA = construir_grafo_ejemplo()
    print(f"\n  Grafo construido: {kg.total_triples()} triples")

    # 1. Exportar serializaciones
    print(f"\n  1. Exportando serializaciones...")
    archivos = exportar_serializaciones(kg, DIR_SALIDA)
    for fmt, ruta in archivos.items():
        tamaño = os.path.getsize(ruta)
        print(f"     {fmt:<10} -> {os.path.basename(ruta)} ({tamaño} bytes)")

    # 2. Glosario
    print(f"\n  2. Glosario de la ontología (extracto):")
    for linea in GLOSARIO_ONTOLOGIA.split('\n')[:8]:
        print(f"     {linea}")

    # 3. Consultas SPARQL
    print(f"\n  3. Consultas SPARQL de negocio:")
    for i, c in enumerate(CONSULTAS_SPARQL, 1):
        print(f"\n     Consulta {i}: {c['nombre']}")
        print(f"     Pregunta: {c['pregunta_negocio']}")
        try:
            resultados = kg.consultar(c['query'])
            if resultados:
                print(f"     Resultados: {len(resultados)} filas")
                for fila in resultados[:3]:
                    print(f"       {[str(v)[:30] for v in fila]}")
            else:
                print(f"     (sin resultados para esta demo)")
        except Exception as e:
            print(f"     Error: {e}")

    # 4. Validación SHACL
    print(f"\n  4. Validación SHACL:")
    validacion = validar_con_shacl(kg)
    print(f"     Conforme: {validacion['conforme']}")
    if not validacion['conforme']:
        print(f"     Violaciones:")
        for linea in validacion['resultados'].split('\n')[:5]:
            print(f"       {linea}")
    else:
        print(f"     Sin violaciones encontradas.")

    # 5. Enlaces externos
    print(f"\n  5. Enlaces a vocabularios externos:")
    print(f"     ({len(ENLACES_EXTERNOS_DOC)} enlaces documentados)")
    for e in ENLACES_EXTERNOS_DOC:
        print(f"     {e['uri_local']} --{e['propiedad']}--> {e['uri_externa']}")
        print(f"       [{e['vocabulario']}] {e['justificación'][:70]}...")

    # 6. JSON-LD fragment
    print(f"\n  6. Fragmento JSON-LD:")
    print(f"     {json.dumps(FRAGMENTO_JSONLD, ensure_ascii=False, indent=2)[:200]}...")

    # 7. Texto de descubrimiento
    print(f"\n  7. Descubrimiento y reuso (extracto):")
    for linea in TEXTO_DESCUBRIMIENTO.split('\n')[:5]:
        print(f"     {linea}")

    print(f"\n  {'=' * 65}")
    print(f"  AC-13 COMPLETADO")
    print(f"  Archivos generados:")
    for fmt, ruta in archivos.items():
        print(f"    - {ruta}")
