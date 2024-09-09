<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class AccountSettingsController extends Controller
{
    public function getAccountSettings() {
        $adminAccount = Admin::where('admin_id', '=', Auth::user()->admin_id)->get();
        $title = "Account Settings";
        $message = "Profile successfully updated.";
        return view("admin.account_settings.account-settings", compact(["title", "message", "adminAccount"]));
    }

    public function updateAccount(Request $request) {
        $request->validate([
            "username"  => "required",
            "email"     => "required|email",
        ]);

        $change_password = false;

        if(($request->input('password')) != null ) {
            $request->validate([
                'password'  => ['required', 'confirmed', Rules\Password::defaults()],
            ]);

            $account = Admin::where('admin_id', '=', Auth::user()->admin_id)->update([
                'password'  => Hash::make($request->input('password')),
            ]);

            $users = User::where('admin_id', '=', Auth::user()->admin_id)->update([
                'password'  => Hash::make($request->input('password')),
            ]);

            $change_password = true;
        }

        $account = Admin::where('admin_id', '=', Auth::user()->admin_id)->update([
            'email'     => $request->input('email'),
            'username'  => $request->input('username'),
        ]);

        $users = User::where('admin_id', '=', Auth::user()->admin_id)->update([
            'email'     => $request->input('email'),
            'username'  => $request->input('username'),
        ]);

        if($account && $users || $change_password) {
            return back()
                   ->with(
                        'success',
                        'Account Successfully Updated.'
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
