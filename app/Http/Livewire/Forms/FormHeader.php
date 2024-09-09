<?php

namespace App\Http\Livewire\Forms;

use Livewire\Component;

class FormHeader extends Component
{
    public $page_number;
    public $page_total;
    public $page_title;
    public $progressBar;
    public function render()
    {
        return view('livewire.forms.form-header');
    }
}
