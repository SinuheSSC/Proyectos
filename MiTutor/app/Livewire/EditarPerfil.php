<?php

namespace App\Livewire;

use App\Models\Grupo;
use App\Models\Tutor;
use App\Models\Tutorado;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;
use Livewire\Component;

use function PHPUnit\Framework\isArray;
use function PHPUnit\Framework\isNull;

class EditarPerfil extends Component
{

    public $user;
    public $data;
    public $grupos;
    public $enfermedad = 'Si';
    public $discapcidad = 'Si';
    public $psicologica = 'Si';
    public $necesidadEsp = 'Si';


    public function mount($curp)  {

        try {
            $tipoUser = User::where('curp',  Crypt::decrypt($curp))->first('rol');

            switch($tipoUser->rol){
                case 'Profesor':
                    $this->user = Tutor::where('curp' , Crypt::decrypt($curp))->with('grupo')->first();
                    //dd($this->user->grupo);
                    break;
                case 'Admin':
                    $this->user = Tutor::where('curp' , Crypt::decrypt($curp))->with('grupo')->first();
                    //dd($this->user->grupo);
                    break;
                case 'Estudiante':
                    $this->user = Tutorado::where('curp' , Crypt::decrypt($curp))->with('grupo')->first();
                    if($this->user->enfemerdad === null){
                        $this->enfermedad = 'No';
                    }
                    if($this->user->discapacidadFisica === null){
                        $this->discapcidad = 'No';
                    }
                    if($this->user->situacionPsicologica === null){
                        $this->psicologica = 'No';
                    }
                    if($this->user->necesidadEspecial === null){
                        $this->necesidadEsp = 'No';
                    }
                    break;

                default:
                    $this->user = null;
            }

            $this->data = $this->user->toArray();
            $this->grupos = Grupo::all();



        } catch (\Throwable $th) {
            redirect()->route('admin-editar-perfil');
        }

    }


    public function guardar(){
        //dd($this->data);

        $this->user->update($this->data);
        dd('guardado');
    }
    public function render()
    {
        if($this->enfermedad === 'No'){
            $this->data['enfemerdad'] = null;
        }
        if($this->discapcidad === 'No'){
            $this->data['discapacidadFisica'] = null;
        }
        if($this->psicologica === 'No'){
            $this->data['situacionPsicologica'] = null;
        }
        if($this->necesidadEsp === 'No'){
            $this->data['necesidadEspecial'] = null;
        }

        return view('livewire.editar-perfil');
    }
}
