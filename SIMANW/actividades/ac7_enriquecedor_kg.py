from rdflib import Graph, Namespace, Literal, URIRef, RDF, RDFS, OWL, XSD
from rdflib.namespace import DC, FOAF, SKOS


class EnriquecedorKG:
    """
    AC-7: Enriquece el Knowledge Graph local conectando
    entidades con Wikidata/DBpedia mediante enlaces semánticos.
    """

    def __init__(self, knowledge_graph):
        self.kg = knowledge_graph
        self.WD = Namespace("http://www.wikidata.org/entity/")
        self.WDT = Namespace("http://www.wikidata.org/prop/direct/")
        self.kg.graph.bind("wd", self.WD)
        self.kg.graph.bind("wdt", self.WDT)
        self.enlaces_externos = []

    def enlazar_entidad(self, entidad_local, wikidata_id, etiqueta):
        """Enlaza una entidad local con su equivalente en Wikidata."""
        self.kg.graph.add((entidad_local, OWL.sameAs, self.WD[wikidata_id]))
        self.kg.graph.add((entidad_local, SKOS.exactMatch, self.WD[wikidata_id]))
        self.kg.graph.add((self.WD[wikidata_id], RDFS.label,
                          Literal(etiqueta, lang="es")))
        self.enlaces_externos.append({
            'local': str(entidad_local),
            'wikidata': wikidata_id,
            'etiqueta': etiqueta,
        })

    def agregar_datos_externos(self, entidad_local, propiedades):
        """Agrega propiedades obtenidas de fuentes externas."""
        for prop, valor in propiedades.items():
            if isinstance(valor, str):
                self.kg.graph.add(
                    (entidad_local, self.WDT[prop], Literal(valor, lang="es"))
                )
            else:
                self.kg.graph.add(
                    (entidad_local, self.WDT[prop], Literal(valor))
                )

    def consulta_enriquecimiento(self):
        """Query SPARQL para verificar enlaces externos creados."""
        query = """
        PREFIX owl: <http://www.w3.org/2002/07/owl#>
        PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
        PREFIX skos: <http://www.w3.org/2004/02/skos/core#>

        SELECT ?local ?externo ?etiqueta
        WHERE {
            ?local owl:sameAs ?externo .
            ?externo rdfs:label ?etiqueta .
        }
        """
        return list(self.kg.graph.query(query))

    def consulta_categorias_enriquecidas(self):
        """SPARQL que muestra categorías con datos enriquecidos."""
        query = """
        PREFIX simanw: <http://simanw.org/ontology/>
        PREFIX data: <http://simanw.org/data/>
        PREFIX wdt: <http://www.wikidata.org/prop/direct/>

        SELECT ?categoria ?propiedad ?valor
        WHERE {
            ?categoria a simanw:Categoria .
            ?categoria wdt:?propiedad ?valor .
        }
        """
        try:
            return list(self.kg.graph.query(query))
        except Exception:
            return []

    def generar_query_wikidata(self, tema):
        """Genera consultas SPARQL para Wikidata según el tema."""
        queries = {
            'tecnologia': """
# Herramientas de NLP en Wikidata
SELECT ?item ?itemLabel ?description WHERE {
  ?item wdt:P31 wd:Q7397 .          # instancia de software
  ?item wdt:P366 wd:Q30642 .        # uso: NLP
  SERVICE wikibase:label { bd:serviceParam wikibase:language "es,en". }
}
LIMIT 10""",
            'ciencia': """
# Artículos sobre cambio climático
SELECT ?item ?itemLabel ?date WHERE {
  ?item wdt:P31 wd:Q13442814 .      # artículo científico
  ?item wdt:P921 wd:Q7942 .         # tema: cambio climático
  ?item wdt:P577 ?date .
  FILTER(YEAR(?date) >= 2024)
  SERVICE wikibase:label { bd:serviceParam wikibase:language "es,en". }
}
LIMIT 10""",
            'economia': """
# Indicadores económicos en Wikidata
SELECT ?item ?itemLabel ?value WHERE {
  ?item wdt:P31 wd:Q8134 .          # instancia de indicador económico
  SERVICE wikibase:label { bd:serviceParam wikibase:language "es,en". }
}
LIMIT 10""",
        }
        return queries.get(tema, "# No hay consulta predefinida para este tema")


