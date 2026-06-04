<?php

namespace App\Livewire;

use Livewire\Component;
use Carbon\Carbon;

class TutorFechaSelector extends Component
{
    public $fechas = [];
    public $fechaSeleccionada = '';

    public function mount()
    {
        $inicio = Carbon::now()->startOfWeek();
        $semanas = 10;

        for ($i = 0; $i < $semanas; $i++) {
            $fecha = $inicio->copy()->addWeeks($i);
            $this->fechas[] = [
                'valor' => $fecha->format('Y-m-d'),
                'formateado' => $fecha->translatedFormat('d - F')
            ];
        }

        $this->fechaSeleccionada = $this->fechas[0]['valor'];
    }

    public function seleccionarFecha($fecha)
    {
        $this->fechaSeleccionada = $fecha;

        // ✅ En Livewire 3 se usa dispatch
        $this->dispatch('fechaSeleccionadaCambiada', $fecha);
    }

    public function render()
    {
        return view('livewire.tutor-fecha-selector');
    }
}


