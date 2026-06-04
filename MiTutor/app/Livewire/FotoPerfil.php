<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Tutorado;

class FotoPerfil extends Component
{
    public $fotoPerfil;

    public function mount()
    {
        // Solo para pruebas: traer el primer tutorado de la BD
$tutorado = Tutorado::where('curp', session('curp'))->first();
    if ($tutorado) {
        $this->nombre = $tutorado->nombres;
        // ...
    }        $this->fotoPerfil = $tutorado?->fotoPerfil;
    }


    public function render()
    {
        return view('livewire.foto-perfil');
    }
}
