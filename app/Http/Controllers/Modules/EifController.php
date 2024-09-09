<?php

namespace App\Http\Controllers\Modules;

use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Forms\Eif\EifAnswers;
use App\Models\Forms\Eif\EifCategories;
use App\Models\Forms\Eif\EifQuestions;
use App\Models\Alumni;
use App\Models\Courses;
use Barryvdh\DomPDF\Facade\Pdf;
use DB;
use PhpOffice\PhpSpreadsheet\Calculation\Category;

class EifController extends Controller
{
    // This is a the new EIF Controller with graphs
    
    public $arrayAnswers = array();
    public $alumniPersonalInfo = array();
    public $get4th=0;

    public $tableConfig=array();
    public $answersAsTable = array();

    public function getQuestionNumbering($question_id){
      $tableIgnore = ['1','2','50','51','52'];
      
        if(!in_array($question_id, $tableIgnore)) {
            $questions = EifQuestions::where('question_id','=', $question_id)->pluck('question_text');
            return $questions;
        } else
            return "null";
    }

    public function getSuggestions(){
        $suggestions = EifAnswers::join('form_eif_questions','form_eif_questions.question_id', '=', 'form_eif_answers.question_id')
        ->where('form_eif_answers.question_id','=',50)
        ->select('answer')
        ->get();
        
        return $suggestions;
    }

    public function getSuggestionsByCourse($request, $course){
      $suggestion = EifAnswers::join('form_eif_questions','form_eif_questions.question_id', '=', 'form_eif_answers.question_id')
              ->join('tbl_alumni','tbl_alumni.alumni_id','=','form_eif_answers.alumni_id')
              ->where('form_eif_answers.question_id','=',"50")
              ->where('tbl_alumni.course_id','=',$course->course_id)
              ->whereRaw('tbl_alumni.batch BETWEEN '.$request->batch_from.' AND '.$request->batch_to.'')
              ->select('answer')
              ->get();
      return $suggestion;
    }
    
