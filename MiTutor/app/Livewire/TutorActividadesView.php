<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Tutorado;
use App\Models\Foda;
use App\Models\Vida;
use App\Models\Lectura;
use App\Models\ExamenVocacional;

class TutorActividadesView extends Component
{
    //Ahi le adaptas, asi le puse nomas para calar

    // CAMBIA A LOS FILTROS CORRECTOS
    public $filterByCareer = '';
    public $filterByStatus = '';

    public $grupoId;
    public $actividades;
    public $actividad;


    public function evaluateLevel($cant, $total)
    {
        if ($cant === 0) {
            return 'SIN COMENZAR';
        } elseif ($cant < $total) {
            return 'EN PROCESO';
        } else {
            return 'TERMINADO';
        }
    }
    public function verDetalles($numeroActividad)
    {
        $this->redirectRoute('tutor.actividades.banco.mostrar', ['numeroActividad' => $numeroActividad,'grupoId' => $this->grupoId]);
    }
    public function mount($grupoId)
    {
        $this->grupoId = $grupoId;
        $tutorados = Tutorado::where('idGrupo', $this->grupoId)->get();
        $cantFodas = 0;
        $cantLineas = 0;
        $cantLecturas = 0;
        $cantExamenes = 0;
        foreach ($tutorados as $tutorado) {
            // Recorrer la tabla Foda para el tutorado actual
            $fodasTutorados = Foda::where('idCuentaTutorado', $tutorado->idCuentaTutorado)->get();
            if ($fodasTutorados->isNotEmpty()) {
                // Se encontraron registros FODA para este tutorado
                $cantFodas++;
            }
            // Recorrer la tabla LineaDeVida para el tutorado actual
            $lineasDeVidaTutorados = Vida::where('idCuentaTutorado', $tutorado->idCuentaTutorado)->get();
            if ($fodasTutorados->isNotEmpty()) {
                // Se encontraron registros FODA para este tutorado
                $cantLineas++;
            }

            // Recorrer la tabla ComprensionLectora para el tutorado actual
            $lecturasTutorados = Lectura::where('idCuentaTutorado', $tutorado->idCuentaTutorado)->get();
            if ($lecturasTutorados->isNotEmpty()) {
                // Se encontraron registros FODA para este tutorado
                $cantLecturas++;
            }

            // Recorrer la tabla ExamenVocacional para el tutorado actual
            $examenesTutorados = ExamenVocacional::where('idCuentaTutorado', $tutorado->idCuentaTutorado)->get();
            if ($examenesTutorados->isNotEmpty()) {
                // Se encontraron registros FODA para este tutorado
                $cantExamenes++;
            }
        }

        $this->actividades = collect([
            (object)[
                'numero' => '01',
                'nombre' => 'Comprensión Lectora',
                'tipo' => 'Capacidades',
                'terminado' => $this->evaluateLevel($cantLecturas, $tutorados->count()),
            ],
            (object)[
                'numero' => '02',
                'nombre' => 'Análisis FODA',
                'tipo' => 'Autoconocimiento',
                'terminado' => $this->evaluateLevel($cantFodas, $tutorados->count()),
            ],
            (object)[
                'numero' => '03',
                'nombre' => 'Línea de Vida',
                'tipo' => 'Autoconocimiento',
                'terminado' => $this->evaluateLevel($cantLineas, $tutorados->count()),
            ],
            (object)[
                'numero' => '04',
                'nombre' => 'Examen Vocacional',
                'tipo' => 'Autoconocimiento',
                'terminado' => $this->evaluateLevel($cantExamenes, $tutorados->count()),
            ],
        ]);
    }

    // Método para resetear la paginación cuando cambia la búsqueda o los filtros
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterByCareer()
    {
        $this->resetPage();
    }

    public function updatingFilterByStatus()
    {
        $this->resetPage();
    }
    public function render()
    {
        return view('livewire.tutor-actividades-view');
    }
}
