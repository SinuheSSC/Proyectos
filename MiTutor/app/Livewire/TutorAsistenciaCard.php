<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Tutorado;
use App\Models\Asistencia;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;

class TutorAsistenciaCard extends Component
{
    public $grupoId;
    public $tutorados = [];
    public $asistencias = [];
    public $fechaSeleccionada;

    #[On('fechaSeleccionadaCambiada')] // ✅ Esto reemplaza $listeners
    public function actualizarFechaSeleccionada($fecha)
    {
        $this->fechaSeleccionada = Carbon::parse($fecha)->toDateString();
        $this->cargarTutoradosYAsistencias();
    }

    public function mount($grupoId)
    {
        $this->grupoId = $grupoId;
        $this->fechaSeleccionada = Carbon::now()->startOfWeek()->toDateString();
        $this->cargarTutoradosYAsistencias();
    }

    private function cargarTutoradosYAsistencias()
    {
        $this->tutorados = Tutorado::where('idGrupo', $this->grupoId)->get();
        $this->asistencias = [];

        foreach ($this->tutorados as $tutorado) {
            $registro = Asistencia::where('idCuentaTutorado', $tutorado->idCuentaTutorado)
                ->whereDate('asistencia', $this->fechaSeleccionada)
                ->exists();

            $this->asistencias[$tutorado->idCuentaTutorado] = $registro;
        }
    }

    public function cambiarEstadoAsistencia($id)
    {
        $fecha = Carbon::parse($this->fechaSeleccionada)->startOfDay();

        $existe = Asistencia::where('idCuentaTutorado', $id)
            ->whereDate('asistencia', $fecha)
            ->exists();

        if ($existe) {
            Asistencia::where('idCuentaTutorado', $id)
                ->whereDate('asistencia', $fecha)
                ->delete();
        } else {
            DB::table('asistencias')->insert([
                'idCuentaTutorado' => $id,
                'asistencia' => $fecha,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->asistencias[$id] = !$existe;
    }

    public function render()
    {
        return view('livewire.tutor-asistencia-card', [
            'tutorados' => $this->tutorados,
            'asistencias' => $this->asistencias
        ]);
    }
}



