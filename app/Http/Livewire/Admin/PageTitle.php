<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;

class PageTitle extends Component
{
    public $title;
    public function render()
    {
        return view('livewire.admin.page-title');
    }
}
