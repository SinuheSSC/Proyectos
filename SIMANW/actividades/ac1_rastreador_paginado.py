import time
import json
import requests
from urllib.parse import urlparse

try:
    import feedparser
    FEEDPARSER_DISPONIBLE = True
except ImportError:
    FEEDPARSER_DISPONIBLE = False
    import xml.etree.ElementTree as ET


class RastreadorRSS:
    """
    AC-1: Rastreador alternativo mediante RSS feeds.
    Obtiene noticias reales de portales usando sus feeds RSS/Atom.

    Ventajas sobre el scraper HTML:
      - Sin riesgo de baneo (el feed está diseñado para consumo programático)
      - Estructura de datos predecible (XML con <item>/<entry>)
      - No requiere selectores CSS que cambian con el diseño
      - La mayoría de portales de noticias tienen RSS

    Soporta paginación mediante:
      - link rel="next" en el feed (estándar Atom)
      - Parámetros ?page=N en la URL del feed
    """

    def __init__(self, feed_url, delay=3, max_items=50,
                 user_agent='SIMANW-Academico/1.0',
                 parametro_pagina='page'):
        self.feed_url = feed_url
        self.delay = delay
        self.max_items = max_items
        self.user_agent = user_agent
        self.parametro_pagina = parametro_pagina
        self.resultados = []
        self.dominio = urlparse(feed_url).netloc
        self._feedparser_ok = FEEDPARSER_DISPONIBLE

    def _headers(self):
        return {'User-Agent': self.user_agent}

    def extraer_items(self, url):
        """Descarga y parsea un feed RSS/Atom, devolviendo lista de entradas."""
        if self._feedparser_ok:
            # Usar feedparser (más robusto, maneja RSS 2.0, Atom, RDF)
            datos = feedparser.parse(url, agent=self.user_agent)
            if datos.bozo and not datos.entries:
                print(f"  [!] Error parseando feed: {datos.bozo_exception}")
                return [], None
            items = []
            for entry in datos.entries:
                items.append({
                    'titulo': entry.get('title', ''),
                    'resumen': (
                        entry.get('summary', '') or
                        entry.get('description', '') or
                        ''
                    ),
                    'url': entry.get('link', ''),
                    'fecha_publicacion': entry.get('published', ''),
                    'autor': (
                        entry.get('author', '') or
                        entry.get('author_detail', {}).get('name', '') or
                        ''
                    ),
                })
            # Detectar siguiente página via link rel="next"
            siguiente_url = None
            for link in datos.feed.get('links', []):
                if link.get('rel') == 'next':
                    siguiente_url = link.get('href')
                    break
            return items, siguiente_url
        else:
            # Fallback con xml.etree.ElementTree (stdlib)
            resp = requests.get(url, headers=self._headers(), timeout=15)
            resp.raise_for_status()
            root = ET.fromstring(resp.content)
            ns = self._detectar_ns(root.tag)
            items = []
            entry_tag = f'{{{ns}}}entry' if ns else 'entry'
            item_tag = f'{{{ns}}}item' if ns else 'item'
            for entry in root.iter(entry_tag):
                items.append(self._parse_entry_xml(entry, ns))
            if not items:
                for item in root.iter(item_tag):
                    items.append(self._parse_entry_xml(item, ns))
            # Buscar link rel="next"
            siguiente_url = None
            for link in root.iter(f'{{{ns}}}link'):
                if link.get('rel') == 'next':
                    siguiente_url = link.get('href')
                    break
            return items, siguiente_url

    def _detectar_ns(self, tag):
        """Extrae el namespace XML de una etiqueta."""
        if tag.startswith('{'):
            return tag[1:tag.index('}')]
        return ''

    def _parse_entry_xml(self, elem, ns):
        """Parsea una entrada/item XML manualmente."""
        def g(tag):
            t = elem.find(f'{{{ns}}}{tag}') if ns else elem.find(tag)
            return t.text.strip() if t is not None and t.text else ''
        return {
            'titulo': g('title'),
            'resumen': g('summary') or g('description') or '',
            'url': g('link') or '',
            'fecha_publicacion': g('published') or g('pubDate') or g('updated') or '',
            'autor': g('author') or '',
        }

    def rastrear(self):
        """Ejecuta el rastreo completo del feed RSS."""
        print(f"\n  Usando feed RSS: {self.feed_url}")
        print(f"  Librería: {'feedparser' if self._feedparser_ok else 'xml.etree (stdlib)'}")

        url_actual = self.feed_url
        pagina = 1

        while url_actual and len(self.resultados) < self.max_items:
            print(f"  [{pagina}] Solicitando feed...")
            try:
                items, url_siguiente = self.extraer_items(url_actual)
            except Exception as e:
                print(f"  [!] Error al obtener feed: {e}")
                break

            self.resultados.extend(items)
            print(f"       -> {len(items)} ítems (acumulados: {len(self.resultados)})")

            if len(self.resultados) >= self.max_items:
                break

            if url_siguiente:
                url_actual = url_siguiente
                pagina += 1
                print(f"       Esperando {self.delay}s para siguiente página...")
                time.sleep(self.delay)
            else:
                # Intentar paginación por ?page=N
                if '?' in self.feed_url:
                    base = self.feed_url.split('?')[0]
                else:
                    base = self.feed_url
                url_actual = f"{base}?{self.parametro_pagina}={pagina + 1}"
                pagina += 1
                print(f"       Esperando {self.delay}s para página {pagina}...")
                time.sleep(self.delay)

        self.resultados = self.resultados[:self.max_items]
        print(f"\n  [OK] Rastreo completado: {len(self.resultados)} noticias")
        return self.resultados

    def guardar_json(self, archivo):
        """Guarda los resultados en un archivo JSON."""
        with open(archivo, 'w', encoding='utf-8') as f:
            json.dump({
                'fuente': self.dominio,
                'feed_url': self.feed_url,
                'total_noticias': len(self.resultados),
                'fecha_extraccion': time.strftime('%Y-%m-%d %H:%M:%S'),
                'parametros': {
                    'max_items': self.max_items,
                    'delay': self.delay,
                },
                'noticias': self.resultados,
            }, f, ensure_ascii=False, indent=2)
        print(f"  [OK] Guardado en: {archivo}")
        return len(self.resultados)


