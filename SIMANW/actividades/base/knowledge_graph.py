from rdflib import Graph, Namespace, Literal, URIRef, RDF, RDFS, OWL, XSD
from rdflib.namespace import DC, FOAF, DCTERMS


class KnowledgeGraphSIMANW:
    """Knowledge Graph semántico del sistema SIMANW."""

    def __init__(self):
        self.graph = Graph()
        self.NS = Namespace("http://simanw.org/ontology/")
        self.DATA = Namespace("http://simanw.org/data/")
        self.graph.bind("simanw", self.NS)
        self.graph.bind("data", self.DATA)
        self.graph.bind("dc", DC)
        self.graph.bind("foaf", FOAF)
        self._definir_ontologia()

    def _definir_ontologia(self):
        """Define la ontología del SIMANW."""
        # Clases
        self.graph.add((self.NS.Noticia, RDF.type, OWL.Class))
        self.graph.add((self.NS.Autor, RDF.type, OWL.Class))
        self.graph.add((self.NS.Categoria, RDF.type, OWL.Class))
        self.graph.add((self.NS.Fuente, RDF.type, OWL.Class))

        # Propiedades de objeto
        self.graph.add((self.NS.tieneAutor, RDF.type, OWL.ObjectProperty))
        self.graph.add((self.NS.tieneAutor, RDFS.domain, self.NS.Noticia))
        self.graph.add((self.NS.tieneAutor, RDFS.range, self.NS.Autor))
        self.graph.add((self.NS.tieneCategoria, RDF.type, OWL.ObjectProperty))
        self.graph.add((self.NS.provieneDe, RDF.type, OWL.ObjectProperty))
        self.graph.add((self.NS.relacionadaCon, RDF.type, OWL.ObjectProperty))

        # Propiedades de datos
        self.graph.add((self.NS.sentimientoScore, RDF.type, OWL.DatatypeProperty))
        self.graph.add((self.NS.sentimientoEtiqueta, RDF.type, OWL.DatatypeProperty))
        self.graph.add((self.NS.urlOriginal, RDF.type, OWL.DatatypeProperty))

    def agregar_noticia(self, noticia, noticia_id):
        """Agrega una noticia procesada al knowledge graph."""
        uri = self.DATA[f"noticia_{noticia_id}"]
        self.graph.add((uri, RDF.type, self.NS.Noticia))
        self.graph.add((uri, DC.title, Literal(noticia['titulo'], lang="es")))
        self.graph.add((uri, DC.description, Literal(noticia['cuerpo'][:200], lang="es")))
        self.graph.add((uri, DC.date, Literal(noticia['fecha'], datatype=XSD.date)))

        # Autor
        autor_uri = self.DATA[f"autor_{noticia['autor'].replace(' ', '_')}"]
        self.graph.add((autor_uri, RDF.type, self.NS.Autor))
        self.graph.add((autor_uri, FOAF.name, Literal(noticia['autor'])))
        self.graph.add((uri, self.NS.tieneAutor, autor_uri))

        # Categoría
        cat = noticia.get('categoria_predicha', noticia.get('categoria_original', 'general'))
        cat_uri = self.DATA[f"categoria_{cat}"]
        self.graph.add((cat_uri, RDF.type, self.NS.Categoria))
        self.graph.add((cat_uri, RDFS.label, Literal(cat, lang="es")))
        self.graph.add((uri, self.NS.tieneCategoria, cat_uri))

        # Sentimiento
        if 'sentimiento' in noticia:
            sent = noticia['sentimiento']
            self.graph.add((uri, self.NS.sentimientoScore,
                           Literal(sent['compound'], datatype=XSD.float)))
            self.graph.add((uri, self.NS.sentimientoEtiqueta,
                           Literal(sent['etiqueta'])))

        # URL fuente
        if 'url' in noticia:
            self.graph.add((uri, self.NS.urlOriginal, Literal(noticia['url'], datatype=XSD.anyURI)))

    def consultar(self, sparql_query):
        """Ejecuta una consulta SPARQL."""
        return list(self.graph.query(sparql_query))

    def total_triples(self):
        return len(self.graph)

    def serializar(self, formato='turtle'):
        return self.graph.serialize(format=formato)
