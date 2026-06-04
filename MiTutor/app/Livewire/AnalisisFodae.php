<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Foda;
use App\Models\Tutorado;
use Illuminate\Support\Facades\Auth;

class AnalisisFodae extends Component
{
    public $fortaleza = '';
    public $debilidad = '';
    public $oportunidad = '';
    public $amenazas = '';
    public $idCuentaTutorado;
    public $fodaExistente = null;

    public function mount()
    {
        // Obtener el ID del tutorado logueado (ajusta según tu sistema de autenticación)
       $curp = session('curp');

        if (!$curp) abort(403, 'No tienes una sesión activa.');

        $tutorado = Tutorado::where('curp', $curp)->first();

        if (!$tutorado) abort(403, 'No tienes un tutorado asociado.');

        // Corregido: usar el campo correcto de la base de datos
        $this->idCuentaTutorado = $tutorado->idCuentaTutorado ?? $tutorado->idCuenta;
        
        // Verificar si ya existe un FODA para este tutorado
        $this->fodaExistente = Foda::where('idCuentaTutorado', $this->idCuentaTutorado)->first();
        
        // Si existe, cargar los datos
        if ($this->fodaExistente) {
            $this->fortaleza = $this->fodaExistente->fortaleza ?? '';
            $this->debilidad = $this->fodaExistente->debilidad ?? '';
            $this->oportunidad = $this->fodaExistente->oportunidad ?? '';
            $this->amenazas = $this->fodaExistente->amenazas ?? '';
        }
    }

    public function guardarFoda()
    {
        // Validar los campos
        $this->validate([
            'fortaleza' => 'required|string|max:255',
            'debilidad' => 'required|string|max:255',
            'oportunidad' => 'required|string|max:255',
            'amenazas' => 'required|string|max:255',
        ], [
            'fortaleza.required' => 'El campo fortaleza es obligatorio.',
            'debilidad.required' => 'El campo debilidad es obligatorio.',
            'oportunidad.required' => 'El campo oportunidad es obligatorio.',
            'amenazas.required' => 'El campo amenazas es obligatorio.',
        ]);

        try {
            if ($this->fodaExistente) {
                // Actualizar registro existente
                $this->fodaExistente->update([
                    'fortaleza' => $this->fortaleza,
                    'debilidad' => $this->debilidad,
                    'oportunidad' => $this->oportunidad,
                    'amenazas' => $this->amenazas,
                ]);
                
                session()->flash('info', 'Análisis FODA actualizado correctamente.');
            } else {
                // Crear nuevo registro
                Foda::create([
                    'fortaleza' => $this->fortaleza,
                    'debilidad' => $this->debilidad,
                    'oportunidad' => $this->oportunidad,
                    'amenazas' => $this->amenazas,
                    'idCuentaTutorado' => $this->idCuentaTutorado,
                ]);
                
                session()->flash('info', 'Análisis FODA guardado correctamente.');
            }

        } catch (\Exception $e) {
            session()->flash('error', 'Error al guardar el análisis FODA: ' . $e->getMessage());
        }
    }

    public function limpiarCampos()
    {
        $this->fortaleza = '';
        $this->debilidad = '';
        $this->oportunidad = '';
        $this->amenazas = '';
        
        session()->flash('info', 'Campos limpiados correctamente.');
    }

    public function goBack()
    {
        // Redirigir a la página anterior o a una ruta específica
        return redirect()->back();
        // O si quieres redirigir a una ruta específica:
        // return redirect()->route('dashboard');
    }

    public function render()
    {
        return view('livewire.analisis-fodae');
    }
}