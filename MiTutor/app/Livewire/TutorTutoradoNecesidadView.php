<?php

namespace App\Livewire;

use Livewire\Component;
use Carbon\Carbon;
use App\Models\Tutorado;
use App\Models\Cita;

class TutorTutoradoNecesidadView extends Component
{
    public $alumno;
    public $showModal = false; // Property to control modal visibility
    public $citas = [];
    public $idCuentaTutorado;
    public $fechaCita; 
    public $resultados;
    public $canalizacion;
    public $descripcion;


    public function openModal()
    {
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function mount($idCuentaTutorado)
    {
        $this->idCuentaTutorado = $idCuentaTutorado;

        // Cargar tutorado con relaciones
        $tutorado = \App\Models\Tutorado::with('grupo.tutor')->where('idCuentaTutorado', $idCuentaTutorado)->firstOrFail();

        $fotoPath = public_path('images/tutor/' . $tutorado->fotoPerfil);
        $fotoFinal = file_exists($fotoPath) ? $tutorado->fotoPerfil : 'default.webp';

        // Obtener nombre completo del tutor asignado
        $nombreTutor = optional($tutorado->grupo->tutor)->nombres
            ? $tutorado->grupo->tutor->nombres . ' ' .
            $tutorado->grupo->tutor->apellidoPaterno . ' ' .
            $tutorado->grupo->tutor->apellidoMaterno
            : 'Sin tutor asignado';

        $this->alumno = (object) [
            'id' => $tutorado->idCuentaTutorado,
            'nombre' => $tutorado->nombres,
            'apellido_paterno' => $tutorado->apellidoPaterno,
            'apellido_materno' => $tutorado->apellidoMaterno,
            'fecha_nacimiento' => Carbon::parse($tutorado->fechaNacimiento),
            'genero' => $tutorado->genero,
            'curp' => $tutorado->curp,
            'matricula' => $tutorado->matricula,
            'tutor_asignado' => $nombreTutor,
            'foto_perfil' => asset('images/tutor/' . $fotoFinal),
            'discapacidad' => $tutorado->discapacidadFisica ?? 'NO',
            'enfermedad' => $tutorado->enfemerdad ?? 'NO',
            'condicion_especial' => $tutorado->necesidadEspecial ?? 'NO',
        ];

        $this->citas = \App\Models\Cita::where('idCuentaTutorado', $idCuentaTutorado)->latest()->get();
    }

    public function saveAttention()
{
    
    $this->validate([
        'fechaCita' => 'required|date',
        'canalizacion' => 'required|string',
        'resultados' => 'required|string',
        'descripcion' => 'nullable|string|max:255',
    ]);

    $fechaFormateada = \Carbon\Carbon::parse($this->fechaCita)->format('Y-m-d H:i:s');

    \App\Models\Cita::create([
        'fechaCita' => $fechaFormateada,
        'idCuentaTutorado' => $this->idCuentaTutorado,
        'descripcion' => $this->descripcion,
        'canalizacion' => $this->canalizacion,
        'resultados' => $this->resultados,
    ]);

    $this->citas = Cita::where('idCuentaTutorado', $this->idCuentaTutorado)->latest()->get();
    $this->reset(['fechaCita', 'canalizacion', 'resultados', 'descripcion', 'showModal']);
}

    public function render()
    {
        return view('livewire.tutor-tutorado-necesidad-view');
    }
}
