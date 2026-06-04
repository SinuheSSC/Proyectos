<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Tutor;
use App\Models\Grupo;
use App\Models\CicloEscolar;
use Illuminate\Validation\ValidationException;



class TutorActividadesConsultaCard extends Component
{
    public $grupos;
    public $ciclos;
    public $grupo;
    public $ciclo;

    protected $rules = [
        'grupo' => 'required', // 'grupo' es el nombre de tu propiedad wire:model
        'ciclo' => 'required',
    ];// Define un listener para el evento 'refreshGrupos' que se disparará desde JavaScript
    protected $listeners = ['refreshAll' => 'refresh'];


    public function guardar()
    {
        $this->resetValidation();
        try {
            $this->validate();
            $this->redirectRoute('tutor.actividades.banco.index', ['grupoId' => $this->grupo]);
        } catch (ValidationException $e) {
            session()->flash('error', 'Por favor, selecciona ambas opciones');
        }
    }

    public function mount()
    {
        $curp = session('curp');
        $tutor = Tutor::where('curp', $curp)->first();
        $this->grupos = $tutor ? Grupo::where('idCuentaTutor', $tutor->idCuentaTutor)->get() : collect();
        $this->ciclos = $tutor ? CicloEscolar::where('idCuentaTutor', $tutor->idCuentaTutor)->get() : collect();
        $this->refresh();
    }

    public function render()
    {
        return view('livewire.tutor-actividades-consulta-card');
    }

    public function refresh(){
        $this->grupo='';
        $this->ciclo='';
    }
}

