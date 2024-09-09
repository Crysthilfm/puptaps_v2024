<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Alumni;
use App\Models\Courses;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\DB;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $courses = Courses::all();
        return view('auth.register', compact('courses'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $check_studNumber = Alumni::where('stud_number', '=', $request->stud_number)->first();

        $this->validate(request(),
            [
            'stud_number' =>
                array(
                    'required',
                    // 'regex:/^\d{4}-\d{5}-TG-[01]$/'
                    'regex:/^(?:\d{2}-\d{5}|\d{4}-\d{5}-TG-[01]$)$/'
                ),
            'email' =>
                array(
                    'required',
                    'email',
                    'max:255',
                    'unique:users'
                ),

            ],
            [
                'stud_number.required'  => 'Student Number is required',
                'stud_number.*'  => 'Invalid Format for Student Number',
                'email.required'        => 'Email is required',
                'email.email'        => 'Email format is invalid',
            ]
        );
        if($check_studNumber) {
            $hasPassword = User::where('username', '=', $request->stud_number)->first();
            $email = $request->email;
            $stud_number = $request->stud_number;

            if ($hasPassword['password'] == 'Not Set') {
                return redirect(route('mail.sendTemporaryPassword', [$email, $stud_number]));
            }
            else {
                return back()->with('fail', 'A temporary password has already been sent to an email connected to your account.');
            }
        }
        else {
            return back()->with('fail', 'Sorry, you are not allowed to create an account if you are not an official graduate of PUP-Taguig.');
        }
    }
}