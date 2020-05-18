<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Conversations extends Model
{
    protected $fillable = ['user_one', 'user_two', 'room_id'];

    public function userOne()
    {
        return $this->belongsTo('App\Models\User', 'user_one');
    }

    public function userTwo()
    {
        return $this->belongsTo('App\Models\User', 'user_two');
    }

    public function room()
    {
        return $this->belongsTo('App\Models\Room', 'room_id');
    }


    public function allMessages()
    {
        return $this->hasMany('App\Models\PrivateMessages', 'conversation_id');
    }


    public function messages()
    {
        return $this->hasMany('App\Models\PrivateMessages', 'conversation_id')
            ->latest()->take(10);
    }

    public static function getId($sender, $receiver)
    {
        $query = DB::table('conversations')->whereIn('conversations.user_one',[$sender,$receiver])
            ->orWhereIn('conversations.user_two',[$sender,$receiver]);

        return $query->exists() ? $query->first()->id : false;
    }


    public static function existsOrCreate($sender, $receiver, $room_id,$message)
    {
        $query = DB::table('conversations')->whereIn('conversations.user_one',[$sender,$receiver])
            ->orWhereIn('conversations.user_two',[$sender,$receiver]);

        if($query->exists()) {
            $conversation_id = $query->first()->id;
            return PrivateMessages::addMessage($sender, $receiver, $room_id, $message,$conversation_id);
        } else {
            self::addConversation($sender, $receiver, $room_id, $message);
        }

    }


    public static function addConversation($_user_id, $_receiver_id, $_room_id, $_message)
    {
        $newConversation = new Conversations();
        $newConversation->room_id = $_room_id;
        $newConversation->user_one = $_user_id;
        $newConversation->user_two = $_receiver_id;
        return $newConversation->save()
            ? PrivateMessages::addMessage($_user_id, $_receiver_id, $_room_id, $_message, $newConversation->id) : false;
    }

}
