<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();
        return view('dashboard',['user' => $user]);
    }
    public function markNotification(Request $request)
    {
        $notificationId = request('id');
        $userUnreadNotification = auth()->user()
            ->unreadNotifications
            ->where('id', $notificationId)
            ->first();
        if($userUnreadNotification) {
            $userUnreadNotification->markAsRead();
        }
    }
}
