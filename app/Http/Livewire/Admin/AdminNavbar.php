<?php

namespace App\Http\Livewire\Admin;

use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class AdminNavbar extends Component
{
    public function render()
    {
        $admin_name = Admin::where('admin_id', '=', Auth::user()->admin_id)->value('first_name');
        return view('livewire.admin.admin-navbar', compact('admin_name'));
    }
}
