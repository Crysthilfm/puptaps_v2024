<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;

class RejectCareerApproval extends Component
{
    public $career;
    public $users;
    public function render()
    {
        return view('livewire.admin.reject-career-approval');
    }
}
