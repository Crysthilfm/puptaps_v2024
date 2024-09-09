<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
    public function getAdminLogin() {
        return view('auth.loginAdmin');
    }

    public function adminLogin(Request $request) {
        $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);

        $admin = Admin::where('username', '=', $request->username)->first();
        if($admin) {
            if(Hash::check($request->password, $admin->password)) {
                $request->session()->put('adminID', $admin->username);
                Log::channel('admin')->info("AdminId: ".$admin->admin_id." | Username: ".$user->name." - Logged in the system");
                return redirect(route('admin.homepage'));
            }
            else {
                return redirect(route('admin.getLogin'))->with('fail', 'The password is incorrect.');
            }
        }
        else {
            return redirect(route('admin.getLogin'))->with('fail', 'This username is not yet registered.');
        }
    }

    public function adminLogout() {

    }
}
