<?php

namespace App\Http\Middleware;

use App\Models\Alumni;
use App\Models\Tracer\TracerAnswers;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UnansweredTracer
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
        $tracer_answers = TracerAnswers::where('alumni_id', '=', Auth::user()->alumni_id)->get();

        if (count($tracer_answers) > 0) {
            return back();
        }

        return $next($request);
    }
}
