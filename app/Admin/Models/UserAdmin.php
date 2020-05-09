<?php
/**
 * Created by PhpStorm.
 * User: Alna7ari
 * Date: 31/03/2020
 * Time: 04:47 ص
 */

namespace App\Admin\Models;


use App\Models\User;
use Encore\Admin\Auth\Database\Administrator;

class UserAdmin extends Administrator
{
    public function user_roles()
    {
        $pivotTable = 'user_role_users';

        $relatedModel = UserRole::class;

        return $this->belongsToMany($relatedModel, $pivotTable, 'user_id', 'role_id');
    }
    public static function showAdmin($_adminLevel, $_id, $_userId = null)
    {
        $userLevel = User::getUserLevel($_userId);
        if ($userLevel >= 2) {
            return User::query()
                ->where('user_level', '=', $_adminLevel)
                ->where('created_by', '=', $_userId)
                ->findOrFail($_id);
        }
        return User::query()
            ->where('user_level', '=', $_adminLevel)
            ->findOrFail($_id);
    }

    public static function canEdit($_adminLevel, $_id, $_userId = null)
    {
        $userLevel = User::getUserLevel($_userId);
        if ($userLevel >= 2) {
            return User::query()
                    ->where('user_level', '=', $_adminLevel)
                    ->where('created_by', '=', $_userId)
                    ->where('id', '=', $_id)
                    ->count() > 0;
        }
        return User::query()
                ->where('user_level', '=', $_adminLevel)
                ->where('id', '=', $_id)
                ->count() > 0;
    }
    public function admin_uri()
    {
        return $this->hasOne('App\Models\AdminUri', 'user_id', 'id');
    }
}