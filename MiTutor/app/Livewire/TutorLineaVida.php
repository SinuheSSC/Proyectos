<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Tutorado;
use App\Models\Vida;

class TutorLineaVida extends Component
{
    public $idTutorado;
    public $grupoId;
    public $nAct;
    public $respuestaVida;
    public $tutorado;
    public function mount($idTutorado, $grupoId, $nAct)
    {
        $this->idTutorado = $idTutorado;
        $this->grupoId = $grupoId;
        $this->nAct = $nAct;
        $this->respuestaVida = Vida::where('idCuentaTutorado', $idTutorado)->first();
        $this->tutorado = Tutorado::where('idCuentaTutorado', $idTutorado)->first();
    }
    public function goBack()
    {
        $this->redirectRoute('tutor.index', ['idTutorado' => $this->idTutorado, 'grupoId' => $this->grupoId, 'nAct' => $this->nAct]);
    }
    public function render()
    {
        return view('livewire.tutor-linea-vida');
    }
}
