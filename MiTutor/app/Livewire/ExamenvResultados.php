<?php

namespace App\Http\Livewire; // Corregí el namespace a App\Http\Livewire según convención

use Livewire\Component;
use App\Models\ExamenVocacional;

class ExamenvResultados extends Component
{
    public $examen;

    // El método mount recibe el parámetro $id para cargar el examen
    public function mount($id)
    {
        $this->examen = ExamenVocacional::where('idExamen', $id)->firstOrFail();
    }

    public function render()
    {
        // Pasas explícitamente la variable a la vista, aunque no es obligatorio
        return view('livewire.examenv-resultados', [
            'examen' => $this->examen
        ]);
    }
}
