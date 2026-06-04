<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Tutorado;

class MiInformacionE extends Component
{
    public $hasDisability = null;
    public $disabilityDetails = '';

    public $hasIllness = null;
    public $illnessDetails = '';

    public $PsychologicalSituation = null;
    public $PsychologicalSituationDetails = '';

    public $hasSpecialCondition = null;
    public $specialConditionDetails = ''; // 🔧 Agregado

    public $generalNeeds = ''; // Para necesidadEspecial

    public $tutorado;
    public $curp = 'GALA850615HMCRPN08';

    public function mount()
    {
          $curp = session('curp');

    if ($curp) {
        $this->tutorado = Tutorado::where('curp', $curp)->first();
    } else {
        $this->tutorado = null;
    }

        if ($this->tutorado) {
            $this->hasDisability = $this->tutorado->discapacidadFisica ? 'Sí' : 'No';
            $this->disabilityDetails = $this->tutorado->discapacidadFisica;

            $this->hasIllness = $this->tutorado->enfemerdad ? 'Sí' : 'No';
            $this->illnessDetails = $this->tutorado->enfemerdad;

            $this->PsychologicalSituation = $this->tutorado->situacionPsicologica ? 'Sí' : 'No';
            $this->PsychologicalSituationDetails = $this->tutorado->situacionPsicologica;

            // Guardar el valor de la condición especial en el campo generalNeeds
            $this->generalNeeds = $this->tutorado->necesidadEspecial;
        }
    }

    public function updatedHasDisability()
    {
        if ($this->hasDisability !== 'Sí') {
            $this->disabilityDetails = '';
        }
    }

    public function updatedHasIllness()
    {
        if ($this->hasIllness !== 'Sí') {
            $this->illnessDetails = '';
        }
    }

    public function updatedHasSpecialCondition()
    {
        if ($this->hasSpecialCondition !== 'Sí') {
            $this->specialConditionDetails = '';
        }
    }

    public function saveSpecialNeeds()
    {
        if ($this->tutorado) {
            $this->tutorado->discapacidadFisica = $this->hasDisability === 'Sí' ? $this->disabilityDetails : null;
            $this->tutorado->enfemerdad = $this->hasIllness === 'Sí' ? $this->illnessDetails : null;
            $this->tutorado->necesidadEspecial = $this->hasSpecialCondition === 'Sí' ? $this->specialConditionDetails : null;
            $this->tutorado->situacionPsicologica = $this->PsychologicalSituation === 'Sí' ? $this->PsychologicalSituationDetails : null;

            $this->tutorado->save();

            session()->flash('message', 'Información de necesidades especiales guardada correctamente.');
        }
    }

    public function render()
    {
        return view('livewire.mi-informacionE');
    }
}
