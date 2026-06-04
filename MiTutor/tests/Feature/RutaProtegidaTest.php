<?php
namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class RutaProtegidaTest extends TestCase
{
    /** @test */
    #[Test]
    public function un_usuario_no_autenticado_no_puede_acceder_a_rutas_protegidas()
    {
        $response = $this->get('/inicio');

        $response->assertRedirect('/login');
    }
}
