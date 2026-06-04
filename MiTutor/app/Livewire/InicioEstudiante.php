<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Tutorado;

class InicioEstudiante extends Component
{
    public $nombre;
    public $matricula;
    public $carrera;
    public $necesidad_especial;
    public $tutorado;
    public $razonesCarrera;

    public function mount()
    {
        $curp = session('curp');

        if ($curp) {
            $this->tutorado = Tutorado::where('curp', $curp)->first();
            
            if ($this->tutorado) {
                $this->nombre = $this->tutorado->nombres;
                $this->matricula = $this->tutorado->idCuentaTutorado;
                $this->carrera = $this->tutorado->carrera;
                $this->razonesCarrera = $this->tutorado->razonesCarrera;
                
                // Validación para necesidad especial
                // Si el campo está vacío, null o es cadena vacía, mostrar "NO"
                // Si tiene cualquier valor, mostrar "SI"
                $this->necesidad_especial = (!empty($this->tutorado->necesidadEspecial) && 
                                           !is_null($this->tutorado->necesidadEspecial) && 
                                           trim($this->tutorado->necesidadEspecial) !== '') ? 'SI' : 'NO';
            }
        } else {
            $this->tutorado = null;
        }
    }

    public function render()
    {
        return view('livewire.inicio-estudiante');
    }
}