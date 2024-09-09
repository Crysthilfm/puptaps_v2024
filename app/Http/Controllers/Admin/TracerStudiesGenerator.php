<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Alumni;
use App\Models\Tracer\TracerQuestions;
use App\Models\Tracer\TracerAnswers;
use DB;
use Illuminate\Support\Facades\Log;
use App\Models\TracerVersion;

class TracerStudiesGenerator extends Controller
{
  public $arrayAnswers = array();

    public function export_TracerStudies(){
        //Empty Method
    }

    public function getQuestionNumbering($question_id){
      // gets lables for charts

      $tableIgnore = ['4','6','8','7','9','10','11','12','13','15','16','17','18','19','20','26'];
      
      if(!in_array($question_id, $tableIgnore)) {
        $questions = TracerQuestions::where('question_id','=', $question_id)->pluck('question_text');
        return $questions;
      } else
        return "null";
    }

    public function getChartDescription ($question_id, $request, $totalRespondentsThisBatch, $course) {
      $description = "";

      // Removes already set up tables
      $tableIgnore = ['4','6','8','7','9','10','11','12','13','15','16','17','18','19','20','26'];

      if($request->course == 'all') {
        if(!in_array($question_id, $tableIgnore)) {
          $answers = TracerAnswers::join('tbl_tracer_questions','tbl_tracer_questions.question_id', '=', 'tbl_tracer_answers.question_id')
          ->join('tbl_alumni','tbl_alumni.alumni_id','=','tbl_tracer_answers.alumni_id')
          ->where('tbl_tracer_answers.question_id','=',$question_id)
          ->whereRaw('tbl_alumni.batch BETWEEN '.$request->batch_from.' AND '.$request->batch_to.'')
          //->where('answer','!=','N/A')
          ->select('answer', DB::raw('count(answer) as count'))
          ->groupBy('tbl_tracer_questions.question_id','question_text', 'answer')
          ->orderBy('count', 'DESC')
          ->get();
        } else  
            return "null";
        }
        else{
            if(!in_array($question_id, $tableIgnore)) {
              $answers = TracerAnswers::join('tbl_tracer_questions','tbl_tracer_questions.question_id', '=', 'tbl_tracer_answers.question_id')
              ->join('tbl_alumni','tbl_alumni.alumni_id','=','tbl_tracer_answers.alumni_id')
              ->where('tbl_tracer_answers.question_id','=',$question_id)
              ->where('tbl_alumni.course_id','=',$request->course)
              ->whereRaw('tbl_alumni.batch BETWEEN '.$request->batch_from.' AND '.$request->batch_to.'')
              //->where('answer','!=','N/A')
              ->select('answer', DB::raw('count(answer) as count'))
              ->groupBy('tbl_tracer_questions.question_id','question_text', 'answer')
              ->orderBy('count', 'DESC')
              ->get();
            } else  
                return "null";
        }
        if($answers){
            if($answers[0]->count > ($totalRespondentsThisBatch/2)) $wordRepresentation = "Majority";
            else if($answers[0]->count > ($totalRespondentsThisBatch/3)) $wordRepresentation = "Most";
            else $wordRepresentation = "Some";
            
            $description = "According to the survey, ".$wordRepresentation." of the ".$course." alumni answered '".$answers[0]->answer."' [".$answers[0]->count."]";
            return $description;
        } else {
            return "Not enough data";
        }
    }

