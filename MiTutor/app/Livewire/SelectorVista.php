<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Tutor;
use App\Models\Tutorado;

class SelectorVista extends Component
{
    public $vistaActual = 'tutores';
    public $datos = [];

    public function mount()
    {
        $this->actualizarDatos();
    }

    public function cambiarVista($vista)
    {
        $this->vistaActual = $vista;
        $this->actualizarDatos();
    }

    public function actualizarDatos()
    {
        if ($this->vistaActual === 'tutores') {
            $this->datos = Tutor::with('grupo')->get();
        } else {
            $this->datos = Tutorado::with('grupo')->get();
        }
    }

    public function render()
    {
        return view('livewire.selector-vista');
    }
}
