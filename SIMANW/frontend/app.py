import sys, os, json, io, time, re
from datetime import datetime
from collections import Counter
from pathlib import Path

BASE = Path(__file__).resolve().parent.parent
sys.path.insert(0, str(BASE))
sys.path.insert(0, str(BASE / 'actividades'))

import flet as ft
from flet.controls.box import BoxFit
from flet.controls.alignment import Alignment

# ── Importar módulos de actividades ──────────────────────────────
def _import(name):
    mod = __import__(name)
    for p in name.split('.')[1:]:
        mod = getattr(mod, p)
    return mod

from ac1_rastreador_paginado import RastreadorRSS, RastreadorRSSXataka, RastreadorRSSBBC, RastreadorRSSTheGuardian
from ac2_analisis_discurso import AnalisisDiscurso
from ac3_selector_modelo import SelectorModelo, DATOS_AC3, NUEVAS_NOTICIAS
from ac4_analizador_hilo import AnalizadorHiloDiscusion
from ac5_comparador_modelos import ComparadorModelos, NOTICIAS as NOTICIAS_AC5, CONSULTAS_EVAL
from ac6_chatbot_contextual import ChatbotContextual, MotorBusquedaLocal, NOTICIAS as NOTICIAS_AC6
from ac8_validador_corpus import ValidadorCorpus
from ac9_tendencias_temporales import AnalizadorTemporal
from ac10_sistema_alertas import SistemaAlertas

# ── Tema SIMANW ──────────────────────────────────────────────────
class Tema:
    primario = "#1A1A2E"
    secundario = "#16213E"
    acento = "#0F3460"
    dorado = "#E94560"
    fondo = "#F0F2F5"
    blanco = "#FFFFFF"
    texto = "#1A1A2E"
    texto_sec = "#6B7280"
    exito = "#10B981"
    advertencia = "#F59E0B"
    error = "#EF4444"
    info = "#3B82F6"

    @staticmethod
    def card(content, width=None, height=None, **kwargs):
        return ft.Container(
            content=content,
            bgcolor=Tema.blanco,
            border_radius=16,
            padding=20,
            shadow=ft.BoxShadow(blur_radius=20, color=ft.Colors.BLACK12, spread_radius=0, offset=ft.Offset(0, 4)),
            width=width, height=height, **kwargs,
        )

    @staticmethod
    def borde():
        s = ft.BorderSide(1, ft.Colors.GREY_200)
        return ft.Border(left=s, top=s, right=s, bottom=s)

    @staticmethod
    def stat_card(valor, etiqueta, color=None, icono=None):
        color = color or Tema.acento
        return Tema.card(ft.Column([
            ft.Row([
                ft.Icon(icono, color=color, size=28) if icono else ft.Container(),
                ft.Text(str(valor), size=32, weight=ft.FontWeight.BOLD, color=color),
            ], alignment=ft.MainAxisAlignment.START, spacing=8),
            ft.Text(etiqueta, size=13, color=Tema.texto_sec),
        ], spacing=4), width=200)

    @staticmethod
    def titulo_seccion(texto):
        return ft.Text(texto, size=18, weight=ft.FontWeight.W_600, color=Tema.texto)

    @staticmethod
    def subtitulo(texto):
        return ft.Text(texto, size=12, color=Tema.texto_sec)

    @staticmethod
    def boton(texto, on_click, icono=None, color=None):
        return ft.FilledButton(
            content=texto, on_click=on_click, icon=icono,
            style=ft.ButtonStyle(
                bgcolor=color or Tema.acento,
                color=ft.Colors.WHITE,
                shape=ft.RoundedRectangleBorder(radius=10),
                padding=ft.padding.Padding(left=20, top=14, right=20, bottom=14),
            ),
        )

    @staticmethod
    def tab_selector(tab_labels, pages):
        tab_bar = ft.TabBar(
            tabs=[ft.Tab(label=t) for t in tab_labels],
            indicator_color=Tema.dorado,
            label_color=Tema.acento,
            unselected_label_color=Tema.texto_sec,
        )
        view = ft.TabBarView(controls=pages, expand=True)
        return ft.Tabs(
            content=ft.Column([tab_bar, view], expand=True),
            selected_index=0,
            length=len(tab_labels),
        )


# ── DATOS COMPARTIDOS ────────────────────────────────────────────
class Datos:
    noticias = []
    fuente = ""
    kg = None
    manifiesto = {}


def cargar_noticias():
    ruta = BASE / 'noticias_hackernews.json'
    if ruta.exists():
        with open(ruta, encoding='utf-8') as f:
            data = json.load(f)
        Datos.noticias = data.get('noticias', [])
        Datos.fuente = data.get('fuente', 'desconocida')
    return Datos.noticias


def ejecutar_capturando_print(fn):
    buf = io.StringIO()
    old = sys.stdout
    sys.stdout = buf
    try:
        fn()
    finally:
        sys.stdout = old
    return buf.getvalue()


