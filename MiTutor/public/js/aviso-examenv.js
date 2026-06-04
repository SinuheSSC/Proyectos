class AvisoExamenv extends HTMLElement {
    constructor() {
      super();
      this.attachShadow({ mode: "open" });
  
      this.shadowRoot.innerHTML = `
        <style>
          .card {
            background: #f0f7ff;
            border-left: 5px solid #6495ed;
            padding: 20px;
            border-radius: 8px;
            max-width: 450px;
            font-family: Arial, sans-serif;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
          }
          .title {
            color: #6495ed;
            font-size: 16px;
            font-weight: bold;
            display: flex;
            align-items: center;
          }
          .title::before {
            content: "ℹ️";
            margin-right: 8px;
          }
          .content {
            font-size: 14px;
            color: #333;
            margin-top: 10px;
          }
          .button-container {
            text-align: right;
            margin-top: 15px;
          }
          button {
            background: #ccc;
            color: #888;
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            font-size: 14px;
            cursor: not-allowed;
          }
        </style>
        <div class="card">
          <div class="title">Importante leer antes de comenzar el examen de vocación.</div>
          <div class="content">
            <p>La realización de un test vocacional pretende ayudar a la persona a conocer su orientación profesional, para saber cuál área se adapta a sus aptitudes.</p>
            <p>Este examen no tiene un límite de tiempo, lea cuidadosamente las preguntas y responda con honestidad.</p>
            <p>(Si ya completó el examen y necesita volver a realizarlo, usted puede volver a realizar el examen).</p>
            <p><strong>Este examen consta de 25 preguntas.</strong></p>
          </div>
          <div class="button-container">
            <button >Iniciar</button>
          </div>
        </div>
      `;
    }
  }
  
  // Registrar el Web Component
  customElements.define("aviso-examenv", AvisoExamenv);
  