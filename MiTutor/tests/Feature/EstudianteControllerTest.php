<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class EstudianteControllerTest extends TestCase
{
    /** @test */
    #[Test]
    public function puede_ver_la_pagina_de_inicio()
    {
        // Crear un usuario de prueba con el campo curp
        /** @var \App\Models\User $usuario */
        $usuario = User::factory()->create();

        // Autenticamos al usuario
        $response = $this->actingAs($usuario)
                        ->get('/inicio');

        $response->assertStatus(200);

        $response->assertViewIs('estudiante.inicio');

        $response->assertSee($usuario->curp);
    }
}
