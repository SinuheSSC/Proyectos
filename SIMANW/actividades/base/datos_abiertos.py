from rdflib import Namespace, Literal, RDF, XSD
from rdflib.namespace import DC


class ConectorDatosAbiertos:
    """Conecta el SIMANW con fuentes de datos abiertos."""

    def __init__(self, knowledge_graph):
        self.kg = knowledge_graph
        self.DCAT = Namespace("http://www.w3.org/ns/dcat#")
        self.GOB = Namespace("http://datos.gob.mx/")
        self.kg.graph.bind("dcat", self.DCAT)
        self.kg.graph.bind("gob", self.GOB)

    def cargar_dataset_gobierno(self, nombre, datos, publicador, tema):
        """Integra un dataset de datos abiertos al knowledge graph."""
        ds_uri = self.GOB[f"dataset/{nombre.replace(' ', '_')}"]
        self.kg.graph.add((ds_uri, RDF.type, self.DCAT.Dataset))
        self.kg.graph.add((ds_uri, DC.title, Literal(nombre, lang="es")))
        self.kg.graph.add((ds_uri, DC.publisher, Literal(publicador)))
        self.kg.graph.add((ds_uri, self.GOB.tema, Literal(tema)))

        for i, registro in enumerate(datos):
            reg_uri = self.GOB[f"registro/{nombre.replace(' ', '_')}_{i}"]
            self.kg.graph.add((ds_uri, self.GOB.tieneRegistro, reg_uri))
            for campo, valor in registro.items():
                if isinstance(valor, (int, float)):
                    self.kg.graph.add((reg_uri, self.GOB[campo],
                                     Literal(valor, datatype=XSD.float)))
                else:
                    self.kg.graph.add((reg_uri, self.GOB[campo], Literal(valor, lang="es")))

    def consultar_datos(self, tema=None):
        """Consulta los datos abiertos cargados."""
        filtro = f'FILTER(?tema = "{tema}")' if tema else ''
        query = f"""
        PREFIX dcat: <http://www.w3.org/ns/dcat#>
        PREFIX dc: <http://purl.org/dc/elements/1.1/>
        PREFIX gob: <http://datos.gob.mx/>

        SELECT ?titulo ?publicador ?tema
        WHERE {{
            ?ds a dcat:Dataset ;
                dc:title ?titulo ;
                dc:publisher ?publicador ;
                gob:tema ?tema .
            {filtro}
        }}
        """
        return list(self.kg.graph.query(query))

    def enlazar_noticias_con_datos(self):
        """Enlaza noticias con datasets relacionados semánticamente."""
        query = """
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
        """
        return list(self.kg.graph.query(query))
