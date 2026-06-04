<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Vida;
use App\Models\Tutorado;
use Carbon\Carbon;

class LineaVida extends Component
{
    public $fechas = [];
    public $hitos = [];
    public $lineaVidaExistente = null;
    public $idCuentaTutorado = null;

    public function mount()
    {
        // Validar CURP en sesión
        $curp = session('curp');

        if (!$curp) {
            abort(403, 'No tienes una sesión activa.');
        }

        $tutorado = Tutorado::where('curp', $curp)->first();

        if (!$tutorado) {
            abort(403, 'No tienes un tutorado asociado.');
        }

        // Obtener el ID correcto del tutorado
        $this->idCuentaTutorado = $tutorado->idCuentaTutorado ?? $tutorado->idCuenta;

        if (!$this->idCuentaTutorado) {
            abort(403, 'No se pudo obtener el ID de la cuenta del tutorado.');
        }

        // Inicializar arrays para 5 hitos
        $this->fechas = array_fill(0, 5, '');
        $this->hitos = array_fill(0, 5, '');

        // Cargar datos existentes si los hay
        $this->cargarDatos();
    }

    public function cargarDatos()
    {
        // Buscar si ya existe una línea de vida para el usuario actual
        $this->lineaVidaExistente = Vida::where('idCuentaTutorado', $this->idCuentaTutorado)->first();

        if ($this->lineaVidaExistente) {
            // Cargar los datos existentes - Función auxiliar para formatear fechas
            $this->fechas[0] = $this->formatearFecha($this->lineaVidaExistente->fechaHitoUno);
            $this->hitos[0] = $this->lineaVidaExistente->hitoUno ?? '';

            $this->fechas[1] = $this->formatearFecha($this->lineaVidaExistente->fechaHitoDos);
            $this->hitos[1] = $this->lineaVidaExistente->hitoDos ?? '';

            $this->fechas[2] = $this->formatearFecha($this->lineaVidaExistente->fechaHitoTres);
            $this->hitos[2] = $this->lineaVidaExistente->hitoTres ?? '';

            $this->fechas[3] = $this->formatearFecha($this->lineaVidaExistente->fechaHitoCuatro);
            $this->hitos[3] = $this->lineaVidaExistente->hitoCuatro ?? '';

            $this->fechas[4] = $this->formatearFecha($this->lineaVidaExistente->fechaHitoCinco);
            $this->hitos[4] = $this->lineaVidaExistente->hitoCinco ?? '';
        }
    }

    /**
     * Función auxiliar para formatear fechas de manera segura
     */
    private function formatearFecha($fecha)
    {
        if (!$fecha) {
            return '';
        }

        // Si ya es una instancia de Carbon/DateTime, usar format()
        if ($fecha instanceof \Carbon\Carbon || $fecha instanceof \DateTime) {
            return $fecha->format('Y-m-d');
        }

        // Si es un string, intentar parsearlo
        if (is_string($fecha)) {
            try {
                return Carbon::parse($fecha)->format('Y-m-d');
            } catch (\Exception $e) {
                // Si no se puede parsear, devolver el string tal como está
                return $fecha;
            }
        }

        return '';
    }

    public function guardar()
    {
        // Validar que tengamos el ID del tutorado
        if (!$this->idCuentaTutorado) {
            session()->flash('error', 'Error: No se pudo identificar al usuario.');
            return;
        }

        // Validar que al menos un hito tenga fecha y descripción
        $tieneAlMenosUno = false;
        for ($i = 0; $i < 5; $i++) {
            if (!empty($this->fechas[$i]) && !empty($this->hitos[$i])) {
                $tieneAlMenosUno = true;
                break;
            }
        }

        if (!$tieneAlMenosUno) {
            session()->flash('error', 'Debe completar al menos un hito con fecha y descripción.');
            return;
        }

        try {
            $datos = [
                'hitoUno' => $this->hitos[0] ?? null,
                'fechaHitoUno' => !empty($this->fechas[0]) ? $this->fechas[0] : null,
                'hitoDos' => $this->hitos[1] ?? null,
                'fechaHitoDos' => !empty($this->fechas[1]) ? $this->fechas[1] : null,
                'hitoTres' => $this->hitos[2] ?? null,
                'fechaHitoTres' => !empty($this->fechas[2]) ? $this->fechas[2] : null,
                'hitoCuatro' => $this->hitos[3] ?? null,
                'fechaHitoCuatro' => !empty($this->fechas[3]) ? $this->fechas[3] : null,
                'hitoCinco' => $this->hitos[4] ?? null,
                'fechaHitoCinco' => !empty($this->fechas[4]) ? $this->fechas[4] : null,
                'idCuentaTutorado' => $this->idCuentaTutorado,
            ];

            if ($this->lineaVidaExistente) {
                // Actualizar registro existente
                $this->lineaVidaExistente->update($datos);
                session()->flash('success', 'Línea de vida actualizada correctamente.');
            } else {
                // Crear nuevo registro
                Vida::create($datos);
                session()->flash('success', 'Línea de vida guardada correctamente.');
            }

            // Recargar datos para mostrar los cambios
            $this->cargarDatos();

        } catch (\Exception $e) {
            session()->flash('error', 'Error al guardar la línea de vida: ' . $e->getMessage());
        }
    }

    public $reviewing = false;
    public function render()
    {
        return view('livewire.linea-vida');
    }
}