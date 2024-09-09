<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alumni;
use App\Models\Courses;
use App\Models\Forms;
use App\Models\Forms\Eif\EifAnswers;
use App\Models\Forms\Pds\PdsAnswers;
use App\Models\Forms\Sas\SasAnswers;
use App\Models\Tracer\TracerAnswers;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    public function getFormReports() {
        $forms = Forms::all();
        $courses = Courses::all();
        $totalAlumni = count(Alumni::all());
        $totalPds = count(PdsAnswers::select('alumni_id')->distinct()->get());
        $totalEif = count(EifAnswers::select('alumni_id')->distinct()->get());
        $totalSas = count(SasAnswers::select('alumni_id')->distinct()->get());

        return view("admin.reports.form-index", compact(["forms", "courses", "totalAlumni", "totalPds", "totalEif", "totalSas"]));
    }

    public function getFormReportsCharts() {
        $forms = Forms::all();
        $courses = Courses::all();
        $totalAlumni = count(Alumni::all());
        $totalPds = count(PdsAnswers::select('alumni_id')->distinct()->get());
        $totalEif = count(EifAnswers::select('alumni_id')->distinct()->get());
        $totalSas = count(SasAnswers::select('alumni_id')->distinct()->get());

        return view("admin.reports.form-index-charts", compact(["forms", "courses", "totalAlumni", "totalPds", "totalEif", "totalSas"]));
    }


    public function getTracerReports() {
        $totalAlumni = count(Alumni::all());
        $totalTracer = count(TracerAnswers::select('alumni_id')->distinct()->get());
        $courses = Courses::all();
        $checkBoardPassers = Alumni::join('tbl_tracer_answers', 'tbl_alumni.alumni_id', '=', 'tbl_tracer_answers.alumni_id')
                ->where('tbl_tracer_answers.question_id', '=', 1)
                ->where('tbl_tracer_answers.answer', '=', "Yes")
                ->select('tbl_tracer_answers.answer',)
                ->get();
        $totalPassers = count($checkBoardPassers);

        return view("admin.reports.tracer-index", compact(["courses","totalAlumni", "totalTracer", "totalPassers"]));
    }
}
