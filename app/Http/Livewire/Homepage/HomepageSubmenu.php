<?php

namespace App\Http\Livewire\Homepage;

use Livewire\Component;

class HomepageSubmenu extends Component
{
    public $query;

    public function render()
    {
        return view('livewire.homepage.homepage-submenu');
    }
}
