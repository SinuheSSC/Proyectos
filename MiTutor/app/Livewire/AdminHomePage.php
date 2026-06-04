<?php

namespace App\Livewire;

use App\Models\Tutor;
use Livewire\Component;

class AdminHomePage extends Component
{
    public $user;
    public function mount(){
        $this->user = Tutor::where('curp',session('curp'))->first();

    }
    public function render()
    {
        return view('livewire.admin-home-page');
    }
}
