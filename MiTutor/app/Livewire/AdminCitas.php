<?php

namespace App\Livewire;

use App\Models\Cita;
use App\Models\Grupo;
use App\Models\Tutor;
use App\Models\Tutorado;
use Carbon\Carbon;
use Livewire\Component;

class AdminCitas extends Component
{
    public $currentDate;
    public $weeks = [];
    public $showModal = false;
    public $modalNecesidadEspecial = 'No';
    public $dataModal = [];
    public $dataModalNew = [];
    public $addCita = false;


    public function mount()
    {
        $this->currentDate = Carbon::now();
        $this->buildCalendar();
    }

    public function buildCalendar()
    {
        $startOfMonth = $this->currentDate->copy()->startOfMonth();
        $endOfMonth = $this->currentDate->copy()->endOfMonth();

        // Ajustamos para comenzar el calendario en el domingo anterior si el mes no comienza en domingo
        $startOfCalendar = $startOfMonth->copy()->startOfWeek();
        $endOfCalendar = $endOfMonth->copy()->endOfWeek();



        $this->weeks = [];
        $currentDay = $startOfCalendar->copy();

        while ($currentDay <= $endOfCalendar) {
            $week = [];
            for ($i = 0; $i < 7; $i++) {
                $week[] = [
                    'date' => $currentDay->copy(),
                    'isCurrentMonth' => $currentDay->month === $this->currentDate->month,
                    'isToday' => $currentDay->isToday(),
                    'events' => $this->getEventsForDate($currentDay) // Aquí cargarías las citas reales
                ];
                $currentDay->addDay();
            }
            $this->weeks[] = $week;
        }

        //dd($this->weeks);
    }

    public function verMasCita($data){

       // dd($data);

        $this->dataModal['idCita'] = $data['idCita'];
        $this->dataModal['fechaCita'] = Carbon::parse($data['fechaCita'])->toDateString();
        $this->dataModal['hora'] = Carbon::parse($data['fechaCita'])->format('h:i');
        $this->dataModal['canalizacion'] = $data['canalizacion'];
        $this->dataModal['nombre'] = $data['tutorado']['nombres'] ." ". $data['tutorado']['apellidoPaterno']." ".$data['tutorado']['apellidoMaterno'];

        $grupo = Grupo::where('idGrupo',$data['tutorado']['idGrupo'])->first(['letra','idCuentaTutor']);
        $this->dataModal['grupo'] = $grupo->letra;

        $tutor = Tutor::where('idCuentaTutor' , $grupo->idCuentaTutor)->first(['nombres', 'apellidoPaterno' , 'apellidoMaterno']);
        $this->dataModal['tutor'] = $tutor->nombres . " " . $tutor->apellidoPaterno . " " . $tutor->apellidoMaterno;

        $this->dataModal['discapacidadFisica'] = $data['tutorado']['discapacidadFisica'];
        $this->dataModal['enfemerdad'] = $data['tutorado']['enfemerdad'];
        $this->dataModal['situacionPsicologica'] = $data['tutorado']['situacionPsicologica'];
        $this->dataModal['resultados'] = $data['resultados'];
        $this->dataModal['necesidadEspecial'] = $data['tutorado']['necesidadEspecial'];

        if($this->dataModal['necesidadEspecial'] !== null){
            $this->modalNecesidadEspecial = 'Si';
        }

        $this->dataModal['idCuentaTutorado'] = $data['tutorado']['idCuentaTutorado'];

        $this->dataModal['citaFutura'] = false;
        if($this->dataModal['fechaCita'] < $this->currentDate){
            $this->dataModal['citaFutura'] = true;
        }



        $this->showModal = true;
    }

    public function closeModal(){
        $this->showModal = false;
        $this->addCita= false;
        $this->dataModal = [];
        $this->dataModalNew = [];
        $this->buildCalendar();

    }
    public function previousMonth()
    {
        $this->currentDate->subMonth();
        $this->buildCalendar();
    }
    protected function getEventsForDate($date)
    {
        $citas = Cita::whereDate('fechaCita' , $date)->with('tutorado')->get();
        return $citas;
    }

