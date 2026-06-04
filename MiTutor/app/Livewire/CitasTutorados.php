<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Cita;
use Carbon\Carbon;
use App\Models\Tutorado;

class CitasTutorados extends Component
{
    public $mesActual;
    public $añoActual;
    public $lineaVidaExistente = null;
    public $idCuentaTutorado;


    public function mount()
    {
        $this->mesActual = now()->format('F');
        $this->añoActual = now()->year;
        $curp = session('curp');

        if (!$curp) abort(403, 'No tienes una sesión activa.');

        $tutorado = Tutorado::where('curp', $curp)->first();

        if (!$tutorado) abort(403, 'No tienes un tutorado asociado.');

        // Corregido: usar el campo correcto de la base de datos
        $this->idCuentaTutorado = $tutorado->idCuentaTutorado ?? $tutorado->idCuenta;
    }

    public function mesAnterior()
    {
        $fecha = Carbon::create($this->añoActual, $this->getNumeroMes($this->mesActual), 1)->subMonth();
        $this->mesActual = $fecha->format('F');
        $this->añoActual = $fecha->year;
    }

    public function mesSiguiente()
    {
        $fecha = Carbon::create($this->añoActual, $this->getNumeroMes($this->mesActual), 1)->addMonth();
        $this->mesActual = $fecha->format('F');
        $this->añoActual = $fecha->year;
    }

    private function getNumeroMes($nombreMes)
    {
        return Carbon::parse("1 {$nombreMes} 2000")->month;
    }

  public function getCitas()
{
    $numeroMes = $this->getNumeroMes($this->mesActual);

    return Cita::with('tutorado')
        ->where('idCuentaTutorado', $this->idCuentaTutorado) // 👈 Aquí filtramos por el tutorado logueado
        ->whereYear('fechaCita', $this->añoActual)
        ->whereMonth('fechaCita', $numeroMes)
        ->orderBy('fechaCita', 'asc')
        ->get();
}



    public function render()
    {
        $citas = $this->getCitas();
        
        return view('livewire.citas-tutorados', [
            'citas' => $citas
        ]);
    }
}