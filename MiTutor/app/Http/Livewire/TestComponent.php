<?php

namespace App\Http\Livewire;

use Livewire\Component;

class TestComponent extends Component
{
    public $mensaje = "Hola desde Livewire 🎉";

    public function actualizarMensaje()
    {
        $this->mensaje = "Mensaje actualizado";
    }

    public function render()
    {
        return view('livewire.test-component');
    }
}
