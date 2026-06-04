<?php

namespace App\Livewire;

use App\Models\Tutor;
use Livewire\Component;

class TutorNavbar extends Component
{
    public $userName;
    public $userAvatar;
    public $isDropdownOpen = false;

    public function mount()
    {
        $curp = session('curp');
        $tutor = Tutor::where('curp', $curp)->first();

        if ($tutor) {
            $this->userName = $tutor->nombres . ' ' . $tutor->apellidoPaterno . ' ' . $tutor->apellidoMaterno;
            $this->userAvatar = $tutor->fotoPerfil
            ? asset('storage/' . $tutor->fotoPerfil)
            : asset('images/tutor/default.webp');

        } else {
            $this->userName = 'Nombre no disponible';
            $this->userAvatar = asset('images/tutor/default.webp');
        }
    }

    public function render()
    {
        return view('livewire.tutor-navbar');
    }

    public function toggleDropdown()
    {
        $this->isDropdownOpen = !$this->isDropdownOpen;
    }

    public function logout()
    {
        return redirect()->to('/login');
    }
}

