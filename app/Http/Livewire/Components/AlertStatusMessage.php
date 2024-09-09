<?php

namespace App\Http\Livewire\Components;

use Livewire\Component;

class AlertStatusMessage extends Component
{
    public $message;
    public function render()
    {
        return view('livewire.components.alert-status-message');
    }
}