# ═══════════════════════════════════════════════════════════════════
#  PÁGINA 1: DASHBOARD
# ═══════════════════════════════════════════════════════════════════
def build_dashboard(page):
    noticias = cargar_noticias()
    total = len(noticias)

    cols = ['tecnologia', 'economia', 'ciencia', 'politica', 'general']
    cats = Counter()
    for n in noticias:
        tl = n.get('titulo', '').lower()
        if any(w in tl for w in ['ai','software','code','app','data','python','startup','algorithm']):
            cats['Tecnología'] += 1
        elif any(w in tl for w in ['market','stock','funding','invest','money','bank','economy']):
            cats['Economía'] += 1
        elif any(w in tl for w in ['science','research','study','space','nasa','medical','climate']):
            cats['Ciencia'] += 1
        elif any(w in tl for w in ['government','law','legal','court','congress','president']):
            cats['Política'] += 1
        else:
            cats['General'] += 1

    colores_cat = {'Tecnología': '#0F3460', 'Economía': '#10B981', 'Ciencia': '#F59E0B', 'Política': '#EF4444', 'General': '#6B7280'}
    max_cat = max(cats.values()) if cats else 1

    cats_display = ft.Column(spacing=6)
    for cat, cnt in sorted(cats.items(), key=lambda x: -x[1]):
        pct = cnt / max_cat
        cats_display.controls.append(ft.Row([
            ft.Text(cat, size=13, width=90, color=Tema.texto),
            ft.Text(str(cnt), size=13, weight=ft.FontWeight.BOLD, width=36, color=Tema.texto),
            ft.Container(
                ft.Container(expand=True, height=22, border_radius=6, bgcolor=colores_cat.get(cat, Tema.acento)),
                width=max(220 * pct, 30), border_radius=6,
            ),
        ], spacing=8))

    # Stats de ACs
    acs_status = ft.Container(height=300, content=ft.Column([
        ft.Row([ft.Text(f"AC-{i}", size=11, weight=ft.FontWeight.BOLD, color=Tema.texto_sec, width=32),
                ft.Container(width=12, height=12, border_radius=6, bgcolor=Tema.exito),
                ft.Text(n, size=11, color=Tema.texto, width=130),
                ft.Icon(ft.Icons.CHECK_CIRCLE, size=14, color=Tema.exito)], spacing=4)
        for i, n in enumerate([
            "Rastreo RSS","Análisis Discurso","Clasificador","Hilos","Búsqueda",
            "Chatbot","KG","Validador","Tendencias","Alertas","Usabilidad","Trazabilidad","Semántica"
        ], 1)
    ], scroll=ft.ScrollMode.AUTO), padding=10)

    stats_row = ft.ResponsiveRow([
        ft.Container(Tema.stat_card(total, "Noticias", Tema.acento, ft.Icons.ARTICLE), col={"sm": 6, "md": 3}),
        ft.Container(Tema.stat_card(len(cats), "Categorías", Tema.exito, ft.Icons.CATEGORY), col={"sm": 6, "md": 3}),
        ft.Container(Tema.stat_card("13/13", "AC Completadas", Tema.dorado, ft.Icons.TASK_ALT), col={"sm": 6, "md": 3}),
        ft.Container(Tema.stat_card(Datos.fuente or "N/A", "Fuente", Tema.info, ft.Icons.SOURCE), col={"sm": 6, "md": 3}),
    ], spacing=16)

    return ft.Column([
        ft.Text("Dashboard SIMANW", size=26, weight=ft.FontWeight.BOLD, color=Tema.texto),
        ft.Text(f"Reporte generado el {datetime.now().strftime('%d/%m/%Y %H:%M')}", size=12, color=Tema.texto_sec),
        ft.Divider(height=20, color=ft.Colors.GREY_200),
        stats_row,
        ft.Divider(height=20, color=ft.Colors.GREY_200),
        ft.Row([
            Tema.card(ft.Column([Tema.titulo_seccion("Distribución por Categoría"), Tema.subtitulo("Noticias clasificadas por tema"), ft.Divider(height=10), cats_display]), expand=2),
            Tema.card(ft.Column([Tema.titulo_seccion("Progreso del Sistema"), Tema.subtitulo("Actividades completadas"), ft.Divider(height=10), acs_status]), expand=1),
        ], spacing=16, vertical_alignment=ft.CrossAxisAlignment.START),
        ft.Divider(height=20, color=ft.Colors.GREY_200),
        Tema.card(ft.Column([
            Tema.titulo_seccion("Resumen del Pipeline"),
            Tema.subtitulo("Flujo de datos entre las fases del sistema"),
            ft.Divider(height=10),
            ft.Row([
                ft.Container(ft.Column([ft.Icon(ft.Icons.RSS_FEED, color=Tema.acento, size=24), ft.Text("AC-1\nRastreo", size=10, text_align=ft.TextAlign.CENTER)], horizontal_alignment=ft.CrossAxisAlignment.CENTER), width=80, height=70, bgcolor=ft.Colors.INDIGO_50, border_radius=10),
                ft.Icon(ft.Icons.ARROW_FORWARD, color=Tema.texto_sec, size=18),
                ft.Container(ft.Column([ft.Icon(ft.Icons.VERIFIED, color=Tema.exito, size=24), ft.Text("AC-8\nValidar", size=10, text_align=ft.TextAlign.CENTER)], horizontal_alignment=ft.CrossAxisAlignment.CENTER), width=80, height=70, bgcolor=ft.Colors.GREEN_50, border_radius=10),
                ft.Icon(ft.Icons.ARROW_FORWARD, color=Tema.texto_sec, size=18),
                ft.Container(ft.Column([ft.Icon(ft.Icons.ANALYTICS, color=Tema.advertencia, size=24), ft.Text("AC-3/9\nAnálisis", size=10, text_align=ft.TextAlign.CENTER)], horizontal_alignment=ft.CrossAxisAlignment.CENTER), width=80, height=70, bgcolor=ft.Colors.AMBER_50, border_radius=10),
                ft.Icon(ft.Icons.ARROW_FORWARD, color=Tema.texto_sec, size=18),
                ft.Container(ft.Column([ft.Icon(ft.Icons.SEARCH, color=Tema.info, size=24), ft.Text("AC-5/10\nBúsqueda", size=10, text_align=ft.TextAlign.CENTER)], horizontal_alignment=ft.CrossAxisAlignment.CENTER), width=80, height=70, bgcolor=ft.Colors.BLUE_50, border_radius=10),
                ft.Icon(ft.Icons.ARROW_FORWARD, color=Tema.texto_sec, size=18),
                ft.Container(ft.Column([ft.Icon(ft.Icons.CHAT, color=Tema.dorado, size=24), ft.Text("AC-6\nChatbot", size=10, text_align=ft.TextAlign.CENTER)], horizontal_alignment=ft.CrossAxisAlignment.CENTER), width=80, height=70, bgcolor=ft.Colors.RED_50, border_radius=10),
                ft.Icon(ft.Icons.ARROW_FORWARD, color=Tema.texto_sec, size=18),
                ft.Container(ft.Column([ft.Icon(ft.Icons.HUB, color=Tema.acento, size=24), ft.Text("AC-7/13\nSemántica", size=10, text_align=ft.TextAlign.CENTER)], horizontal_alignment=ft.CrossAxisAlignment.CENTER), width=80, height=70, bgcolor=ft.Colors.INDIGO_50, border_radius=10),
            ], spacing=4, alignment=ft.MainAxisAlignment.CENTER),
        ])),
    ], scroll=ft.ScrollMode.AUTO, expand=True)


