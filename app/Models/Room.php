<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
    public function room_roles()
    {
        $pivotTable = 'room_role_rooms';

        $relatedModel = new RoomRole();

        return $this->belongsToMany($relatedModel, $pivotTable, 'room_id', 'role_id');
    }
    public static function showRoom($_id, $_userId)
    {
        $ids = User::getSupervisorIdsByCreator($_userId);
        $ids[] = $_userId;
        return Room::query()
            ->whereIn('user_id', $ids)
            ->findOrFail($_id);
    }

    public static function canEditRoom($_id, $_userId)
    {
        $ids = User::getSupervisorIdsByCreator($_userId);
        $ids[] = $_userId;
        return Room::query()
                ->whereIn('user_id', $ids)
                ->where('id', '=', $_id)
                ->count() > 0;
    }
    public static function getRooms($_id)
    {

        $userLevel = User::getUserLevel($_id);
        if ($userLevel < config('admin.admin_level.supervisor'))return array();
        if ($userLevel == config('admin.admin_level.supervisor'))
        {
            return self::getRoomsById($_id);
        }
        return self::getRoomsById(User::getCreatedBy($_id));

    }
    private static function getRoomsById($_supervisorId)
    {
        return self::where('user_id', '=', $_supervisorId)->get();
    }
}
