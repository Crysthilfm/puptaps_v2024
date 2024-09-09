<?php

namespace App\Http\Middleware;

use App\Models\Alumni;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UnfinishedRegistration
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {

        $check_profile = Alumni::where('alumni_id', '=', Auth::user()->alumni_id)->value('profile_status');

        // if ($check_profile == "Complete") {
        //     return back();
        // }

        return $next($request);
    }
}