# ═══════════════════════════════════════════════════════════════════
#  PÁGINA 2: RASTREO (AC-1 + AC-8 + AC-12)
# ═══════════════════════════════════════════════════════════════════
def build_rastreo(page):
    log_output = ft.Text(value="", size=12, color=Tema.texto, selectable=True, font_family="monospace")
    log_view = ft.Container(content=ft.Column([log_output], scroll=ft.ScrollMode.AUTO), height=300, bgcolor="#1A1A2E", border_radius=10, padding=16)
    noticias_table = ft.DataTable(columns=[ft.DataColumn(ft.Text("#", size=10)), ft.DataColumn(ft.Text("Título", size=10)), ft.DataColumn(ft.Text("URL", size=10))], rows=[], width=800)

    resultado_text = ft.Text("", size=13, color=Tema.exito)

    def run_ac1(e):
        noticias_table.rows.clear()
        log_output.value = ""
        resultado_text.value = "Rastreando feed RSS..."
        page.update()

        def _work():
            try:
                import requests as _requests
                r = RastreadorRSSXataka(delay=1, max_items=10)
                r._headers = lambda: {'User-Agent': r.user_agent, 'Accept': 'application/rss+xml, application/xml, text/xml'}
                resultados = r.rastrear()
            except Exception as ex:
                resultados = []
                log_output.value = f"RSS no disponible: {ex}"

            if resultados:
                r = RastreadorRSSXataka(delay=1, max_items=10)
                r.resultados = resultados
                r.guardar_json(str(BASE / 'noticias_xataka_rss.json'))
                for i, n in enumerate(resultados[:15], 1):
                    noticias_table.rows.append(ft.DataRow([ft.DataCell(ft.Text(str(i), size=10)), ft.DataCell(ft.Text(n.get('titulo','')[:60], size=10)), ft.DataCell(ft.Text(n.get('url','')[:30], size=10, color=Tema.info))]))
                resultado_text.value = f"✓ {len(resultados)} noticias desde Xataka"
                log_output.value += "\nRastreo RSS completado exitosamente."
            else:
                resultado_text.value = "⚠ Sin conexión. Usando corpus local."
                ns = cargar_noticias()
                if ns:
                    for i, n in enumerate(ns[:20], 1):
                        noticias_table.rows.append(ft.DataRow([ft.DataCell(ft.Text(str(i), size=10)), ft.DataCell(ft.Text(n.get('titulo','')[:60], size=10)), ft.DataCell(ft.Text(n.get('url','')[:30], size=10, color=Tema.info))]))
                    resultado_text.value = f"✓ {len(ns)} noticias del corpus local (HackerNews)"
            page.update()

        page.run_thread(_work)

    # AC-8: Validador
    validacion_output = ft.Text("", size=12, selectable=True, font_family="monospace")
    def run_ac8(e):
        ns = cargar_noticias()
        if not ns:
            validacion_output.value = "No hay corpus. Ejecuta AC-1 primero."
            page.update(); return
        v = ValidadorCorpus()
        def _run():
            nonlocal v
            informe = v.validar_corpus(ns, Datos.fuente)
            dep = v.corpus_depurado()
            validacion_output.value = (
                f"Total: {informe['resumen']['total_registros']} | Aceptados: {informe['resumen']['registros_aceptados']} | "
                f"Rechazados: {informe['resumen']['registros_rechazados']} | "
                f"Dup. exactos: {informe['resumen']['duplicados_exactos']} | "
                f"Dup. cercanos: {informe['resumen']['duplicados_cercanos']}\n"
                f"Corpus depurado: {len(dep)} registros\n\n{v.parrafo_explicativo(Datos.fuente)}"
            )
        ejecutar_capturando_print(_run)
        v.guardar_informe(str(BASE / 'informe_calidad_corpus.json'))
        page.update()

    # AC-12: Trazabilidad
    trazabilidad_output = ft.Text("", size=12, selectable=True, font_family="monospace")
    def run_ac12(e):
        from ac12_trazabilidad import PipelineTracker, ejecutar_pipeline_simulado
        def _run():
            t = PipelineTracker(str(BASE))
            ejecutar_pipeline_simulado(t)
            ruta = t.exportar_manifiesto()
            m = t.generar_manifiesto()['manifiesto_simanw']
            trazabilidad_output.value = (
                f"Versión: {m['version']} | Fecha: {m['fecha_ejecucion']} | "
                f"Python: {m['sistema']['python']} | {m['total_etapas']} etapas\n"
                f"Manifiesto: {ruta}"
            )
        ejecutar_capturando_print(_run)
        page.update()

    rastreo_tabs = ["AC-1: Rastreo", "AC-8: Validación", "AC-12: Trazabilidad"]
    rastreo_content = ft.Column([
        Tema.titulo_seccion("Rastreo de Noticias"),
        Tema.subtitulo("Pipeline de adquisición y validación de datos"),
        ft.Divider(height=10),
        Tema.boton("▶ Ejecutar Rastreo (Xataka)", run_ac1, ft.Icons.RSS_FEED, Tema.acento),
        ft.Divider(height=5),
        resultado_text,
        log_view,
        noticias_table,
    ], scroll=ft.ScrollMode.AUTO)

    validacion_content = ft.Column([
        Tema.titulo_seccion("Validación del Corpus"),
        Tema.subtitulo("Control de calidad sobre las noticias rastreadas"),
        ft.Divider(height=10),
        Tema.boton("▶ Validar Corpus", run_ac8, ft.Icons.VERIFIED, Tema.exito),
        ft.Divider(height=5),
        validacion_output,
    ], scroll=ft.ScrollMode.AUTO)

    trazabilidad_content = ft.Column([
        Tema.titulo_seccion("Trazabilidad del Pipeline"),
        Tema.subtitulo("Manifiesto de ejecución y reproducibilidad"),
        ft.Divider(height=10),
        Tema.boton("▶ Generar Manifiesto", run_ac12, ft.Icons.ACCOUNT_TREE, Tema.info),
        ft.Divider(height=5),
        trazabilidad_output,
    ], scroll=ft.ScrollMode.AUTO)

    pages = [rastreo_content, validacion_content, trazabilidad_content]
    return Tema.tab_selector(rastreo_tabs, pages)


