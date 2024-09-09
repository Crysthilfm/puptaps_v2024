<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\UsersImport;
use App\Models\Alumni;
use App\Models\AlumniList;
use App\Models\Courses;
use App\Models\Forms\Eif\EifAnswers;
use App\Models\Forms\Pds\PdsAnswers;
use App\Models\Forms\Sas\SasAnswers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx\Rels;

class UserManagerController extends Controller
{
    public function getAlumniManager(Request $request) {
        $data['q'] = $request->get('q');
        $data['alumni'] = Alumni::where('last_name', 'like', '%' . $data['q'] . '%')->orwhere('stud_number', 'like', '%' . $data['q'] . '%')->paginate(15)->withQueryString();
        $courses = Courses::all();
        $title = "Alumni Manager";
        $PDS = PdsAnswers::all();
        $EIF = EifAnswers::all();
        $SAS = SasAnswers::all();
        return view('admin.user_management.alumni_manager', $data, compact(['title', 'courses', 'PDS', 'EIF', 'SAS']));
    }

    public function addAlumniList(Request $request) {
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,csv',
        ]);

        Excel::import(new UsersImport, $request->file('excel_file'));

        $user = auth()->user();
        Log::channel('admin')->info("UserId:".$user->user_id." | AdminId: ".$user->admin_id." | Username: ".$user->name." - Imported an Alumni List");
        return back()->with(
            'success',
            'Alumni Added.'
        );
    }

    public function updateAlumniInfo(Request $request) {
        $request->validate([
            'last_name'             => 'required',
            'first_name'            => 'required',
            'middle_name'           => 'required',
            'stud_number'           => 'required',
            'batch'                 => 'required',
            'course_id'             => 'required',
            'email'                 => 'required|email',
            'number'                => 'required',
            'birthday'              => 'required',
            'age'                   => 'required',
            'sex'                   => 'required',
            'city_address'          => 'required',
            'provincial_address'    => 'required',
        ],
        [
            '*.required'    => 'This is required',
            'email.email'   => 'Invalid Email',
        ]);

        $account = Alumni::where('alumni_id', '=', $request->alumni_id)->update([
            'last_name'             => $request->input('last_name'),
            'first_name'            => $request->input('first_name'),
            'middle_name'           => $request->input('middle_name'),
            'suffix'                => $request->input('suffix'),
            'stud_number'           => $request->input('stud_number'),
            'batch'                 => $request->input('batch'),
            'course_id'             => $request->input('course_id'),
            'email'                 => $request->input('email'),
            'number'                => $request->input('number'),
            'birthday'              => $request->input('birthday'),
            'age'                   => $request->input('age'),
            'sex'                   => $request->input('sex'),
            'city_address'          => $request->input('city_address'),
            'provincial_address'    => $request->input('provincial_address'),
        ]);

        $newUserAccount = User::where('alumni_id','=',$request->alumni_id)->update([
            'email'                 => $request->input('email'),
        ]);

        if($account) {
            $user = auth()->user();
            Log::channel('admin')->info("UserId:".$user->user_id." | AdminId: ".$user->admin_id." | Username: ".$user->name." - Updated student no.[".$request->stud_number."] data");
            return back()
                   ->with(
                        'success',
                        'Alumni Information Successfully Updated.'
                    );
        }
        else {
            $user = auth()->user();
            Log::channel('admin')->info("UserId:".$user->user_id." | AdminId: ".$user->admin_id." | Username: ".$user->name." - Failed to update student no.[".$request->stud_number."] data");
            return back()
                   ->with(
                        'fail',
                        'There is an Error Occured'
                    );
        }
    }

    public function deleteAlumniProfile(Request $request) {
        $alumni = Alumni::find($request->alumni_id);
        $alumni->delete();

        $user = auth()->user();
        Log::channel('admin')->info("UserId:".$user->user_id." | AdminId: ".$user->admin_id." | Username: ".$user->name." - Deleted Alumni Id:[".$request->alumni_id."]");
        return back();
    }

    public function downloadListTemplate() {
        $user = auth()->user();
        Log::channel('admin')->info("UserId:".$user->user_id." | AdminId: ".$user->admin_id." | Username: ".$user->name." - Downloaded the list template");
        return response()->download(public_path("files/list_template.xlsx"));
        //return response()->download(asset("files/list_template.xlsx"));
    }

    public function resetPds(Request $request) {
        $deleteAll = PdsAnswers::where('alumni_id', $request->alumni_id)->delete();

        return back()->with('success', 'Form Successfully reset.');
    }

    public function resetEif(Request $request) {
        $deleteAll = EifAnswers::where('alumni_id', $request->alumni_id)->delete();

        return back()->with('success', 'Form Successfully reset.');
    }

    public function resetSas(Request $request) {
        $deleteAll = SasAnswers::where('alumni_id', $request->alumni_id)->delete();

        return back()->with('success', 'Form Successfully reset.');
    }
}
