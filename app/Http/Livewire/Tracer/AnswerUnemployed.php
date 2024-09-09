<?php

namespace App\Http\Livewire\Tracer;

use App\Models\Alumni;
use App\Models\Tracer\TracerAnswers;
use App\Models\Tracer\TracerCategories;
use App\Models\Tracer\TracerQuestions;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class AnswerUnemployed extends Component
{
    public $answer;
    public $arrayAnswers = [];
    public $totalPage = 3;
    public $currentPage = 1;
    public $progressBar = 0;
    public $countNull = 1;
    public $temp = 0;
    public $no_board_exam;
    public $no_job_yet;
    public $currently_unemployed;

    public function render() {
        $this->addNullAnswers();
        $users = Alumni::where("alumni_id", "=", Auth::user()->alumni_id)->get();
        $categories = TracerCategories::all();
        $questions = TracerQuestions::all();

        return view("livewire.tracer.answer-unemployed",
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
                    $this->progressBar = $this->progressBar + 33;
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
        "arrayAnswers.*.answer" => "required",
    ];

    protected $messages = [
        "arrayAnswers.*.answer.required" => "This is required.",
        "arrayAnswers.*.answer.email" => "This is must be a valid email address.",
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

    public function unemployed_value() {
        if ($this->currently_unemployed == 'CURRENTLY_UNEMPLOYED') {
            $this->currently_unemployed = '';
            $this->arrayAnswers[5]['answer'] = '';
            $this->arrayAnswers[6]['answer'] = '';
            $this->arrayAnswers[7]['answer'] = '';
            $this->arrayAnswers[8]['answer'] = '';
            $this->arrayAnswers[9]['answer'] = '';
            $this->arrayAnswers[10]['answer'] = '';
            $this->arrayAnswers[11]['answer'] = '';
            $this->arrayAnswers[12]['answer'] = '';
            $this->arrayAnswers[13]['answer'] = '';
        }
        else {
            $this->currently_unemployed = 'CURRENTLY_UNEMPLOYED';
            $this->arrayAnswers[5]['answer'] = '---';
            $this->arrayAnswers[6]['answer'] = '---';
            $this->arrayAnswers[7]['answer'] = '---';
            $this->arrayAnswers[8]['answer'] = '---';
            $this->arrayAnswers[9]['answer'] = '---';
            $this->arrayAnswers[10]['answer'] = '---';
            $this->arrayAnswers[11]['answer'] = '---';
            $this->arrayAnswers[12]['answer'] = '---';
            $this->arrayAnswers[13]['answer'] = '---';
        }
    }

    public function noJobYet() {
        if ($this->no_job_yet == null) {
            $this->no_job_yet = 'NO_JOB_YET';
            $this->arrayAnswers[14]['answer'] = '---';
            $this->arrayAnswers[15]['answer'] = '---';
            $this->arrayAnswers[16]['answer'] = '---';
            $this->arrayAnswers[17]['answer'] = '---';
            $this->arrayAnswers[18]['answer'] = '---';
            $this->arrayAnswers[19]['answer'] = '---';
        }
        else {
            $this->no_job_yet = null;
            $this->arrayAnswers[14]['answer'] = '';
            $this->arrayAnswers[15]['answer'] = '';
            $this->arrayAnswers[16]['answer'] = '';
            $this->arrayAnswers[17]['answer'] = '';
            $this->arrayAnswers[18]['answer'] = '';
            $this->arrayAnswers[19]['answer'] = '';
        }
    }

    public function sameCurrent() {
        $this->arrayAnswers[14]['answer'] = $this->arrayAnswers[5]['answer'];
        $this->arrayAnswers[15]['answer'] = $this->arrayAnswers[6]['answer'];
        $this->arrayAnswers[16]['answer'] = $this->arrayAnswers[7]['answer'];
        $this->arrayAnswers[17]['answer'] = $this->arrayAnswers[8]['answer'];
        $this->arrayAnswers[18]['answer'] = $this->arrayAnswers[11]['answer'];
        $this->arrayAnswers[19]['answer'] = $this->arrayAnswers[12]['answer'];
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
            if ($this->currently_unemployed == 'CURRENTLY_UNEMPLOYED') {
                $this->arrayAnswers[5]['answer'] = 'UNEMPLOYED';
                $this->arrayAnswers[6]['answer'] = 'UNEMPLOYED';
                $this->arrayAnswers[7]['answer'] = 'UNEMPLOYED';
                $this->arrayAnswers[8]['answer'] = 'UNEMPLOYED';
                $this->arrayAnswers[9]['answer'] = 'UNEMPLOYED';
                $this->arrayAnswers[10]['answer'] = 'UNEMPLOYED';
                $this->arrayAnswers[11]['answer'] = 'noemail@email.com';
                $this->arrayAnswers[12]['answer'] = 'UNEMPLOYED';
                $this->arrayAnswers[13]['answer'] = 'UNEMPLOYED';
            }
            elseif ($this->currentPage == 2) {
                $this->validate([
                    'arrayAnswers.5.answer' => 'required',
                    'arrayAnswers.6.answer' => 'required',
                    'arrayAnswers.7.answer' => 'required',
                    'arrayAnswers.8.answer' => 'required',
                    'arrayAnswers.9.answer' => 'required',
                    'arrayAnswers.10.answer' => 'required',
                    'arrayAnswers.11.answer' => 'required|email',
                    'arrayAnswers.12.answer' => 'required',
                    'arrayAnswers.13.answer' => 'required',
                ]);
            }
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
        elseif ($this->currentPage == 2) {
            $this->validate([
                'arrayAnswers.5.answer' => 'required',
                'arrayAnswers.6.answer' => 'required',
                'arrayAnswers.7.answer' => 'required',
                'arrayAnswers.8.answer' => 'required',
                'arrayAnswers.9.answer' => 'required',
                'arrayAnswers.10.answer' => 'required',
                'arrayAnswers.11.answer' => 'required|email',
                'arrayAnswers.12.answer' => 'required',
                'arrayAnswers.13.answer' => 'required',
            ]);
        }
        $this->currentPage++;
        if($this->currentPage > $this->totalPage) {
            $this->currentPage = $this->totalPage;
        }
    }

    public function updateAnswer() {
        if ($this->no_job_yet == "NO_JOB_YET") {
            $this->arrayAnswers[14]['answer'] = 'UNEMPLOYED';
            $this->arrayAnswers[15]['answer'] = 'UNEMPLOYED';
            $this->arrayAnswers[16]['answer'] = 'UNEMPLOYED';
            $this->arrayAnswers[17]['answer'] = 'UNEMPLOYED';
            $this->arrayAnswers[18]['answer'] = 'noemail@email.com';
            $this->arrayAnswers[19]['answer'] = 'UNEMPLOYED';
        }
        else {
            $this->validate([
                'arrayAnswers.14.answer' => 'required',
                'arrayAnswers.15.answer' => 'required',
                'arrayAnswers.16.answer' => 'required',
                'arrayAnswers.17.answer' => 'required',
                'arrayAnswers.18.answer' => 'required|email',
                'arrayAnswers.19.answer' => 'required',
            ]);
        }
        $getAnswers = TracerAnswers::where('alumni_id', '=', Auth::user()->alumni_id)->get();

        foreach ($getAnswers as $answers) {
            $update = TracerAnswers::where('answer_id', '=', $answers->answer_id)->update([
                'answer' => $this->arrayAnswers[$this->temp]['answer'],
            ]);
            $this->temp++;
        }
        $this->arrayAnswers = [];
        $this->countNull = 1;
        $this->temp = 0;

        return redirect(route("userTracer.getTracerIndex"));
    }
}
