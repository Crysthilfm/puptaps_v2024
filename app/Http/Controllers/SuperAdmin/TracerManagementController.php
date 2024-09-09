<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tracer\TracerCategories;
use App\Models\Tracer\TracerQuestions;
use App\Models\Tracer\TracerOptions;
use RealRashid\SweetAlert\Facades\Alert;
use Exception;
use DB;
use Illuminate\Support\Facades\Log;
use App\Models\TracerVersion;

class TracerManagementController extends Controller
{
    //
    public function getTracerManagement(){
        $version = TracerVersion::all();
        $current_version = TracerVersion::orderBy('created_at','DESC')->take(1)->get();
        $current_version = $current_version[0]['tracer_version_id'];

        $tracerQuestionsBoards = TracerQuestions::where('category_id','=',1)->get();
        $tracerQuestionsCurrentJob = TracerQuestions::where('category_id','=',2)->get();
        $tracerQuestionsFirstJob = TracerQuestions::where('category_id','=',3)->get();
        return view("super_admin.Tracer Management.tracer_questions_management", compact(["current_version","version","tracerQuestionsBoards","tracerQuestionsCurrentJob","tracerQuestionsFirstJob"]));
    }

    public function getTracerManagementVersion(Request $request){
        $version = TracerVersion::all();
        $current_version = $request->versionID;
        $tracerQuestionsBoards = TracerQuestions::where('category_id','=',1)->where('tracer_version_id','=',$request->versionID)->get();
        $tracerQuestionsCurrentJob = TracerQuestions::where('category_id','=',2)->where('tracer_version_id','=',$request->versionID)->get();
        $tracerQuestionsFirstJob = TracerQuestions::where('category_id','=',3)->where('tracer_version_id','=',$request->versionID)->get();
        return view("super_admin.Tracer Management.tracer_questions_management", compact(["current_version","version","tracerQuestionsBoards","tracerQuestionsCurrentJob","tracerQuestionsFirstJob"]));
    }

    public function getAddQuestion() {
        return view("super_admin.Tracer Management.add_question");
    }

    public function saveQuestion(Request $request){
        $request->validate([
            'question_text'=>'required'
        ]); 

        try{
            // Creates new question in tbl_tracer_questions
            $newQuestion = new TracerQuestions();
            $newQuestion->category_id=$request->category_id;
            $newQuestion->question_text=$request->question_text;
            $newQuestion->question_type=$request->question_type;
            $newQuestion->save();

            // Adds the option for select and radio questions
            if($request->question_type == "radio" || $request->question_type == "select") {
                // Find Question
                $currentQuestion = TracerQuestions::where('tbl_tracer_questions.question_text', '=', $request->question_text)->first();
                if($request->option1 != null) {
                    $newOption = new TracerOptions();
                    $newOption->question_id = $currentQuestion->question_id;
                    $newOption->option_text = $request->option1;
                    $newOption->save();
                }
                if($request->option2 != null) {
                    $newOption = new TracerOptions();
                    $newOption->question_id = $currentQuestion->question_id;
                    $newOption->option_text = $request->option2;
                    $newOption->save();
                }
                if($request->option3 != null) {
                    $newOption = new TracerOptions();
                    $newOption->question_id = $currentQuestion->question_id;
                    $newOption->option_text = $request->option3;
                    $newOption->save();
                }
                if($request->option4 != null) {
                    $newOption = new TracerOptions();
                    $newOption->question_id = $currentQuestion->question_id;
                    $newOption->option_text = $request->option4;
                    $newOption->save();
                }
            }
            $user = auth()->user();
            Log::channel('superadmin')->info("UserId:".$user->user_id." | AdminId: ".$user->admin_id." | Username: ".$user->name." - Added a new question: question id[".$newQuestion->question_id."]");
            Alert::success('Success','');
        } catch (Exception $e) {
            $user = auth()->user();
            Log::channel('superadmin')->info("UserId:".$user->user_id." | AdminId: ".$user->admin_id." | Username: ".$user->name." - Failed adding a question attempt");
            Alert::error('Error','');
        }
        Alert::success('success','');
        return redirect('super-admin/tracer');
    }

