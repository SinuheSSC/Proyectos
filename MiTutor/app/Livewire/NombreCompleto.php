<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Tutorado;

class NombreCompleto extends Component
{
    public $nombre_completo;

    public function mount() 
    {
        $tutorado = Tutorado::where('curp', session('curp'))->first();

        if ($tutorado) {
            $this->nombre_completo = $tutorado->nombres . ' ' . $tutorado->apellidoPaterno . ' ' . $tutorado->apellidoMaterno;
        } else {
            $this->nombre_completo = 'Usuario desconocido';
        }
    }

    public function render()
    {
        return view('livewire.nombre-completo');
    }
}
