<?php

namespace App\Http\Controllers\modules;

use App\Http\Controllers\Controller;
use App\Models\Alumni;
use App\Models\Announcements;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function getUserHomepage(Request $request) {
        $users = Alumni::where('alumni_id', '=', Auth::user()->alumni_id)->get();

        $data['query'] = $request->get('query');

        if ($data['query'] == 'Announcements') {
            $data['announcements'] = Announcements::orderby('announcement_id', 'desc')->get();
            $data['news'] = [];
        }

        elseif ($data['query'] == 'News') {
            $data['news'] = News::orderby('news_id', 'desc')->get();
            $data['announcements'] = [];
        }

        elseif ($data['query'] == 'All') {
            $data['announcements'] = Announcements::orderby('announcement_id', 'desc')->get();
            $data['news'] = News::orderby('news_id', 'desc')->get();
        }
        elseif ($data['query'] == null) {
            $data['query'] = "All";
            $data['announcements'] = Announcements::orderby('announcement_id', 'desc')->get();
            $data['news'] = News::orderby('news_id', 'desc')->get();
        }
        return view('user.homepage', compact(["users"]), $data);
    }

    public function getLandingPage() {
        return view('landingPage');
    }

    public function getContactsPage() {
        $users = Alumni::where('alumni_id', '=', Auth::user()->alumni_id)->get();
        return view('user.contacts', compact('users'));
    }

    public function getAboutPage() {
        $users = Alumni::where('alumni_id', '=', Auth::user()->alumni_id)->get();
        return view('user.about', compact('users'));
    }
}
