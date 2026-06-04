<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Tutorado;
use Livewire\WithFileUploads;


class MiInformacionEEditar extends Component
{
    use WithFileUploads;

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
        'foto' => 'nullable|string|max:255',
    ];

    public function mount()
    {
    $curp = session('curp');

    if (!$curp) {
        abort(403, 'No hay CURP en la sesión. Por favor inicia sesión.');
    }

    // 📌 Buscar el tutorado asociado a la CURP
    $tutorado = Tutorado::where('curp', $curp)->first();



    // Si quieres, puedes asignar el tutorado a una propiedad pública
    $this->tutorado = $tutorado;
        $this->nombres = $tutorado->nombres;
        $this->apellidoPaterno = $tutorado->apellidoPaterno;
        $this->apellidoMaterno = $tutorado->apellidoMaterno;
        $this->fechaNacimiento = \Carbon\Carbon::parse($tutorado->fechaNacimiento)->format('Y-m-d');
        $this->genero = $tutorado->genero;
        $this->telefono = $tutorado->telefono;
        $this->estadoCivil = $tutorado->estadoCivil;
        $this->email = $tutorado->email;
        $this->municipio = $tutorado->municipio;
        $this->estado = $tutorado->estado;
        $this->calleYnumero = $tutorado->calleYnumero;
        $this->codigoPostal = $tutorado->codigoPostal;
        $this->colonia = $tutorado->colonia;
        $this->foto = $tutorado->fotoPerfil ?? null;
    }


    public function actualizarInformacion()
{
    $this->validate();

    $curp = session('curp');

    if (!$curp) {
        abort(403, 'No hay CURP en la sesión. Por favor inicia sesión.');
    }

    // 📌 Buscar el tutorado asociado a la CURP
    $tutorado = Tutorado::where('curp', $curp)->first();

    $tutorado->update([
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
        'codigoPostal' => $this->codigoPostal,
        'colonia' => $this->colonia,
        'fotoPerfil' => $this->foto 

    ]);
      if ($this->nuevaFoto) {
    $rutaFoto = $this->nuevaFoto->store('fotos', 'public');
    $this->foto = $rutaFoto;

    // 🔥 ACTUALIZAR la columna 'fotoPerfil' en la base de datos
    $tutorado->update([
        'fotoPerfil' => $rutaFoto,
    ]);
}




    session()->flash('mensaje', '¡Datos actualizados correctamente!');
}


    public function render()
    {
        return view('livewire.mi-informacionE-editar');

    }
}
