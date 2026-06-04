<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ExamenVocacional;
use App\Models\Tutorado;

class Examenv extends Component
{
    public $examen;
    public $tutorado;

    public function mount()
{
    $curp = session('curp');

    if ($curp) {
        $this->tutorado = Tutorado::where('curp', $curp)->first();

        if (!$this->tutorado) {
            abort(403, 'Usuario no encontrado');
        }

        
        $this->examen = ExamenVocacional::where('idCuentaTutorado', $this->tutorado->id)->first();
    } else {
        abort(403, 'No has iniciado sesión');
    }
}

    // Método para crear o actualizar el examen cuando el usuario termine
    public function guardar($datosExamen)
    {
        // Validar datos recibidos (puedes adaptar esta validación)
        $this->validate([
            'areasDeEspecialidad' => 'required',
            // otros campos y reglas que necesites
        ]);

        if ($this->examen) {
            // Actualizar examen existente
            $this->examen->update($datosExamen);
        } else {
            // Crear nuevo examen cuando se termine
            $this->examen = ExamenVocacional::create(array_merge([
                'idCuentaTutorado' => $this->tutorado->id,
            ], $datosExamen));
        }

        // Opcional: emitir evento o mensaje de éxito
        $this->emit('examenGuardado');
    }

    public $currentStep = 1;
    public $reviewing = false; // Bandera para controlar la vista de revisión del FODA

    public function nextStep()
    {
        if ($this->currentStep < 4) {
            $this->currentStep++;
        }
    }

    public function finishExam()
    {
        return redirect()->route('tutor.actividades.banco.actividad.examenv.resultados');
    }

    public function prevStep()
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }
    public $contestando = true;
    public $descripcionCarrerasRecomendadas = "Esta sección muestra las 3 principales recomendaciones de carrera que el sistema ha proporcionado al tutorado, basándose en los resultados de su evaluación. Ofrece una visión rápida de las principales alineaciones vocacionales del tutorado identificadas por el sistema.";

    public $descripcionResumenResultados = "Esta sección proporciona al tutorado una declaración breve y general que resume los hallazgos clave de la evaluación. Destaca sus aptitudes o fortalezas generales tal como las interpreta el sistema.";


    public $descripcionRecomendacionesGenerales = [
        "Esta sección exhaustiva detalla los consejos amplios y los pasos a seguir que se le han dado al tutorado para su desarrollo.",
        "Cubre áreas esenciales, incluyendo tanto habilidades técnicas (por ejemplo, reforzar materias fundamentales, aprender lenguajes de programación específicos) como habilidades blandas (por ejemplo, comunicación, trabajo en equipo, liderazgo).",
        "Además, enfatiza la importancia del aprendizaje continuo y la exploración de diversas especializaciones dentro de los campos recomendados. Este contexto ayuda al tutorado a comprender cómo el sistema está guiándolo hacia el desarrollo de habilidades y la exploración de carreras."
    ];
    public function render()
    {
        return view('livewire.examenv');
    }
}
