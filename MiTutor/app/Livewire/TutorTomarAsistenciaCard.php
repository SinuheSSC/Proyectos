<?php

namespace App\Livewire;

use App\Models\Grupo;
use App\Models\Tutor;
use Livewire\Component;
use Illuminate\Validation\ValidationException;

class TutorTomarAsistenciaCard extends Component
{
    public $grupo;

    protected $rules = [
        'grupo' => 'required', // 'grupo' es el nombre de tu propiedad wire:model
    ];

    public function guardar()
    {
        $this->resetValidation();
        try {
            $this->validate();
            $this->redirectRoute('tutor.asistencia.index', ['grupo' => $this->grupo]);
        } catch (ValidationException $e) {
            session()->flash('error', 'Por favor, selecciona un grupo');
        }
    }

    public function render()
    {
        $curp = session('curp');
        $tutor = Tutor::where('curp', $curp)->first();

        $grupos = $tutor ? Grupo::where('idCuentaTutor', $tutor->idCuentaTutor)->get() : collect();

        return view('livewire.tutor-tomar-asistencia-card', [
            'grupos' => $grupos
        ]);
    }
}


