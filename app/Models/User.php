<?php

namespace App\Models;

use App\Admin\Models\UserRole;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Arr;

class User extends Authenticatable
{
    use Notifiable;
    use HasPermissions;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'name', 'nick_name', 'email', 'password', 'created_by',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function getNameAttribute($value)
    {
        return ucfirst($value);
    }

    public function user_roles()
    {
        $pivotTable = 'user_role_users';

        $relatedModel = UserRole::class;

        return $this->belongsToMany($relatedModel, $pivotTable, 'user_id', 'role_id');
    }

    public function block_user()
    {
        return $this->hasMany('App\Models\BlockUsers', 'user_id', 'id');
    }
    public function room()
    {
        return $this->hasMany('App\Models\Room');
    }

    public function message()
    {
        return $this->hasMany('App\Models\Message');
    }

    public function admin_uri()
    {
        return $this->hasOne('App\Models\AdminUri');
    }



    public static function showUser($_id, $_userId)
    {
        $ids = User::getSupervisorIdsByCreator($_userId);
        $ids[] = $_userId;
        return User::query()
            ->where('user_level', '>', config('admin.admin_level.supervisor'))
            ->whereIn('created_by', $ids)
            ->findOrFail($_id);
    }

    public static function canEditUser($_id, $_userId)
    {
        $ids = User::getSupervisorIdsByCreator($_userId);
        $ids[] = $_userId;
        return User::query()
                ->where('user_level', '>', config('admin.admin_level.supervisor'))
                ->whereIn('created_by', $ids)
                ->where('id', '=', $_id)
                ->count() > 0;
    }



    public static function getSupervisorIdsByCreator($_createdBy)
    {
        $users = User::query()
            ->where('user_level', '=', config('admin.admin_level.supervisor'))
            ->where('created_by', '=', $_createdBy)
            ->select('id')->get()->toArray();
        return Arr::pluck($users, 'id');
    }

    public static function getUserLevel($_id)
    {
        return User::find($_id)->user_level;
    }
    public static function getCreatedBy($_id)
    {
        return User::find($_id)->created_by;
    }
    public static function getSupervisorUsers($_userId)
    {
        $userLevel = User::getUserLevel($_userId);

        if ($userLevel < 3)return array();
        if ($userLevel == 3)
        {
            return User::query()
                ->where('created_by', '=', $_userId)->get();
        }
        $supervisorId = self::getCreatedBy($_userId);
        return User::query()
            ->where('created_by', '=', $supervisorId)->get();

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
    protected static function boot()
    {
        parent::boot();

        static::created(function($user) {
            $user->user_roles()->attach([1,2,4,5]);
        });
        static::deleted(function($user) {
            $user->user_roles()->detach([1,2,4,5]);
        });
    }

}
