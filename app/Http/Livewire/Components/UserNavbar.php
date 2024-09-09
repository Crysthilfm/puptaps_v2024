<?php

namespace App\Http\Livewire\Components;

use Livewire\Component;

class UserNavbar extends Component
{
    public $users;

    public function render()
    {
        return view('livewire.components.user-navbar');
    }
}
