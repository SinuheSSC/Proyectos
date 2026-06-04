<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Lectura;
use App\Models\Tutorado;

class ComprensionLectorae extends Component
{
    public $readingTab = 'introduction';
    public $readingQuizAnswers = [];
    public $idCuentaTutorado; // Propiedad para almacenar el ID del tutorado
    public $readingQuizQuestions = [
        [
            'question' => '¿Cuál es la paradoja central que plantea la Inteligencia Artificial, según el texto?',
            'options' => [
                'a' => 'Si la IA puede aprender sin supervisión humana.',
                'b' => 'Si una entidad puede exhibir inteligencia sin poseer conciencia.',
                'c' => 'Si la IA puede superar a los humanos en todos los dominios.',
                'd' => 'Si la IA puede desarrollar sus propias metas y objetivos.',
            ],
            'correct' => 'b'
        ],
        [
            'question' => '¿Cómo define el "funcionalismo" la inteligencia?',
            'options' => [
                'a' => 'Por la presencia de una experiencia interna o conciencia.',
                'b' => 'Por el sustrato biológico subyacente del sistema.',
                'c' => 'Por el comportamiento observable y los resultados.',
                'd' => 'Por la capacidad de integrar información de manera compleja.',
            ],
            'correct' => 'c'
        ],
        [
            'question' => 'Según la "teoría de la información integrada" (IIT) de Giulio Tononi, ¿qué propiedad es clave para la conciencia?',
            'options' => [
                'a' => 'La capacidad de replicar funciones cognitivas humanas.',
                'b' => 'Que el sistema esté hecho de neuronas biológicas.',
                'c' => 'La capacidad de integrar información de manera compleja.',
                'd' => 'La aprobación exitosa del Test de Turing.',
            ],
            'correct' => 'c'
        ],
        [
            'question' => '¿Qué son los "problemas difíciles de la conciencia" según David Chalmers?',
            'options' => [
                'a' => 'La dificultad de construir IA que superen a los humanos.',
                'b' => 'La brecha explicativa entre la función (cómo la IA procesa) y la experiencia (cómo sería sentir).',
                'c' => 'Los desafíos éticos de la IA.',
                'd' => 'La imposibilidad de predecir el comportamiento de una IA.',
            ],
            'correct' => 'b'
        ],
        [
            'question' => '¿Qué preocupación ética se menciona si una IA desarrollara conciencia?',
            'options' => [
                'a' => 'El riesgo de que la IA se vuelva demasiado lenta.',
                'b' => 'La falta de innovación en el desarrollo de IA.',
                'c' => 'Si tendría derechos y si seríamos responsables de su bienestar.',
                'd' => 'La dificultad para que la IA se integre en la sociedad.',
            ],
            'correct' => 'c'
        ],
        [
            'question' => '¿Qué ha llevado a figuras como Nick Bostrom a plantear escenarios de riesgo existencial en relación con la IA?',
            'options' => [
                'a' => 'La falta de interés público en la IA.',
                'b' => 'La posible emergencia de una "súper-inteligencia" con metas no alineadas con los humanos.',
                'c' => 'El lento progreso en el desarrollo de IA.',
                'd' => 'La dificultad de programar IA para tareas simples.',
            ],
            'correct' => 'b'
        ],
        [
            'question' => 'Según el texto, ¿qué tipo de enfoque define la inteligencia por el comportamiento observable y los resultados?',
            'options' => [
                'a' => 'La teoría de la información integrada.',
                'b' => 'La teoría del caos.',
                'c' => 'El funcionalismo.',
                'd' => 'El determinismo científico.',
            ],
            'correct' => 'c'
        ],
        [
            'question' => '¿Qué no existe que pueda determinar si una IA está realmente experimentando conciencia?',
            'options' => [
                'a' => 'Un "simulacrómetro".',
                'b' => 'Un "conciómetro".',
                'c' => 'Un "pensametro".',
                'd' => 'Un "cerebrómetro".',
            ],
            'correct' => 'b'
        ],
        [
            'question' => '¿Cuál es la naturaleza del fenómeno de la conciencia según el texto?',
            'options' => [
                'a' => 'Es un fenómeno objetivo y de tercera persona.',
                'b' => 'Es un fenómeno subjetivo y de primera persona.',
                'c' => 'Es completamente medible externamente.',
                'd' => 'Es solo una ilusión computacional.',
            ],
            'correct' => 'b'
        ],
        [
            'question' => '¿Qué se afirma en el texto sobre la pregunta de la conciencia en la IA?',
            'options' => [
                'a' => 'Ya ha sido resuelta por la ciencia moderna.',
                'b' => 'Es irrelevante para el desarrollo futuro de la IA.',
                'c' => 'Sigue siendo uno de los desafíos más profundos y complejos de la ciencia y la filosofía contemporáneas.',
                'd' => 'Solo concierne a los ingenieros de software.',
            ],
            'correct' => 'c'
        ],
    ];
    public $quizSubmitted = false;
    public $correctAnswersCount = 0;

    public function mount()
    {
        // Validar CURP en sesión
        $curp = session('curp');

        if (!$curp) {
            abort(403, 'No tienes una sesión activa.');
        }

        $tutorado = Tutorado::where('curp', $curp)->first();

        if (!$tutorado) {
            abort(403, 'No tienes un tutorado asociado.');
        }

        // Obtener el ID correcto del tutorado
        $this->idCuentaTutorado = $tutorado->idCuentaTutorado ?? $tutorado->idCuenta;

        if (!$this->idCuentaTutorado) {
            abort(403, 'No se pudo obtener el ID de la cuenta del tutorado.');
        }

        // Inicializa las respuestas del cuestionario a null o vacío para cada pregunta
        foreach ($this->readingQuizQuestions as $index => $q) {
            $this->readingQuizAnswers['q' . ($index + 1)] = null;
        }
    }

    public function submitReadingQuiz()
    {
        // Reglas de validación para asegurar que todas las preguntas sean respondidas
        $rules = [];
        foreach ($this->readingQuizQuestions as $index => $q) {
            $rules['readingQuizAnswers.q' . ($index + 1)] = 'required';
        }
        $this->validate($rules);

        $this->correctAnswersCount = 0;
        foreach ($this->readingQuizQuestions as $index => $q) {
            $answerKey = 'q' . ($index + 1);
            if (isset($this->readingQuizAnswers[$answerKey]) && $this->readingQuizAnswers[$answerKey] === $q['correct']) {
                $this->correctAnswersCount++;
            }
        }

        // Guardar en la base de datos
        Lectura::create([
            'nivelComprensionLectora' => $this->correctAnswersCount,
            'idCuentaTutorado' => $this->idCuentaTutorado,
        ]);

        $this->quizSubmitted = true;
        session()->flash('message', 'Cuestionario completado. ¡Revisa tus resultados!');
    }

    public function render()
    {
        return view('livewire.comprension-lectorae');
    }
}