class RastreadorRSSBBC(RastreadorRSS):
    """
    Adaptación para BBC News (internacional, inglés).
    Feed principal de noticias mundiales.
    """
    def __init__(self, delay=3, max_items=50):
        super().__init__(
            feed_url="https://feeds.bbci.co.uk/news/rss.xml",
            delay=delay,
            max_items=max_items,
        )


class RastreadorRSSXataka(RastreadorRSS):
    """
    Adaptación para Xataka (España, tecnología).
    Blog tecnológico en español.
    """
    def __init__(self, delay=3, max_items=50):
        super().__init__(
            feed_url="https://feeds.weblogssl.com/xataka2",
            delay=delay,
            max_items=max_items,
        )


class RastreadorRSSTheGuardian(RastreadorRSS):
    """
    Adaptación para The Guardian (internacional, inglés).
    Sección World News.
    """
    def __init__(self, delay=3, max_items=50):
        super().__init__(
            feed_url="https://www.theguardian.com/world/rss",
            delay=delay,
            max_items=max_items,
        )


if __name__ == '__main__':
    print("=" * 65)
    print("  AC-1: Rastreo de sitio real con paginaci�n v�a RSS")
    print("=" * 65)
    print()
    print("  Elige el feed RSS:")
    print("    1 - BBC News (ingl�s, internacional)")
    print("    2 - Xataka (espa�ol, tecnolog�a)")
    print("    3 - The Guardian (ingl�s, internacional)")

    modo = input("\n  Opci�n (1/2/3, default 1): ").strip() or '1'
    print()

    if modo == '2':
        print("  Usando Xataka RSS...")
        rastreador = RastreadorRSSXataka(delay=3, max_items=50)
        archivo = "noticias_xataka_rss.json"
    elif modo == '3':
        print("  Usando The Guardian RSS...")
        rastreador = RastreadorRSSTheGuardian(delay=3, max_items=50)
        archivo = "noticias_guardian_rss.json"
    else:
        print("  Usando BBC News RSS...")
        rastreador = RastreadorRSSBBC(delay=3, max_items=50)
        archivo = "noticias_bbc_rss.json"

    resultados = rastreador.rastrear()
    total = rastreador.guardar_json(archivo)

    print(f"\n  Noticias extra�das: {total}")
    print("\n  Primeras 10 noticias:")
    for i, r in enumerate(resultados[:10], 1):
        print(f"    {i:2d}. {r['titulo'][:85]}")
