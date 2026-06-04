<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class TestControllerTest extends TestCase
{
    // Este test verifica que la ruta /test:
    // 1. Devuelve un código de estado HTTP 200 (OK)
    // 2. Contiene el texto "Hola desde Livewire", como respuesta

    #[Test]
    public function la_ruta_test_carga_correctamente()
    {
        $response = $this->get('/test');

        $response->assertStatus(200);
        $response->assertSee('Hola desde Livewire');
    }
}
