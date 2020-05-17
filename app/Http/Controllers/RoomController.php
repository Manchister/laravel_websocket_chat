<?php

namespace App\Http\Controllers;

use App\Models\BlockUsers;
use App\Models\Conversations;
use App\Models\Room;
use App\Models\User;
use App\Models\PrivateMessages;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Hoss\Hoss;
use Illuminate\Support\Facades\Hash;

class RoomController extends Controller
{

    protected $anonymous_domain = 'anonymous.com';

    use RegistersUsers;

    public function anonymous($admin)
    {
        if (DB::table(config('admin.database.admin_uris_table'))->where('uri', $admin)->count() <= 0) {
            return back()->withInput()->withErrors([
                'room_id' => "لايوجد مشرف بهذا الاسم: " . "$admin",
            ]);
        } else {
            $admin_id = DB::table(config('admin.database.admin_uris_table'))->where('uri', $admin)->get()->first()->user_id;
        }
        if (Auth::guest()) {
            $id = Hoss::generateGuestID();
            if (User::query()->where('username', $id)->count() > 0) {
                $user = User::query()->where('username', $id)->first();
            } else {
                $nick_name = 'Guest' . rand(50, 100) . $id;
                $data = [
                    'name' => '#f00000',
                    'username' => $id,
                    'email' => $id . $this->anonymous_domain,
                    'nick_name' => $nick_name,
                    'password' => $id,
                    'admin_id' => $admin_id,
                ];
//                $user = $this->createUser($data);
                event(new Registered($user = $this->createUser($data)));
                $this->createUserRoles($user);
            }

            $this->guard()->login($user);

            return redirect($admin);
        } else {
            return redirect($admin);
        }
    }

