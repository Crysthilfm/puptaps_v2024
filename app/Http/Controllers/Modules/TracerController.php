<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Livewire\Tracer\Update;
use App\Models\Alumni;
use App\Models\Courses;
use App\Models\Tracer\TracerAnswers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\Tracer\TracerCategories;
use App\Models\Tracer\TracerQuestions;
use App\Models\Tracer\TracerOptions;
use Exception;
use App\Models\TracerVersion;

class TracerController extends Controller
{
    public function getTracerIndex() {
        $users = Alumni::where('alumni_id', '=', Auth::user()->alumni_id)->get();
        $tracer_answers = TracerAnswers::where('alumni_id', '=', Auth::user()->alumni_id)->get();
        $title = "Tracer Form";
        $courses = Courses::all();
        return view('user.tracer.index', compact(['users', 'tracer_answers', 'title', 'courses']));
    }

    public function getAnswerPage() {
        $versionId = TracerQuestions::select('tracer_version_id')->first();
        $versionName = TracerVersion::find($versionId->tracer_version_id)->first();
        $users = Alumni::where('alumni_id', '=', Auth::user()->alumni_id)->get();
        $title = "Answer Tracer Form";
        $categories = TracerCategories::all();
        $questions = TracerQuestions::all();
        $options = TracerOptions::all();
        $noBoards = true;
        

        // Check if user has board exams
        // dd($users[0]->course_id);
        if($users[0]->course_id == 'BSECE' || $users[0]->course_id == 'BSED-Eng'
        || $users[0]->course_id == 'BSED-Math' || $users[0]->course_id == 'BSA' || $users[0]->course_id == 'BSME') $noBoards = false;
        else $noBoards = true;

        $questionNumbering = 1;

        return view('user.tracer.answer', compact(['versionName','questionNumbering','users', 'title','categories', 'questions', 'options', 'noBoards']));
    }

    public function getAnswerUnemployedPage() {
        $users = Alumni::where('alumni_id', '=', Auth::user()->alumni_id)->get();
        return view('user.tracer.answer-unemployed', compact(['users']));
    }

    public function getUpdatePage() {
        $versionId = TracerQuestions::select('tracer_version_id')->first();
        $versionName = TracerVersion::find($versionId->tracer_version_id)->first();

        $users = Alumni::where('alumni_id', '=', Auth::user()->alumni_id)->get();
        $title = "Answer Tracer Form";
        $categories = TracerCategories::all();
        $questions = TracerAnswers::where('alumni_id', '=', Auth::user()->alumni_id)
                        ->join('tbl_tracer_questions', 'tbl_tracer_questions.question_id', '=','tbl_tracer_answers.question_id')
                        ->get();
        $options = TracerOptions::all();
        $answers = TracerAnswers::where('alumni_id', '=', Auth::user()->alumni_id)
                    ->join('tbl_tracer_questions', 'tbl_tracer_questions.question_id', '=','tbl_tracer_answers.question_id')
                    ->get();
        $noBoards = true;

        // Check if user has board exams
        // dd($users[0]->course_id);
        if($users[0]->course_id == 'BSECE' || $users[0]->course_id == 'BSED-Eng'
        || $users[0]->course_id == 'BSED-Math' || $users[0]->course_id == 'BSA' || $users[0]->course_id == 'BSME') $noBoards = false;
        else $noBoards = true;

        return view('user.tracer.update', compact(['versionName','users', 'title','categories', 'questions', 'options', 'noBoards','answers']));
    }

    public function getAnswerModal() {
        $users = Alumni::where('alumni_id', '=', Auth::user()->alumni_id)->get();
        return view('user.tracer.answer-modal', compact(['users']));
    }

    public function setUnemployed(Request $request, $questions){
        // Sets category 2 'Current Job' answers to unemployed
        if($request->unemployed == "on"){
            foreach($questions as $question) {
                if($question->category_id == 2){
                    if($question->question_type == 'date')
                        $request['q'.$question->question_id] = "0000-00-00";
                    if($question->question_id == 12)
                        $request['q'.$question->question_id] = "unemployed@gmail.com";
                    else
                        $request['q'.$question->question_id] = "UNEMPLOYED";
                }
            }
        }
    }

    public function setSameCurrent(Request $request, $questions){
        // Sets category 3 'First Job' answers as the same with their Category 2 counter
        // parts.
        if($request->sameCurrent == "on"){
            $request['q15'] = $request['q6'];
            $request['q16'] = $request['q7'];
            $request['q17'] = $request['q8'];
            $request['q18'] = $request['q9'];
            $request['q19'] = $request['q12'];
            $request['q20'] = $request['q13'];
        }
        
    }

