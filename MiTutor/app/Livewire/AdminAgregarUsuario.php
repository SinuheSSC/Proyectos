<?php

namespace App\Livewire;

use App\Models\Grupo;
use App\Models\Tutor;
use App\Models\Tutorado;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class AdminAgregarUsuario extends Component
{
    public $tipoCuenta = 'Estudiante';
    public $data = [];
    public $grupos;

    //protected $rules = ;
    //protected $messages = ;

    public function guardar(){
        //dd($this->data);
        if($this->tipoCuenta === 'Estudiante'){
            $this->validate([
                        'data.curp' => 'required|max:18',
                        'data.nombres' => 'required|min:1',
                        'data.fechaNacimiento' => 'required',
                        'data.apellidoPaterno' => 'required|min:1',
                        'data.apellidoMaterno' => 'required|min:1',
                        'data.estadoCivil' => 'required|in:Soltero/a,Casado/a,Viudo/a',
                        'data.telefono' => 'required|min:1',
                        'data.email' => 'required',
                        'data.codigoPostal' => 'required|min:1',
                        'data.estado' => 'required|min:1',
                        'data.municipio' => 'required|min:1',
                        'data.calleYnumero' => 'required|min:1',
                        'data.carrera' => 'required|min:1',
                        'data.genero' => 'required|in:M,F',
                        'data.idGrupo' => 'required|min:1',
                        'data.contrasena' => 'required|min:8',
                    ],[
                    // Mensajes para reglas 'required'
                    'data.curp.required' => 'El campo CURP es requerido',
                    'data.nombres.required' => 'El campo Nombres es requerido',
                    'data.fechaNacimiento.required' => 'El campo Fecha de nacimiento es requerido',
                    'data.apellidoPaterno.required' => 'El campo Apellido paterno es requerido',
                    'data.apellidoMaterno.required' => 'El campo Apellido materno es requerido',
                    'data.estadoCivil.required' => 'El campo Estado civil es requerido',
                    'data.telefono.required' => 'El campo Teléfono es requerido',
                    'data.email.required' => 'El campo Correo electrónico es requerido',
                    'data.codigoPostal.required' => 'El campo Código postal es requerido',
                    'data.estado.required' => 'El campo Estado es requerido',
                    'data.municipio.required' => 'El campo Municipio es requerido',
                    'data.calleYnumero.required' => 'El campo Calle y número es requerido',
                    'data.carrera.required' => 'El campo Carrera es requerido',
                    'data.genero.required' => 'El campo Género es requerido',
                    'data.idGrupo.required' => 'El campo Grupo es requerido',
                    'data.contrasena.required' => 'El campo Contraseña es requerido',

                    // Mensajes para reglas 'max' y 'min'
                    'data.curp.max' => 'La CURP no debe exceder los 18 caracteres',
                    'data.contrasena.min' => 'La contraseña debe tener al menos 8 caracteres',

                    // Mensajes para reglas 'in'
                    'data.estadoCivil.in' => 'El estado civil debe ser: Soltero/a, Casado/a o Viudo/a',
                    'data.genero.in' => 'El género debe ser M (Masculino) o F (Femenino)',

                    // Mensaje genérico para campos que solo requieren 'min:1'
                    'data.*.min' => 'Este campo es obligatorio',

                    // Mensaje para email (puedes agregar más específico si lo deseas)
                    'data.email.email' => 'Debe ingresar un correo electrónico válido'
            ]);

            if(!isset($this->data['razonesCarrera'])) {
                $this->data['razonesCarrera'] = null;
            }


            User::create([
                'curp' => $this->data['curp'],
                'contrasena' => Hash::make($this->data['contrasena']),
                'rol' => $this->tipoCuenta
            ]);


            Tutorado::create([
                'curp' => $this->data['curp'],
                'nombres' => $this->data['nombres'],
                'apellidoPaterno' => $this->data['apellidoPaterno'],
                'apellidoMaterno' => $this->data['apellidoMaterno'],
                'fechaNacimiento' => $this->data['fechaNacimiento'],
                'genero' => $this->data['genero'],
                'idGrupo' => $this->data['idGrupo'],
                'carrera' => $this->data['carrera'],
                'razonesCarrera' => $this->data['razonesCarrera'],
                'telefono' => $this->data['telefono'],
                'estadoCivil' => $this->data['estadoCivil'],
                'email' => $this->data['email'],
                'municipio' => $this->data['municipio'],
                'estado' => $this->data['estado'],
                'calleYnumero' => $this->data['calleYnumero'],
                'codigoPostal' => $this->data['codigoPostal'],
            ]);
        } else if ($this->tipoCuenta === 'Admin' || $this->tipoCuenta === 'Profesor'){

            $this->validate([
                        'data.curp' => 'required|max:18',
                        'data.nombres' => 'required|min:1',
                        'data.fechaNacimiento' => 'required',
                        'data.apellidoPaterno' => 'required|min:1',
                        'data.apellidoMaterno' => 'required|min:1',
                        'data.estadoCivil' => 'required|in:Soltero/a,Casado/a,Viudo/a',
                        'data.telefono' => 'required|min:1',
                        'data.email' => 'required',
                        'data.codigoPostal' => 'required|min:1',
                        'data.estado' => 'required|min:1',
                        'data.municipio' => 'required|min:1',
                        'data.calleYnumero' => 'required|min:1',
                        'data.genero' => 'required|in:M,F',
                        'data.contrasena' => 'required|min:8',
                        'data.RFC' => 'required|max:13',
                        'data.cedulaProfesional' => 'required|min:1',
                        'data.titulo' => 'required|min:1',
                        'data.especialidad' => 'required|min:1',
                    ],[
                    // Mensajes para reglas 'required'
                    'data.curp.required' => 'El campo CURP es requerido',
                    'data.nombres.required' => 'El campo Nombres es requerido',
                    'data.fechaNacimiento.required' => 'El campo Fecha de nacimiento es requerido',
                    'data.apellidoPaterno.required' => 'El campo Apellido paterno es requerido',
                    'data.apellidoMaterno.required' => 'El campo Apellido materno es requerido',
                    'data.estadoCivil.required' => 'El campo Estado civil es requerido',
                    'data.telefono.required' => 'El campo Teléfono es requerido',
                    'data.email.required' => 'El campo Correo electrónico es requerido',
                    'data.codigoPostal.required' => 'El campo Código postal es requerido',
                    'data.estado.required' => 'El campo Estado es requerido',
                    'data.municipio.required' => 'El campo Municipio es requerido',
                    'data.calleYnumero.required' => 'El campo Calle y número es requerido',
                    'data.genero.required' => 'El campo Género es requerido',
                    'data.contrasena.required' => 'El campo Contraseña es requerido',
                    'data.RFC.required' => 'El campo RFC es requerido',
                    'data.cedulaProfesional.required' => 'El campo Cedula es requerido',
                    'data.titulo.required' => 'El campo Titulo es requerido',
                    'data.especialidad.required' => 'El campo Especialidad es requerido',

                    // Mensajes para reglas 'max' y 'min'
                    'data.curp.max' => 'La CURP no debe exceder los 18 caracteres',
                    'data.RFC.max' => 'La RFC no debe exceder los 13 caracteres',
                    'data.contrasena.min' => 'La contraseña debe tener al menos 8 caracteres',

                    // Mensajes para reglas 'in'
                    'data.estadoCivil.in' => 'El estado civil debe ser: Soltero/a, Casado/a o Viudo/a',
                    'data.genero.in' => 'El género debe ser M (Masculino) o F (Femenino)',

                    // Mensaje genérico para campos que solo requieren 'min:1'
                    'data.*.min' => 'Este campo es obligatorio',

                    // Mensaje para email (puedes agregar más específico si lo deseas)
                    'data.email.email' => 'Debe ingresar un correo electrónico válido'
            ]);


            User::create([
                'curp' => $this->data['curp'],
                'contrasena' => Hash::make($this->data['contrasena']),
                'rol' => $this->tipoCuenta
            ]);


            Tutor::create([
                'curp' => $this->data['curp'],
                'nombres' => $this->data['nombres'],
                'apellidoPaterno' => $this->data['apellidoPaterno'],
                'apellidoMaterno' => $this->data['apellidoMaterno'],
                'fechaNacimiento' => $this->data['fechaNacimiento'],
                'genero' => $this->data['genero'],
                'telefono' => $this->data['telefono'],
                'estadoCivil' => $this->data['estadoCivil'],
                'email' => $this->data['email'],
                'municipio' => $this->data['municipio'],
                'estado' => $this->data['estado'],
                'calleYnumero' => $this->data['calleYnumero'],
                'codigoPostal' => $this->data['codigoPostal'],
                'RFC' => $this->data['RFC'],
                'cedulaProfesional' => $this->data['cedulaProfesional'],
                'especialidad' => $this->data['especialidad'],
                'titulo' => $this->data['titulo'],
            ]);

        }

        return redirect()->route('admin-agregar-user')->with('succesStore','Usuario Agregado');
    }

    public function mount(){
        $this->grupos = Grupo::all();
    }
    public function render()
    {
        return view('livewire.admin-agregar-usuario');
    }
}
