<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Livewire\LoginForm as LivewireLoginForm;
use Livewire\Livewire;
use Illuminate\Database\Eloquent;

class LoginFormTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_log_in_a_user_successfully_using_livewire()
    {
        // Crear un usuario con CURP y contraseña
        $user = User::factory()->create([
            'curp' => 'A1234567890123',
            'contrasena' => bcrypt('password123'), // Contraseña encriptada
        ]);

        // Autenticar al usuario para la prueba
        $this->actingAs($user);  // Esto autentica al usuario

        // Realizar la prueba con Livewire
        Livewire::test(LivewireLoginForm::class)
            ->set('curp', 'A1234567890123')    // Asignamos el CURP
            ->set('contrasena', 'password123')    // Asignamos la contraseña
            ->call('login')                     // Llamamos al método login
            ->assertSessionHasNoErrors();        // Verifica que no haya errores en la sesión

        // Verificamos que el usuario está autenticado
        $this->assertAuthenticatedAs($user);  // Verifica que el usuario está autenticado
    }


    /** @test */
    public function it_shows_error_for_invalid_credentials_using_livewire()
    {
        // Realizamos la prueba con Livewire y proporcionamos credenciales incorrectas
        Livewire::test(LivewireLoginForm::class)
            ->set('curp', 'wrongCurp123')       // CURP incorrecto
            ->set('contrasena', 'wrongPassword')  // Contraseña incorrecta
            ->set('rol', 'A')                   // Rol incorrecto
            ->call('login')                     // Llamamos al método login
            ->assertHasErrors(['curp' => 'exists']); // Verifica que haya un error para el CURP
    }
}
