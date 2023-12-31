<?php

namespace App\Http\Controllers\Auth;

use App\Conf\Config;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
//    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    public function showLoginForm()
    {
        return view('authentication.user-login');
    }
    public function logout(Request $request) {
        Auth::logout();
        return redirect('/login');
    }
    protected function authenticated($request, $user)
    {
        if($user->hasRole('Adjuster'))
        {
            return redirect('/home');
        }
        else if($user->hasRole(Config::$ROLES['ASSESSOR']))
        {
            return redirect('/home');
        }
        else if($user->hasRole(Config::$ROLES['ADJUSTER']))
        {
            return redirect('/home');
        }else if($user->hasRole(Config::$ROLES['HEAD-ASSESSOR']))
        {
            return redirect('/home');
        }
        else if($user->hasRole(Config::$ROLES['ASSISTANT-HEAD']))
        {
            return redirect('/home');
        }
        else if($user->hasRole(Config::$ROLES['MANAGER']))
        {
            return redirect('/home');
        }
        else if($user->hasRole(Config::$ROLES['ASSESSMENT-MANAGER']))
        {
            return redirect('/home');
        }
        else if($user->hasRole(Config::$ROLES['RE-INSPECTION-OFFICER']))
        {
            return redirect('/home');
        }
    }
}
