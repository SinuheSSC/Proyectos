<?php

namespace App\Livewire;

use App\Models\ExamenVocacional;
use App\Models\Foda;
use App\Models\Lectura;
use Livewire\Component;
use Carbon\Carbon;
use App\Models\Tutorado;
use App\Models\Tutor;
use App\Models\Vida;

class TutorTutoradoIndex extends Component
{
    public $alumno;
    public $idCuentaTutorado;

    public $foda;
    public $lineaVida;
    public $examenV;
    public $comprensionL;

    public function mount($idCuentaTutorado)
    {
        $this->idCuentaTutorado = $idCuentaTutorado;

        // Carga también el grupo y el tutor del grupo
        $tutorado = Tutorado::with('grupo.tutor')->where('idCuentaTutorado', $this->idCuentaTutorado)->firstOrFail();

        $fotoPath = public_path('images/tutor/' . $tutorado->fotoPerfil);
        $fotoPerfil = file_exists($fotoPath) ? $tutorado->fotoPerfil : 'default.webp';

        $nombreTutor = optional($tutorado->grupo->tutor)->nombres
            ? $tutorado->grupo->tutor->nombres . ' ' .
            $tutorado->grupo->tutor->apellidoPaterno . ' ' .
            $tutorado->grupo->tutor->apellidoMaterno
            : 'Sin tutor asignado';

        $foda = Foda::where('idCuentaTutorado', $tutorado->idCuentaTutorado)->first();
        $lineaVida = Vida::where('idCuentaTutorado', $tutorado->idCuentaTutorado)->first();
        $examenV = ExamenVocacional::where('idCuentaTutorado', $tutorado->idCuentaTutorado)->first();
        $comprensionL = Lectura::where('idCuentaTutorado', $tutorado->idCuentaTutorado)->first();

        $this->alumno = (object) [
            'id' => $tutorado->idCuentaTutorado,
            'nombre' => $tutorado->nombres,
            'apellido_paterno' => $tutorado->apellidoPaterno,
            'apellido_materno' => $tutorado->apellidoMaterno,
            'fecha_nacimiento' => \Carbon\Carbon::parse($tutorado->fechaNacimiento),
            'genero' => $tutorado->genero,
            'curp' => $tutorado->curp,
            'idGrupo' => $tutorado->idGrupo,
            'tutor_asignado' => $nombreTutor,
            'foto_perfil' => asset('images/tutor/' . $fotoPerfil),
            'discapacidad' => $tutorado->discapacidadFisica ?? 'NO',
            'enfermedad' => $tutorado->enfemerdad ?? 'NO',
            'condicion_especial' => $tutorado->necesidadEspecial ? 'SI' : 'NO',
            'actividades' => collect([
                (object)[
                    'numero' => '01',
                    'nombre' => 'Comprensión Lectora',
                    'estado' => $comprensionL ? 'SI' : 'NO', // Si $comprensionL no es nulo, es 'SI', de lo contrario 'NO'
                    'nivelComprensionLectora' => $comprensionL ? $comprensionL->nivelComprensionLectora : null,
                ],
                (object)[
                    'numero' => '02',
                    'nombre' => 'FODA',
                    'estado' => $foda ? 'SI' : 'NO' // Si $foda no es nulo, es 'SI', de lo contrario 'NO'
                ],
                (object)[
                    'numero' => '03',
                    'nombre' => 'Línea de Vida',
                    'estado' => $lineaVida ? 'SI' : 'NO' // Si $lineaVida no es nulo, es 'SI', de lo contrario 'NO'
                ],
                (object)[
                    'numero' => '04',
                    'nombre' => 'Examen Vocacional',
                    'estado' => $examenV ? 'SI' : 'NO' // Si $examenV no es nulo, es 'SI', de lo contrario 'NO'
                ],
            ]),
        ];
    }
    public function consultarNecesidad($idCuentaTutorado)
    {
        $this->redirectRoute('tutor.tutorados.tutorado.necesidad', $idCuentaTutorado);
    }
    public function consultarActividad($numeroActividad)
    {
        switch ($numeroActividad) {
            case 1:
                if (!(Lectura::where('idCuentaTutorado', $this->alumno->id)->exists())) {
                    session()->flash('error', 'No realizado');
                } else
                    $this->redirectRoute('tutor.actividades.banco.comprension', ['idTutorado' => $this->alumno->id, 'grupoId' => $this->alumno->idGrupo, 'nAct' => $numeroActividad]);
                break;
            case 2:
                // Lógica para FODA
                if (!(Foda::where('idCuentaTutorado', $this->alumno->id)->exists())) {
                    session()->flash('error', 'No realizado');
                } else
                    $this->redirectRoute('tutor.actividades.banco.foda', ['idTutorado' => $this->alumno->id, 'grupoId' => $this->alumno->idGrupo, 'nAct' => $numeroActividad]);
                break;
            case 3:
                // Lógica para Línea de Vida
                if (!(Vida::where('idCuentaTutorado', $this->alumno->id)->exists())) {
                    session()->flash('error', 'No realizado');
                } else
                    $this->redirectRoute('tutor.actividades.banco.vida', ['idTutorado' => $this->alumno->id, 'grupoId' => $this->alumno->idGrupo, 'nAct' => $numeroActividad]);
                break;
            case 4:
                // Lógica para Examen Vocacional
                if (!(ExamenVocacional::where('idCuentaTutorado', $this->alumno->id)->exists())) {
                    session()->flash('error', 'No realizado');
                } else
                    $this->redirectRoute('tutor.actividades.banco.examen', ['idTutorado' => $this->alumno->id, 'grupoId' => $this->alumno->idGrupo, 'nAct' => $numeroActividad]);
                break;
            default:
                // Opción no válida
                $this->redirectRoute('login');
                break;
        }
    }

    public function render()
    {
        return view('livewire.tutor-tutorado-index');
    }
}