# ═══════════════════════════════════════════════════════════════════
#  PÁGINA 3: ANÁLISIS (AC-2 + AC-4 + AC-9)
# ═══════════════════════════════════════════════════════════════════
def build_analisis(page):
    # AC-2: Discurso
    discurso_input = ft.TextField(multiline=True, min_lines=6, max_lines=12, hint_text="Pega aquí un discurso político, artículo científico o reseña...", border_radius=10, expand=True)
    discurso_output = ft.Text("", size=12, selectable=True, font_family="monospace")
    nube_img = ft.Image(src="", width=500, fit=BoxFit.CONTAIN)

    def run_ac2(e):
        texto = discurso_input.value.strip()
        if not texto:
            discurso_output.value = "Ingresa un texto primero."
            page.update(); return
        a = AnalisisDiscurso()
        r = a.analizar(texto, "Análisis", generar_nube=True, dir_salida=str(BASE))
        discurso_output.value = (
            f"Oraciones: {r['oraciones']} | Palabras: {r['palabras_totales']} | "
            f"Vocabulario: {r['vocabulario_unico']} | Riqueza: {r['riqueza_lexica_global']:.3f}\n"
            f"Top bigramas: {r['top_bigramas'][:5]}\n"
            f"Top trigramas: {r['top_trigramas'][:3]}\n"
            f"Entidades: {list(dict.fromkeys(r['entidades'].get('personas',[])+r['entidades'].get('lugares',[])+r['entidades'].get('organizaciones',[])))[:8]}"
        )
        nubes = list(BASE.glob("nube_*.png"))
        if nubes:
            nube_img.src = str(max(nubes, key=lambda p: p.stat().st_mtime))
        page.update()

    # AC-4: Hilos
    hilo_input = ft.TextField(multiline=True, min_lines=6, max_lines=10, hint_text="Ingresa mensajes del hilo (uno por línea: usuario|texto)", border_radius=10)
    hilo_output = ft.Text("", size=12, selectable=True, font_family="monospace")

    def run_ac4(e):
        lines = [l.strip() for l in hilo_input.value.strip().split('\n') if l.strip()]
        if len(lines) < 3:
            hilo_output.value = "Ingresa al menos 3 mensajes (formato: usuario|texto)"
            page.update(); return
        mensajes = []
        for i, l in enumerate(lines):
            parts = l.split('|', 1)
            if len(parts) == 2:
                mensajes.append({"usuario": parts[0].strip(), "texto": parts[1].strip(), "timestamp": f"{i*5:02d}:00"})
        if len(mensajes) < 3:
            hilo_output.value = "Formato incorrecto. Usa: usuario|texto"
            page.update(); return
        a = AnalizadorHiloDiscusion()
        a.cargar_hilo(mensajes)
        res = a.resumen_hilo()
        sub = a.detectar_subtemas(min(3, len(mensajes)))
        evo = a.evolucion_sentimiento(3)
        hilo_output.value = (
            f"Mensajes: {res['total_mensajes']} | Participantes: {res['participantes']} | "
            f"Tono: {res['tono']} ({res['sentimiento_promedio']:+.3f})\n"
            f"Hashtags: {res['hashtags_top']}\n"
            "Subtemas: " + ", ".join(f"#{k+1}: {v['keywords']}" for k, v in sub.items())
        )
        page.update()

    # AC-9: Tendencias
    tendencias_img = ft.Image(src="", width=600, fit=BoxFit.CONTAIN)
    tendencias_output = ft.Text("", size=12, selectable=True, font_family="monospace")

    def run_ac9(e):
        ns = cargar_noticias()
        if not ns:
            tendencias_output.value = "No hay noticias. Ejecuta AC-1 primero."
            page.update(); return
        import random
        random.seed(42)
        extraccion = datetime.now()
        corpus = []
        for i, n in enumerate(ns):
            tl = n.get('titulo', '').lower()
            cat = 'general'
            if any(kw in tl for kw in ['ai','artificial','software','programming','code','app','data','algorithm','python','startup']): cat = 'tecnologia'
            elif any(kw in tl for kw in ['market','stock','economy','funding','invest','money','bank','finance']): cat = 'economia'
            elif any(kw in tl for kw in ['science','research','study','space','nasa','medical','climate','health']): cat = 'ciencia'
            elif any(kw in tl for kw in ['government','regulation','law','legal','court','president','election']): cat = 'politica'
            dias = (len(ns) - i) * 2 // 3
            corpus.append({'titulo': tl[:100], 'fecha': extraccion - __import__('datetime').timedelta(days=dias), 'categoria': cat})
        cats = ['tecnologia','economia','ciencia','politica']
        at = AnalizadorTemporal(corpus)
        per = at.agrupar_por_periodo('semana')
        h, f = at.generar_tabla(per, cats)
        picos = []
        for c in cats:
            pico, caida = at.picos_y_caidas(per, c)
            if pico and pico[1] > 0:
                picos.append(f"  {c}: pico={pico[0]}({pico[1]})")
        suben, bajan = at.terminos_emergentes(per)
        img_path = BASE / 'tendencias_temporales.png'
        at.generar_visualizacion(per, cats, str(img_path))
        tendencias_output.value = f"Periodos: {len(per)} | Picos:\n" + '\n'.join(picos) + f"\nSuben: {list(suben.keys())[:5]}"
        if img_path.exists():
            tendencias_img.src = str(img_path)
        page.update()

    analisis_tabs = ["AC-2: Discurso", "AC-4: Hilos", "AC-9: Tendencias"]
    discurso_content = ft.Column([
        Tema.titulo_seccion("Análisis de Discurso"),
        Tema.subtitulo("N-gramas, riqueza léxica, entidades y nube de palabras"),
        ft.Divider(height=10),
        discurso_input, ft.Divider(height=5),
        Tema.boton("▶ Analizar", run_ac2, ft.Icons.ANALYTICS, Tema.advertencia),
        ft.Divider(height=5), discurso_output, nube_img,
    ], scroll=ft.ScrollMode.AUTO, expand=True)

    hilo_content = ft.Column([
        Tema.titulo_seccion("Análisis de Hilos"),
        Tema.subtitulo("Evolución del sentimiento, subtemas y resumen"),
        ft.Divider(height=10),
        hilo_input, ft.Divider(height=5),
        ft.Text("Formato: usuario|mensaje (uno por línea)", size=11, color=Tema.texto_sec, italic=True),
        Tema.boton("▶ Analizar Hilo", run_ac4, ft.Icons.FORUM, Tema.dorado),
        ft.Divider(height=5), hilo_output,
    ], scroll=ft.ScrollMode.AUTO, expand=True)

    tendencias_content = ft.Column([
        Tema.titulo_seccion("Tendencias Temporales"),
        Tema.subtitulo("Línea de tiempo y evolución por categoría"),
        ft.Divider(height=10),
        Tema.boton("▶ Generar Tendencias", run_ac9, ft.Icons.TRENDING_UP, Tema.exito),
        ft.Divider(height=5), tendencias_output, tendencias_img,
    ], scroll=ft.ScrollMode.AUTO, expand=True)

    pages = [discurso_content, hilo_content, tendencias_content]
    return Tema.tab_selector(analisis_tabs, pages)