    protected function createUser(array $data)
    {
        $userCreate = User::create([
            'name' => $data['name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'nick_name' => $data['nick_name'],
            'password' => Hash::make($data['password']),
            'created_by' => $data['admin_id'],
        ]);
        return $userCreate;
    }

    protected function createUserRoles($user)
    {
        for ($i = 1; $i < 6; $i++) {
            DB::table('user_role_users')->insert(['user_id' => $user->id, 'role_id' => $i]);
        }
    }

    public function updateUserRole(Request $request)
    {
        $this->middleware(['auth']);
        if (Auth::user()->user_level == 3) {
            $user_id = $request->user_id;
            $role_id = $request->role_id;
            if ($role_id == 100) {
                $room_id = $request->room_id;
                $block_time = $request->block_time;
                $userBlocked = DB::table('block_users')->where([
                    ['user_id', '=', $user_id],
                    ['role_id', '=', $role_id],
                    ['room_id', '=', $room_id],
                ]);
                if ($userBlocked->exists()) {
                    return $userBlocked->delete() ? ['status' => 'success', 'do' => 0] : ['status' => 'error'];
                } else {
                    $blockTime = (isset($data->blockTime)) ? $data->blockTime : 0;
                    $blockUsers = new BlockUsers();
                    $blockUsers->room_id = $room_id;
                    $blockUsers->user_id = $user_id;
                    $blockUsers->role_id = $role_id;
                    $blockUsers->blocker_id = Auth::user()->id;
                    $blockUsers->block_until = $block_time;
                    return $blockUsers->save() ? ['status' => 'success', 'do' => 1] : ['status' => 'error'];
//                    $this->userresources[$data->channel][$data->userId][$roleSlug] = true;
                }
            } else {
                $userRole = DB::table('user_role_users')->where([
                    ['user_id', '=', $user_id],
                    ['role_id', '=', $role_id],
                ]);
                if ($userRole->exists()) {
                    return $userRole->delete() ? ['status' => 'success', 'do' => 0] : ['status' => 'error'];
                } else {
                    $addRole = DB::table('user_role_users')->insert(['user_id' => $user_id, 'role_id' => $role_id]);
                    return $addRole ? ['status' => 'success', 'do' => 1] : ['status' => 'error'];
                }
            }
        } else {
            return ['status' => 'error'];
        }
    }

    public function rooms($id)
    {
        $this->middleware(['auth']);
        if (Auth::user()->user_level == 3) {
            $created_by = Auth::id();
        } else {
            $created_by = Auth::user()->created_by;
        }

        $rooms = [];
        if (DB::table(config('admin.database.admin_uris_table'))->where('uri', $id)->where('user_id', $created_by)->count() <= 0) {
            Auth::logout();

            return redirect('');
        } else {
            $rooms = Room::getRooms($created_by);
        }

        return view('room.index', ['rooms' => $rooms, 'id' => $id]);
    }

    // $id = $admin
    public function single($id, $room)
    {
        $this->middleware(['auth']);
        if (isset(Auth::user()->user_level)) {
            if (Auth::user()->user_level == 3) {
                $created_by = Auth::id();
            } else {
                $created_by = Auth::user()->created_by;
            }
        } else {
            return redirect('');
        }
        $rooms = [];
        if (DB::table(config('admin.database.admin_uris_table'))->where('uri', $id)->where('user_id', $created_by)->count() <= 0) {
            Auth::logout();

            return redirect('');
        } else {
            $rooms = Room::getRooms($created_by);
        }

        $room = Room::find($room);
        $room_users = $room->roomUsers();
//        dd($room_users);
//        dd($room->id);
//        dd($online_users);
//        $last_200_msg =

        return view('room.single', ['id' => $id, 'rooms' => $rooms, 'room' => $room, 'supervisor' => $created_by, 'room_users' => $room_users]);
    }

    public function loadUserActions(Request $request)
    {
        $this->middleware(['auth']);
        $items = User::find($request->he)->userActions($request->room_id);
        return $items ? ['status' => 'success', 'items' => $items] : ['status' => 'error', 'items' => ''];
    }

    public function sendPrivateMessage(Request $request)
    {
        $this->middleware(['auth']);
        if ($this->checkPrivateChatAbility($request->me, $request->he, $request->room_id)) {
            return Conversations::existsOrCreate($request->me, $request->he, $request->room_id, $request->message);
        } else {
            return false;
        }
    }

    protected function checkPrivateChatAbility($me_id, $he_id, $room_id): bool
    {
        if ($me_id != Auth::id()) {
            dd('01');
            return false;
        }
        $me = auth()->user();
        if (auth()->user()->user_level == 3 || ($me->can('active') && $me->can('can_make_private_chat'))) {
            if (Room::find($room_id)->exists()) {
//                $room = Room::find($room_id);
//                $created_by = $room->created_by;
                $he = User::find($he_id);
            } else {
                return false;
            }
            if (($he->created_by == $me_id) || ($me->created_by == $he->created_by) || ($me->created_by == $he_id) || $this->isAdminStartedChattingMe($he)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    protected function isAdminStartedChattingMe($admin): bool
    {
        return $admin->is_admin ? $this->isUserChattedMeBefore($admin->id) : false;
    }

    protected function isUserChattedMeBefore($user_id): bool
    {
        return DB::table('private_messages')->where(['private_messages.receiver_id' => Auth::id(), 'private_messages.user_id' => $user_id])->exists();
    }

    public function checkNewPrivateChat(Request $request)
    {
        $this->middleware(['auth']);
        return $this->newPrivateChatUsers($request->room_id);
    }
    private function newPrivateChatUsers($room_id)
    {
        return DB::table('private_messages')->where(['private_messages.receiver_id' => Auth::id(), 'private_messages.is_seen' => 0])
            ->join('users', 'private_messages.user_id', '=', 'users.id')
            ->get(['user_id','users.nick_name','private_messages.conversation_id'])->unique()->toArray();
    }

    public function loadConversation(Request $request)
    {
        $this->middleware(['auth']);
        $conversation =  Conversations::find($request->conversation_id);
        return $conversation->messages()->get()->reverse()->values();
    }


    public function getConversationId(Request $request)
    {
        $this->middleware(['auth']);
        return  Conversations::getId(Auth::id(),$request->user_id);
    }



    public function receivePrivateMessage(Request $request)
    {
        $this->middleware(['auth']);
        return $this->retrievePrivateMessage($request->room_id);
    }
    private function retrievePrivateMessage($room_id)
    {
        $messages = DB::table('private_messages')->where(['private_messages.receiver_id' => Auth::id(), 'private_messages.is_seen' => 0])
            ->join('users', 'private_messages.user_id', '=', 'users.id')
            ->orderBy('private_messages.created_at', 'desc')->take(25)
            ->get(['user_id', 'receiver_id', 'message', 'users.nick_name', 'private_messages.created_at'])->groupBy('private_messages.user_id')->toArray();


//        $messages = PrivateMessages::query()->where(['private_messages.receiver_id'=>Auth::id(),'private_messages.is_seen'=>0])
//            ->leftJoin('users', 'private_messages.user_id', '=', 'users.id')
//            ->latest()->take(25)->get()->groupBy('private_messages.user_id');
//        return response()->json($messages);
        return $messages[''];
    }


    public function index(Request $request, $id = 'none')
    {
        $this->middleware(['auth']);
        if (Auth::user()->user_level == 3) {
            $created_by = Auth::id();
        } else {
            $created_by = Auth::user()->created_by;
        }


        if (DB::table(config('admin.database.admin_uris_table'))->where('uri', $id)->where('user_id', $created_by)->count() <= 0) {
            Auth::logout();

            return redirect('');
        };

        return view('room.index', ['id' => $id]);
        // var_dump($request->user());
        //$user = new User();
        //echo (User::isAdmin())?"yes, he is admin": "no, he is not admin";
    }

    public function edit(Request $request, $id = 'none')
    {
        $this->middleware(['auth']);
        return "okey";
    }

    protected function validator(array $data)
    {

        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'nick_name' => ['required', 'string', 'max:255'],
            'room_id' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    public function update(Request $request, $parentUri = 'none', $userId = 'none')
    {
        $this->middleware(['auth']);
        $user = Auth::user();
        $data = $this->validate($request, [
//            'name' => ['required', 'string', 'max:255'],
            'nick_name' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $name_color = isset($request->name_color) ? $request->name_color : (($user->name != '') ? $user->name : '#ffffff');
        $user->name = $name_color;
        $user->nick_name = $data['nick_name'];
        if ($data['password'] != '**********') {
            $user->password = $data['password'];
        }


        $user->save();
        return ['message' => 'تم تعديل معلوماتك بنجاح', 'name_color' => $name_color];
        //return redirect("/$parentUri".'/chatRoom/')->with('success', 'User has been updated!!');
        //return "$parentUri == $userId == $user";
    }

    public function create()
    {
        $this->middleware(['auth']);
        return "create";
    }

    public function test()
    {
        $this->middleware(['auth']);
        $text = "==";
        //$userId = User::find(Auth::id())->user_roles;
        $userId = Auth::user()->can('can_write');
        //$user = Auth::user()->can('can_write');
        //return $user;
        /*        foreach ($userId as $role) {

                    $text .= "=====$role";

                }*/
        return $userId;
    }

}
