<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Alumni;
use App\Models\Careers;
use App\Models\User;
use App\Models\ReminderHistory;
use App\Models\ReminderRecipients;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public $employed_count = 0;
    

    public function getAdminHomepage() {
        $boardExam = Alumni::join('tbl_tracer_answers', 'tbl_alumni.alumni_id', '=', 'tbl_tracer_answers.alumni_id')
            ->where('tbl_tracer_answers.question_id', '=', 3)
            ->where('tbl_tracer_answers.answer', '!=', 'N/A')
            ->where('tbl_tracer_answers.answer', '!=', 'No Boards')
            ->select('tbl_tracer_answers.answer as answers', DB::raw('count(tbl_alumni.alumni_id) as alumniCount'))
            ->groupBy('answers')
            ->get();

        $boardExamTable = Alumni::join('tbl_tracer_answers', 'tbl_alumni.alumni_id', '=', 'tbl_tracer_answers.alumni_id')
            ->where('tbl_tracer_answers.question_id', '=', 3)
            ->where('tbl_tracer_answers.answer', '!=', 'N/A')
            ->where('tbl_tracer_answers.answer', '!=', 'No Boards')
            ->select('tbl_tracer_answers.answer as board_exam', DB::raw('tbl_alumni.stud_number, tbl_alumni.last_name, tbl_alumni.first_name, tbl_alumni.middle_name, tbl_alumni.suffix, course_id, tbl_alumni.batch, tbl_alumni.email'))
            ->orderBy('board_exam')
            ->get();
            
        $perBoardExam = $boardExam->mapWithKeys(function ($item, $key) {
            //return [$item->answers => $item->alumniCount];
            if ($item->answers == "Certified Public Accountant Board Exam") {
                return ["CPABE" => $item->alumniCount];
            } elseif ($item->answers == "Electronics Engineer Licensure Examination") {
                return ["EELE" => $item->alumniCount];
            } elseif ($item->answers == "Licensure Examination for Teachers") {
                return ["LET" => $item->alumniCount];
            } elseif ($item->answers == "Professional Mechanical Engineer") {
                return ["PME" => $item->alumniCount];
            } 
            else {
                return [$item->answers => $item->alumniCount];
            }
        });

        // ===== START Total Tracer Chart Data =====
        $totalTracerAnswers = Alumni::join('tbl_tracer_answers', 'tbl_alumni.alumni_id', '=', 'tbl_tracer_answers.alumni_id')
        ->where('tbl_tracer_answers.question_id', '=', 3)
        ->select(DB::raw('count(tbl_alumni.alumni_id) as alumniCount'))
        ->count();

        $tracerAnswers = Alumni::join('tbl_tracer_answers', 'tbl_alumni.alumni_id', '=', 'tbl_tracer_answers.alumni_id')
        ->where('tbl_tracer_answers.question_id', '=', 3)
        ->select(DB::raw('tbl_alumni.stud_number, tbl_alumni.last_name, tbl_alumni.first_name, tbl_alumni.middle_name, tbl_alumni.suffix, course_id, tbl_alumni.batch, tbl_alumni.email'))
        ->get();

        // Check with more data
        // $tracerAnswers = Alumni::where('batch', '2023')->get();
        // ===== END Total Tracer Chart Data =======

        // ===== START Tracer By Course =======
        $answerByCourse = Alumni::join('tbl_tracer_answers', 'tbl_alumni.alumni_id' , '=', 'tbl_tracer_answers.alumni_id')
        ->where('tbl_tracer_answers.question_id', '=', 10)
        ->select('tbl_alumni.course_id as course', DB::raw('count(tbl_alumni.alumni_id) as alumniCount'))
        ->groupBy('course')
        ->get();

        $tracerPerCourseTable = Alumni::join('tbl_tracer_answers', 'tbl_alumni.alumni_id' , '=', 'tbl_tracer_answers.alumni_id')
        ->where('tbl_tracer_answers.question_id', '=', 10)
        ->where('tbl_alumni.course_id', '=', 'BSIT')
        ->orWhere('tbl_alumni.course_id', '=', 'BSED')
        ->orWhere('tbl_alumni.course_id', '=', 'BSECE')
        ->orWhere('tbl_alumni.course_id', '=', 'BSOA')
        ->select(DB::raw('tbl_alumni.stud_number, tbl_alumni.last_name, tbl_alumni.first_name, tbl_alumni.middle_name, tbl_alumni.suffix, tbl_alumni.course_id, tbl_alumni.batch, tbl_alumni.email'))
        ->distinct('tbl_alumni.stud_number')
        ->orderByRaw('course_id')
        ->get();

        $tracerPerCourse = $answerByCourse->mapWithKeys(function ($item, $key) {
            return [$item->course => $item->alumniCount];
        });
        // ===== END Tracer By Course =======


        // ==== START Civil Service =====
        $civilService = Alumni::join('tbl_tracer_answers', 'tbl_alumni.alumni_id', '=', 'tbl_tracer_answers.alumni_id')
            ->where('tbl_tracer_answers.question_id', '=', 5)
            ->select('tbl_tracer_answers.answer as answers', DB::raw('count(tbl_alumni.alumni_id) as alumniCount'))
            ->groupBy('answers')
            ->get();

        $civilServiceTable = Alumni::join('tbl_tracer_answers', 'tbl_alumni.alumni_id', '=', 'tbl_tracer_answers.alumni_id')
            ->where('tbl_tracer_answers.question_id', '=', 5)
            ->select('tbl_tracer_answers.answer as civil_service', DB::raw('tbl_alumni.stud_number, tbl_alumni.last_name, tbl_alumni.first_name, tbl_alumni.middle_name, tbl_alumni.suffix, tbl_alumni.course_id, tbl_alumni.batch, tbl_alumni.email'))
            ->orderBy('civil_service', 'DESC')
            ->get();

        $perCivilService = $civilService->mapWithKeys(function ($item, $key) {
            if ($item->answers == "Yes") {
                return ["PASSERS" => $item->alumniCount];
            } elseif ($item->answers == "No") {
                return ["NON-PASSERS" => $item->alumniCount];
            } else {
                return ["NON-TAKERS" => $item->alumniCount];
            }
        });
        // ==== END Civil Service =====

        // ======== START Salary Rank Chart
        $salaryRank = Alumni::join('tbl_tracer_answers', 'tbl_alumni.alumni_id', '=', 'tbl_tracer_answers.alumni_id')
            ->where('tbl_tracer_answers.question_id', '=', 11)
            ->where('tbl_tracer_answers.answer', '!=', 'UNEMPLOYED')
            ->where('tbl_tracer_answers.answer', '!=', 'Not Applicable')
            ->select('tbl_tracer_answers.answer as Salary', DB::raw('count(tbl_alumni.alumni_id) as alumniCount'))
            ->distinct('Salary')
            ->orderByRaw('Salary DESC')
            ->limit(5)
            ->groupBy('Salary')
            ->get();

        $salaries = Alumni::join('tbl_tracer_answers', 'tbl_alumni.alumni_id', '=', 'tbl_tracer_answers.alumni_id')
        ->where('tbl_tracer_answers.question_id', '=', 11)
        ->where('tbl_tracer_answers.answer', '!=', 'UNEMPLOYED')
        ->where('tbl_tracer_answers.answer', '!=', 'Not Applicable')
        ->select(DB::raw('tbl_alumni.stud_number, tbl_alumni.last_name, tbl_alumni.first_name, tbl_alumni.middle_name, tbl_alumni.suffix, course_id, tbl_alumni.batch, tbl_tracer_answers.answer as salary'))
        ->orderByRaw('salary DESC')
        ->limit(5)
        ->get();
        // ======== END Salary Rank Chart

        // ===== START Employment =======
        $employment = Alumni::join('tbl_tracer_answers', 'tbl_alumni.alumni_id', '=', 'tbl_tracer_answers.alumni_id')
            ->where('tbl_tracer_answers.question_id', '=', 6)
            ->select('tbl_tracer_answers.answer as answers', DB::raw('count(tbl_alumni.alumni_id) as alumniCount'))
            ->groupBy('answers')
            ->get();
        
        $employmentTable = Alumni::join('tbl_tracer_answers', 'tbl_alumni.alumni_id', '=', 'tbl_tracer_answers.alumni_id')
        ->where('tbl_tracer_answers.question_id', '=', 7)
        ->select('tbl_tracer_answers.answer as company', DB::raw('tbl_alumni.stud_number, tbl_alumni.last_name, tbl_alumni.first_name, tbl_alumni.middle_name, tbl_alumni.suffix, course_id, tbl_alumni.batch'))
        ->orderByRaw('company')
        ->get();

        $employedAlumni = $employment->mapWithKeys(function ($item, $key) {
            if ($item->answers != "UNEMPLOYED" || $item->answers != "Not Applicable") {
                return ["EMPLOYED" => $this->employed_count = $item->alumniCount + $this->employed_count];
            } else {
                return [$item->answers => $item->alumniCount];
            }
        });

        // ===== END Employment =======

        // ===== Inline Jobs with course Chart Data
        $inlineAnswers = Alumni::join('tbl_tracer_answers', 'tbl_alumni.alumni_id', '=', 'tbl_tracer_answers.alumni_id')
            ->where('tbl_tracer_answers.question_id', '=', 14)
            ->where('tbl_tracer_answers.answer', '!=', 'UNEMPLOYED')
            ->select('tbl_tracer_answers.answer as answers', DB::raw('count(tbl_alumni.alumni_id) as alumniCount'))
            ->groupBy('answers')
            ->get();

        $inlineAnswersTable = Alumni::join('tbl_tracer_answers', 'tbl_alumni.alumni_id', '=', 'tbl_tracer_answers.alumni_id')
        ->where('tbl_tracer_answers.question_id', '=', 14)
        ->where('tbl_tracer_answers.answer', '!=', 'UNEMPLOYED')
        ->select('tbl_tracer_answers.answer as inline', DB::raw('tbl_alumni.stud_number, tbl_alumni.last_name, tbl_alumni.first_name, tbl_alumni.middle_name, tbl_alumni.suffix, course_id, tbl_alumni.batch'))
        ->orderby('tbl_alumni.alumni_id')
        ->get();

        $JobsAnswersTable = Alumni::join('tbl_tracer_answers', 'tbl_alumni.alumni_id', '=', 'tbl_tracer_answers.alumni_id')
        ->where('tbl_tracer_answers.question_id', '=', 6)
        ->select('tbl_tracer_answers.answer as jobs')
        ->orderby('tbl_alumni.alumni_id')
        ->get();

        $inlineWithCourse = $inlineAnswers->mapWithKeys(function ($item, $key) {
            if ($item->answers == "Yes") {
                return ["Inline with Course" => $item->alumniCount];
            } elseif ($item->answers == "No") {
                return ["Not-Inline with Course" => $item->alumniCount];
            } else {
                return [$item->answers => $item->alumniCount];
            }
        });

        // ===== END Inline Jobs with course Chart Data

        // ===== START Alumni Type Chart Data
        $employmentType = Alumni::join('tbl_tracer_answers', 'tbl_alumni.alumni_id', '=', 'tbl_tracer_answers.alumni_id')
        ->where('tbl_tracer_answers.question_id', '=', 10)
        ->select('tbl_tracer_answers.answer as answers', DB::raw('count(tbl_alumni.alumni_id) as alumniCount'))
        ->groupBy('answers')
        ->get();

        $employmentTypeTable = Alumni::join('tbl_tracer_answers', 'tbl_alumni.alumni_id', '=', 'tbl_tracer_answers.alumni_id')
        ->where('tbl_tracer_answers.question_id', '=', 10)
        ->select('tbl_tracer_answers.answer as employment_type', DB::raw('tbl_alumni.stud_number, tbl_alumni.last_name, tbl_alumni.first_name, tbl_alumni.middle_name, tbl_alumni.suffix, course_id, tbl_alumni.batch'))
        ->orderByRaw('employment_type')
        ->get();

        $alumniEmploymentType = $employmentType->mapWithKeys(function ($item, $key) {
            return [$item->answers => $item->alumniCount];
        });

        // ===== END Alumni Type Chart Data

        $career = Careers::orderBy('created_at', 'desc')->where('approval', 1)->first();
        $users              = Alumni::where('alumni_id', '=', Auth::user()
            ->alumni_id)->get();
        $alumni             = Alumni::all();
        $admin              = Admin::all();
        $username           = User::all();

        $title = "Dashboard";
        $user = auth()->user();
        Log::channel('admin')->info("UserId:".$user->user_id." | AdminId: ".$user->admin_id." | Username: ".$user->name." - Opened the dashboard");
        return  view('admin.homepage', compact(["JobsAnswersTable","civilServiceTable","boardExamTable","inlineAnswersTable","employmentTypeTable","employmentTable","tracerPerCourseTable","salaries","tracerAnswers", "inlineWithCourse","alumniEmploymentType","tracerPerCourse","salaryRank","totalTracerAnswers","perBoardExam", "perCivilService", "employedAlumni", "career", 'users', 'alumni', 'admin', 'username']));
    }

    function getTracerReminder() {
        return view('admin.Tracer.tracerreminder');
    }

    public static function getTracerAnswersByAlumniId($alumniId){
        $alumnitracerById = Alumni::join('tbl_tracer_answers', 'tbl_alumni.alumni_id', '=', 'tbl_tracer_answers.alumni_id')
        ->join('tbl_tracer_questions', 'tbl_tracer_questions.question_id', '=', 'tbl_tracer_answers.question_id')
        ->join('tbl_tracer_categories', 'tbl_tracer_questions.category_id', '=', 'tbl_tracer_categories.category_id')
        ->where('tbl_tracer_answers.alumni_id', '=', $alumniId)
        ->where('tbl_tracer_questions.category_id','=',3)
        // ->select(DB::raw(''))
        ->distinct('question_id')
        ->get();

        return $alumnitracerById;
    }
    public static function getTracerAnswersBLC($alumniId){
        $alumnitracerById = Alumni::join('tbl_tracer_answers', 'tbl_alumni.alumni_id', '=', 'tbl_tracer_answers.alumni_id')
        ->join('tbl_tracer_questions', 'tbl_tracer_questions.question_id', '=', 'tbl_tracer_answers.question_id')
        ->join('tbl_tracer_categories', 'tbl_tracer_questions.category_id', '=', 'tbl_tracer_categories.category_id')
        ->where('tbl_tracer_answers.alumni_id', '=', $alumniId)
        ->where('tbl_tracer_questions.category_id','=',1)
        ->where('tbl_tracer_questions.question_id','<',6)
        // ->select(DB::raw(''))
        ->distinct('question_id')
        ->get();

        return $alumnitracerById;
    }
    public static function getTracerAnswersCurrent($alumniId){
        $alumnitracerById = Alumni::join('tbl_tracer_answers', 'tbl_alumni.alumni_id', '=', 'tbl_tracer_answers.alumni_id')
        ->join('tbl_tracer_questions', 'tbl_tracer_questions.question_id', '=', 'tbl_tracer_answers.question_id')
        ->join('tbl_tracer_categories', 'tbl_tracer_questions.category_id', '=', 'tbl_tracer_categories.category_id')
        ->where('tbl_tracer_answers.alumni_id', '=', $alumniId)
        ->where('tbl_tracer_questions.category_id','=',2)
        // ->select(DB::raw(''))
        ->distinct('question_id')
        ->get();

        return $alumnitracerById;
    }
    
    function getTracerHistory() {
        // $tracerHistory = ReminderHistory::join('reminder_recipients', 'reminder_recipients.rh_id', '=', 'reminder_histories.rh_id')
        // ->select('reminder_histories.rh_id as RecordID', 'reminder_histories.date_sent as DateSent', DB::raw('count(reminder_recipients.rr_id) as alumniCount'))
        // ->distinct('tbl_tracer_answers.alumni_id')
        // ->groupby('RecordID', 'reminder_histories.date_sent')
        // ->get();
        $tracerHistory = ReminderHistory::join('reminder_recipients', 'reminder_recipients.rh_id', '=', 'reminder_histories.rh_id')
        ->groupby('RecordID', 'reminder_histories.date_sent')
        ->select('reminder_histories.rh_id as RecordID', 'reminder_histories.date_sent as DateSent', DB::raw('count(reminder_recipients.rr_id) as TotalRecipients'))
        ->get();

        $tracerRecipient = Alumni::select('tbl_alumni.middle_name as Middle', 'tbl_alumni.first_name as Given','tbl_alumni.last_name as Surname','tbl_alumni.email as Email', 'tbl_alumni.tracer_updated_at as LastTracer','tbl_alumni.number as Contact', DB::raw('DATEDIFF("'.date('Y-m-d').'", tbl_alumni.tracer_updated_at) as dateDiff'))
        ->where('tbl_alumni.tracer_updated_at', '!=', 'null')
        ->get();

        return view('admin.Tracer.view-tracer-history', compact(['tracerHistory','tracerRecipient']));
    }

    function updatesInAWeek (Request $request) {
        $tracerHistory = ReminderHistory::join('reminder_recipients', 'reminder_recipients.rh_id', '=', 'reminder_histories.rh_id')
        ->groupby('RecordID', 'reminder_histories.date_sent')
        ->select('reminder_histories.rh_id as RecordID', 'reminder_histories.date_sent as DateSent', DB::raw('count(reminder_recipients.rr_id) as TotalRecipients'))
        ->get();

        
        // $tracerRecipient = ReminderHistory::join('reminder_recipients','reminder_recipients.rh_id', '=', 'reminder_histories.rh_id')
        // ->leftjoin('tbl_alumni', 'tbl_alumni.email', '=', 'reminder_recipients.recipientEmail')
        // ->select('reminder_histories.rh_id as rh_id','tbl_alumni.last_name as Surname','reminder_recipients.recipientEmail as Email', 'tbl_alumni.tracer_updated_at as LastTracer', DB::raw('DATEDIFF(tbl_alumni.tracer_updated_at,'.date('Y-m-d').') as dateDiff'))
        // // ->where('tbl_alumni.tracer_updated_at', '>', Carbon::now()->subDays(6)->toDateString())
        // ->whereRaw('DATEDIFF(tbl_alumni.tracer_updated_at,'.date('Y-m-d').') < 6')
        // ->get();

        $tracerRecipient = Alumni::select('tbl_alumni.middle_name as Middle', 'tbl_alumni.first_name as Given','tbl_alumni.last_name as Surname','tbl_alumni.email as Email', 'tbl_alumni.tracer_updated_at as LastTracer', 'tbl_alumni.number as Contact', DB::raw('DATEDIFF("'.date('Y-m-d').'", tbl_alumni.tracer_updated_at) as dateDiff'))
        ->whereRaw('DATEDIFF("'.date('Y-m-d').'", tbl_alumni.tracer_updated_at) < 6')
        ->get();
        
        return view('admin.Tracer.view-tracer-history', compact(['tracerHistory','tracerRecipient']));
    }

    function updatesInAMonth (Request $request) {
        $tracerHistory = ReminderHistory::join('reminder_recipients', 'reminder_recipients.rh_id', '=', 'reminder_histories.rh_id')
        ->groupby('RecordID', 'reminder_histories.date_sent')
        ->select('reminder_histories.rh_id as RecordID', 'reminder_histories.date_sent as DateSent', DB::raw('count(reminder_recipients.rr_id) as TotalRecipients'))
        ->get();

        $tracerRecipient = Alumni::select('tbl_alumni.middle_name as Middle', 'tbl_alumni.first_name as Given','tbl_alumni.last_name as Surname','tbl_alumni.email as Email', 'tbl_alumni.tracer_updated_at as LastTracer','tbl_alumni.number as Contact', DB::raw('DATEDIFF("'.date('Y-m-d').'", tbl_alumni.tracer_updated_at) as dateDiff'))
        ->whereRaw('DATEDIFF("'.date('Y-m-d').'", tbl_alumni.tracer_updated_at) < 30')
        ->get();
        
        return view('admin.Tracer.view-tracer-history', compact(['tracerHistory','tracerRecipient']));
    }

    function updatesIn6Months (Request $request) {
        $tracerHistory = ReminderHistory::join('reminder_recipients', 'reminder_recipients.rh_id', '=', 'reminder_histories.rh_id')
        ->groupby('RecordID', 'reminder_histories.date_sent')
        ->select('reminder_histories.rh_id as RecordID', 'reminder_histories.date_sent as DateSent', DB::raw('count(reminder_recipients.rr_id) as TotalRecipients'))
        ->get();
        
        $tracerRecipient = Alumni::select('tbl_alumni.middle_name as Middle', 'tbl_alumni.first_name as Given','tbl_alumni.last_name as Surname','tbl_alumni.email as Email', 'tbl_alumni.tracer_updated_at as LastTracer', 'tbl_alumni.number as Contact', DB::raw('DATEDIFF("'.date('Y-m-d').'", tbl_alumni.tracer_updated_at) as dateDiff'))
        ->whereRaw('DATEDIFF("'.date('Y-m-d').'", tbl_alumni.tracer_updated_at) < 180')
        ->get();
        
        return view('admin.Tracer.view-tracer-history', compact(['tracerHistory','tracerRecipient']));
    }
}
