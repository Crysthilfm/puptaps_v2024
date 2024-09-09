<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Alumni;
use App\Models\Forms\Sas\SasQuestions;
use App\Models\Forms\Eif\EifQuestions;
use DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use PhpParser\Node\Stmt\Else_;

class ReportGenerationWithGraphs extends Controller
{
    public $sasQuestionArray = array();
    public $alumniData = array();

    public function getAlumniCount(Request $request){
        if($request->course == "all"){
            $alumni = Alumni::where('tbl_alumni.batch', '=', $request->batch_from)
            ->where('tbl_alumni.profile_status','=','Complete')
            ->get();
        } else {
            $alumni = Alumni::where('tbl_alumni.batch', '=', $request->batch_from)
            ->where('tbl_alumni.profile_status','=','Complete')
            ->where('tbl_alumni.course_id', '=', $request->course)
            ->get();
        }
        

        $alumniCount = count($alumni);
        return $alumniCount;
    }

    public function getAlumniAge(Request $request){
        $processedData = array();
        if($request->course == "all"){
            $alumni = Alumni::where('tbl_alumni.batch', '=', $request->batch_from)
            ->groupBy('tbl_alumni.age')
            ->distinct('tbl_alumni.alumni_id')
            ->where('tbl_alumni.profile_status','=','Complete')
            ->select(DB::raw('tbl_alumni.age as age, COUNT(tbl_alumni.alumni_id) as answerCount'))
            ->get();
        } else {
            $alumni = Alumni::where('tbl_alumni.batch', '=', $request->batch_from)
            ->where('tbl_alumni.course_id', '=', $request->course)
            ->distinct('tbl_alumni.alumni_id')
            ->groupBy('tbl_alumni.age')
            ->where('tbl_alumni.profile_status','=','Complete')
            ->select(DB::raw('tbl_alumni.age as age, COUNT(tbl_alumni.alumni_id) as answerCount'))
            ->get();
        }
        foreach($alumni as $a){
            $cell = new resultModel();
            $cell->set_item($a->age);
            $cell->set_count($a->answerCount);
            array_push($processedData, $cell);
        }
        return $processedData;
    }

    public function getAlumniGender(Request $request){
        $processedData = array();
        if($request->course == "all"){
            $alumni = Alumni::where('tbl_alumni.batch', '=', $request->batch_from)
            ->distinct('tbl_alumni.alumni_id')
            ->where('tbl_alumni.profile_status','=','Complete')
            ->groupBy('tbl_alumni.sex')
            ->select(DB::raw('tbl_alumni.sex as sex, COUNT(tbl_alumni.alumni_id) as answerCount'))
            ->get();
        } else {
            $alumni = Alumni::where('tbl_alumni.batch', '=', $request->batch_from)
            ->where('tbl_alumni.course_id', '=', $request->course)
            ->distinct('tbl_alumni.alumni_id')
            ->groupBy('tbl_alumni.sex')
            ->where('tbl_alumni.profile_status','=','Complete')
            ->select(DB::raw('tbl_alumni.sex as sex, COUNT(tbl_alumni.alumni_id) as answerCount'))
            ->get();
        }
        foreach($alumni as $a){
            $cell = new resultModel();
            $cell->set_item($a->sex);
            $cell->set_count($a->answerCount);
            array_push($processedData, $cell);
        }
        return $processedData;
    }
    public function getAlumniCS(Request $request){
        $processedData = array();
        if($request->course == "all"){
            $alumni = Alumni::where('tbl_alumni.batch', '=', $request->batch_from)
            ->groupBy('tbl_alumni.civil_status')
            ->distinct('tbl_alumni.alumni_id')
            ->where('tbl_alumni.profile_status','=','Complete')
            ->select(DB::raw('tbl_alumni.civil_status as civil_status, COUNT(tbl_alumni.alumni_id) as answerCount'))
            ->get();
        } else {
            $alumni = Alumni::where('tbl_alumni.batch', '=', $request->batch_from)
            ->where('tbl_alumni.course_id', '=', $request->course)
            ->distinct('tbl_alumni.alumni_id')
            ->groupBy('tbl_alumni.civil_status')
            ->where('tbl_alumni.profile_status','=','Complete')
            ->select(DB::raw('tbl_alumni.civil_status as civil_status, COUNT(tbl_alumni.alumni_id) as answerCount'))
            ->get();
        }
        foreach($alumni as $a){
            $cell = new resultModel();
            $cell->set_item($a->civil_status);
            $cell->set_count($a->answerCount);
            array_push($processedData, $cell);
        }
        return $processedData;
    }
    public function getAlumniCourse(Request $request){
        $processedData = array();
        if($request->course == "all"){
            $alumni = Alumni::where('tbl_alumni.batch', '=', $request->batch_from)
            ->groupBy('tbl_alumni.course_id')
            ->distinct('tbl_alumni.alumni_id')
            ->where('tbl_alumni.profile_status','=','Complete')
            ->select(DB::raw('tbl_alumni.course_id as course_id, COUNT(tbl_alumni.alumni_id) as answerCount'))
            ->get();
        } else {
            $alumni = Alumni::where('tbl_alumni.batch', '=', $request->batch_from)
            ->where('tbl_alumni.course_id', '=', $request->course)
            ->distinct('tbl_alumni.alumni_id')
            ->groupBy('tbl_alumni.course_id')
            ->where('tbl_alumni.profile_status','=','Complete')
            ->select(DB::raw('tbl_alumni.course_id as course_id, COUNT(tbl_alumni.alumni_id) as answerCount'))
            ->get();
        }
        foreach($alumni as $a){
            $cell = new resultModel();
            $cell->set_item($a->course_id);
            $cell->set_count($a->answerCount);
            array_push($processedData, $cell);
        }
        return $processedData;
    }

