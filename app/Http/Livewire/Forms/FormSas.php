<?php

namespace App\Http\Livewire\Forms;

use App\Models\Alumni;
use App\Models\Forms\Sas\SasAnswers;
use App\Models\Forms\Sas\SasCategories;
use App\Models\Forms\Sas\SasQuestions;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class FormSas extends Component
{
    public $answer;
    public $arrayAnswers = [];
    public $totalPage = 15;
    public $currentPage = 1;
    public $countNull = 1;
    public $progressBar = 0;

    public function render() {
        $this->addNullAnswers();
        $users = Alumni::where("alumni_id", "=", Auth::user()->alumni_id)->get();
        $categories = SasCategories::all();
        $questions = SasQuestions::all();

        return view("livewire.forms.form-sas",
        compact([
            "users",
            "categories",
            "questions",
        ])
        );
    }

    public function addNullAnswers() {
        $categories = SasCategories::all();
        $questions = SasQuestions::all();

        foreach($categories as $category) {
            if($this->currentPage == $category->category_id) {
                if($this->countNull == $this->currentPage) {
                    $this->progressBar = $this->progressBar + 6.66;
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
        $this->currentPage++;
        if($this->currentPage > $this->totalPage) {
            $this->currentPage = $this->totalPage;
        }
    }

    public function saveAnswer() {
        $this->validate();
        $questions = count(SasQuestions::all());
        $ctr = 1;

        foreach ($this->arrayAnswers as $key => $value) {
            if($ctr > $questions) {
                break;
            }
            $answers = SasAnswers::insert([
                "alumni_id"     => Auth::user()->alumni_id,
                "question_id"   => $ctr,
                "answer"        => $value["answer"],
            ]);
            $ctr++;
        }
        $this->arrayAnswers = [];
        $this->countNull = 1;

        return redirect(route("userForm.index"));
    }
}
