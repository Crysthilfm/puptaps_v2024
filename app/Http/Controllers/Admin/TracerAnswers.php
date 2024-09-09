<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use DB;
use App\Models\Alumni;
use App\Models\Careers;
use App\Models\Tracer\TracerAnswers as TracerData;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Log;

class TracerAnswers extends Controller
{
    public $alumnitraceranswers;

    public static function getIndividualAnswers($alumni_id){
        $alumniTracerData = TracerData::where('tbl_tracer_answers.alumni_id','=',$alumni_id)
        ->get();
        
        return $alumniTracerData;
    }

    public function show_tracer($alumni_id){ 
        $user = auth()->user();
        $alumniTracerData = TracerData::where('tbl_tracer_answers.alumni_id','=',$alumni_id)
        ->get();

        $alumniProfile = Alumni::where('alumni_id','=',$alumni_id)
        ->first();

        if(count($alumniTracerData) > 1) {
            Log::channel('admin')->info("UserId:".$user->user_id." | AdminId: ".$user->admin_id." | Username: ".$user->name." - Viewed [Alumni Id: ".$alumni_id."] tracer data");
            return view('admin.Tracer.view-alumnitracer-data', compact(['alumniTracerData','alumniProfile']));
        }
        else {
            Alert::error('Empty Tracer Data','This alumni currently has not filled out his tracer data');
            return back();
        }
    }

    function getTracerAnswers() {
        
        $alumnitraceranswers = Alumni::join('tbl_tracer_answers', 'tbl_tracer_answers.alumni_id','=','tbl_alumni.alumni_id')
        ->select('tbl_alumni.alumni_id', 'tbl_alumni.stud_number', 'tbl_alumni.last_name', 'tbl_alumni.first_name', 'tbl_alumni.middle_name', 'tbl_alumni.batch', 'tbl_alumni.course_id')
        ->distinct('tbl_tracer_answers.alumni_id')
        ->get();


        return view('admin.Tracer.view-tracer-answers', compact('alumnitraceranswers'));
        
    }
}