    public function saveUpdateTracer(Request $request){
        $questions = TracerQuestions::all();
        $users = Alumni::where('alumni_id', '=', Auth::user()->alumni_id)->first();
        // Sets unemployed and same with current job shortcuts

        $this->setUnemployed($request, $questions);
        $this->setSameCurrent($request, $questions);

        // Set null date and suggestion
        if($request['q4'] == null) $request['q4'] = 'N/A';
        if($request['q26'] == null) $request['q26'] = 'none';
        
        // Validates requests
        $this->validate($request,[
            'q'.'1'=>'required|not_in: "notSelected"',
            'q'.'2'=>'required|not_in: "notSelected"',
            'q'.'3'=>'required|not_in: "notSelected"',
            'q'.'5'=>'required|not_in: "notSelected"',
            'q'.'6'=>'required|not_in: "notSelected"',
            'q'.'7'=>'required|not_in: "notSelected"',
            'q'.'8'=>'required|not_in: "notSelected"',
            'q'.'9'=>'required|not_in: "notSelected"',
            'q'.'10'=>'required|not_in: "notSelected"',
            'q'.'11'=>'required|not_in: "notSelected"',
            'q'.'12'=>'required|email|not_in: "notSelected"',
            'q'.'13'=>'required|not_in: "notSelected"',
            'q'.'14'=>'required|not_in: "notSelected"',
            'q'.'15'=>'required|not_in: "notSelected"',
            'q'.'16'=>'required|not_in: "notSelected"',
            'q'.'17'=>'required|not_in: "notSelected"',
            'q'.'18'=>'required|not_in: "notSelected"',
            'q'.'19'=>'required|email|not_in: "notSelected"',
            'q'.'20'=>'required|not_in: "notSelected"',
        ]);

        

        $updateTracer = TracerAnswers::where('alumni_id','=',Auth::user()->alumni_id)->get();
        // Deletes existence of answers
        foreach($updateTracer as $update){
            $update->delete();
        }

        // Inserts new updated answers
        foreach($questions as $question){ 
            try{  
                $newAnswer = new TracerAnswers;
                $newAnswer->alumni_id = Auth::user()->alumni_id;
                $newAnswer->question_id = $question->question_id;
                $newAnswer->answer = $request['q'.$question->question_id];  
                $newAnswer->save();
            }catch (Exception $e){
                
            }
        }

        $users->tracer_updated_at = date('Y-m-d');
        $users->save();

        return redirect()->route('userTracer.getTracerIndex');
    }

    // TRACER SAVING
    public function saveAnswers(Request $request){
        $questions = TracerQuestions::all();
        $users = Alumni::where('alumni_id', '=', Auth::user()->alumni_id)->first();


        // Check if user has board exams
        if($users->course_id == 'BSECE' || $users->course_id == 'BSED-Eng'
        || $users->course_id == 'BSED-Math' || $users->course_id == 'BSA' || $users->course_id == 'BSME') $noBoards = false;
        else $noBoards = true;
        if($noBoards) $request['q4'] = 'N/A';

        // Set null date and suggestion
        if($request['q4'] == null) $request['q4'] = 'N/A';
        if($request['q26'] == null) $request['q26'] = 'none';

        // Sets unemployed and same with current job shortcuts
        $this->setUnemployed($request, $questions);
        $this->setSameCurrent($request, $questions);
        
        // Validates requests
        $this->validate($request,[
            'q'.'1'=>'required|not_in: "notSelected"',
            'q'.'2'=>'required|not_in: "notSelected"',
            'q'.'3'=>'required|not_in: "notSelected"',
            'q'.'5'=>'required|not_in: "notSelected"',
            'q'.'6'=>'required|not_in: "notSelected"',
            'q'.'7'=>'required|not_in: "notSelected"',
            'q'.'8'=>'required|not_in: "notSelected"',
            'q'.'9'=>'required|not_in: "notSelected"',
            'q'.'10'=>'required|not_in: "notSelected"',
            'q'.'11'=>'required|not_in: "notSelected"',
            'q'.'12'=>'required|email|not_in: "notSelected"',
            'q'.'13'=>'required|not_in: "notSelected"',
            'q'.'14'=>'required|not_in: "notSelected"',
            'q'.'15'=>'required|not_in: "notSelected"',
            'q'.'16'=>'required|not_in: "notSelected"',
            'q'.'17'=>'required|not_in: "notSelected"',
            'q'.'18'=>'required|not_in: "notSelected"',
            'q'.'19'=>'required|email|not_in: "notSelected"',
            'q'.'20'=>'required|not_in: "notSelected"',
        ]);

        foreach($questions as $question){ 
                $newAnswer = new TracerAnswers;
                $newAnswer->alumni_id = Auth::user()->alumni_id;
                $newAnswer->question_id = $question->question_id;
                $newAnswer->answer = $request['q'.$question->question_id];  
                $newAnswer->save();
                dd($newAnswer);
        }
        $users->tracer_updated_at = date('Y-m-d');
        $users->save();
        
        if($users->profile_status == 'Complete') {
            return redirect(route('user.homepage'));
        }
        else {
            return redirect(route('userProfile.set-up'));
        }
        
        //dd(Auth::user()->alumni_id);
    }
}
