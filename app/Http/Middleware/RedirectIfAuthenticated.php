<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {

            if (Auth::user()->hasRole('Manager')){

                return redirect('/claims-list');
            }
            if (Auth::user()->hasRole('Assessor')) {

                return redirect('/assessor/assessments');

            }

            if (Auth::user()->hasRole('Head Assessor')) {

                return redirect('/head-assessor/claims');

            }

            if (Auth::user()->hasRole('Adjuster')) {

                return redirect('/adjuster/uploadClaims');

            }

            return redirect('/');

        }

        return $next($request);
    }
}
