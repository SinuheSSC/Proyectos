<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Grupo;
use App\Models\Tutorado;
use App\Models\Asistencia;
use App\Models\CicloEscolar;
use App\Models\Cita;

class TutorReportesResultadoView extends Component
{
    public $grupo;
    public $cicloEscolar;
    public $sesiones;
    public $promAsistencia;
    public $atenciones;

    public function mount($grupoId, $cicloId)
    {
        $grupo = Grupo::where('idGrupo', $grupoId)->firstOrFail();
        $ciclo = CicloEscolar::where('idCicloEscolar', $cicloId)->firstOrFail();

        $this->grupo = 'Grupo ' . $grupo->letra;
        $this->cicloEscolar = $ciclo->ciclo . ' - ' . $ciclo->year;
        $this->sesiones = $ciclo->sesiones;

        $tutorados = Tutorado::where('idGrupo', $grupo->idGrupo)->get();
        $totalEsperadas = $tutorados->count() * 24;

        $this->promAsistencia = $totalEsperadas > 0
            ? round((Asistencia::whereIn('idCuentaTutorado', $tutorados->pluck('idCuentaTutorado'))->count() / $totalEsperadas) * 100, 2)
            : 0;

        $this->atenciones = Cita::whereIn('idCuentaTutorado', $tutorados->pluck('idCuentaTutorado'))->count();
    }



    public function render()
    {
        return view('livewire.tutor-reportes-resultado-view');
    }

    public function goBack(){
        $this->redirectRoute('tutor.index');
    }
}
