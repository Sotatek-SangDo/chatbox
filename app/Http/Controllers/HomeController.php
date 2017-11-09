<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\ChatEvent;
use App\Message;
use Auth;
use App\ChatRoom;

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

    public function home()
    {
        $chatRooms = ChatRoom::all();
        return view('welcome', ['chatRooms' => $chatRooms]);
    }

    public function index($id)
    {
        $message = Message::with(['user'])->where('chat_room_id', $id)->get();
        return view('home',['message'=> $message, 'id' => $id]);
    }

    public function chat(Request $request)
    {
        $message = new Message();
        $message->content = $request['message'];
        $message->date = $request['date'];
        $message->user_id = Auth::user()->id;
        $message->chat_room_id = $request['room_id'];
        $message->save();
        broadcast(new ChatEvent($message));
        return [
            'status' => true
        ];
    }

    public function check(Request $request)
    {
        $room = ChatRoom::where('id', $request['room_id'])
                        ->where('password', $request['pass'])
                        ->first();
        if(!$room) {
            return [
                'mess' => 'Password not correct!',
                'status' => false
            ];
        }
        return [
            'url' => '/room-'.$request['room_id'],
            'status' => true
        ];
    }
}
