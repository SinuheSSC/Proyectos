<?php

namespace App\Livewire;

use App\Models\Grupo;
use App\Models\Tutor;
use App\Models\Tutorado;
use Livewire\Component;
use Livewire\WithPagination;

class ListaTutoresTutorados extends Component
{
    use WithPagination;
    public $tutores = false;
    public $modalStatus = 'hidden';
    public $modalStatusGrupo = 'hidden';
    public $modalStatusAsignacion = 'hidden';
    public $usuario;
    public $tutores_all;
    public $grupos_all;
    public $letra;
    public $tutorNuevoGrupo;
    public $tutoradoAsignado;
    public $asignacion;
    public $search = "";
    public $rowsPerPage = 10;

    public function setTutores($data)
    {
        $this->tutores = $data;
    }

    public function nuevoGrupo(){
        //($this->letra, $this->tutorNuevoGrupo);
        Grupo::create([
            'idCuentaTutor' => $this->tutorNuevoGrupo,
            'letra' => $this->letra
        ]);
        $this->closeModal();

    }

    public function showModal($curp){
        $this->modalStatus = 'block';

        if(!$this->tutores){
            $this->usuario = Tutor::where('curp' , '=', $curp)->first();
        } else {
            $this->usuario = Tutorado::with('grupo')->where('curp' , '=', $curp)->first();
        }

    }

    public function showModalGrupo(){
        $this->modalStatusGrupo = 'block';
    }

    public function showModalAginacion($id){
        $this->modalStatusAsignacion = 'block';
        $this->tutoradoAsignado = $id;
    }

    public function asignacionGrupo(){
        $tutorado = Tutorado::find($this->tutoradoAsignado);
        $tutorado->update([
            'idGrupo' => $this->asignacion
        ]);
        $this->closeModal();
        //dd($tutorado);
    }

    public function closeModal(){
        $this->modalStatus = 'hidden';
        $this->modalStatusGrupo = 'hidden';
        $this->modalStatusAsignacion = 'hidden';
        $this->usuario = null;
    }

    public function editarPerfil($curp)  {
        return redirect()->route('admin-editar-perfil',['curp' => $curp]);
    }

    public function mount()
    {
        $this->tutores_all = Tutor::all();
        $this->grupos_all = Grupo::all();
    }

    public function render()
    {

        if($this->search === ""){
            if(!$this->tutores){
                $data = Tutor::all();
            } else {
                $data = Tutorado::all();
            }
        } else {
            if(!$this->tutores){
                $data = Tutor::where('nombres','ilike','%'. $this->search .'%')->orWhere('apellidoPaterno','ilike','%'. $this->search .'%')->orWhere('apellidoMaterno','ilike','%'. $this->search .'%')->get();
            } else {
                $data = Tutorado::where('nombres','ilike','%'. $this->search .'%')->get();
            }
        }

        return view('livewire.lista-tutores-tutorados',compact('data'));
    }
}
