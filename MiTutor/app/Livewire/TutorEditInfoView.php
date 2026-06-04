<?php

namespace App\Livewire;

use App\Models\Tutor;
use Livewire\Component;
use Livewire\WithFileUploads;

class TutorEditInfoView extends Component
{
    use WithFileUploads;

    public $curp;
    public $foto, $nuevaFoto;
    public $nombres, $apellidoPaterno, $apellidoMaterno, $fechaNacimiento;
    public $genero, $telefono, $estadoCivil, $email, $municipio;
    public $estado, $calleYnumero, $codigoPostal, $colonia;

    protected $rules = [
        'nombres' => 'required|string|max:30',
        'apellidoPaterno' => 'required|string|max:20',
        'apellidoMaterno' => 'required|string|max:20',
        'fechaNacimiento' => 'required|date',
        'genero' => 'required|string|max:12',
        'telefono' => 'required|string|max:11',
        'estadoCivil' => 'required|string|max:10',
        'email' => 'required|email|max:50',
        'municipio' => 'required|string|max:30',
        'estado' => 'required|string|max:30',
        'calleYnumero' => 'required|string|max:70',
        'codigoPostal' => 'required|string|max:5',
        'colonia' => 'nullable|string|max:50',
    ];

    public function mount()
{
    $curp = session('curp');
    $tutor = Tutor::where('curp', $curp)->firstOrFail();

    $this->nombres = $tutor->nombres;
    $this->apellidoPaterno = $tutor->apellidoPaterno;
    $this->apellidoMaterno = $tutor->apellidoMaterno;
    $this->fechaNacimiento = $tutor->fechaNacimiento;
    $this->genero = $tutor->genero;
    $this->telefono = $tutor->telefono;
    $this->estadoCivil = $tutor->estadoCivil;
    $this->email = $tutor->email;
    $this->municipio = $tutor->municipio;
    $this->estado = $tutor->estado;
    $this->calleYnumero = $tutor->calleYnumero;
    $this->codigoPostal = $tutor->codigoPostal;
    $this->colonia = ''; // no está en tu migración, puedes remover si no aplica
    $this->foto = $tutor->fotoPerfil ? asset('storage/' . $tutor->fotoPerfil) : asset('images/tutor/default.webp');
}

public function actualizarInformacion()
{
    $this->validate();

    $curp = session('curp');
    $tutor = Tutor::where('curp', $curp)->firstOrFail();

    if ($this->nuevaFoto) {
    $nombreArchivo = $this->nuevaFoto->store('tutores', 'public');
    $tutor->fotoPerfil = 'tutores/' . basename($nombreArchivo); // guarda solo la ruta relativa
}

    $tutor->update([
        'nombres' => $this->nombres,
        'apellidoPaterno' => $this->apellidoPaterno,
        'apellidoMaterno' => $this->apellidoMaterno,
        'fechaNacimiento' => $this->fechaNacimiento,
        'genero' => $this->genero,
        'telefono' => $this->telefono,
        'estadoCivil' => $this->estadoCivil,
        'email' => $this->email,
        'municipio' => $this->municipio,
        'estado' => $this->estado,
        'calleYnumero' => $this->calleYnumero,
        'codigoPostal' => $this->codigoPostal
    ]);

    if ($this->nuevaFoto) {
        $nombreArchivo = $this->nuevaFoto->store('images/tutor', 'public');
        $tutor->update(['fotoPerfil' => $nombreArchivo]);
    }

    session()->flash('mensaje', '¡Datos actualizados correctamente!');
}

    public function render()
    {
        return view('livewire.tutor-edit-info-view');
    }
}

