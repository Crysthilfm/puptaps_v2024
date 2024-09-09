<?php

namespace App\Http\Livewire\Tracer;

use App\Models\Alumni;
use App\Models\Tracer\TracerAnswers;
use App\Models\Tracer\TracerCategories;
use App\Models\Tracer\TracerQuestions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Livewire\Component;

class Update extends Component
{
    public $answer;
    public $arrayAnswers = [];
    public $totalPage = 2;
    public $currentPage = 1;
    public $progressBar = 0;
    public $countNull = 1;
    public $temp = 0;
    public $no_board_exam;
    public $currently_unemployed;

    public function render() {
        $this->addNullAnswers();
        $users = Alumni::where("alumni_id", "=", Auth::user()->alumni_id)->get();
        $categories = TracerCategories::all();
        $questions = TracerQuestions::all();

        return view("livewire.tracer.update",
        compact([
            "users",
            "categories",
            "questions",
        ])
        );
    }

    public function addNullAnswers() {
        $categories = TracerCategories::all();
        $questions = TracerQuestions::all();

        foreach($categories as $category) {
            if($this->currentPage == $category->category_id) {
                if($this->countNull == $this->currentPage) {
                    $this->progressBar = $this->progressBar + 49.5;
                    foreach($questions as $question) {
                        if($question->category_id == $category->category_id) {
                            array_push($this->arrayAnswers, [
                                "answer" => "",
                            ]);
                        }
                    }
                    $this->countNull++;
                }
            }
        }
    }

    protected $rules = [
        "arrayAnswers.*.answer" => "required|string",
    ];

    protected $messages = [
        "arrayAnswers.*.answer.required" => "This is required.",
        "arrayAnswers.*.answer.string"   => "This is required.",
    ];

    public function mount() {
        $this->currentPage = 1;
    }

    public function board_value() {
        if ($this->no_board_exam == "NO_BOARD_EXAM") {
            $this->no_board_exam = '';
            $this->arrayAnswers[0]['answer'] = '';
            $this->arrayAnswers[1]['answer'] = '';
            $this->arrayAnswers[2]['answer'] = '';
            $this->arrayAnswers[3]['answer'] = '';
        }
        else {
            $this->no_board_exam = "NO_BOARD_EXAM";
            $this->arrayAnswers[0]['answer'] = 'N/A';
            $this->arrayAnswers[1]['answer'] = 'N/A';
            $this->arrayAnswers[2]['answer'] = 'N/A';
            $this->arrayAnswers[3]['answer'] = 'N/A';
        }
    }

    public function previousPage() {
        $this->resetErrorBag();
        $this->currentPage--;
        if($this->currentPage < 1) {
            $this->currentPage = 1;
        }
    }

    public function nextPage() {
        $this->resetErrorBag();
        $this->addNullAnswers();
        $temp_null = $this->countNull - 1;
        if($temp_null == $this->currentPage) {
            $this->validate();
        }
        elseif ($this->currentPage == 1) {
            $this->validate([
                'arrayAnswers.0.answer' => 'required',
                'arrayAnswers.1.answer' => 'required',
                'arrayAnswers.2.answer' => 'required',
                'arrayAnswers.3.answer' => 'required',
                'arrayAnswers.4.answer' => 'required',
            ]);
        }
        $this->currentPage++;
        if($this->currentPage > $this->totalPage) {
            $this->currentPage = $this->totalPage;
        }
    }

    public function updateAnswer() {
        $this->validate();
        $getAnswers = TracerAnswers::where('alumni_id', '=', Auth::user()->alumni_id)->wherein('question_id', [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14])->get();

        foreach ($getAnswers as $answers) {
            $update = TracerAnswers::where('answer_id', '=', $answers->answer_id)->update([
                'answer' => $this->arrayAnswers[$this->temp]['answer'],
            ]);
            
            // Sets update date
            $update = TracerAnswers::where('alumni_id', '=', Auth::user()->alumni_id)->update([
                'tracer_updated_at' => date('Y-m-d')]);
            $this->temp++;
        }
        $this->arrayAnswers = [];
        $this->countNull = 1;
        $this->temp = 0;

        return redirect(route("userTracer.getTracerIndex"));
    }
}
