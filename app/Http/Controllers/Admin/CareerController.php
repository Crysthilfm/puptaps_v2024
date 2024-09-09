<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\RejectCareerPost;
use App\Models\Admin;
use App\Models\Alumni;
use App\Models\Careers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class CareerController extends Controller
{
    public function getAdminCareerIndex(Request $request) {
        $title = "Career Dashboard";
        $users              = Alumni::where('alumni_id', '=', Auth::user()
                              ->alumni_id)->get();
        $alumni             = Alumni::all();
        $admin              = Admin::all();
        $username           = User::all();
        $data['query']      = $request->get('query');
        $data['subquery']   = $request->get('subquery');
        $data['careers']    = Careers::where('approval', '=', 1)
                              ->where('job_name', 'like', '%' . $data['query'] . '%')
                              ->where('category', 'like', '%' . $data['subquery'] . '%')
                              ->orderby('career_id', 'desc')
                              ->paginate(15)
                              ->withQueryString();

        return view('admin.careers.index', compact(['title', 'users', 'alumni', 'admin', 'username']), $data);
    }

    public function getCareerRequest() {
        $careers = Careers::where("approval", "=", 0)->get();
        $users = Alumni::all();
        $title = "Posting Approval";
        return view('admin.careers.career-approval', compact(['careers', 'users', 'title']));
    }

    public function approveCareer($career_id) {
        $user = auth()->user();
        $career = Careers::where('career_id', '=', $career_id)->update(['approval' => 1]);

        Log::channel('admin')->info("UserId:".$user->user_id." | AdminId: ".$user->admin_id." | Username: ".$user->name." - Approved Career post id[".$career_id."]");
        return back()->with('success', 'Post successfully approved!');
    }

    public function rejectCareer(Request $request) {
        $user1 = auth()->user();
        $career = Careers::where('career_id', '=', $request->career_id)->first();
        $user = Alumni::where('alumni_id', '=', $request->alumni_id)->first();

        $email = $user['email'];
        $reason = $request->input('reason');

        Mail::to($email)->send(new RejectCareerPost($email, $reason));

        $career->delete();
        Log::channel('admin')->info("UserId:".$user1->user_id." | AdminId: ".$user1->admin_id." | Username: ".$user1->name." - Rejected Career post id[".$request->career_id."]");
        return back()->with('success', 'Post successfully rejected!');
    }

    public function addTextCareer(Request $request) {
        $user = auth()->user();
        $this->validate($request,[
            'job_name'      => 'required',
            'company'       => 'required',
            'salary'        => 'required',
            'description'   => 'required',
            'category'      => 'required',
            'email'         => 'required',
            'number'        => 'required',
        ]);

        $career = new Careers();
        $career->admin_id   = Auth::user()->admin_id;
        $career->job_name       = $request->input('job_name');
        $career->company        = $request->input('company');
        $career->salary         = $request->input('salary');
        $career->description    = $request->input('description');
        $career->category       = $request->input('category');
        $career->email          = $request->input('email');
        $career->number         = $request->input('number');
        $career->approval   = 1;

        $career->save();

        if($career->save()) {
            Log::channel('admin')->info("UserId:".$user->user_id." | AdminId: ".$user->admin_id." | Username: ".$user->name." - Added a Career post id[".$career->career_id."]");
            return back()
                   ->with(
                        'success',
                        'Thank you for posting.'
                    );
        }
        else {
            return back()
                   ->with(
                        'fail',
                        'There is an Error Occured'
                    );
        }
    }

    public function addImageCareer(Request $request) {
        $user = auth()->user();
        $request->validate([
            'job_ad_image'  => 'required|mimes:jpg,jpeg,png',
            'job_name'      => 'required',
            'category'      => 'required',
        ]);

        $career = new Careers();
        $career->admin_id   = Auth::user()->admin_id;
        $career->approval   = 1;
        $career->job_name   = $request->input('job_name');
        $career->category   = $request->input('category');

        if($request->hasFile('job_ad_image')) {

            $file       = $request->file('job_ad_image');
            $extension  = $file->getClientOriginalExtension();
            date_default_timezone_set('Asia/Manila');
            $fileName   = date('m_d_Y [H-i-s]') . '.' . $extension;
            $file->move('Uploads/Career/', $fileName);

            $career->job_ad_image = $fileName;

        }

        $career->save();

        if($career->save()) {
            Log::channel('admin')->info("UserId:".$user->user_id." | AdminId: ".$user->admin_id." | Username: ".$user->name." - Added a Career image post id[".$career->career_id."]");
            return back()
                   ->with(
                        'success',
                        'Thank you for posting.'
                    );

        }
        else {
            return back()
                   ->with(
                        'fail',
                        'There is an Error Occured'
                    );
        }
    }
}

?>
