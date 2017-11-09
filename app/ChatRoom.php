<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChatRoom extends Model
{
    public $table = 'chat_rooms';

    public $fillable = ['id', 'room_name', 'password'];
}