# ═══════════════════════════════════════════════════════════════════
#  PÁGINA 4: CLASIFICACIÓN (AC-3 + AC-5 + AC-10)
# ═══════════════════════════════════════════════════════════════════
def build_clasificacion(page):
    # AC-3: Clasificador
    modelo_output = ft.Text("", size=12, selectable=True, font_family="monospace")
    prediccion_input = ft.TextField(hint_text="Escribe un texto para clasificar...", border_radius=10, expand=True)
    prediccion_output = ft.Text("", size=14, weight=ft.FontWeight.BOLD)
    selector = [None]

    def run_ac3(e):
        s = SelectorModelo()
        s.evaluar_todos(DATOS_AC3['textos'], DATOS_AC3['etiquetas'], cv_folds=5)
        selector[0] = s
        modelo_output.value = s.reporte() + f"\n\nMejor: {s.mejor_modelo[0]}"
        page.update()

    def predecir(e):
        if not selector[0]:
            prediccion_output.value = "Primero entrena el clasificador."
            page.update(); return
        txt = prediccion_input.value.strip()
        if not txt:
            return
        s = selector[0]
        pred = s.predecir([txt])
        prediccion_output.value = f"Categoría: {pred[0]}"
        page.update()

    # AC-5: Búsqueda
    busqueda_input = ft.TextField(hint_text="Ej: inteligencia artificial, mercado, ciencia...", border_radius=10, expand=True)
    busqueda_output = ft.Text("", size=12, selectable=True, font_family="monospace")
    comparador = ComparadorModelos(NOTICIAS_AC5)

    def buscar(e):
        q = busqueda_input.value.strip()
        if not q:
            return
        vec = comparador.busqueda_vectorial(q, top_k=5)
        bol = comparador.busqueda_booleana(q)
        lines = [f"Booleano: {len(bol)} resultados | Vectorial: {len(vec)} resultados"]
        for idx, score in vec:
            doc = NOTICIAS_AC5[idx]
            lines.append(f"  [{score:.3f}] {doc['titulo'][:70]}")
        busqueda_output.value = '\n'.join(lines)
        page.update()

    # AC-10: Alertas
    alertas_output = ft.Text("", size=12, selectable=True, font_family="monospace")
    def run_ac10(e):
        s = SistemaAlertas()
        consultas = [('IA y ML','inteligencia artificial machine learning deep'),('Mercados','mercado bolsa acciones inversión'),('Ciencia','científico investigación descubrimiento'),('Política regulatoria','gobierno ley reforma senado'),('Salud','terapia genética médico enfermedad')]
        for n, t in consultas:
            s.agregar_consulta(n, t)
        s.procesar_lote([{"titulo": "Nueva inteligencia artificial", "resumen": "avances en machine learning", "url": "https://x.com/1"}])
        alertas_output.value = f"Consultas: {len(s.consultas)} | Alertas: {s.total_alertas()}\nHistorial:\n{s.historial_reciente(5)}"
        page.update()

    tabs_ref = ["AC-3: Clasificador", "AC-5: Búsqueda", "AC-10: Alertas"]
    clasif_content = ft.Column([
        Tema.titulo_seccion("Clasificador Multimodelo"),
        Tema.subtitulo("Selección automática del mejor modelo con validación cruzada"),
        ft.Divider(height=10),
        Tema.boton("▶ Entrenar Modelos", run_ac3, ft.Icons.MODEL_TRAINING, Tema.acento),
        ft.Divider(height=5), modelo_output, ft.Divider(height=10),
        ft.Row([prediccion_input, Tema.boton("Predecir", predecir, ft.Icons.ONLINE_PREDICTION, Tema.dorado)], spacing=8),
        prediccion_output,
    ], scroll=ft.ScrollMode.AUTO)

    busqueda_content = ft.Column([
        Tema.titulo_seccion("Buscador de Noticias"),
        Tema.subtitulo("Comparación booleano vs. vectorial (TF-IDF + coseno)"),
        ft.Divider(height=10),
        ft.Row([busqueda_input, Tema.boton("Buscar", buscar, ft.Icons.SEARCH, Tema.info)], spacing=8),
        ft.Divider(height=5), busqueda_output,
    ], scroll=ft.ScrollMode.AUTO)

    alertas_content = ft.Column([
        Tema.titulo_seccion("Sistema de Alertas"),
        Tema.subtitulo("Consultas guardadas que monitorean noticias entrantes"),
        ft.Divider(height=10),
        Tema.boton("▶ Inicializar Alertas", run_ac10, ft.Icons.NOTIFICATIONS, Tema.error),
        ft.Divider(height=5), alertas_output,
    ], scroll=ft.ScrollMode.AUTO)

    pages = [clasif_content, busqueda_content, alertas_content]
    return Tema.tab_selector(tabs_ref, pages)


