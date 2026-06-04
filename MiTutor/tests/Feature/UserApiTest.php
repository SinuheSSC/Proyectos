<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use App\Livewire\LoginForm as LivewireLoginForm;
use Illuminate\Support\Facades\DB;


class UserApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * ALEXXXIS
     */
    public function test_crear_usuario(): void
    {
        $data = [
            'curp' => 'GACA030930HGTRNLA9',
            'contrasena' => '12345678',
            'rol' => 'Admin'
        ];

        $response = $this->postJson(route('users.store'), $data);

        $response->assertStatus(201) // Código HTTP 201 significa "Creado"
            ->assertJson([
                'curp' => $data['curp'],
                'rol' => $data['rol'],
            ]);

        $this->assertDatabaseHas('users', ['curp' => $data['curp']]);
    }

    public function test_obtener_un_usuario(): void
    {
        User::factory()->count(3)->create();

        $response = $this->getJson(route('users.index'));

        $response->assertStatus(200)
                ->assertJsonStructure([
                    '*' => ['curp', 'rol', 'created_at', 'updated_at']
                ]);
    }

     /**
     * SINUHE
     */
    public function test_el_componente_test_component_funciona()
    {
        Livewire::test(\App\Http\Livewire\TestComponent::class)
            ->assertSee('Hola desde Livewire')
            ->call('actualizarMensaje')
            ->assertSee('Mensaje actualizado');
    }

    public function test_la_ruta_test_carga_correctamente()
    {
        $response = $this->get('/test');

        $response->assertStatus(200);
        $response->assertSee('Hola desde Livewire');
    }

    /**
     * KEVIN
     */

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
                               // Rol incorrecto
             ->call('login')                     // Llamamos al método login
             ->assertHasErrors(['curp' => 'exists']); // Verifica que haya un error para el CURP
     }


     /**
      * YAYO
      */

      public function test_elimina_usuario_correctamente_y_redirecciona()
    {
        // Insertar el usuario como lo hace tu controlador (sin Eloquent create/save)
        DB::table('users')->insert([
            'curp' => 'ABC123456789MICH00',
            'contrasena' => bcrypt('password123'),
            'rol' => 'Admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->assertDatabaseHas('users', [
            'curp' => 'ABC123456789MICH00',
        ]);

        // Ejecutar DELETE como cliente HTTP
        $response = $this->delete("/api/users/ABC123456789MICH00");

        // El controlador hace redirect, por eso esperamos 302
        $response->assertStatus(302);

        // Verificar que fue eliminado
        $this->assertDatabaseMissing('users', [
            'curp' => 'ABC123456789MICH00',
        ]);
    }
}
