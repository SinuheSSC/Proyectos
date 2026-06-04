<?php

namespace App\Livewire;

use Livewire\Component;

class TutorSidebar extends Component
{
    public $activeSection;
    public $isOpen; // Controla si el sidebar está abierto en pantallas pequeñas

    public function setActiveSection($section)
    {
        $this->activeSection = $section;
        // Opcional: Cerrar el sidebar en móvil después de la selección
        // Puedes añadir una condición aquí si necesitas cerrar el menú en móviles después de hacer clic en un ítem.
        // Por ejemplo, usando Alpine.js y una propiedad de media query o un evento de JS.
    }

    public function toggleSidebar()
    {
        $this->isOpen = !$this->isOpen;
    }

    public function mount(){
        $this->activeSection = 'inicio';
        $this->isOpen = false;
    }
    public function render()
    {
        return view('livewire.tutor-sidebar');
    }
}
