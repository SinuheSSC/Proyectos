<?php

namespace App\Livewire;

use App\Models\Asistencia;
use App\Models\ExamenVocacional;
use App\Models\Foda;
use App\Models\Lectura;
use App\Models\Tutorado;
use App\Models\Vida;
use Livewire\Component;
use Carbon\Carbon;
use Livewire\WithPagination; // Importa el trait WithPagination
use Illuminate\Pagination\LengthAwarePaginator; // Necesario para paginar colecciones

class TutorTutoradosView extends Component
{
    use WithPagination; // Usa el trait WithPagination en tu componente

    public $search = ''; // Propiedad para la búsqueda general (nombre o carrera)
    public $filterByCareer = ''; // No se usará directamente por ahora, pero se mantiene si lo necesitas después
    public $filterByStatus = ''; // No se usará directamente por ahora, pero se mantiene si lo necesitas después
    public $grupo;
    public $fecha;

    // Puedes definir el tema de paginación si no usas Tailwind CSS por defecto
    // protected $paginationTheme = 'bootstrap';

    public function more($idCuentaTutorado)
    {
        $this->redirectRoute('tutor.tutorados.tutorado.tutorado', ['idCuentaTutorado' => $idCuentaTutorado]);
    }

    public function mount($grupo = null)
    {
        $this->grupo = $grupo;
        $this->fecha = now()->startOfWeek()->toDateString();
    }

    // Resetea la paginación a la primera página cuando cambia el filtro de búsqueda
    public function updatingSearch()
    {
        $this->resetPage();
    }

    // Los siguientes métodos `updating` ya los tenías, los mantengo por consistencia
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
        $tutor = \App\Models\Tutor::where('curp', session('curp'))->first();
        $grupos = $tutor
            ? \App\Models\Grupo::where('idCuentaTutor', $tutor->idCuentaTutor)->get()
            : collect();

        $fechaInicio = Carbon::parse($this->fecha)->startOfWeek();
        $fechaFin = $fechaInicio->copy()->endOfWeek();

        // 1. Obtener TODOS los tutorados para el grupo, aplicando los filtros de búsqueda
        $query = Tutorado::with('grupo')
            ->where('idGrupo', $this->grupo);

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('nombres', 'like', "%{$this->search}%")
                    ->orWhere('apellidoPaterno', 'like', "%{$this->search}%")
                    ->orWhere('apellidoMaterno', 'like', "%{$this->search}%")
                    ->orWhere('carrera', 'like', "%{$this->search}%"); // Búsqueda por carrera
            });
        }

        $allTutoradosInGroup = $query->get(); // Esto carga todos los resultados a la memoria

        // 2. Mapear la colección para añadir los cálculos de porcentajes y actividades
        $tutoradosConCalculos = $allTutoradosInGroup->map(function ($tutorado) use ($fechaInicio, $fechaFin) {
            // Los cálculos de asistencia y actividades se mantienen como los tenías
            $asistenciasSemana = Asistencia::where('idCuentaTutorado', $tutorado->idCuentaTutorado)
                ->whereBetween('asistencia', [$fechaInicio, $fechaFin])
                ->count();
            $asistenciasTotales = Asistencia::where('idCuentaTutorado', $tutorado->idCuentaTutorado)->count();
            $porcentajeAsistencia = ($asistenciasTotales > 0 && 24 > 0) ? round(($asistenciasTotales / 24) * 100, 2) : 0; // Evita división por cero

            $actividadesTotales = 4;
            $porcentajeActividades = 0;
            $actividadesHechas = 0;
            if (Foda::where('idCuentaTutorado', $tutorado->idCuentaTutorado)->exists()) {
                $porcentajeActividades += 25;
                $actividadesHechas++;
            }
            if (ExamenVocacional::where('idCuentaTutorado', $tutorado->idCuentaTutorado)->exists()) {
                $porcentajeActividades += 25;
                $actividadesHechas++;
            }
            if (Vida::where('idCuentaTutorado', $tutorado->idCuentaTutorado)->exists()) {
                $porcentajeActividades += 25;
                $actividadesHechas++;
            }
            if (Lectura::where('idCuentaTutorado', $tutorado->idCuentaTutorado)->exists()) {
                $porcentajeActividades += 25;
                $actividadesHechas++;
            }

            return (object) [
                'idCuentaTutorado' => $tutorado->idCuentaTutorado,
                'nombres' => $tutorado->nombres,
                'apellidoPaterno' => $tutorado->apellidoPaterno,
                'apellidoMaterno' => $tutorado->apellidoMaterno,
                'carrera' => $tutorado->carrera, // Ahora 'carrera' se incluye en el objeto mapeado
                'necesidadEspecial' => $tutorado->necesidadEspecial,
                'asistenciasSemana' => $asistenciasSemana,
                'asistenciasTotales' => $asistenciasTotales,
                'porcentajeAsistencia' => $porcentajeAsistencia,
                'porcentajeActividades' => $porcentajeActividades,
                'actividadesHechas' => $actividadesHechas,
            ];
        });

        // 3. Paginación manual de la colección
        // Livewire automáticamente toma el 'page' query param
        $perPage = 10;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentItems = $tutoradosConCalculos->slice(($currentPage - 1) * $perPage, $perPage)->values();

        $paginatedTutorados = new LengthAwarePaginator(
            $currentItems,
            $tutoradosConCalculos->count(),
            $perPage,
            $currentPage,
            ['path' => LengthAwarePaginator::resolveCurrentPath()]
        );

        return view('livewire.tutor-tutorados-view', [
            'tutorados' => $paginatedTutorados, // Pasa la colección paginada a la vista
            'grupos' => $grupos,
        ]);
    }
}
