<?php

namespace App\Livewire;

use App\Models\Tutor;
use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginForm extends Component
{
    public $curp;
    public $contrasena;
    public $loginExitoso = false;

    protected $rules = [
        'curp' => 'required|exists:users,curp',
        'contrasena' => 'required|min:8',
    ];

    public function login()
    {
        $this->validate();

        $user = User::where('curp', $this->curp)->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'curp' => ['La CURP ingresada no está registrada.'],
            ]);
        }

        if (!Hash::check($this->contrasena, $user->contrasena)) {
            throw ValidationException::withMessages([
                'contrasena' => ['La contraseña es incorrecta.'],
            ]);
        }

        // ⚠️ Simula inicio de sesión: guarda la CURP en sesión
        session(['curp' => $user->curp, 'rol' => $user->rol]);

        if($user->rol === 'Admin'){
            $admin = Tutor::where('curp',$user->curp)->first();
            $nameAdmin = $admin->nombres ." ". $admin->apellidoPaterno." ". $admin->apellidoMaterno;
        }

        // Redirige según rol
        return match ($user->rol) {
            'Profesor'   => redirect()->route('tutor.index'),
            'Admin',     => redirect()->route('admin-home-page')->with('nameAdmin',$nameAdmin),
            'Estudiante' => redirect()->route('dashboardE'),
            default      => redirect('/'),
        };
    }

    public function render()
    {
        return view('livewire.login-form');
    }
}

