<?php

namespace App\Livewire;

use App\Models\ExamenVocacional;
use App\Models\Tutorado;
use Livewire\Component;

class TutorExamenVocacional extends Component
{
    public $idTutorado;
    public $grupoId;
    public $nAct;
    public $respuestaVocacional;
    public $tutorado;
    public function mount($idTutorado, $grupoId, $nAct)
    {
        $this->idTutorado = $idTutorado;
        $this->grupoId = $grupoId;
        $this->nAct = $nAct;
        $this->respuestaVocacional = ExamenVocacional::where('idCuentaTutorado', $idTutorado)->first();
        $this->tutorado = Tutorado::where('idCuentaTutorado', $idTutorado)->first();
    }
    public function goBack()
    {
        $this->redirectRoute('tutor.index', ['idTutorado' => $this->idTutorado, 'grupoId' => $this->grupoId, 'nAct' => $this->nAct]);
    }
    public function render()
    {
        return view('livewire.tutor-examen-vocacional');
    }
}
