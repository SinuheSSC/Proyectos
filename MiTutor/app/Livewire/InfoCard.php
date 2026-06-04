<?php

namespace App\Livewire;

use Livewire\Component;

class InfoCard extends Component
{
    public $responsable;
    public $carrera;
    public $estado;
    public $grupo;
    public $tipo;
    public $vista = 'tutores';

    public function render()
    {
        //logger('Renderizando InfoCard'); // Para probar si se ejecuta
        return view('livewire.info-card');
    }
}
