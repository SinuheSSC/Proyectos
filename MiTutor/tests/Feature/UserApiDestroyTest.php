<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class UserApiDestroyTest extends TestCase
{
    use RefreshDatabase;

    public function test_elimina_usuario_correctamente_y_redirecciona()
    {
        // Insertar el usuario como lo hace tu controlador (sin Eloquent create/save)
        DB::table('users')->insert([
            'curp' => 'ABC123456789MICH00',
            'contrasena' => bcrypt('password123'),
            'rol' => '1',
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






