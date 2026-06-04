<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;


class UserController extends Controller
{
    /**
     * Muestra la lista de usuarios.
     */
    public function index()
    {
        $users = User::all();
        return response()->json($users, 200);
    }



     /**
     * Crea un nuevo usuario.
     */
    public function store(Request $request)
    {
        $request->validate([
            'curp' => 'required|max:18|',
            'contrasena' => 'required|min:8',
            'rol' => 'required',
        ]);


        $user = User::create([
            'curp' => $request->curp,
            'contrasena' => Hash::make($request->contrasena),
            'rol' => $request->rol,
        ]);

        return response()->json($user, 201);
    }




    /**
     * Muestra un usuario específico.
     */
    public function show(string $curp)
    {
        $user = User::where('curp',$curp)->firstOrFail();
        return view('edit', compact('user'));
    }





    /**
     * Funcion de login
     */
    public function login(Request $request)
    {
        // Validar los datos recibidos
        $validated = $request->validate([
            'curp' => 'required|max:18',
            'contrasena' => 'required|min:8',
        ]);

        // Buscar el usuario por CURP
        $user = User::where('curp', $validated['curp'])->first();

        // Verificar la contraseña
        if (!$user || !Hash::check($validated['contrasena'], $user->contrasena)) {
            return response()->json([
                'message' => 'Credenciales incorrectas'
            ], 401);
        }

        // Generar token de acceso con Sanctum
        //$token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'message' => 'Inicio de sesion exitoso',
            'user' => $user,
        ]);
    }





    /**
     * Actualiza un usuario.
     */
    public function update(Request $request, string $curp)
    {

        $validated = $request->validate([
            'contrasena' => 'required|min:8',
            'rol' => 'required|max:1',
        ]);

        $validated['contrasena'] = Hash::make($validated['contrasena']);

        User::where('curp',$curp)->update([
            'contrasena' => $validated['contrasena'],
            'rol' => $validated['rol']
        ]);

        return redirect()->route('users.index');
    }

    
    public function destroy(string $curp)
    {   
        User::where('curp',$curp)->delete();
        return redirect()->route('users.index');
    }

}