# ============================================================================
# Demostración
# ============================================================================
if __name__ == '__main__':
    import sys
    import os
    sys.path.insert(0, os.path.join(os.path.dirname(__file__), '..'))
    from base.knowledge_graph import KnowledgeGraphSIMANW

    kg = KnowledgeGraphSIMANW()
    DATA = kg.DATA

    noticias_ejemplo = [
        {"titulo": "IA Generativa revoluciona la industria",
         "cuerpo": "Los modelos de lenguaje están transformando la creación de contenido digital.",
         "fecha": "2026-05-20", "autor": "Redacción Tech",
         "categoria_original": "tecnologia", "url": "https://ejemplo.com/ia"},
        {"titulo": "Mercados financieros en recuperación",
         "cuerpo": "Los principales índices bursátiles muestran signos de recuperación económica.",
         "fecha": "2026-05-19", "autor": "Redacción Economía",
         "categoria_original": "economia", "url": "https://ejemplo.com/economia"},
        {"titulo": "Nuevo estudio sobre cambio climático",
         "cuerpo": "Investigadores advierten sobre el acelerado calentamiento global.",
         "fecha": "2026-05-18", "autor": "Redacción Ciencia",
         "categoria_original": "ciencia", "url": "https://ejemplo.com/ciencia"},
    ]

    for i, n in enumerate(noticias_ejemplo):
        kg.agregar_noticia(n, i + 1)

    enriquecedor = EnriquecedorKG(kg)

    enriquecedor.enlazar_entidad(
        DATA["categoria_tecnologia"], "Q11016", "Tecnología de la información"
    )
    enriquecedor.enlazar_entidad(
        DATA["categoria_economia"], "Q159810", "Economía"
    )
    enriquecedor.enlazar_entidad(
        DATA["categoria_ciencia"], "Q336", "Ciencia"
    )

    enriquecedor.agregar_datos_externos(DATA["categoria_tecnologia"], {
        "P279": "Sector económico: servicios",
        "P910": "Categoría: Tecnología de la información",
    })
    enriquecedor.agregar_datos_externos(DATA["categoria_economia"], {
        "P279": "Ciencias sociales",
        "P910": "Categoría: Economía",
    })

    # --- Reporte ---
    print("=" * 65)
    print("  AC-7: Enriquecimiento del KG con Wikidata")
    print("=" * 65)

    print(f"\n  Triples totales tras enriquecimiento: {kg.total_triples()}")
    print(f"  Enlaces externos creados: {len(enriquecedor.enlaces_externos)}")

    print("\n  Enlaces locales -> Wikidata:")
    for enlace in enriquecedor.enlaces_externos:
        local_short = enlace['local'].split('/')[-1]
        print(f"    {local_short} -> {enlace['wikidata']} "
              f"({enlace['etiqueta']})")

    print("\n  Verificación SPARQL (owl:sameAs):")
    for row in enriquecedor.consulta_enriquecimiento():
        local_short = str(row.local).split('/')[-1]
        externo_short = str(row.externo).split('/')[-1]
        print(f"    {local_short} -> {externo_short} ({row.etiqueta})")

    print("\n  KG serializado (Turtle) - extracto:")
    turtle = kg.serializar('turtle')
    for linea in turtle.split('\n'):
        if any(w in linea for w in ['sameAs', 'exactMatch', 'wdt:',
                                     'categoria_tecnologia',
                                     'categoria_economia',
                                     'categoria_ciencia']):
            print(f"    {linea}")

    print("\n  Query sugerida para Wikidata (tecnología):")
    print(enriquecedor.generar_query_wikidata('tecnologia'))
