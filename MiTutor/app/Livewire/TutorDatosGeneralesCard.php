<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Tutor;
use App\Models\Grupo;

class TutorDatosGeneralesCard extends Component
{
    public $name;
    public $rfc;
    public $groups = [];

    public function mount()
    {
        $curp = session('curp');

        $tutor = Tutor::where('curp', $curp)->first();

        if ($tutor) {
            $this->name = "{$tutor->nombres} {$tutor->apellidoPaterno} {$tutor->apellidoMaterno}";
            $this->rfc = $tutor->RFC;

            // ✅ Obtener las letras de los grupos impartidos por este tutor
            $this->groups = Grupo::where('idCuentaTutor', $tutor->idCuentaTutor)
                                ->pluck('letra')
                                ->toArray();
        } else {
            $this->name = "No encontrado";
            $this->rfc = "N/A";
            $this->groups = [];
        }
    }

    public function render()
    {
        return view('livewire.tutor-datos-generales-card');
    }
}


