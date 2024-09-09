<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;

class ApproveCareerApproval extends Component
{
    public $career;
    public $users;
    public function render()
    {
        return view('livewire.admin.approve-career-approval');
    }
}
