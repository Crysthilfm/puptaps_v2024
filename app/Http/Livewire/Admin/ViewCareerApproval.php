<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;

class ViewCareerApproval extends Component
{
    public $career;
    public $users;
    public function render()
    {
        return view('livewire.admin.view-career-approval');
    }
}