# ═══════════════════════════════════════════════════════════════════
#  PÁGINA 5: CHATBOT (AC-6)
# ═══════════════════════════════════════════════════════════════════
def build_chatbot(page):
    chat_msgs = ft.Column(scroll=ft.ScrollMode.AUTO, expand=True)
    chat_input = ft.TextField(hint_text="Escribe tu pregunta...", expand=True, border_radius=10)
    chatbot_ref = [None]

    def iniciar_chat(e=None):
        motor = MotorBusquedaLocal(NOTICIAS_AC6)
        chatbot_ref[0] = ChatbotContextual(NOTICIAS_AC6, motor)
        chat_msgs.controls.clear()
        chat_msgs.controls.append(ft.Container(
            content=ft.Column([ft.Text("🤖 SIMANW Bot", weight=ft.FontWeight.BOLD, size=14, color=Tema.blanco), ft.Text("¡Hola! Soy el asistente SIMANW. Pregúntame sobre noticias de tecnología, economía, ciencia y más.", size=12, color=ft.Colors.GREY_200)]),
            bgcolor=Tema.acento, border_radius=12, padding=14, margin={"bottom": 8},
        ))
        page.update()

    def enviar(e):
        msg = chat_input.value.strip()
        if not msg or not chatbot_ref[0]:
            return
        chat_msgs.controls.append(ft.Container(
            content=ft.Column([ft.Text("Tú", weight=ft.FontWeight.BOLD, size=12, color=Tema.texto), ft.Text(msg, size=12, color=Tema.texto)]),
            bgcolor=ft.Colors.GREY_100, border_radius=12, padding=14, margin={"bottom": 8, "left": 40},
        ))
        resp, tipo, conf = chatbot_ref[0].responder(msg)
        color_bot = {"contextual": Tema.info, "personalizada": Tema.exito, "directa": Tema.acento, "fallback": Tema.advertencia}.get(tipo, Tema.acento)
        chat_msgs.controls.append(ft.Container(
            content=ft.Column([
                ft.Row([ft.Text(f"🤖 Bot", weight=ft.FontWeight.BOLD, size=12, color=Tema.blanco), ft.Container(padding=ft.padding.Padding(left=6, top=2, right=6, bottom=2), bgcolor=color_bot, border_radius=6, content=ft.Text(tipo, size=9, color=Tema.blanco, weight=ft.FontWeight.W_600)), ft.Text(f"{conf:.2f}", size=10, color=ft.Colors.GREY_300)], spacing=6),
                ft.Text(resp[:150], size=12, color=ft.Colors.GREY_200),
            ]),
            bgcolor=Tema.acento, border_radius=12, padding=14, margin={"bottom": 8},
        ))
        chat_input.value = ""
        page.update()

    chat_input.on_submit = enviar

    return ft.Column([
        Tema.titulo_seccion("Chatbot SIMANW"),
        Tema.subtitulo("Asistente conversacional con memoria de contexto (AC-6)"),
        ft.Divider(height=10),
        Tema.boton("🔄 Iniciar Conversación", iniciar_chat, ft.Icons.CHAT, Tema.acento),
        ft.Divider(height=10),
        ft.Container(content=chat_msgs, expand=True, bgcolor=Tema.blanco, border_radius=12, padding=14, shadow=ft.BoxShadow(blur_radius=10, color=ft.Colors.BLACK12, offset=ft.Offset(0, 2))),
        ft.Divider(height=8),
        ft.Row([chat_input, ft.IconButton(icon=ft.Icons.SEND, on_click=enviar, icon_color=Tema.acento, bgcolor=ft.Colors.INDIGO_50)], spacing=8),
    ], expand=True)


