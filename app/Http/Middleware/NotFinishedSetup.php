<?php

namespace App\Http\Middleware;

use App\Models\Alumni;
use App\Models\Tracer\TracerAnswers;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotFinishedSetup
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
        $profile_status = Alumni::where('alumni_id', '=', Auth::user()->alumni_id)->value('profile_status');
        //$tracer_answers = TracerAnswers::where('alumni_id', '=', Auth::user()->alumni_id)->first();

        // if($profile_status == 'Incomplete' || $tracer_answers == null) {
        //     return redirect(route('userProfile.set-up'));
        // }
        if($profile_status == 'Incomplete') {
            return redirect(route('userProfile.set-up'));
        }

        return $next($request);
    }
}
