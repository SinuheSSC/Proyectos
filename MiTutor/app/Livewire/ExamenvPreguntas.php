<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ExamenVocacional;
use App\Models\Tutorado;
use Illuminate\Support\Facades\Auth;

class ExamenvPreguntas extends Component
{
    public $currentStep = 1;
    public $idCuentaTutorado;
    public $respuestas = [];

    private $puntajes = [
        'Ciencias Médicas' => 0,
        'Ingeniería' => 0,
        'Negocios y Administración' => 0,
        'Ciencias Sociales' => 0,
        'Humanidades y Artes' => 0,
        'Educación' => 0,
        'Físico-Matemático' => 0,
        'Ciencias Agropecuarias y Naturales' => 0,
    ];

    public function mount()
    {
        $curp = session('curp');

        if (!$curp) abort(403, 'No tienes una sesión activa.');

        $tutorado = Tutorado::where('curp', $curp)->first();

        if (!$tutorado) abort(403, 'No tienes un tutorado asociado.');

        // Corregido: usar el campo correcto de la base de datos
        $this->idCuentaTutorado = $tutorado->idCuentaTutorado ?? $tutorado->idCuenta;
    }

    public function nextStep() { if ($this->currentStep < 4) $this->currentStep++; }
    public function prevStep() { if ($this->currentStep > 1) $this->currentStep--; }

    public function finishExam()
    {
        $curp = session('curp');
        if (!$curp) abort(403, 'No tienes una sesión activa.');

        $tutorado = Tutorado::where('curp', $curp)->first();
        if (!$tutorado) abort(403, 'No se encontró el tutorado para guardar el examen.');

        $this->calcularPuntajes();
        $tipo = $this->determinarTipo();
        $areasDeEspecialidad = $this->getAreasDeEspecialidad($tipo);
        $carrerasRecomendadas = $this->getCarrerasRecomendadas($tipo);
        $recomendaciones = $this->getRecomendaciones($tipo);

        // Usar el campo correcto para la clave
        $fieldKey = $tutorado->idCuentaTutorado ?? $tutorado->idCuenta;

        $examen = ExamenVocacional::updateOrCreate(
            ['idCuentaTutorado' => $fieldKey],
            [
                'areasDeEspecialidad' => $areasDeEspecialidad,
                'carrerasRecomendadas' => $carrerasRecomendadas,
                'recomendaciones' => $recomendaciones,
                'idCuentaTutorado' => $fieldKey,
                'respuestas' => json_encode($this->respuestas),
            ]
        );

        return redirect()->route('examenv.resultados', ['id' => $examen->idExamen]);
    }

    private function calcularPuntajes()
    {
        foreach ($this->puntajes as $area => $valor) {
            $this->puntajes[$area] = 0;
        }

        foreach ($this->respuestas as $respuesta) {
            $respuesta = strtolower($respuesta);

            if (str_contains($respuesta, 'salud') || str_contains($respuesta, 'medicina')) {
                $this->puntajes['Ciencias Médicas']++;
            } elseif (str_contains($respuesta, 'programar') || str_contains($respuesta, 'tecnología') || str_contains($respuesta, 'ingeniería')) {
                $this->puntajes['Ingeniería']++;
            } elseif (str_contains($respuesta, 'empresa') || str_contains($respuesta, 'negocio') || str_contains($respuesta, 'administración')) {
                $this->puntajes['Negocios y Administración']++;
            } elseif (str_contains($respuesta, 'sociedad') || str_contains($respuesta, 'psicología') || str_contains($respuesta, 'política')) {
                $this->puntajes['Ciencias Sociales']++;
            } elseif (str_contains($respuesta, 'arte') || str_contains($respuesta, 'historia') || str_contains($respuesta, 'literatura')) {
                $this->puntajes['Humanidades y Artes']++;
            } elseif (str_contains($respuesta, 'enseñar') || str_contains($respuesta, 'educación')) {
                $this->puntajes['Educación']++;
            } elseif (str_contains($respuesta, 'matemática') || str_contains($respuesta, 'física') || str_contains($respuesta, 'cálculo')) {
                $this->puntajes['Físico-Matemático']++;
            } elseif (str_contains($respuesta, 'agro') || str_contains($respuesta, 'naturaleza') || str_contains($respuesta, 'medio ambiente')) {
                $this->puntajes['Ciencias Agropecuarias y Naturales']++;
            }
        }
    }

    private function determinarTipo()
    {
        return collect($this->puntajes)->sortDesc()->keys()->first();
    }

    private function getAreasDeEspecialidad($tipo)
    {
        return $tipo;
    }

    private function getCarrerasRecomendadas($tipo)
    {
        $carreras = [
            'Ciencias Médicas' => 'Medicina, Enfermería, Odontología',
            'Ingeniería' => 'Ingeniería en Sistemas, Mecatrónica, Civil',
            'Negocios y Administración' => 'Administración, Contaduría, Marketing',
            'Ciencias Sociales' => 'Psicología, Sociología, Ciencia Política',
            'Humanidades y Artes' => 'Historia, Filosofía, Artes Visuales',
            'Educación' => 'Pedagogía, Educación Especial, Educación Inicial',
            'Físico-Matemático' => 'Matemáticas, Física, Actuaría',
            'Ciencias Agropecuarias y Naturales' => 'Agronomía, Biología, Ciencias Ambientales',
        ];
        return $carreras[$tipo] ?? 'General';
    }

    private function getRecomendaciones($tipo)
    {
        $tips = [
            'Ciencias Médicas' => 'Busca programas con enfoque en salud y contacto humano.',
            'Ingeniería' => 'Fortalece tus habilidades lógico-matemáticas y de resolución de problemas.',
            'Negocios y Administración' => 'Aprende sobre economía, liderazgo y finanzas.',
            'Ciencias Sociales' => 'Participa en debates, lecturas de actualidad y análisis crítico.',
            'Humanidades y Artes' => 'Explora tu creatividad mediante lectura, escritura o arte.',
            'Educación' => 'Desarrolla habilidades de comunicación y empatía.',
            'Físico-Matemático' => 'Desafía tu mente con acertijos y problemas abstractos.',
            'Ciencias Agropecuarias y Naturales' => 'Interésate por la sostenibilidad y la vida natural.',
        ];
        return $tips[$tipo] ?? 'Explora tus intereses y busca orientación profesional.';
    }

  public function render()
{
    try {
        $curp = session('curp');
        
        if (!$curp) {
            return view('livewire.error-view')->with('error', 'Sesión no válida');
        }

        $tutorado = Tutorado::where('curp', $curp)->first();
        
        if (!$tutorado) {
            return view('livewire.error-view')->with('error', 'Tutorado no encontrado');
        }

        $fieldKey = $tutorado->idCuentaTutorado ?? $tutorado->idCuenta;
        $examen = ExamenVocacional::where('idCuentaTutorado', $fieldKey)->first();

        // Retorna la vista sin layout específico (usará el default)
        return view('livewire.examenv-preguntas', [
            'examen' => $examen,
            'currentStep' => $this->currentStep,
            'respuestas' => $this->respuestas
        ]);
        
    } catch (\Exception $e) {
        return view('livewire.error-view')->with('error', 'Error al cargar el examen: '.$e->getMessage());
    }
}

}