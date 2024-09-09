<?php

namespace App\Http\Livewire\Career;

use App\Models\CareerCategories;
use Livewire\Component;

class AddCareerText extends Component
{
    public function render()
    {
        $careerCategories = CareerCategories::all();
        return view('livewire.career.add-career-text', compact("careerCategories"));
    }
}