    public function getEIFQuestions(Request $request) {
        $this->sasQuestionArray = array();
        // This is actually EIF Question Array, rename when free
        $sasQuestions = EIFQuestions::where("question_id",">", 2)
        ->where("question_id","<", 50)
        ->get();

        foreach($sasQuestions as $question) {
            $sasQuestionCell = new QuestionModel();
            $sasQuestionCell->set_qId($question->question_id);
            $sasQuestionCell->set_text($question->question_text);
            array_push($this->sasQuestionArray, $sasQuestionCell);
        }
        $questionArray = array();
        //SELECT question_id, answer, COUNT(answer) FROM tbl_alumni JOIN form_sas_answers ON 
        //tbl_alumni.alumni_id = form_sas_answers.alumni_id WHERE question_id = 5 GROUP BY answer;
        foreach($sasQuestions as $question){
            $answerArray = array();
            if($question->question_id > 2 && $question->question_id < 76){
                if($request->course == "all"){
                    $alumniSAS = Alumni::join('form_eif_answers', 'form_eif_answers.alumni_id', '=', 'tbl_alumni.alumni_id')
                    ->where('question_id', $question->question_id)
                    ->where('tbl_alumni.batch', '=', $request->batch_from)
                    ->where('tbl_alumni.profile_status','=','Complete')
                    ->groupBy('form_eif_answers.answer')
                    ->distinct('tbl_alumni.alumni_id')
                    ->orderByRaw('answer DESC')
                    ->select(DB::raw('answer, COUNT(form_eif_answers.answer) as answerCount'))
                    ->get();
                } else {
                    $alumniSAS = Alumni::join('form_eif_answers', 'form_eif_answers.alumni_id', '=', 'tbl_alumni.alumni_id')
                    ->where('question_id', $question->question_id)
                    ->where('tbl_alumni.batch', '=', $request->batch_from)
                    ->where('tbl_alumni.course_id', '=', $request->course)
                    ->where('tbl_alumni.profile_status','=','Complete')
                    ->groupBy('form_eif_answers.answer')
                    ->distinct('tbl_alumni.alumni_id')
                    ->orderByRaw('answer DESC')
                    ->select(DB::raw('answer, COUNT(form_eif_answers.answer) as answerCount'))
                    ->get();
                }

                foreach($alumniSAS as $answer){
                    $graphedData = new GraphedAnswerData();
                    $graphedData->set_description($answer->answer);
                    $graphedData->set_totalCount($answer->answerCount);
                    array_push($answerArray, $graphedData);
                }

                $graphedQuestionData = new GraphedQuestionList();
                $graphedQuestionData->set_answer($answerArray);
                array_push($questionArray, $graphedQuestionData);
            }
        }
        return $questionArray;
    }

    
    public function getSASQuestions(Request $request) {
        $this->sasQuestionArray = array();
        $sasQuestions = SasQuestions::where("question_id",">", 2)
        ->where("question_id","<", 77)
        ->get();

        foreach($sasQuestions as $question) {
            $sasQuestionCell = new QuestionModel();
            $sasQuestionCell->set_qId($question->question_id);
            $sasQuestionCell->set_text($question->question_text);
            array_push($this->sasQuestionArray, $sasQuestionCell);
        }
        $questionArray = array();
        //SELECT question_id, answer, COUNT(answer) FROM tbl_alumni JOIN form_sas_answers ON 
        //tbl_alumni.alumni_id = form_sas_answers.alumni_id WHERE question_id = 5 GROUP BY answer;
        foreach($sasQuestions as $question){
            $answerArray = array();
            if($question->question_id > 2 && $question->question_id < 76){
                if($request->course == "all"){
                    $alumniSAS = Alumni::join('form_sas_answers', 'form_sas_answers.alumni_id', '=', 'tbl_alumni.alumni_id')
                    ->where('question_id', $question->question_id)
                    ->where('tbl_alumni.batch', '=', $request->batch_from)
                    ->where('tbl_alumni.profile_status','=','Complete')
                    ->distinct('tbl_alumni.alumni_id')
                    ->groupBy('form_sas_answers.answer')
                    ->select(DB::raw('answer, COUNT(form_sas_answers.answer) as answerCount'))
                    ->get();
                    
                } else {
                    $alumniSAS = Alumni::join('form_sas_answers', 'form_sas_answers.alumni_id', '=', 'tbl_alumni.alumni_id')
                    ->where('question_id', $question->question_id)
                    ->where('tbl_alumni.batch', '=', $request->batch_from)
                    ->where('tbl_alumni.course_id', '=', $request->course)
                    ->where('tbl_alumni.profile_status','=','Complete')
                    ->distinct('tbl_alumni.alumni_id')
                    ->groupBy('form_sas_answers.answer')
                    ->select(DB::raw('answer, COUNT(form_sas_answers.answer) as answerCount'))
                    ->get();
                }

                foreach($alumniSAS as $answer){
                    $graphedData = new GraphedAnswerData();
                    $graphedData->set_description($answer->answer);
                    $graphedData->set_totalCount($answer->answerCount);
                    array_push($answerArray, $graphedData);
                }

                $graphedQuestionData = new GraphedQuestionList();
                $graphedQuestionData->set_answer($answerArray);
                array_push($questionArray, $graphedQuestionData);
            }
        }
        return $questionArray;
    }

