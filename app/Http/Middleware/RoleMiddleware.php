<?php



namespace ClaimSurvey\Http\Middleware;



use Closure;

use Illuminate\Support\Facades\Auth;



class RoleMiddleware

{

    /**

     * Handle an incoming request.

     *

     * @param  \Illuminate\Http\Request  $request

     * @param  \Closure  $next

     * @return mixed

     */

    public function handle($request, Closure $next, $role) {

        if (Auth::guest()) {

            return redirect('/login');

        }



        if (! $request->user()->hasRole($role)) {

            return redirect('/index');

        }



        return $next($request);

    }

}

