<?php

namespace App\Livewire;

use App\Models\Lectura;
use App\Models\Tutorado;
use Livewire\Component;

class TutorReadingTestView extends Component
{
    public $idTutorado;
    public $grupoId;
    public $nAct;
    public $respuestaReading;
    public $tutorado;
    public function mount($idTutorado,$grupoId,$nAct)
    {
        $this->idTutorado = $idTutorado;
        $this->grupoId = $grupoId;
        $this->nAct = $nAct;
        $this->respuestaReading = Lectura::where('idCuentaTutorado',$idTutorado)->first();
        $this->tutorado = Tutorado::where('idCuentaTutorado',$idTutorado)->first();

    }
    public function goBack(){

        $this->redirectRoute('tutor.index', ['idTutorado' => $this->idTutorado,'grupoId'=>$this->grupoId,'nAct'=>$this->nAct]);
    }

    public function render()
    {
        return view('livewire.tutor-reading-test-view');
    }
}