    public function getAnswerChartURL($question_id, $request){
      // Removes already set up tables
      $tableIgnore = ['4','6','8','7','9','10','11','12','13','15','16','17','18','19','20','26'];
      
      // Gets array answers by question id
      if($request->course == 'all') {
          if(!in_array($question_id, $tableIgnore)) {
            $answers = TracerAnswers::join('tbl_tracer_questions','tbl_tracer_questions.question_id', '=', 'tbl_tracer_answers.question_id')
            ->join('tbl_alumni','tbl_alumni.alumni_id','=','tbl_tracer_answers.alumni_id')
            ->where('tbl_tracer_answers.question_id','=',$question_id)
            ->whereRaw('tbl_alumni.batch BETWEEN '.$request->batch_from.' AND '.$request->batch_to.'')
            //->where('answer','!=','N/A')
            ->select('answer', DB::raw('count(answer) as count'))
            ->groupBy('tbl_tracer_questions.question_id','question_text', 'answer')
            ->get();

            $questions = TracerQuestions::where('question_id','=', $question_id)->pluck('question_text');
            // get chart url data
                  $chartLabels = '';
                  $chartData = '';
                  $ctr=0;
                  foreach($answers as $a){
                    if($ctr>0) $comma = ', ';
                    else $comma = '';
                    $chartLabels.=$comma.'"'.$a->answer.'"';
                    $chartData.=$comma.$a->count;
                    $ctr++;
                  }
                  $chartConfig = '{
                    "type": "pie",
                    "data": {
                      "labels": ['.$chartLabels.'],
                      "datasets": [{
                        "label": " Test ",
                        "data": ['.$chartData.'],
                        backgroundColor: ["rgb(255, 55, 132)", "rgb(54, 162, 235)", "rgb(75, 192, 192)", "rgb(247, 120, 37)", 
                                          "rgb(153, 102, 255)","rgb(55, 55, 255)","rgb(41, 179, 34)","rgb(201, 38, 38)","rgb(201, 201, 38)",
                                          "rgb(176, 60, 214)","rgb(83, 166, 86)","rgb(191, 88, 119)","rgb(184, 126, 75)"],
                      }]
                    },
                    "options": {
                      "legend": {
                        "position": "right",
                        "labels": {
                          "fontSize": 15,
                          "fontColor": "rgb(0,0,0)"
                        }
                      },
                      "plugins": {
                        "datalabels": {
                          "color": ["rgb(0,0,0)","rgb(0,0,0)","rgb(0,0,0)","rgb(0,0,0)","rgb(0,0,0)","rgb(0,0,0)","rgb(0,0,0)"],
                          "font": {
                            "size": 18,
                          }
                        }
                      }
                    }
                  }';
                  $chartUrl = 'https://quickchart.io/chart?w=500&h=300&c=' . urlencode($chartConfig);
            return $chartUrl;
          } else  
              return "null";
      }
      else{
          if(!in_array($question_id, $tableIgnore)) {
            $answers = TracerAnswers::join('tbl_tracer_questions','tbl_tracer_questions.question_id', '=', 'tbl_tracer_answers.question_id')
            ->join('tbl_alumni','tbl_alumni.alumni_id','=','tbl_tracer_answers.alumni_id')
            ->where('tbl_tracer_answers.question_id','=',$question_id)
            ->where('tbl_alumni.course_id','=',$request->course)
            ->whereRaw('tbl_alumni.batch BETWEEN '.$request->batch_from.' AND '.$request->batch_to.'')
            //->where('answer','!=','N/A')
            ->select('answer', DB::raw('count(answer) as count'))
            ->groupBy('tbl_tracer_questions.question_id','question_text', 'answer')
            ->get();

            $questions = TracerQuestions::where('question_id','=', $question_id)->pluck('question_text');
            // get chart url data
                  $chartLabels = '';
                  $chartData = '';
                  $ctr=0;
                  foreach($answers as $a){
                    if($ctr>0) $comma = ', ';
                    else $comma = '';
                    $chartLabels.=$comma.'"'.$a->answer.'"';
                    $chartData.=$comma.$a->count;
                    $ctr++;
                  }
                  $chartConfig = '{
                    "type": "pie",
                    "data": {
                      "labels": ['.$chartLabels.'],
                      "datasets": [{
                        "label": " Test ",
                        "data": ['.$chartData.'],
                        backgroundColor: ["rgb(255, 55, 132)", "rgb(54, 162, 235)", "rgb(75, 192, 192)", "rgb(247, 120, 37)", 
                                          "rgb(153, 102, 255)","rgb(55, 55, 255)","rgb(41, 179, 34)","rgb(201, 38, 38)","rgb(201, 201, 38)",
                                          "rgb(176, 60, 214)","rgb(83, 166, 86)","rgb(191, 88, 119)","rgb(184, 126, 75)"],
                      }]
                    },
                    "options": {
                      "legend": {
                        "position": "right",
                        "labels": {
                          "fontSize": 15,
                          "fontColor": "rgb(0,0,0)"
                        }
                      },
                      "plugins": {
                        "datalabels": {
                          "color": ["rgb(0,0,0)","rgb(0,0,0)","rgb(0,0,0)","rgb(0,0,0)","rgb(0,0,0)","rgb(0,0,0)","rgb(0,0,0)"],
                          "font": {
                            "size": 18,
                            "color": "black"
                          }
                        }
                      }
                    }
                  }';
                  $chartUrl = 'https://quickchart.io/chart?w=500&h=300&c=' . urlencode($chartConfig);
            return $chartUrl;
          } else  
              return "null";
      }
    }

    public function getDataFromForm (Request $request){
      $batch_from = $request->batch_from; 
      $batch_to = $request->batch_to; 
      $course = $request->course;
      // Get all questions
      $questions = TracerQuestions::get();
      $questionArray = array();
      $descriptionArray = array();
      

      // Get Current Version
      $getTracerVersion = Alumni::join('tbl_tracer_answers', 'tbl_alumni.alumni_id', '=', 'tbl_tracer_answers.alumni_id')
          ->whereRaw('tbl_alumni.batch BETWEEN '.$batch_from.' AND '.$batch_to.'')
          ->select(DB::raw('tbl_tracer_answers.tracer_version_id'))
          ->first();
      $tracerVersionName = TracerVersion::where('tracer_version_id', '=', $getTracerVersion->tracer_version_id)->select('tracer_version_name')->first();

      // ========================= Count total answeree ============================================================================================================================
      if($course == 'all'){
        $totalAlumniThisBatch = Alumni::whereRaw('tbl_alumni.batch BETWEEN '.$batch_from.' AND '.$batch_to.'')
          ->select(DB::raw('count(tbl_alumni.alumni_id) as alumniCount'))
          ->count();
        $totalRespondentsThisBatch = Alumni::join('tbl_tracer_answers', 'tbl_alumni.alumni_id', '=', 'tbl_tracer_answers.alumni_id')
          ->where('tbl_tracer_answers.question_id', '=', 3)
          ->whereRaw('tbl_alumni.batch BETWEEN '.$batch_from.' AND '.$batch_to.'')
          ->select(DB::raw('count(tbl_alumni.alumni_id) as alumniCount'))
          ->count();
      } else {
        $totalAlumniThisBatch = Alumni::whereRaw('tbl_alumni.batch BETWEEN '.$batch_from.' AND '.$batch_to.'')
          ->where('tbl_alumni.course_id', '=', $course)
          ->select(DB::raw('count(tbl_alumni.alumni_id) as alumniCount'))
          ->count();
        $totalRespondentsThisBatch = Alumni::join('tbl_tracer_answers', 'tbl_alumni.alumni_id', '=', 'tbl_tracer_answers.alumni_id')
          ->where('tbl_tracer_answers.question_id', '=', 3)
          ->where('tbl_alumni.course_id', '=', $course)
          ->whereRaw('tbl_alumni.batch BETWEEN '.$batch_from.' AND '.$batch_to.'')
          ->select(DB::raw('count(tbl_alumni.alumni_id) as alumniCount'))
          ->count();
      }
      
      // ========================= END Count total answeree ============================================================================================================================

      foreach($questions as $question){
        if($this->getAnswerChartURL($question->question_id, $request) != "null") {
          array_push($this->arrayAnswers, $this->getAnswerChartURL($question->question_id, $request));
          array_push($descriptionArray, $this->getChartDescription($question->question_id, $request, $totalRespondentsThisBatch, $course));
        }
        if($this->getQuestionNumbering($question->question_id, $request) != "null") {
          array_push($questionArray,$this->getQuestionNumbering($question->question_id,$request));
        }
        
      }
        $arrayanswer = $this->arrayAnswers;

        $ctr=0;
        $course_query='';

        if($course=='all')
        $course_query='';
        else
        $course_query = "AND tbl_alumni.course_id = '".$course."'";


        $queryForHighest = 'SELECT
            "6 months" as Months,
            COUNT(*) as Count
            FROM
            `tbl_alumni`
            INNER JOIN `tbl_tracer_answers` ON `tbl_alumni`.`alumni_id` = `tbl_tracer_answers`.`alumni_id`
            WHERE 
                tbl_tracer_answers.question_id = 8
            AND
                tbl_alumni.batch BETWEEN '.$batch_from.' AND '.$batch_to.'
            '.$course_query.'
            AND
                TIMESTAMPDIFF(
                    MONTH,
                    tbl_tracer_answers.answer,
                    tbl_alumni.graduate_year
                ) < 6
            UNION ALL
            SELECT
                "6 months to 1 year" as Months,
            COUNT(*) as Count
            FROM
            `tbl_alumni`
            INNER JOIN `tbl_tracer_answers` ON `tbl_alumni`.`alumni_id` = `tbl_tracer_answers`.`alumni_id`
            WHERE 
                tbl_tracer_answers.question_id = 8
            AND
            tbl_alumni.batch BETWEEN '.$batch_from.' AND '.$batch_to.'
            '.$course_query.'
            AND
                TIMESTAMPDIFF(
                    MONTH,
                    tbl_tracer_answers.answer,
                    tbl_alumni.graduate_year
                ) BETWEEN 6 AND 12
            UNION ALL
            SELECT
                "1 to 2 years" as Months,
            COUNT(*) as Count
            FROM
            `tbl_alumni`
            INNER JOIN `tbl_tracer_answers` ON `tbl_alumni`.`alumni_id` = `tbl_tracer_answers`.`alumni_id`
            WHERE 
                tbl_tracer_answers.question_id = 8
            AND
            tbl_alumni.batch BETWEEN '.$batch_from.' AND '.$batch_to.'
            '.$course_query.'
            AND
                TIMESTAMPDIFF(
                    MONTH,
                    tbl_tracer_answers.answer,
                    tbl_alumni.graduate_year
                ) BETWEEN 12 AND 24
            UNION ALL
            SELECT
                "More than 2 years" as Months,
            COUNT(*) as Count
            FROM
            `tbl_alumni`
            INNER JOIN `tbl_tracer_answers` ON `tbl_alumni`.`alumni_id` = `tbl_tracer_answers`.`alumni_id`
            WHERE 
                tbl_tracer_answers.question_id = 8
            AND
            tbl_alumni.batch BETWEEN '.$batch_from.' AND '.$batch_to.'
            '.$course_query.'
            AND
                TIMESTAMPDIFF(
                    MONTH,
                    tbl_tracer_answers.answer,
                    tbl_alumni.graduate_year
                ) > 24
            ORDER BY Count DESC
            ';        
      $longquery = 'SELECT
                "6 months" as Months,
            COUNT(*) as Count
            FROM
            `tbl_alumni`
            INNER JOIN `tbl_tracer_answers` ON `tbl_alumni`.`alumni_id` = `tbl_tracer_answers`.`alumni_id`
            WHERE 
                tbl_tracer_answers.question_id = 8
            AND
                tbl_alumni.batch BETWEEN '.$batch_from.' AND '.$batch_to.'
            '.$course_query.'
            AND
                TIMESTAMPDIFF(
                    MONTH,
                    tbl_tracer_answers.answer,
                    tbl_alumni.graduate_year
                ) < 6
            UNION ALL
            SELECT
                "6 months to 1 year" as Months,
            COUNT(*) as Count
            FROM
            `tbl_alumni`
            INNER JOIN `tbl_tracer_answers` ON `tbl_alumni`.`alumni_id` = `tbl_tracer_answers`.`alumni_id`
            WHERE 
                tbl_tracer_answers.question_id = 8
            AND
              tbl_alumni.batch BETWEEN '.$batch_from.' AND '.$batch_to.'
            '.$course_query.'
            AND
                TIMESTAMPDIFF(
                    MONTH,
                    tbl_tracer_answers.answer,
                    tbl_alumni.graduate_year
                ) BETWEEN 6 AND 12
            UNION ALL
            SELECT
                "1 to 2 years" as Months,
            COUNT(*) as Count
            FROM
            `tbl_alumni`
            INNER JOIN `tbl_tracer_answers` ON `tbl_alumni`.`alumni_id` = `tbl_tracer_answers`.`alumni_id`
            WHERE 
                tbl_tracer_answers.question_id = 8
            AND
              tbl_alumni.batch BETWEEN '.$batch_from.' AND '.$batch_to.'
            '.$course_query.'
            AND
                TIMESTAMPDIFF(
                    MONTH,
                    tbl_tracer_answers.answer,
                    tbl_alumni.graduate_year
                ) BETWEEN 12 AND 24
            UNION ALL
            SELECT
                "More than 2 years" as Months,
            COUNT(*) as Count
            FROM
            `tbl_alumni`
            INNER JOIN `tbl_tracer_answers` ON `tbl_alumni`.`alumni_id` = `tbl_tracer_answers`.`alumni_id`
            WHERE 
                tbl_tracer_answers.question_id = 8
            AND
              tbl_alumni.batch BETWEEN '.$batch_from.' AND '.$batch_to.'
               '.$course_query.'
            AND
                TIMESTAMPDIFF(
                    MONTH,
                    tbl_tracer_answers.answer,
                    tbl_alumni.graduate_year
                ) > 24
            ';

        
        
        // ================ START Graduation to Employment =========================================================================================================
        $chartLabels = '';
        $chartData = '';
        $graduateToEmployment = DB::select($longquery);
        $graduateToEmploymentOrdered = DB::select($queryForHighest);
        
        if($graduateToEmploymentOrdered[0]->Count > ($totalRespondentsThisBatch/2)) $wordRepresentation = "Majority";
        else if($graduateToEmploymentOrdered[0]->Count > ($totalRespondentsThisBatch/3)) $wordRepresentation = "Most";
        else $wordRepresentation = "Some";

        $graduateToEmploymentDescription = "According to the data gathered from the Alumni Tracer, ".$wordRepresentation." of the ".$course." respondents takes ".$graduateToEmploymentOrdered[0]->Months." before landing their first job.";

        $ctr=0;
        foreach($graduateToEmployment as $a){
          if($ctr>0) $comma = ', ';
          else $comma = '';
          $chartLabels.=$comma.'"'.$a->Months.'"';
          $chartData.=$comma.$a->Count;
          $ctr++;
        }
        $chartConfig = '{
          "type": "bar",
          "data": {
            "labels": ['.$chartLabels.'],
            "datasets": [{
              "label": "Span of Time before they land to their first job",
              "data": ['.$chartData.'],
              backgroundColor: [
                "rgb(255, 55, 132)", "rgb(54, 162, 235)", "rgb(75, 192, 192)", "rgb(247, 120, 37)", 
                "rgb(153, 102, 255)","rgb(55, 55, 255)","rgb(41, 179, 34)","rgb(201, 38, 38)","rgb(201, 201, 38)",
                "rgb(176, 60, 214)","rgb(83, 166, 86)","rgb(191, 88, 119)","rgb(184, 126, 75)"
              ],
              "options": {
                scales: {
                    xAxes: [{
                        ticks: {
                            fontSize: 18
                        }
                    }],
                    yAxes: [{
                        ticks: {
                            fontSize: 18
                        }
                    }],
                }
              }
            }]
          }
        }';
        $chartUrl_GradToEmp = 'https://quickchart.io/chart?w=500&h=300&c=' . urlencode($chartConfig);
        // ================ END Graduation to Employment =========================================================================================================

        // ================ START Chart EmploymentType =========================================================================================================
        $chartLabels = '';
        $chartData = '';

        if($course == 'all'){
          $employmentType = Alumni::join('tbl_tracer_answers', 'tbl_alumni.alumni_id', '=', 'tbl_tracer_answers.alumni_id')
          ->where('tbl_tracer_answers.question_id', '=', 10)
          ->whereRaw('tbl_alumni.batch BETWEEN '.$batch_from.' AND '.$batch_to.'')
          //->where('tbl_tracer_answers.answer', '!=', 'UNEMPLOYED') 
          ->select('tbl_tracer_answers.answer as answers', DB::raw('count(tbl_alumni.alumni_id) as alumniCount'))
          ->groupBy('answers')
          ->get();
          $topEmploymentType = Alumni::join('tbl_tracer_answers', 'tbl_alumni.alumni_id', '=', 'tbl_tracer_answers.alumni_id')
          ->where('tbl_tracer_answers.question_id', '=', 10)
          //->where('tbl_tracer_answers.answer', '!=', 'UNEMPLOYED')
          ->whereRaw('tbl_alumni.batch BETWEEN '.$batch_from.' AND '.$batch_to.'')
          ->select('tbl_tracer_answers.answer as answers', DB::raw('count(tbl_alumni.alumni_id) as alumniCount'))
          ->groupBy('answers')
          ->orderBy('alumniCount', 'DESC')
          ->limit(3)
          ->get();
        } else {
          $employmentType = Alumni::join('tbl_tracer_answers', 'tbl_alumni.alumni_id', '=', 'tbl_tracer_answers.alumni_id')
          ->where('tbl_tracer_answers.question_id', '=', 10)
          ->where('tbl_alumni.course_id', '=', $course)
          //->where('tbl_tracer_answers.answer', '!=', 'UNEMPLOYED')
          ->whereRaw('tbl_alumni.batch BETWEEN '.$batch_from.' AND '.$batch_to.'')
          ->select('tbl_tracer_answers.answer as answers', DB::raw('count(tbl_alumni.alumni_id) as alumniCount'))
          ->groupBy('answers')
          ->get();
          $topEmploymentType = Alumni::join('tbl_tracer_answers', 'tbl_alumni.alumni_id', '=', 'tbl_tracer_answers.alumni_id')
          ->where('tbl_tracer_answers.question_id', '=', 10)
          ->where('tbl_alumni.course_id', '=', $course)
          //->where('tbl_tracer_answers.answer', '!=', 'UNEMPLOYED')
          ->whereRaw('tbl_alumni.batch BETWEEN '.$batch_from.' AND '.$batch_to.'')
          ->select('tbl_tracer_answers.answer as answers', DB::raw('count(tbl_alumni.alumni_id) as alumniCount'))
          ->groupBy('answers')
          ->orderBy('alumniCount', 'DESC')
          ->limit(3)
          ->get();
        }

        if($topEmploymentType[0]['alumniCount']>($totalRespondentsThisBatch/2)) $wordRepresentation = "Majority";
        else if($topEmploymentType[0]['alumniCount']>($totalRespondentsThisBatch/3)) $wordRepresentation = "Most";
        else $wordRepresentation = "Some";

        if($topEmploymentType[0]['answers']=="UNEMPLOYED") $topEmploymentText = "unemployed [".$topEmploymentType[0]['alumniCount']."]";
        else $topEmploymentText = "a ".$topEmploymentType[0]['answers']." employee at their respective companies [".$topEmploymentType[0]['alumniCount']."]";

        $employmentTypeDescription = "According to the data gathered from the Alumni Tracer, ".$wordRepresentation." of the ".$course." respondents are ".$topEmploymentText;
        // dd($employmentTypeDescription);

        $ctr=0;
        foreach($employmentType as $a){
          if($ctr>0) $comma = ', ';
          else $comma = '';
          $chartLabels.=$comma.'"'.$a->answers.'"';
          $chartData.=$comma.$a->alumniCount;
          $ctr++;
        }
        $chartConfig = '{
          "type": "pie",
          "data": {
            "labels": ['.$chartLabels.'],
            "datasets": [{
              "label": "EmploymentType",
              "data": ['.$chartData.'],
              backgroundColor: [
                "rgb(255, 55, 132)", "rgb(54, 162, 235)", "rgb(75, 192, 192)", "rgb(247, 120, 37)", 
                "rgb(153, 102, 255)","rgb(55, 55, 255)","rgb(41, 179, 34)","rgb(201, 38, 38)","rgb(201, 201, 38)",
                "rgb(176, 60, 214)","rgb(83, 166, 86)","rgb(191, 88, 119)","rgb(184, 126, 75)"
              ],
            }]
          },
          "options": {
            "legend": {
              "position": "right",
              "labels": {
                "fontSize": 15,
                "fontColor": "rgb(0,0,0)"
              }
            },
            "plugins": {
              "datalabels": {
                "color": ["rgb(0,0,0)","rgb(0,0,0)","rgb(0,0,0)","rgb(0,0,0)","rgb(0,0,0)","rgb(0,0,0)","rgb(0,0,0)"],
                "font": {
                  "size": 18,
                  "color": "black"
                }
              }
            }
          }
        }';
        $chartUrl_EmpType = 'https://quickchart.io/chart?w=500&h=300&c=' . urlencode($chartConfig);
        // ================ End Chart EmploymentType =========================================================================================================
        
        // ================ START Salary =========================================================================================================
        $chartLabels = '';
        $chartData = '';

        if($course=='all'){
          $salaryRank = Alumni::join('tbl_tracer_answers', 'tbl_alumni.alumni_id', '=', 'tbl_tracer_answers.alumni_id')
              ->where('tbl_tracer_answers.question_id', '=', 11)
              ->where('tbl_tracer_answers.answer', '!=', 'UNEMPLOYED')
              ->where('tbl_tracer_answers.answer', '!=', 'unemployed')
              ->whereRaw('tbl_alumni.batch BETWEEN '.$batch_from.' AND '.$batch_to.'')
              ->select('tbl_tracer_answers.answer as Salary', DB::raw('count(tbl_alumni.alumni_id) as alumniCount'))
              ->orderByRaw('alumniCount DESC')
              ->groupBy('Salary')
              ->get();
        } else {
          $salaryRank = Alumni::join('tbl_tracer_answers', 'tbl_alumni.alumni_id', '=', 'tbl_tracer_answers.alumni_id')
              ->where('tbl_tracer_answers.question_id', '=', 11)
              ->where('tbl_alumni.course_id', '=', $course)
              ->where('tbl_tracer_answers.answer', '!=', 'UNEMPLOYED')
              ->where('tbl_tracer_answers.answer', '!=', 'unemployed')
              ->whereRaw('tbl_alumni.batch BETWEEN '.$batch_from.' AND '.$batch_to.'')
              ->select('tbl_tracer_answers.answer as Salary', DB::raw('count(tbl_alumni.alumni_id) as alumniCount'))
              ->orderByRaw('alumniCount DESC')
              ->groupBy('Salary')
              ->get();
        }

        if($salaryRank[0]['alumniCount']>($totalRespondentsThisBatch/2)) $wordRepresentation = "Majority";
        else if($salaryRank[0]['alumniCount']>($totalRespondentsThisBatch/3)) $wordRepresentation = "Most";
        else $wordRepresentation = "Some";

        $topSalary = '';
        $topSalary = array();
        $topSalary = & $salaryRank[0];

        $salaryRankDescription = "According to the data gathered from the Alumni Tracer, ".$wordRepresentation." of the ".$course." respondents have ".$topSalary->Salary." as their salary";

            $ctr=0;
            foreach($salaryRank as $a){
              if($ctr>0) $comma = ', ';
              else $comma = '';
              $chartLabels.=$comma.'"'.$a->Salary.'"';
              $chartData.=$comma.$a->alumniCount;
              $ctr++;
            }
            $chartConfig = '{
              "type": "bar",
              "data": {
                "labels": ['.$chartLabels.'],
                "datasets": [{
                  "label": "Salary",
                  "data": ['.$chartData.'],
                  backgroundColor: [
                    "rgb(255, 55, 132)", "rgb(54, 162, 235)", "rgb(75, 192, 192)", "rgb(247, 120, 37)", 
                    "rgb(153, 102, 255)","rgb(55, 55, 255)","rgb(41, 179, 34)","rgb(201, 38, 38)","rgb(201, 201, 38)",
                    "rgb(176, 60, 214)","rgb(83, 166, 86)","rgb(191, 88, 119)","rgb(184, 126, 75)"
                  ],
                }]
              }
            }';
            $chartUrl_Salary = 'https://quickchart.io/chart?w=500&h=300&c=' . urlencode($chartConfig);

        // ================ END Salary

        
        
        // ========================= Company Employed in ======================
        if($course=='all'){
        $companyName = Alumni::join('tbl_tracer_answers', 'tbl_alumni.alumni_id', '=', 'tbl_tracer_answers.alumni_id')
          ->where('tbl_tracer_answers.question_id', '=', 7)
          ->where('tbl_tracer_answers.answer', '!=', 'UNEMPLOYED')
          ->whereRaw('tbl_alumni.batch BETWEEN '.$batch_from.' AND '.$batch_to.'')
          ->select(DB::raw('tbl_tracer_answers.answer as Company, count(tbl_alumni.alumni_id) as alumniCount'))
          ->orderBy('alumniCount', 'DESC')
          ->groupBy('Company')
          ->limit(10)
          ->get();
        } else {
          $companyName = Alumni::join('tbl_tracer_answers', 'tbl_alumni.alumni_id', '=', 'tbl_tracer_answers.alumni_id')
          ->where('tbl_tracer_answers.question_id', '=', 7)
          ->where('tbl_tracer_answers.answer', '!=', 'UNEMPLOYED')
          ->where('tbl_alumni.course_id', '=', $course)
          ->whereRaw('tbl_alumni.batch BETWEEN '.$batch_from.' AND '.$batch_to.'')
          ->select(DB::raw('tbl_tracer_answers.answer as Company, count(tbl_alumni.alumni_id) as alumniCount'))
          ->orderBy('alumniCount', 'DESC')
          ->groupBy('Company')
          ->limit(10)
          ->get();
        }

          $ctr=0;
          foreach($companyName as $a){
            if($ctr>0) $comma = ', ';
            else $comma = '';
            $chartLabels.=$comma.'"'.$a->Company.'"';
            $chartData.=$comma.$a->alumniCount;
            $ctr++;
          }
          $chartConfig = '{
            "type": "bar",
            "data": {
              "labels": ['.$chartLabels.'],
              "datasets": [{
                "label": "EmploymentType",
                "data": ['.$chartData.'],
                backgroundColor: [
                  "rgb(255, 55, 132)", "rgb(54, 162, 235)", "rgb(75, 192, 192)", "rgb(247, 120, 37)", 
                  "rgb(153, 102, 255)","rgb(55, 55, 255)","rgb(41, 179, 34)","rgb(201, 38, 38)","rgb(201, 201, 38)",
                  "rgb(176, 60, 214)","rgb(83, 166, 86)","rgb(191, 88, 119)","rgb(184, 126, 75)"
                ],
              }]
            }
          }';
      $chartUrl_Company = 'https://quickchart.io/chart?w=500&h=300&c=' . urlencode($chartConfig);

        // ========================= END Company EMployed =======================

        // ========================= First Job After College ====================
        $chartLabels = '';
        $chartData = '';

        if($course == 'all'){
          $firstJobAfterCollege = Alumni::join('tbl_tracer_answers', 'tbl_alumni.alumni_id', '=', 'tbl_tracer_answers.alumni_id')
              ->where('tbl_tracer_answers.question_id', '=', 6)
              ->where('tbl_tracer_answers.answer', '!=', 'UNEMPLOYED')
              ->whereRaw('tbl_alumni.batch BETWEEN '.$batch_from.' AND '.$batch_to.'')
              ->select(DB::raw('tbl_tracer_answers.answer as Position, count(tbl_alumni.alumni_id) as alumniCount'))
              ->groupBy('Position')
              ->orderBy('alumniCount', 'DESC')
              ->limit(10)
              ->get();
        } else {
          $firstJobAfterCollege = Alumni::join('tbl_tracer_answers', 'tbl_alumni.alumni_id', '=', 'tbl_tracer_answers.alumni_id')
              ->where('tbl_tracer_answers.question_id', '=', 6)
              ->where('tbl_tracer_answers.answer', '!=', 'UNEMPLOYED')
              ->where('tbl_alumni.course_id', '=', $course)
              ->whereRaw('tbl_alumni.batch BETWEEN '.$batch_from.' AND '.$batch_to.'')
              ->select(DB::raw('tbl_tracer_answers.answer as Position, count(tbl_alumni.alumni_id) as alumniCount'))
              ->groupBy('Position')
              ->orderBy('alumniCount', 'DESC')
              ->limit(10)
              ->get();
        }

        $ctr=0;
            foreach($firstJobAfterCollege as $a){
              if($ctr>0) $comma = ', ';
              else $comma = '';
              $chartLabels.=$comma.'"'.$a->Position.'"';
              $chartData.=$comma.$a->alumniCount;
              $ctr++;
            }
            $chartConfig = '{
              "type": "bar",
              "data": {
                "labels": ['.$chartLabels.'],
                "datasets": [{
                  "label": "First Job",
                  "data": ['.$chartData.'],
                  backgroundColor: [
                    "rgb(255, 55, 132)", "rgb(54, 162, 235)", "rgb(75, 192, 192)", "rgb(247, 120, 37)", 
                    "rgb(153, 102, 255)","rgb(55, 55, 255)","rgb(41, 179, 34)","rgb(201, 38, 38)","rgb(201, 201, 38)",
                    "rgb(176, 60, 214)","rgb(83, 166, 86)","rgb(191, 88, 119)","rgb(184, 126, 75)"
                  ],
                }]
              }
            }';
        $chartUrl_FirstJob = 'https://quickchart.io/chart?w=500&h=300&c=' . urlencode($chartConfig);
        //dd($chartUrl_FirstJob);
        // ========================= END First Job After College ====================

        // ========================= Current Position =============================
        if($course == 'all'){
          $firstJobPosition = Alumni::join('tbl_tracer_answers', 'tbl_alumni.alumni_id', '=', 'tbl_tracer_answers.alumni_id')
            ->where('tbl_tracer_answers.question_id', '=', 6)
            ->where('tbl_tracer_answers.answer', '!=', 'UNEMPLOYED')
            ->whereRaw('tbl_alumni.batch BETWEEN '.$batch_from.' AND '.$batch_to.'')
            ->select(DB::raw('tbl_tracer_answers.answer as Position1'))
            ->orderBy('tbl_alumni.alumni_id')
            ->get();
          $currentJobPosition = Alumni::join('tbl_tracer_answers', 'tbl_alumni.alumni_id', '=', 'tbl_tracer_answers.alumni_id')
            ->where('tbl_tracer_answers.question_id', '=', 15)
            ->where('tbl_tracer_answers.answer', '!=', 'UNEMPLOYED')
            ->whereRaw('tbl_alumni.batch BETWEEN '.$batch_from.' AND '.$batch_to.'')
            ->select(DB::raw('tbl_tracer_answers.answer as Position2'))
            ->orderBy('tbl_alumni.alumni_id')
            ->get();
        } else {
          $firstJobPosition = Alumni::join('tbl_tracer_answers', 'tbl_alumni.alumni_id', '=', 'tbl_tracer_answers.alumni_id')
            ->where('tbl_tracer_answers.question_id', '=', 6)
            ->where('tbl_alumni.course_id', '=', $course)
            ->where('tbl_tracer_answers.answer', '!=', 'UNEMPLOYED')
            ->whereRaw('tbl_alumni.batch BETWEEN '.$batch_from.' AND '.$batch_to.'')
            ->select(DB::raw('tbl_tracer_answers.answer as Position1'))
            ->orderBy('tbl_alumni.alumni_id')
            ->get();
          $currentJobPosition = Alumni::join('tbl_tracer_answers', 'tbl_alumni.alumni_id', '=', 'tbl_tracer_answers.alumni_id')
            ->where('tbl_tracer_answers.question_id', '=', 15)
            ->where('tbl_alumni.course_id', '=', $course)
            ->where('tbl_tracer_answers.answer', '!=', 'UNEMPLOYED')
            ->whereRaw('tbl_alumni.batch BETWEEN '.$batch_from.' AND '.$batch_to.'')
            ->select(DB::raw('tbl_tracer_answers.answer as Position2'))
            ->orderBy('tbl_alumni.alumni_id')
            ->get();
        }
        $getPositionChange = $this->getPositionChanged($firstJobPosition,$currentJobPosition);
        $getPositionNotChanged = $this->getNoChangePosition($firstJobPosition,$currentJobPosition);

        // ========================= END Current Position =============================

        // ============================ GET TEXT DESCRIPTIONS =============================
        $lengthOfJobSearch = $this->getLengthOfJobSearch($graduateToEmploymentOrdered, $totalRespondentsThisBatch, $course);
        $employmentStatus = $this->getEmploymentStatues($topEmploymentType, $totalRespondentsThisBatch, $course);
        $getFirstJobAfterCollege = $this->getFirstJobAfterCollege($firstJobAfterCollege, $totalRespondentsThisBatch, $course);
        $getPresentPosition = $this->getPresentPosition($getPositionChange, $getPositionNotChanged, $totalRespondentsThisBatch, $course);
        // ============================ END GET TEXT DESCRIPTIONS =========================
           
      
        $pdf = app('dompdf.wrapper');
        $user = auth()->user();
        Log::channel('admin')->info("UserId:".$user->user_id." | AdminId: ".$user->admin_id." | Username: ".$user->name." - Generated a Tracer Studies Report");
        $pdf->loadview('pdf.tracer_studies', compact(['tracerVersionName','descriptionArray','graduateToEmploymentDescription','salaryRankDescription','employmentTypeDescription','course','companyName','ctr','getPositionNotChanged','getPositionChange','chartUrl_FirstJob','chartUrl_EmpType', 'chartUrl_Salary', 'chartUrl_GradToEmp', 'graduateToEmploymentOrdered','graduateToEmployment','firstJobAfterCollege','totalAlumniThisBatch', 'totalRespondentsThisBatch', 'topEmploymentType','batch_from', 'batch_to','lengthOfJobSearch','employmentStatus','getFirstJobAfterCollege','getPresentPosition', 'arrayanswer', 'questionArray']));
        return $pdf->stream('tracer_studies.pdf');
    }

    

    public function getPositionChanged($pos1, $pos2){
      $positionChangedArray = array();
      foreach($pos1 as $key=>$row){
        if($row['Position1'] != $pos2[$key]->Position2) array_push($positionChangedArray, $pos2[$key]->Position2);
      }
      return $positionChangedArray;
    }

    public function getNoChangePosition($pos1, $pos2){
      $noChangePos = 0;
      foreach($pos1 as $key=>$row){
        if($row['Position1'] != $pos2[$key]->Position2) $noChangePos++;
      }
      return $noChangePos;
    }

    public function postDataFromForm(Request $request){
        $batch_from = $request->batch_from;
        $batch_to = $request->batch_to;
        $course = $request->course;
        return redirect()->route('tracerstudies.getDataFromForm', [$batch_from, $batch_to, $course]);
    }

    public function view_TracerStudies(){
        return view('pdf.test');
    }

    public function getPresentPosition($getPositionChange, $getPositionNotChanged, $totalRespondentsThisBatch,$course){
      $levelOfAmount="";
      $text="";
      if($course=="all") $course = "alumni";

      if(count($getPositionChange)>$totalRespondentsThisBatch/2){
        $levelOfAmount.="Many";
      } else if(count($getPositionChange)>$totalRespondentsThisBatch/3) {
        $levelOfAmount.="Most";
      } else {
        $levelOfAmount.="Some";
      }
      $text.=$levelOfAmount." of the ".$course." respondents already gained experience and leveled up on their position compared to their
      position when they first entered the industry. ".$levelOfAmount." are have already become ";

      for($i=0; $i<count($getPositionChange); $i++){
          $text.="[".$getPositionChange[$i]."] ";
          if($i>=3) break;
      }

      $text.="while a number of the graduates have stayed in their current position [".$getPositionNotChanged."]";
      return $text;
    }

    public function getFirstJobAfterCollege($firstJobAfterCollege, $totalRespondentsThisBatch,$course){
      $text ="There are different job titles for ".$course." graduates. Based on the tracer study conducted, ";
       $ctr=0;
       $previousData=0;

       if($course=='all'){
        $course='alumni';
       }

       foreach($firstJobAfterCollege as $a) {
          // Check top data
          if($ctr==0){
            if($a->alumniCount > $totalRespondentsThisBatch/2){
              $text.="majority of the ".$course." respondents have become a [".$a->Position."(".$a->alumniCount.")] after graduating ";
            } else if($a->alumniCount > $totalRespondentsThisBatch/3) {
              $text.="most of the ".$course." respondents have become a [".$a->Position."(".$a->alumniCount.")]  after graduating ";
            } else {
              $text.="some of the ".$course." respondents have become a [".$a->Position."(".$a->alumniCount.")]  after graduating ";
            } 
          } else if($ctr==1) {
              if($previousData == $a->alumniCount)
                $text.=" and ";
              else if($a->alumniCount!=0)
                $text.=" while the others have become ";
              $text.=" [".$a->Position."(".$a->alumniCount.")]";
          }  
          else if ($ctr<5){
            if($a->alumniCount!=0)
            $text.=" [".$a->Position."(".$a->alumniCount.")]";
          }
          $ctr++;
          $previousData = $a->alumniCount;
       }
       
       $text.=" as a first job. During the first period of the job, some usually undergo Trainings and Bootcamp for their specialization while others are already given real tasks in their respective industry";
       return $text;
    }

    public function getEmploymentStatues($topEmploymentType, $totalRespondentsThisBatch,$course){
       $text ="";
       $ctr=0;
       $previousData=0;

       if($course=='all'){
        $course='alumni';
       }

       foreach($topEmploymentType as $a) {
          // Check top data
          if($ctr==0){
            if($a->alumniCount > $totalRespondentsThisBatch/2){
              $text.="Majority of the ".$course." respondents are on a [".$a->answers."(".$a->alumniCount.")] status";
            } else if($a->alumniCount > $totalRespondentsThisBatch/3) {
              $text.="Most of the ".$course." respondents are on a [".$a->answers."(".$a->alumniCount.")] status";
            } else {
              $text.="Some of the ".$course." respondents are on a [".$a->answers."(".$a->alumniCount.")] status";
            } 
          } else if($ctr==1) {
              if($previousData == $a->alumniCount)
                $text.=" and ";
              else if($a->alumniCount!=0)
                $text.=" while the others are on a ";
              $text.="[".$a->answers."(".$a->alumniCount.")]";
          }  
          else {
            if($a->alumniCount!=0)
            $text.="[".$a->answers."(".$a->alumniCount.")]";
          }
          $ctr++;
          $previousData = $a->alumniCount;
       }
       
       $text.=" status.";
       return $text;
    }

    public function getLengthOfJobSearch($graduateToEmploymentOrdered, $totalRespondentsThisBatch, $course){
      $ctr=0;
      $text="";
      $previousData=0;

      if($course=='all'){
        $course='alumni';
      }

      foreach($graduateToEmploymentOrdered as $a){
        // Check top data
        if($ctr==0){
          if($a->Count > $totalRespondentsThisBatch/2){
            $text.="Majority of the ".$course." respondents have gotten a job within [".$a->Months."(".$a->Count.")]";
          } else if($a->Count > $totalRespondentsThisBatch/3) {
            $text.="Most of the ".$course." respondents have gotten a job within [".$a->Months."(".$a->Count.")]";
          } else {
            $text.="Some of the ".$course." respondents have gotten a job within [".$a->Months."(".$a->Count.")]";
          }
        }
        else if($ctr==1) {
          if($previousData == $a->Count)
            $text.=" and ";
          else if($a->Count!=0) 
            $text.=" while the others took ";
          
          $text.="[".$a->Months."(".$a->Count.")]";
        } 
        else {
          if($a->Count!=0)
          $text.="[".$a->Months."(".$a->Count.")]";
        }
        $previousData = $a->Count;
        $ctr++;
      }
      $text.=" before landing their first job. Some are being absorbed by the company where they went for 
      during their on-the-job training, while others landed a job through the “job-hunting” season after graduation";
      return $text;
    }
}
