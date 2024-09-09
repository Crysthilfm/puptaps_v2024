<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Models\Alumni;
use App\Models\Courses;
use App\Models\Tracer\TracerAnswers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class ProfileController extends Controller
{
    //
    public function getProfileIndex() {
        $courses = Courses::all();
        $users = Alumni::where('alumni_id', '=', Auth::user()->alumni_id)->get();
        $title = "Profile Settings";
        return view('user.profile.index', compact(['courses', 'users', "title"]));
    }

    public function getProfileSetup() {
        $courses = Courses::all();
        $users = Alumni::where('alumni_id', '=', Auth::user()->alumni_id)->get();
        $title = "Profile Settings";
        return view('user.profile.setup-profile', compact(['users', 'title', 'courses']));
    }

    public function getPostReg() {
        $users = Alumni::where('alumni_id', '=', Auth::user()->alumni_id)->get();
        $tracer_answers = TracerAnswers::where('alumni_id', '=', Auth::user()->alumni_id)->get();
        $profile_setup = Alumni::where('alumni_id', '=', Auth::user()->alumni_id)->value('profile_status');


        return view('user.post-registration', compact(['users', 'tracer_answers', 'profile_setup']));
    }

    public function updateProfile(Request $request) {
        $request->validate(
            [
                'sex'                => 'required',
                'birthday'           => 'required',
                'number'             => 'required',
                'city_address'       => 'required|min:20',
                'provincial_address' => 'required|min:20',
            ],
            [
                '*.required'                => 'This is Required',
                'city_address.min'          => 'Please input complete home address',
                'provincial_address.min'    => 'Please input complete home address',
            ]
        );

        $account = Alumni::where('alumni_id', '=', Auth::user()->alumni_id)->update([
            'sex' => $request->input('sex'),
            'birthday' => $request->input('birthday'),
            'age' => $request->input('age'),
            'religion' => $request->input('religion'),
            'civil_status' => $request->input('civil_status'),
            'number' => $request->input('number'),
            'city_address' => $request->input('city_address'),
            'provincial_address' => $request->input('provincial_address'),
            'profile_status' => 'Complete',
        ]);

        if($account) {
            return redirect(route('user.homepage'));
        }
        else {
            return back()
                   ->with(
                        'fail',
                        'There is an Error Occured'
                    );
        }
    }

    public function updateUserAccount(Request $request) {

        $changeProfile = false;
        if($request->hasFile('user_pfp')) {
            $alumni = Alumni::where('alumni_id', '=', Auth::user()->alumni_id)->get();
            foreach($alumni as $image) {
                if(!($image->user_pfp) == null) {
                    unlink("Uploads/Profiles/".$image->user_pfp);
                }
            }

            $file = $request->file('user_pfp');
            $extension = $file->getClientOriginalExtension();
            date_default_timezone_set('Asia/Manila');
            $fileName = date('m_d_Y [H-i-s]') . '.' . $extension;
            $file->move('Uploads/Profiles/', $fileName);
            $account = Alumni::where('alumni_id', '=', Auth::user()->alumni_id)->update([
                'user_pfp' => $fileName,
            ]);

            $changeProfile = true;
        }

        $request->validate([
            'username' => 'required',
        ]);

        $change_password = false;

        if(($request->input('password')) != null ) {
            $request->validate([
                'password'  => ['required', 'confirmed', Rules\Password::defaults()],
            ]);

            $users = User::where('alumni_id', '=', Auth::user()->alumni_id)->update([
                'password' => Hash::make($request->input('password'))
            ]);

            $change_password = true;
        }

        $users = User::where('alumni_id', '=', Auth::user()->alumni_id)->update([
            'username' => $request->input('username'),
        ]);

        if($users || $change_password || $changeProfile) {
            return back()
                   ->with(
                        'success',
                        'Profile Successfully Updated!'
                    );
        }
        elseif (!($users)) {
            return back()
                    ->with('fail', 'Nothing to change.');
        }
        else {
            return back()
                   ->with(
                        'fail',
                        'There is an Error Occured! Try again later.'
                    );
        }
    }
}