    public function guardarCambios()
    {
        //dd($this->dataModal);

        $cita = Cita::where('idCita' , $this->dataModal['idCita'])->first();
        $cita->canalizacion = $this->dataModal['canalizacion'];
        $cita->fechaCita = Carbon::createFromFormat('Y-m-d H:i', $this->dataModal['fechaCita']." ".$this->dataModal['hora'])->toDateTimeString();
        $cita->resultados = $this->dataModal['resultados'];

        if($cita->fechaCita <= $this->currentDate && $cita->resultados === null){
            session()->flash('error', 'Esta fecha ya esta pasada');
            $this->dataModal['citaFutura'] = false;
            return;
        }

        if($this->modalNecesidadEspecial === 'Si'){
           //dd( Tutorado::where('idCuentaTutorado',$this->dataModal['idCuentaTutorado'])->first());
            Tutorado::where('idCuentaTutorado',$this->dataModal['idCuentaTutorado'])->first()->update(['necesidadEspecial' => $this->dataModal['necesidadEspecial']]);
        }
        if($this->modalNecesidadEspecial === 'No'){
           //dd( Tutorado::where('idCuentaTutorado',$this->dataModal['idCuentaTutorado'])->first());
            Tutorado::where('idCuentaTutorado',$this->dataModal['idCuentaTutorado'])->first()->update(['necesidadEspecial' => null]);
        }

        $cita->save();
        $this->buildCalendar();
        $this->closeModal();
        session()->flash('succesStore', 'Datos guardados');
    }

    public function borrarCita(){
        Cita::find($this->dataModal['idCita'])->delete();
        $this->buildCalendar();
        $this->closeModal();
        session()->flash('succesStore', 'Cita borrada');
    }

    public function showAgregarCita(){
        $this->dataModalNew['fechaNew'] = null;
        $this->dataModalNew['horaNew'] = null;
        $this->dataModalNew['canalizacionNew'] = 'Enfermeria';
        $this->dataModalNew['idTutoradoNew'] = null;
        $this->dataModalNew['grupoNew'] = Grupo::all();

        $this->dataModalNew['idGrupoNew'] =  $this->dataModalNew['grupoNew']->first()->idGrupo;

        $this->addCita = true;
    }
    public function agregarCita(){
        if( $this->dataModalNew['fechaNew'] === null || $this->dataModalNew['horaNew'] === null || $this->dataModalNew['idTutoradoNew'] === null || $this->dataModalNew['idTutoradoNew'] === ""){
            session()->flash('error', 'Datos Faltantes');
            return;
        }

        if($this->dataModalNew['fechaNew'] <= $this->currentDate){
            session()->flash('error', 'Fecha Pasada');
            return;
        }
        $fechaCompletaNew = Carbon::createFromFormat('Y-m-d H:i', $this->dataModalNew['fechaNew']." ".$this->dataModalNew['horaNew'])->toDateTimeString();

        Cita::create(['fechaCita' => $fechaCompletaNew,
                                  'idCuentaTutorado' => $this->dataModalNew['idTutoradoNew'],
                                  'canalizacion' =>  $this->dataModalNew['canalizacionNew']]);


        $this->buildCalendar();
        $this->closeModal();
        session()->flash('succesStore', 'Cita Guardada');
    }

    public function nextMonth()
    {
        $this->currentDate->addMonth();
        $this->buildCalendar();
    }
    public function render()
    {
        if($this->modalNecesidadEspecial === 'No'){
            $this->dataModal['necesidadEspecial'] = null;
        }
        if($this->addCita){
            $this->dataModalNew['tutorados'] = Tutorado::where('idGrupo', $this->dataModalNew['idGrupoNew'])->get();
        }
        return view('livewire.admin-citas');
    }
}