    public function deleteQuestion(Request $request){
        try{
            // Deletes the options of the question
            $options = TracerOptions::where('question_id', '=', $request->question_id)
            ->delete();

            // Deletes the question
            $question = TracerQuestions::find($request->question_id);
            $question->delete();

            $user = auth()->user();
            Log::channel('superadmin')->info("UserId:".$user->user_id." | AdminId: ".$user->admin_id." | Username: ".$user->name." - Deleted a question: question id[".$request->question_id."]");
            Alert::success('Success','');
        } catch (Exception $e) {
            $user = auth()->user();
            Log::channel('superadmin')->info("UserId:".$user->user_id." | AdminId: ".$user->admin_id." | Username: ".$user->name." - Failed deleting a question attempt");
            Alert::error('Error','');
        }

        return redirect('super-admin/tracer');
    }

    public function getEditQuestion(Request $request){
        $question = TracerQuestions::find($request->question_id);
        $options = TracerOptions::where('question_id', '=', $request->question_id)
        ->get();

        return view('super_admin.Tracer Management.edit_question',compact(['question', 'options']));
    }
    public function saveEditQuestion(Request $request){
        try{
            // Finds question
            $question = TracerQuestions::find($request->question_id);

            //Validation
            $request->validate([
                'question_text'=>'required'
            ]); 

            // Saves the changes to the question
            $question->category_id=$request->category_id;
            $question->question_text=$request->input('question_text');
            $question->question_type=$request->input('question_type');
            $question->update();

            $user = auth()->user();
            Log::channel('superadmin')->info("UserId:".$user->user_id." | AdminId: ".$user->admin_id." | Username: ".$user->name." - Edited a question: question id[".$request->question_id."]");
            Alert::success('Success','');
        } catch (Exception $e) {
            $user = auth()->user();
            Log::channel('superadmin')->info("UserId:".$user->user_id." | AdminId: ".$user->admin_id." | Username: ".$user->name." - Failed editing a question attempt");
            Alert::error('Error','');
        }

        return redirect('super-admin/tracer');
    }
    public function deleteOption(Request $request) {
        try{
            // Deletes the question from the db
            $option = TracerOptions::find($request->option_id);
            $option->delete();

            $user = auth()->user();
            Log::channel('superadmin')->info("UserId:".$user->user_id." | AdminId: ".$user->admin_id." | Username: ".$user->name." - Deleted an Option: option id[".$request->option_id."]");
            Alert::success('Success','');
        } catch (Exception $e) {
            $user = auth()->user();
            Log::channel('superadmin')->info("UserId:".$user->user_id." | AdminId: ".$user->admin_id." | Username: ".$user->name." - Failed deleting an option attempt");
            Alert::error('Error','');
        }

        return redirect('super-admin/tracer');
    }
    public function editOption(Request $request) {
        $request->validate([
            'option_change'=>'required'
        ]); 

        try{
            // Finds option
            $option = TracerOptions::whereRaw('option_id = "'.$request->option_id.'"')->first();
            // Replaces the option's text
            $option->option_text = $request->input('option_change');
            $option->update();

            $user = auth()->user();
            Log::channel('superadmin')->info("UserId:".$user->user_id." | AdminId: ".$user->admin_id." | Username: ".$user->name." - Edited an Option: option id[".$request->option_id."]");
            Alert::success('Success','');
        } catch (Exception $e) {
            $user = auth()->user();
            Log::channel('superadmin')->info("UserId:".$user->user_id." | AdminId: ".$user->admin_id." | Username: ".$user->name." - Failed editing option attempt");
            Alert::error('Error','');
        }

        return redirect('super-admin/tracer');
    }
    public function addOption(Request $request) {

        $validated = $request->validate([
            'option_text' => 'required',
        ]);

        try{
            // Creates a new option for the question (radio/select)
            $option = new TracerOptions();
            $option->question_id = $request->question_id;
            $option->option_text = $request->option_text;
            $option->save();

            $user = auth()->user();
            Log::channel('superadmin')->info("UserId:".$user->user_id." | AdminId: ".$user->admin_id." | Username: ".$user->name." - Added an Option: option id[".$option->option_id."]");
            Alert::success('Success','');
        } catch (Exception $e) {
            $user = auth()->user();
            Log::channel('superadmin')->info("UserId:".$user->user_id." | AdminId: ".$user->admin_id." | Username: ".$user->name." - Failed adding an option attempt");
            Alert::error('Error','');
        }
        
        return redirect('super-admin/tracer');
    }
}
