<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

//RUTA DE LA API DE USUARIOS
Route::apiResource('users',UserController::class);

//RUTA PARA LOGIN
Route::post('/loginUser', [UserController::class, 'login'])->name('users.login');


// Ruta de prueba para verificar que la API funciona
Route::get('/test', function () {
    return response()->json(['message' => 'API funcionando correctamente']);
});

// Ruta de autenticación para iniciar sesión y obtener un token
Route::post('/login', function (Request $request) {
    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Credenciales incorrectas'], 401);
    }

    return response()->json([
        'token' => $user->createToken('mi-tutor-token')->plainTextToken
    ]);
});

// Ruta protegida, solo accesible con token
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Puedes agregar más rutas protegidas aquí (ejemplo: CRUDs de usuarios, posts, etc.)
});
