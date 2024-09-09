<?php

namespace App\Http\Livewire\Homepage;

use Livewire\Component;

class HomepageImageAnnouncement extends Component
{
    public $announcements;

    public function render()
    {
        return view('livewire.homepage.homepage-image-announcement');
    }
}
