<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\TemporaryPassword;
use App\Models\Alumni;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class LoginMailController extends Controller
{
    public function sendTemporaryPassword($email, $stud_number) {

        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890_#$!></-+=*%&?@';
        $pass = array();
        $charslen = strlen($chars) - 1;

        for ($ctr = 0; $ctr < 8; $ctr++) {
            $temp = rand(0, $charslen);
            $pass[] = $chars[$temp];
        }

        $password = implode($pass);

        $user = User::where('username', '=', $stud_number)->update([
            'email' => $email,
            'password' => Hash::make($password),
            // Sets password to 'puptalumni'
            //'password' => '$2y$10$KV3W08m8Rb8COnmcLMNDk.YVAclfg8QBftrzjm8.SH2kxE57kxKfK',
        ]);

        $userProfile = Alumni::where('stud_number', '=', $stud_number)->update([
            'email' => $email,
        ]);


        Mail::to($email)->send(new TemporaryPassword($stud_number, $email, $password));
        
        // Mails login details but password = puptalumni
        // Mail::to($email)->send(new TemporaryPassword($stud_number, $email, '$2y$10$KV3W08m8Rb8COnmcLMNDk.YVAclfg8QBftrzjm8.SH2kxE57kxKfK'));

        Log::channel('auth')->info("Email: ".$email." - Temporary Password Sent");
        return redirect()->route('login')->with('success', 'Temporary Password Successfully sent to your Email');
    }
}
