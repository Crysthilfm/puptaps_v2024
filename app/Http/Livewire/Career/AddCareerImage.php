<?php

namespace App\Http\Livewire\Career;

use App\Models\CareerCategories;
use Livewire\Component;

class AddCareerImage extends Component
{
    public function render()
    {
        $careerCategories = CareerCategories::all();
        return view('livewire.career.add-career-image', compact("careerCategories"));
    }
}
