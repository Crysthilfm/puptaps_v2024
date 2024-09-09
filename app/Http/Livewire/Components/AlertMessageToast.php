<?php

namespace App\Http\Livewire\Components;

use Livewire\Component;

class AlertMessageToast extends Component
{
    public $message;
    public function render()
    {
        return view('livewire.components.alert-message-toast');
    }
}
