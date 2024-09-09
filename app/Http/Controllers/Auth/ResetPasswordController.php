<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ResetPassword;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;

class ResetPasswordController extends Controller
{
    public function getResetPassword() {
        return view('auth.reset-password');
    }

    public function sendResetPassword(Request $request) {
        $request->validate(
            [
                'email' => ['required', 'email', 'max:255'],
            ],
            [
                'email.required' => 'Email is required',
            ]
        );

        $checkEmail = User::where('email', '=', $request->email)->first();
        $username = User::where('email', '=', $request->email)->value('username');

        if ($checkEmail != null) {
            $email = $request->email;
            Mail::to($email)->send(new ResetPassword($email, $username));
            $user = User::where('email', '=', $email)->update([
                'account_status' => 'Inactive',
            ]);
            return back()->with('success', 'Email successfully sent.');
        }

        else {
            return back()->with('fail', 'Email does not belong to any users.');
        }
    }

    public function changePassword($email) {

        $checkEmail = User::where('email', '=', $email)->first();

        if ($checkEmail != null) {
            return view('auth.change-password', compact('email'));
        }
        else {
            return back();
        }

    }

    public function changingPassword(Request $request) {
        $request->validate([
            'password'  => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::where('email', '=', $request->email)->update([
            'password' => Hash::make($request->input('password')),
            'account_status' => 'Activated',
        ]);

        return redirect(route('login'));
    }
}
