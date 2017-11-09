<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Message extends Model
{
    public $table = 'messages';

    public $fillable = ['content', 'date', 'user_id', 'chat_room_id'];

    public function getDateAttribute()
    {
        return str_limit($this->attributes['date'], 5, ' ');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
