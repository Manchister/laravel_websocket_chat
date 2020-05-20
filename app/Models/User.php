<?php

namespace App\Models;

use App\Admin\Models\UserRole;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

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

        if ($userLevel < 3) return array();
        if ($userLevel == 3) {
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

    public function isOnline()
    {
        return Cache::has('online-user' . $this->id);
    }

    public function isBlocked($room_id)
    {
        $userBlocked = DB::table('block_users')->where([
            ['user_id', '=', $this->id],
            ['room_id', '=', $room_id],
        ]);
        return $userBlocked->exists();
    }


//    protected $new_private_message = false;
//    protected $can_send_private_message = false;
//    protected $can_send_message = false;
//    protected $block_from_room = false;
//    protected $can_change_name_color = false;
//    protected $stop_user_account = false;

    public function userActions($room_id)
    {
        $actions = [
            auth()->user()->can('can_make_private_chat') || auth()->user()->user_level == 3,
            $this->can('can_write'),
            $this->can('can_make_private_chat'),
            $this->isBlocked($room_id),
            $this->can('can_change_name_color'),
            $this->can('active')
        ];
        return $this->generateUserActionsHtml($actions, $room_id);
    }

    private function generateUserActionsHtml($actions, $room_id)
    {
        $user_id = $this->id;
        $new_private_message = $actions[0] ? '<button class="dropdown-item private_chat" data-username="'.$this->nick_name.'" data-user_id="' . $user_id . '" onclick="startPrivateChat($(this))" href="#">إرسال رسالة خاصة</button>' : '';

        $can_send_message = !$actions[1] ? '<a class="dropdown-item block_message_send" data-user_id="' . $user_id . '" onclick="cancelAccountDisabled($(this))" id="' . $user_id . '" href="#">إلغاء الكتم</a>' :
            '<a class="dropdown-item my_modal_class block_message_send" id="' . $user_id . '" href="#user_settings_model"
            data-body="إختر مدة الكتم" data-title="إعدادات الكتم" data-role-id="1" data-user-id="' . $user_id . '" data-room-id="' . $room_id . '" 
            data-toggle="modal">كتم</a>';

        $can_send_private_message = $actions[2] ? '<a class="dropdown-item private_message" id="' . $user_id . '" href="#">عدم السماح بإرسال رسائل خاصة</a>' :
            '<a class="dropdown-item private_message" id="' . $user_id . '" href="#">السماح بإرسال رسائل خاصة</a>';

        $block_from_room = !$actions[3] ? '<a class="dropdown-item block_from_room" data-user_id="' . $user_id . '" onclick="cancelBlockFromRoom($(this))" id="' . $user_id . '" href="#">إلغاء الطرد</a>' :
            '<a class="dropdown-item my_modal_class block_from_room" id="' . $user_id . '" href="#user_settings_model"
            data-body="إختر مدة الطرد" data-title="إعدادات الطرد" data-role-id="3" data-user-id="' . $user_id . '"
            data-room-id="' . $room_id . '" data-toggle="modal">طرد من الغرفة</a>';

        $can_change_name_color = $actions[4] ? '<a class="dropdown-item change_color"  id="' . $user_id . '" href="#" >عدم السماح بتغيير لون الاسم</a>' :
            '<a class="dropdown-item change_color"  id="' . $user_id . '" href="#" >السماح بتغيير لون الاسم</a>';

        $active = !$actions[5] ? '<a class="dropdown-item stop_account" data-user_id="' . $user_id . '" onclick="cancelAccountDisabled($(this))" id="' . $user_id . '" href="#" >تفعيل الحساب</a>' :
            '<a class="dropdown-item stop_account" id="' . $user_id . '" href="#user_settings_model"
            data-body="إختر مدة الإيقاف" data-title="إعدادات الإيقاف" data-role-id="2" data-user-id="' . $user_id . '" data-room-id="' . $room_id . '" 
            data-toggle="modal" >إيقاف الحساب</a>';

        return $new_private_message . $can_send_message . $can_send_private_message . $block_from_room . $can_change_name_color . $active;
    }


    public function conversations()
    {
        return $this->hasMany('App\Models\Conversations',['user_one','user_two']);
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($user) {
            $user->user_roles()->attach([1, 2, 4, 5]);
        });
        static::deleted(function ($user) {
            $user->user_roles()->detach([1, 2, 4, 5]);
        });
    }

}
