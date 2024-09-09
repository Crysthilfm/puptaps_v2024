<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Log;

class AdminManagementController extends Controller
{
    public function getAddNewAdmin() {
        return view("super_admin.admin_management.add-admin");
    }

    public function getAdminManager() {

        $admins = Admin::all();
        return view("super_admin.admin_management.admin-manager", compact("admins"));
    }

    public function saveNewAdmin(Request $request) {
        $request->validate([
            'last_name'     => ['required'],
            'first_name'    => ['required'],

            'email'         => ['required', 'string', 'email', 'max:255', 'unique:tbl_admin', 'unique:users'],
            'username'      => ['required', 'unique:tbl_admin'],
            'password'      => ['required', 'confirmed', Rules\Password::defaults()]
        ]);

        $admin = new Admin();
        $admin->last_name   = $request->input("last_name");
        $admin->first_name  = $request->input("first_name");
        $admin->middle_name = $request->input("middle_name");
        $admin->suffix      = $request->input("suffix");
        $admin->email       = $request->input("email");
        $admin->username    = $request->input("username");
        $admin->password    = Hash::make($request->input('password'));
        $admin->user_role   = "Admin";
        $admin->save();

        $admin_account = Admin::where("email", "=", $request->input("email"))->get();
        $user = new User();
        foreach($admin_account as $account) {
            $user->admin_id             = $account->admin_id;
            $user->email                = $account->email;
            $user->username             = $account->username;
            $user->password             = $account->password;
            $user->user_role            = $account->user_role;
            $user->account_status       = 'Activated';

            $user->save();
        }


        $user = auth()->user();
        Log::channel('superadmin')->info("UserId:".$user->user_id." | AdminId: ".$user->admin_id." | Username: ".$user->name." - Added a new admin: admin id [".$admin->admin_id."]");
        return back()->with('success', 'Admin successfully added!');
    }

    public function deleteAdmin(Request $request) {
        $user = User::where('admin_id', '=', $request->admin_id)->first();
        $user->delete();

        $admin = Admin::where('admin_id', '=', $request->admin_id)->first();
        $admin->delete();

        $user1 = auth()->user();
        Log::channel('superadmin')->info("UserId:".$user1->user_id." | AdminId: ".$user1->admin_id." | Username: ".$user1->name." - Deleted admin: admin id [".$request->admin_id."]");
        return back()->with('success', 'Admin successfully removed!');
    }
}
