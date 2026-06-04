<?php

namespace App\Livewire;

use App\Models\ExamenVocacional;
use App\Models\Foda;
use App\Models\Tutorado;
use App\Models\Lectura;
use App\Models\Vida;
use Livewire\Component;

class TutorActividadesMostrarView extends Component
{
    public $grupoId;
    public $nAct;
    public $tutoradosGrupo;
    public $tutoradosActividad;
    public $actividad;

    public function render()
    {
        return view('livewire.tutor-actividades-mostrar-view');
    }

    public function mount($grupoId, $nAct)
    {

        $this->grupoId = $grupoId;
        $this->nAct = $nAct;
        $this->tutoradosGrupo = Tutorado::where('idGrupo', $grupoId)->get();
        switch ($nAct) {
            case '01': //Comprension
                $this->actividad = "Comprensión Lectora";
                foreach ($this->tutoradosGrupo as $tutorado) {
                    if (Lectura::where('idCuentaTutorado', $tutorado->idCuentaTutorado)->exists()) {
                        $this->tutoradosActividad[] = $tutorado;
                    }
                }
                break; //Foda
            case '02':
                $this->actividad = "Análisis Foda";
                foreach ($this->tutoradosGrupo as $tutorado) {
                    if (Foda::where('idCuentaTutorado', $tutorado->idCuentaTutorado)->exists()) {
                        $this->tutoradosActividad[] = $tutorado;
                    }
                }
                break;
            // Agrega más casos según sea necesario
            case '03': //Vida
                $this->actividad = "Línea de Vida";
                foreach ($this->tutoradosGrupo as $tutorado) {
                    if (Vida::where('idCuentaTutorado', $tutorado->idCuentaTutorado)->exists()) {
                        $this->tutoradosActividad[] = $tutorado;
                    }
                }
                break;
            case '04': //Vocaci
                $this->actividad = "Examen Vocacional";
                foreach ($this->tutoradosGrupo as $tutorado) {
                    if (ExamenVocacional::where('idCuentaTutorado', $tutorado->idCuentaTutorado)->exists()) {
                        $this->tutoradosActividad[] = $tutorado;
                    }
                }
                break;
        }
    }
    public function ir($idTutorado)
    {
        switch ($this->nAct) {
            case '01': //Comprension
                $this->redirectRoute('tutor.actividades.banco.comprension', ['idTutorado' => $idTutorado,'grupoId'=>$this->grupoId,'nAct'=>$this->nAct]);
                break; //Foda
            case '02':
                $this->redirectRoute('tutor.actividades.banco.foda', ['idTutorado' => $idTutorado,'grupoId'=>$this->grupoId,'nAct'=>$this->nAct]);
                break;
            // Agrega más casos según sea necesario
            case '03': //Vida
                $this->redirectRoute('tutor.actividades.banco.vida', ['idTutorado' => $idTutorado,'grupoId'=>$this->grupoId,'nAct'=>$this->nAct]);
                break;
            case '04': //Vocaci
                $this->redirectRoute('tutor.actividades.banco.examen', ['idTutorado' => $idTutorado,'grupoId'=>$this->grupoId,'nAct'=>$this->nAct]);
                break;
            default:
                $this->redirectRoute('login');
        }
    }
}
