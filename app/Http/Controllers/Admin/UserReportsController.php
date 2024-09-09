<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alumni;
use App\Models\Courses;
use Illuminate\Http\Request;
use DB;

class UserReportsController extends Controller
{
    public function getUserReports(Request $request) {
        $title = "User Reports";
        $courses = Courses::all();

        $data['course_id']  = $request->get('course_id');
        $data['sex']        = $request->get('sex');
        $data['batch_from'] = $request->get('batch_from');
        $data['batch_to']   = $request->get('batch_to');

        if(
            $data['course_id'] == null &&
            $data['sex'] == null &&
            $data['batch_from'] == null &&
            $data['batch_to'] == null
            ){
                $data['alumni'] = Alumni::orderBy('last_name', 'asc')
                            ->paginate(15)
                            ->withQueryString();
        }
        elseif(($data['sex']) == null) {
            $data['alumni'] = Alumni::where('course_id', 'like', '%' . $data['course_id'] . '%')
                            ->whereBetween('batch', [$data['batch_from'], $data['batch_to']])
                            ->orderBy('last_name', 'asc')
                            ->paginate(15)
                            ->withQueryString();
        }
        else {
            $data['alumni'] = Alumni::where('course_id', 'like', '%' . $data['course_id'] . '%')
                            ->where('sex', "=", $data['sex'])
                            ->whereBetween('batch', [$data['batch_from'], $data['batch_to']])
                            ->orderBy('last_name', 'asc')
                            ->paginate(15)
                            ->withQueryString();
        }

        $students = Alumni::select('course_id as course_id', DB::raw('count(alumni_id) as alumniCount'))
                ->groupBy('course_id')
                ->get();
        $studentsPerCourse = $students->mapWithKeys(function ($item, $key) {
            return [$item->course_id => $item->alumniCount];
        });

        $sex = Alumni::select('sex as sex', DB::raw('count(alumni_id) as alumniCount'))
                ->where('sex', 'Male')
                ->orWhere('sex', 'Female')
                ->groupBy('sex')
                ->get();
        $studentsPersex = $sex->mapWithKeys(function ($item, $key) {
            return [$item->sex => $item->alumniCount];
        });

        return view("admin.reports.user-report", compact(["title", "courses", "studentsPerCourse", "studentsPersex"]), $data);
    }
}
