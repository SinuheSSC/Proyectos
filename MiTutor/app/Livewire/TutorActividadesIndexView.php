<?php

namespace App\Livewire;

use App\Models\Tutorado;
use Livewire\Component;

class TutorActividadesIndexView extends Component
{
    public $tutorados;
    public $actividad;
    public function render()
    {
        $this->tutorados = Tutorado::all();
        return view('livewire.tutor-actividades-index-view');
    }
}
