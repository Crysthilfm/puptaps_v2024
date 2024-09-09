<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Models\Alumni;
use App\Models\Forms;
use App\Models\Forms\Eif\EifAnswers;
use App\Models\Forms\Pds\PdsAnswers;
use App\Models\Forms\Sas\SasAnswers;
use Illuminate\Support\Facades\Auth;

class FormsController extends Controller
{

    public function getFormIndex() {
        $users = Alumni::where("alumni_id", "=", Auth::user()->alumni_id)->get();
        $forms = Forms::all();
        $title = "Required Forms";
        $pdsAnswer = PdsAnswers::where("alumni_id", "=", Auth::user()->alumni_id)->get();
        $eifAnswer = EifAnswers::where("alumni_id", "=", Auth::user()->alumni_id)->get();
        $sasAnswer = SasAnswers::where("alumni_id", "=", Auth::user()->alumni_id)->get();

        return view("user.forms.index",
        compact(
            [
                "users",
                "forms",
                "pdsAnswer",
                "eifAnswer",
                "sasAnswer",
                "title",
            ]
        ));
    }

    public function getPDS() {
        $title = "Personal Data Sheet";
        $users = Alumni::where('alumni_id', '=', Auth::user()->alumni_id)->get();
        return view('user.forms.formPDS',
        compact(
            [
                'users',
                'title',
            ]
        ));
    }

    public function getExiteInterview() {
        $title = "Exit Interview Form";
        $users = Alumni::where('alumni_id', '=', Auth::user()->alumni_id)->get();
        return view('user.forms.formExitInterview',
        compact(
            [
                'users',
                'title',
            ]
        ));
    }

    public function getSAS() {
        $title = "Student Affairs and Services Form";
        $users = Alumni::where('alumni_id', '=', Auth::user()->alumni_id)->get();
        return view('user.forms.formSAS',
        compact(
            [
                'users',
                'title',
            ]
        ));
    }
}
