<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\ChatEvent;
use App\Message;
use Auth;

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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $message = Message::with(['user'])->get();
        return view('home',['message'=> $message]);
    }

    public function chat(Request $request)
    {
        $message = new Message();
        $message->content = $request['message'];
        $message->date = $request['date'];
        $message->user_id = Auth::user()->id;
        $message->save();
        broadcast(new ChatEvent($message));
        return [
            'status' => true
        ];
    }
}
