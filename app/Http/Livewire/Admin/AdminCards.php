<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;

class AdminCards extends Component
{
    public $totalRegisteredUser;
    public $totalregisteredUserSex;
    public $totalStudents;
    public $listOfNewAccounts;
    public function render()
    {
        return view('livewire.admin.admin-cards');
    }
}
