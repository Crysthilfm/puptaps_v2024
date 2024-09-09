<?php

namespace App\Http\Livewire\Career;

use App\Models\Careers;
use Livewire\Component;

class DeleteCareerPost extends Component
{
    public $career_id;
    public function render()
    {
        return view('livewire.career.delete-career-post');
    }

    public function deletePost() {
        $post = Careers::find($this->career_id);
        $post->delete();

        return redirect(route('userCareer.index'));
    }
}
