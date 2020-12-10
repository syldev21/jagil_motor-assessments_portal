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

                return redirect('/home');
            }
            if (Auth::user()->hasRole('Assessor')) {

                return redirect('/home');

            }

            if (Auth::user()->hasRole('Head Assessor')) {

                return redirect('/home');

            }

            if (Auth::user()->hasRole('Adjuster')) {

                return redirect('/home');

            }
            if (Auth::user()->hasRole('Manager')) {

                return redirect('/home');

            }
            if (Auth::user()->hasRole('Assessment Manager')) {

                return redirect('/home');

            }

            return redirect('/');

        }

        return $next($request);
    }
}