    public function getReportsPage(Request $request){

        // SAS Reports page
        $questionArray = $this->getSASQuestions($request);
        $sasQuestionArray = $this->sasQuestionArray;
        $alumniCount = $this->getAlumniCount($request);

        // Reports info
        $batch = $request->batch_from;
        $course = $request->course;

        // Get Alumni info
        $alumni = new alumniDataModel();
        $alumni->set_age($this->getAlumniAge($request));
        $alumni->set_gender($this->getAlumniGender($request));
        $alumni->set_civilStatus($this->getAlumniCS($request));

        if($request->course == "all")
            $alumni->set_course($this->getAlumniCourse($request));

        return view('pdf.pds_reports', compact(['questionArray', 'sasQuestionArray', 'batch', 'course','alumniCount','alumni']));
    }

    public function getEifReportsPage(Request $request){
        $questionArray = $this->getEIFQuestions($request);
        // Actually EIF rename when free, should include EIF and SAS in naming
        $sasQuestionArray = $this->sasQuestionArray;
        $alumniCount = $this->getAlumniCount($request);

        // Get Alumni info
        $alumni = new alumniDataModel();
        $alumni->set_age($this->getAlumniAge($request));
        $alumni->set_gender($this->getAlumniGender($request));
        $alumni->set_civilStatus($this->getAlumniCS($request));
        if($request->course == "all")
            $alumni->set_course($this->getAlumniCourse($request));

        // Reports info
        $batch = $request->batch_from;
        $course = $request->course;
        return view('pdf.new_eif_reports', compact(['questionArray', 'sasQuestionArray', 'batch', 'course','alumniCount','alumni']));
    }

    public function getTemplate(){
        $pdf = Pdf::loadView('pdf.report_template');
        return $pdf->stream('report_template.pdf');
    }

    public function printSASReports(Request $request){
        $report_type = $request->report_type;
        $data = $request->chartData;
        $course = $request->course;
        $batch = $request->batch;
        $alumniCount = $request->alumniCount;

        $pdf = Pdf::loadView('pdf.report_template',compact(['data','course','batch','report_type', 'alumniCount']));
        return $pdf->stream('charts.pdf');
    }
}
class GraphedAnswerData {
    // Properties
    public $description;
    public $totalCount;

    function set_description($description) {
        $this->description = $description;
    }
    function set_totalCount($totalCount) {
        $this->totalCount = $totalCount;
    }
}

class GraphedQuestionList {
    // Properties
    public $answerList = array();

    function set_answer($answer) {
        array_push($this->answerList, $answer);
    }
}

class QuestionModel{
    public $question_id;
    public $text;

    function set_qId($question_id) {
        $this->question_id = $question_id;
    }
    function set_text($text) {
        $this->text = $text;
    }
} 

class alumniDataModel{
    public $age = array();
    public $gender = array();
    public $civilStatus = array();
    public $course = array();

    function set_age($age) {
        $this->age = $age;
    }
    function set_gender($gender) {
        $this->gender = $gender;
    }
    function set_civilStatus($civilStatus) {
        $this->civilStatus = $civilStatus;
    }
    function set_course($course) {
        $this->course = $course;
    }
} 

class resultModel{
    public $item;
    public $count;
    
    function set_item($item) {
        $this->item = $item;
    }
    function set_count($count) {
        $this->count = $count;
    }
}