<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Message;

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
        $users = User::where('id', '!=', Auth::id())->get();
        return view('home', ['users' => $users]);
    }
    public function getMessage($user_id, $my_id)
    {
        $my_id = Auth::id();
        $messages = Message::where(function ($query) use ($user_id, $my_id){
            $query->where('from', $my_id)->where('to', $user_id);
        })->orWhere(function ($query) use ($user_id, $my_id){
            $query->where('from', $user_id)->where('to', $my_id);
        })->get();

        return view('messages.index', ['messages'=>$messages]);
    }
}
