<?php

namespace App\Http\Livewire\Homepage;

use Livewire\Component;

class HomepageImageNews extends Component
{
    public $news;

    public function render()
    {
        return view('livewire.homepage.homepage-image-news');
    }
}
