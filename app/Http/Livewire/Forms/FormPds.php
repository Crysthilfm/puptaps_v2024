<?php

namespace App\Http\Livewire\Forms;

use App\Models\Alumni;
use App\Models\Forms\Pds\PdsAnswers;
use App\Models\Forms\Pds\PdsCategories;
use App\Models\Forms\Pds\PdsQuestions;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class FormPds extends Component
{
    public $answer;
    public $arrayAnswers = [];
    public $totalPage = 5;
    public $currentPage = 1;
    public $countNull = 1;
    public $progressBar = 0;
    public $form_name = "PDS";

    public $showSeminar2 = '';
    public $showSeminar3 = '';
    public $showButton2 = 'show';
    public $showButton3 = 'hidden';

    public $default = 'N/A';

    public function render() {
        $this->addNullAnswers();
        $users = Alumni::where("alumni_id", "=", Auth::user()->alumni_id)->get();
        $categories = PdsCategories::all();
        $questions = PdsQuestions::all();

        return view("livewire.forms.form-pds",
        compact([
            "users",
            "categories",
            "questions",
        ])
        );
    }

    public function changeToShow2() {
        $this->showSeminar2 = 'show';
        $this->showButton2 = 'hidden';
        $this->showButton3 = 'show';
    }

    public function changeToShow3() {
        $this->showSeminar3 = 'show';
        $this->showButton3 = 'hidden';
    }

    public function addNullAnswers() {
        $categories = PdsCategories::all();
        $questions = PdsQuestions::all();

        foreach($categories as $category) {
            if($this->currentPage == $category->category_id) {
                if($this->countNull == $this->currentPage) {
                    $this->progressBar = $this->progressBar + 20;
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
        $this->showButton2 = 'show';
        $this->showButton3 = 'hidden';
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

    public function nextPageSeminars() {
        $this->resetErrorBag();
        $this->addNullAnswers();
        $temp_null = $this->countNull - 1;
        if($temp_null == $this->currentPage) {
            if ($this->arrayAnswers[10]['answer'] == null && $this->showButton2 == 'show') {
                $this->arrayAnswers[10]['answer'] = 'N/A';
            }
            if ($this->arrayAnswers[11]['answer'] == null && $this->showButton2 == 'show') {
                $this->arrayAnswers[11]['answer'] = 'N/A';
            }
            if ($this->arrayAnswers[12]['answer'] == null && $this->showSeminar3 == '') {
                $this->arrayAnswers[12]['answer'] = 'N/A';
            }
            if ($this->arrayAnswers[13]['answer'] == null && $this->showSeminar3 == '') {
                $this->arrayAnswers[13]['answer'] = 'N/A';
            }
            $this->validate();
        }
        $this->currentPage++;
        if($this->currentPage > $this->totalPage) {
            $this->currentPage = $this->totalPage;
        }
    }

    public function saveAnswer() {
        $this->validate();
        $questions = count(PdsQuestions::all());
        $ctr = 1;

        foreach ($this->arrayAnswers as $key => $value) {
            if($ctr > $questions) {
                break;
            }
            $answers = PdsAnswers::insert([
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
