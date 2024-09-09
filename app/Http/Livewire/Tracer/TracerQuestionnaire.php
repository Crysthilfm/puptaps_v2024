<?php

namespace App\Http\Livewire\Tracer;

use Livewire\Component;
use App\Models\Alumni;
use Illuminate\Support\Facades\Auth;
use App\Models\Tracer\TracerAnswers;
use App\Models\Tracer\TracerCategories;
use App\Models\Tracer\TracerQuestions;
use App\Models\Tracer\TracerOptions;

class TracerQuestionnaire extends Component
{
    // ===============================================================
    //                  NEW Tracer Studies Questionnaire loading
    // ==============================================================

    public $categories;
    public $questions;
    public $options;
    public $arrayAnswers= [];
    public function render()
    {
        $users = Alumni::where("alumni_id", "=", Auth::user()->alumni_id)->get();
        $this->categories = TracerCategories::all();
        $this->questions = TracerQuestions::all();
        $this->options = TracerOptions::all();



        foreach($this->questions as $question){
            $this->arrayAnswers[$question->question_id][''];
        }

        return view('livewire.tracer.tracer-questionnaire');
    }

    public function submit(){

        
    }

}
