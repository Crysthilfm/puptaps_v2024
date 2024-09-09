<?php

namespace App\Http\Livewire\LandingPage;

use Livewire\Component;
use App\Models\CareerCategories;
use App\Models\Careers;

class CareerView extends Component
{
    
    // Landing page view career
    public function render()
    {
        $ctrCareer = 0;
        $careers = Careers::get();
        return view('livewire.landing-page.career-view', compact(['careers', 'ctrCareer']));
    }
}