# ═══════════════════════════════════════════════════════════════════
#  PÁGINA 6: SEMÁNTICA (AC-7 + AC-13)
# ═══════════════════════════════════════════════════════════════════
def build_semantica(page):
    triples_text = ft.Text("", size=12, selectable=True, font_family="monospace")
    serializaciones_text = ft.Text("", size=12, selectable=True)
    consultas_text = ft.Text("", size=12, selectable=True, font_family="monospace")
    validacion_text = ft.Text("", size=12, selectable=True)
    jsonld_text = ft.Text("", size=12, selectable=True, font_family="monospace")

    def run_ac7(e):
        from ac7_enriquecedor_kg import EnriquecedorKG
        from base.knowledge_graph import KnowledgeGraphSIMANW
        kg = KnowledgeGraphSIMANW()
        DATA = kg.DATA
        notis = [
            {"titulo": "IA Generativa revoluciona la industria","cuerpo":"Modelos de lenguaje transforman contenido digital.","fecha":"2026-05-20","autor":"Redacción Tech","categoria_original":"tecnologia","url":"https://ejemplo.com/ia"},
            {"titulo": "Mercados financieros en recuperación","cuerpo":"Índices bursátiles muestran signos de recuperación.","fecha":"2026-05-19","autor":"Redacción Economía","categoria_original":"economia","url":"https://ejemplo.com/economia"},
            {"titulo": "Estudio sobre cambio climático","cuerpo":"Investigadores advierten sobre calentamiento global.","fecha":"2026-05-18","autor":"Redacción Ciencia","categoria_original":"ciencia","url":"https://ejemplo.com/ciencia"},
        ]
        for i, n in enumerate(notis, 1):
            kg.agregar_noticia(n, i)
        ekg = EnriquecedorKG(kg)
        ekg.enlazar_entidad(DATA["categoria_tecnologia"], "Q11016", "Tecnología de la información")
        ekg.enlazar_entidad(DATA["categoria_economia"], "Q159810", "Economía")
        ekg.enlazar_entidad(DATA["categoria_ciencia"], "Q336", "Ciencia")
        triples_text.value = f"KG construido: {kg.total_triples()} triples\n{len(ekg.enlaces_externos)} enlaces a Wikidata"
        serializaciones_text.value = "Turtle, JSON-LD y RDF/XML exportados en la raíz del proyecto"
        page.update()

    def run_ac13(e):
        from ac13_publicacion_semantica import construir_grafo_ejemplo, exportar_serializaciones, CONSULTAS_SPARQL, validar_con_shacl, FRAGMENTO_JSONLD, GLOSARIO_ONTOLOGIA, ENLACES_EXTERNOS_DOC, TEXTO_DESCUBRIMIENTO
        kg, _ = construir_grafo_ejemplo()
        archivos = exportar_serializaciones(kg, str(BASE))
        qs = []
        for c in CONSULTAS_SPARQL:
            try:
                r = kg.consultar(c['query'])
                qs.append(f"{c['nombre']}: {len(r)} filas")
            except Exception as ex:
                qs.append(f"{c['nombre']}: error parcial")
        v = validar_con_shacl(kg)
        validacion_text.value = f"SHACL: {'✓ Conforme' if v['conforme'] else '✗ Violaciones'}\n{v['resultados'][:300]}"
        consultas_text.value = '\n'.join(qs)
        serializaciones_text.value = '\n'.join(f"{fmt}: {os.path.getsize(ruta)} bytes" for fmt, ruta in archivos.items())
        jsonld_text.value = json.dumps(FRAGMENTO_JSONLD, ensure_ascii=False, indent=2)[:800]
        page.update()

    tabs_ref = ["AC-7: Knowledge Graph", "AC-13: Publicación Semántica"]

    kg_content = ft.Column([
        Tema.titulo_seccion("Knowledge Graph"),
        Tema.subtitulo("Enriquecimiento del grafo con enlaces a Wikidata"),
        ft.Divider(height=10),
        Tema.boton("▶ Construir KG", run_ac7, ft.Icons.HUB, Tema.acento),
        ft.Divider(height=5), triples_text, serializaciones_text,
    ], scroll=ft.ScrollMode.AUTO)

    sem_content = ft.Column([
        Tema.titulo_seccion("Publicación Semántica"),
        Tema.subtitulo("Serialización, validación SHACL, SPARQL y JSON-LD"),
        ft.Divider(height=10),
        Tema.boton("▶ Ejecutar Publicación", run_ac13, ft.Icons.PUBLIC, Tema.info),
        ft.Divider(height=5),
        Tema.card(ft.Column([ft.Text("Serializaciones", weight=ft.FontWeight.BOLD, size=14), serializaciones_text])),
        ft.Divider(height=5),
        Tema.card(ft.Column([ft.Text("Consultas SPARQL", weight=ft.FontWeight.BOLD, size=14), consultas_text])),
        ft.Divider(height=5),
        Tema.card(ft.Column([ft.Text("Validación", weight=ft.FontWeight.BOLD, size=14), validacion_text])),
        ft.Divider(height=5),
        Tema.card(ft.Column([ft.Text("JSON-LD Fragmento", weight=ft.FontWeight.BOLD, size=14), jsonld_text])),
    ], scroll=ft.ScrollMode.AUTO)

    pages = [kg_content, sem_content]
    return Tema.tab_selector(tabs_ref, pages)


