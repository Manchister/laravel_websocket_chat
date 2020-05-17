<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrivateMessages extends Model
{
    public function sender()
    {
        return $this->belongsTo('App\Models\User','user_id');
    }
    public function receiver()
    {
        return $this->belongsTo('App\Models\User','receiver_id');
    }
    public function room()
    {
        return $this->belongsTo('App\Models\Room','room_id');
    }
    public function conversation()
    {
        return $this->belongsTo('App\Models\Conversations','conversation_id');
    }

    public static function addMessage($_user_id, $_receiver_id, $_room_id, $_message, $_conversation_id)
    {
        $newMessage = new PrivateMessages();
        $newMessage->message = $_message;
        $newMessage->room_id = $_room_id;
        $newMessage->user_id = $_user_id;
        $newMessage->receiver_id = $_receiver_id;
        $newMessage->conversation_id = $_conversation_id;
        return $newMessage->save() ? true : false;
    }
}
