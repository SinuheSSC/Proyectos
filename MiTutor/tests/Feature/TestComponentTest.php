<?php

namespace Tests\Feature;

use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class TestComponentTest extends TestCase
{
    // Este test verifica que el componente Livewire TestComponent:
    // 1. Muestra el mensaje inicial "Hola desde Livewire 🎉"
    // 2. Al hacer clic en el botón (llamando al método actualizarMensaje), cambia el mensaje
    // 3. El mensaje actualizado aparece correctamente en la vista

    #[Test]
    public function el_componente_test_component_funciona()
    {
        Livewire::test(\App\Http\Livewire\TestComponent::class)
            ->assertSee('Hola desde Livewire')
            ->call('actualizarMensaje')
            ->assertSee('Mensaje actualizado');
    }
}