# ═══════════════════════════════════════════════════════════════════
#  MAIN
# ═══════════════════════════════════════════════════════════════════
def main(page: ft.Page):
    page.title = "SIMANW - Sistema Inteligente de Monitoreo y Análisis de Noticias Web"
    page.theme_mode = ft.ThemeMode.LIGHT
    page.padding = 0
    page.window_width = 1280
    page.window_height = 860
    page.bgcolor = Tema.fondo

    page.theme = ft.Theme(
        color_scheme=ft.ColorScheme(
            primary=ft.Colors.INDIGO_800,
            primary_container=ft.Colors.INDIGO_100,
            secondary=ft.Colors.TEAL_600,
            surface=Tema.fondo,
        ),
        font_family="Segoe UI",
    )

    cargar_noticias()

    builders = [build_dashboard, build_rastreo, build_analisis, build_clasificacion, build_chatbot, build_semantica]
    pages = [b(page) for b in builders]
    current = ft.Container(content=pages[0], expand=True, padding=30, bgcolor=Tema.fondo)

    def on_nav(e):
        idx = e.control.selected_index
        pages[idx] = builders[idx](page)
        current.content = pages[idx]
        current.update()

    rail = ft.NavigationRail(
        selected_index=0,
        label_type=ft.NavigationRailLabelType.ALL,
        min_width=110,
        group_alignment=-0.9,
        bgcolor=Tema.primario,
        destinations=[
            ft.NavigationRailDestination(icon=ft.Icons.DASHBOARD_OUTLINED, selected_icon=ft.Icons.DASHBOARD, label="Dashboard"),
            ft.NavigationRailDestination(icon=ft.Icons.RSS_FEED_OUTLINED, selected_icon=ft.Icons.RSS_FEED, label="Rastreo"),
            ft.NavigationRailDestination(icon=ft.Icons.ANALYTICS_OUTLINED, selected_icon=ft.Icons.ANALYTICS, label="Análisis"),
            ft.NavigationRailDestination(icon=ft.Icons.CATEGORY_OUTLINED, selected_icon=ft.Icons.CATEGORY, label="Clasificación"),
            ft.NavigationRailDestination(icon=ft.Icons.CHAT_OUTLINED, selected_icon=ft.Icons.CHAT, label="Chatbot"),
            ft.NavigationRailDestination(icon=ft.Icons.HUB_OUTLINED, selected_icon=ft.Icons.HUB, label="Semántica"),
        ],
        on_change=on_nav,
    )

    page.add(
        ft.Row([
            ft.Container(content=rail, padding=8, bgcolor=Tema.primario),
            ft.VerticalDivider(width=1, color=ft.Colors.GREY_300),
            current,
        ], expand=True, spacing=0)
    )


if __name__ == '__main__':
    ft.run(main)