    public function getAlumniInfo($fetchData, $request){
      // Get alumni personal data that answered their EIF
      if($request->course == "all"){
        $alumniInfo = EifAnswers::join('tbl_alumni','tbl_alumni.alumni_id','=','form_eif_answers.alumni_id')
        ->whereRaw('tbl_alumni.batch BETWEEN '.$request->batch_from.' AND '.$request->batch_to.'')
        ->where('tbl_alumni.profile_status','=','Complete')
        ->select(DB::raw('tbl_alumni.'.$fetchData.', COUNT(DISTINCT(tbl_alumni.alumni_id)) as count'))
        ->groupBy('tbl_alumni.'.$fetchData)
        ->orderBy('tbl_alumni.'.$fetchData)
        ->get();

        $chartLabels = '';
        $chartData = '';
        $ctr=0;
        foreach($alumniInfo as $a){
          if($ctr>0) $comma = ', ';
          else $comma = '';

          $chartLabels.=$comma.'"'.$a[$fetchData].'"';
          $chartData.=$comma.$a->count;
          $ctr++;
        }

        if($fetchData == "age") {
          $barType = "bar";
          $optionsConfig = '
          "options": {
            "scales": {
              "yAxes": [{
                  "ticks": {
                      "fontSize": 35
                  }
              }],
              "xAxes": [{
                "ticks": {
                    "fontSize": 35
                  }
                }]
            },
          }';
        } else {
          $barType = "outlabeledPie";
          $optionsConfig = '
          "options": {
            "plugins": {
              "legend": false,
              "outlabels": {
                "text": "%l %p",
                "color": "black",
                "stretch": 35,
                "font": {
                  "resizable": true,
                  "minSize": 30,
                  "maxSize": 30
                }
              }
            }
          }';
        }

        $chartConfig = '{
          "type": "'.$barType.'",                               
          "data": {
            "labels": ['.$chartLabels.'],   
            "datasets": [{
              "backgroundColor": ["rgb(255, 55, 132)", "rgb(54, 162, 235)", "rgb(75, 192, 192)", "rgb(247, 120, 37)", 
                  "rgb(153, 102, 255)","rgb(55, 55, 255)","rgb(41, 179, 34)","rgb(201, 38, 38)","rgb(201, 201, 38)",
                  "rgb(176, 60, 214)","rgb(83, 166, 86)","rgb(191, 88, 119)","rgb(184, 126, 75)"],             
              "data": ['.$chartData.']
            }]
          },'.$optionsConfig.'
          
        }';

        //array_push($this->answersAsTable, $alumniInfo);

        $chartUrl = 'https://quickchart.io/chart?w=965&h=650&v=2.9.4&c='.urlencode($chartConfig);
        return $chartUrl;
      } else {
        $alumniInfo = EifAnswers::join('tbl_alumni','tbl_alumni.alumni_id','=','form_eif_answers.alumni_id')
        ->whereRaw('tbl_alumni.batch BETWEEN '.$request->batch_from.' AND '.$request->batch_to.'')
        ->select(DB::raw('tbl_alumni.'.$fetchData.', COUNT(DISTINCT(tbl_alumni.alumni_id))'))
        ->groupBy('tbl_alumni.'.$fetchData)
        ->get();
        return $alumniInfo;
      }
 
    }
    public function getAnswerChartURL($question_id, $request){ 
        $tableIgnore = ['1','2','50','51','52'];

        if($request->course == "all") {
          if(!in_array($question_id, $tableIgnore)) {
            // Gets array answers by question id
              $answers = EifAnswers::join('form_eif_questions','form_eif_questions.question_id', '=', 'form_eif_answers.question_id')
              ->join('tbl_alumni','tbl_alumni.alumni_id','=','form_eif_answers.alumni_id')
              ->where('form_eif_answers.question_id','=',$question_id)
              ->where('answer','!=','N/A')
              ->whereRaw('tbl_alumni.batch BETWEEN '.$request->batch_from.' AND '.$request->batch_to.'')
              ->select('answer', DB::raw('count(answer) as count'))
              ->groupBy('form_eif_answers.question_id','question_text', 'answer')
              ->get();

              $questions = EifQuestions::where('question_id','=', $question_id)->pluck('question_text');
              // get chart url data
                    $chartLabels = '';
                    $chartData = '';
                    $chartColors = '';
                    $ctr=0;
                    foreach($answers as $a){
                      if($ctr>0) $comma = ', ';
                      else {
                        $comma = '';
                      }
                      switch($a->answer){
                        case 1:
                          $chartLabels.=$comma.'"Poor"';
                          $chartColors .= '"rgb(255, 55, 132)",';
                          break;
                        case 2:
                          $chartLabels.=$comma.'"Fair"';
                          $chartColors .= '"rgb(54, 162, 235)",';
                          break;
                        case 3:
                          $chartLabels.=$comma.'"Satisfactory"';
                          $chartColors .= '"rgb(75, 192, 192)",';
                          break;
                        case 4:
                          $chartLabels.=$comma.'"Very Satisfactory"';
                          $chartColors .= '"rgb(247, 120, 37)",';
                          break;
                        case 5:
                          $chartLabels.=$comma.'"Outstanding"';
                          $chartColors .= '"rgb(153, 102, 255)",';
                          break;
                      }
                      
                      $chartData.=$comma.$a->count;
                      $ctr++;
                    }

                    if($chartColors == '') $chartColors = '"rgb(255, 55, 132)", "rgb(54, 162, 235)", "rgb(75, 192, 192)", "rgb(247, 120, 37)", "rgb(153, 102, 255)",';

                    // if($chartLabels == '"Graduation", "Graduation, Academic", "Graduation, Work-related"') {   
                    // } else {
                    //   $chartLabels = '"Poor","Fair","Satisfactory","Very Satisfactory","Outstanding"'; 
                    // }

                    $chartConfig = '{
                      "type": "outlabeledPie",                               
                      "data": {
                        "labels": ['.$chartLabels.'],   
                        "datasets": [{
                          "backgroundColor": ['.$chartColors.',"rgb(157, 245, 245)","rgb(41, 179, 34)","rgb(201, 38, 38)","rgb(201, 201, 38)",
                          "rgb(176, 60, 214)","rgb(83, 166, 86)","rgb(191, 88, 119)","rgb(184, 126, 75)"],                  
                          "data": ['.$chartData.']
                        }]
                      },
                      "options": {
                        "plugins": {
                          "legend": false,
                          "outlabels": {
                            "text": "%l %p",
                            "color": "black",
                            "stretch": 35,
                            "font": {
                              "resizable": true,
                              "minSize": 30,
                              "maxSize": 30
                            }
                          }
                        }
                      }
                    }';

                  if($question_id >= 47 && $question_id <=49) array_push($this->answersAsTable, $answers);

                  //if($question_id==26) dd($chartConfig);


                  $chartUrl = 'https://quickchart.io/chart?w=965&h=650&v=2.9.4&c='.urlencode($chartConfig);
              return $chartUrl;
            } else 
                return "null";
        }
        else{
          if(!in_array($question_id, $tableIgnore)) {
          // Gets array answers by question id
            $answers = EifAnswers::join('form_eif_questions','form_eif_questions.question_id', '=', 'form_eif_answers.question_id')
            ->join('tbl_alumni','tbl_alumni.alumni_id','=','form_eif_answers.alumni_id')
            ->where('form_eif_answers.question_id','=',$question_id)
            ->where('answer','!=','N/A')
            ->where('tbl_alumni.course_id', '=', $request->course)
            ->whereRaw('tbl_alumni.batch BETWEEN '.$request->batch_from.' AND '.$request->batch_to.'')
            ->select('answer', DB::raw('count(answer) as count'))
            ->groupBy('form_eif_answers.question_id','question_text', 'answer')
            ->get();
    
            $questions = EifQuestions::where('question_id','=', $question_id)->pluck('question_text');
              // get chart url data
              $chartLabels = '';
              $chartData = '';
              $chartColors = '';
              $ctr=0;
              foreach($answers as $a){
                if($ctr>0) $comma = ', ';
                else {
                  $comma = '';
                }
                switch($a->answer){
                  case 1:
                    $chartLabels.=$comma.'"Poor"';
                    $chartColors .= '"rgb(255, 55, 132)",';
                    break;
                  case 2:
                    $chartLabels.=$comma.'"Fair"';
                    $chartColors .= '"rgb(54, 162, 235)",';
                    break;
                  case 3:
                    $chartLabels.=$comma.'"Satisfactory"';
                    $chartColors .= '"rgb(75, 192, 192)",';
                    break;
                  case 4:
                    $chartLabels.=$comma.'"Very Satisfactory"';
                    $chartColors .= '"rgb(247, 120, 37)",';
                    break;
                  case 5:
                    $chartLabels.=$comma.'"Outstanding"';
                    $chartColors .= '"rgb(153, 102, 255)",';
                    break;
                }
                
                $chartData.=$comma.$a->count;
                $ctr++;
              }

              if($chartColors == '') $chartColors = '"rgb(255, 55, 132)", "rgb(54, 162, 235)", "rgb(75, 192, 192)", "rgb(247, 120, 37)", "rgb(153, 102, 255)",';

              // if($chartLabels == '"Graduation", "Graduation, Academic", "Graduation, Work-related"') {   
              // } else {
              //   $chartLabels = '"Poor","Fair","Satisfactory","Very Satisfactory","Outstanding"'; 
              // }

              $chartConfig = '{
                "type": "outlabeledPie",                               
                "data": {
                  "labels": ['.$chartLabels.'],   
                  "datasets": [{
                    "backgroundColor": ['.$chartColors.',"rgb(157, 245, 245)","rgb(41, 179, 34)","rgb(201, 38, 38)","rgb(201, 201, 38)",
                    "rgb(176, 60, 214)","rgb(83, 166, 86)","rgb(191, 88, 119)","rgb(184, 126, 75)"],                  
                    "data": ['.$chartData.']
                  }]
                },
                "options": {
                  "plugins": {
                    "legend": false,
                    "outlabels": {
                      "text": "%l %p",
                      "color": "black",
                      "stretch": 35,
                      "font": {
                        "resizable": true,
                        "minSize": 30,
                        "maxSize": 30
                      }
                    }
                  }
                }
              }';

                  $chartUrl = 'https://quickchart.io/chart?w=965&h=650&v=2.9.4&c='.urlencode($chartConfig);
            return $chartUrl;
          } else 
              return "null";
        }
      }

    public function EIFtoPDF(Request $request) {
        $courses = Courses::all();
        $questions = EifQuestions::orderBy('question_id')->get();
        $questionArray = array();
        $suggestionsByCourse = array();

        
        //Get alumni personal details
        $fetchData = array('sex','age','civil_status','course_id');
        foreach($fetchData as $data){
          array_push($this->alumniPersonalInfo, $this->getAlumniInfo($data, $request));
        }

        $alumniPersonalInfo = $this->alumniPersonalInfo;

        // Count all alumni who have answered their EIF
        if($request->course == "all"){
          $answerCount = EifAnswers::join('form_eif_questions','form_eif_questions.question_id', '=', 'form_eif_answers.question_id')
              ->join('tbl_alumni','tbl_alumni.alumni_id','=','form_eif_answers.alumni_id')
              ->where('answer','!=','N/A')
              ->whereRaw('tbl_alumni.batch BETWEEN '.$request->batch_from.' AND '.$request->batch_to.'')
              ->groupBy('form_eif_answers.question_id','question_text', 'answer')
              ->count();
        } else {
          $answerCount = EifAnswers::join('form_eif_questions','form_eif_questions.question_id', '=', 'form_eif_answers.question_id')
              ->join('tbl_alumni','tbl_alumni.alumni_id','=','form_eif_answers.alumni_id')
              ->where('answer','!=','N/A')
              ->where('tbl_alumni.course_id','=',$request->course)
              ->whereRaw('tbl_alumni.batch BETWEEN '.$request->batch_from.' AND '.$request->batch_to.'')
              ->groupBy('form_eif_answers.question_id','question_text', 'answer')
              ->count();
        }

        // Get suggestions by course
        foreach($courses as $course) {
          array_push($suggestionsByCourse, $this->getSuggestionsByCourse($request, $course));
        }
        
        // Get EIF answers as graphs
        foreach($questions as $question){  
            if($this->getAnswerChartURL($question->question_id,$request) != "null") {
              array_push($this->arrayAnswers, $this->getAnswerChartURL($question->question_id,$request));
            }
            if($this->getQuestionNumbering($question->question_id,$request) != "null") {
              array_push($questionArray,$this->getQuestionNumbering($question->question_id,$request));
            }
            $this->get4th++;
        }
        $arrayanswer = $this->arrayAnswers;

        dd($questionArray);

        $suggestions = $this->getSuggestions();
        $batch_from = $request->batch_from;
        $batch_to = $request->batch_to;

        $pdf = app('dompdf.wrapper');
        $pdf->loadview('pdf.eif_reports', compact(['questionArray','arrayanswer','suggestions','batch_from','batch_to','answerCount','suggestionsByCourse', 'courses','alumniPersonalInfo','fetchData']));
        return $pdf->stream();
    }
}
