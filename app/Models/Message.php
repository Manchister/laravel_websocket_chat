<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public static function addMessage($_userId, $_roomId, $_message)
    {
        $newMessage = new Message();
        $newMessage->message = $_message;
        $newMessage->room_id = $_roomId;
        $newMessage->user_id = $_userId;

        return $newMessage->save() ? $newMessage:false;
    }
}
