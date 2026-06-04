<?php

namespace App\Livewire;

use App\Models\Foda;
use App\Models\Tutorado;
use Livewire\Component;
// Si estás usando Str::slug en tu vista para los IDs de los checkboxes,
// asegúrate de que Illuminate\Support\Str esté disponible.
// Laravel lo incluye por defecto, pero si estás fuera de un contexto típico de Blade,
// a veces es útil saberlo.
// use Illuminate\Support\Str;

class AnalisisFodaIndex extends Component
{
    public $idTutorado;
    public $grupoId;
    public $nAct;
    public $foda;
    public $tutorado;
    public function mount($idTutorado,$grupoId,$nAct)
    {
        $this->idTutorado=$idTutorado;
        $this->grupoId=$grupoId;
        $this->nAct=$nAct;
        $this->foda = Foda::where('idCuentaTutorado',$idTutorado)->first();
        $this->tutorado = Tutorado::where('idCuentaTutorado',$idTutorado)->first();
    }

    public function goBack(){

        $this->redirectRoute('tutor.index', ['idTutorado' => $this->idTutorado,'grupoId'=>$this->grupoId,'nAct'=>$this->nAct]);
    }


    public function render()
    {
        return view('livewire.analisis-foda-index');
    }

    public function salir()
    {
    }
}
