<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Grupo;
use App\Models\CicloEscolar;
use Illuminate\Validation\ValidationException;

class TutorReporteConsultaCard extends Component
{
    public $grupo;
    public $ciclo;
    public $grupos = [];
    public $ciclos = [];

    protected $rules = [
        'grupo' => 'required', // 'grupo' es el nombre de tu propiedad wire:model
        'ciclo' => 'required',
    ];

    public function mount()
    {
        $curp = session('curp');
        $tutorId = \App\Models\Tutor::where('curp', $curp)->value('idCuentaTutor');

        $this->grupos = Grupo::where('idCuentaTutor', $tutorId)->get();
        $this->ciclos = CicloEscolar::where('idCuentaTutor', $tutorId)->get();
    }

    public function guardar()
    {
        $this->resetValidation();
        try {
            $this->validate();
            $this->redirectRoute('tutor.reportes.result', [
                'grupoId' => $this->grupo,
                'cicloId' => $this->ciclo,
            ]);
        } catch (ValidationException $e) {
            session()->flash('error', 'Por favor, selecciona ambos campos');
        }
    }

    public function render()
    {
        return view('livewire.tutor-reporte-consulta-card');
    }
}
