<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RoomController extends Controller
{


    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(Request $request, $id = 'none')
    {
        if (Auth::user()->user_level == 3) {
            $created_by = Auth::id();
        } else {
            $created_by = Auth::user()->created_by;
        }


        if (DB::table(config('admin.database.admin_uris_table'))->where('uri', $id)->where('user_id', $created_by)->count() <= 0) {
            Auth::logout();

            return redirect('');
        };
        // var_dump($request->user());
        //$user = new User();
        //echo (User::isAdmin())?"yes, he is admin": "no, he is not admin";
        return view('room.index', ['id' => $id]);
    }

    public function edit(Request $request, $id = 'none')
    {
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
//        dd($request);
        $user = Auth::user();
        $data = $this->validate($request, [
//            'name' => ['required', 'string', 'max:255'],
            'nick_name' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $name_color = isset($request->name_color) ? $request->name_color : (($user->name != '')?$user->name:'#ffffff');
        $user->name = $name_color;
        $user->nick_name = $data['nick_name'];
        if ($data['password'] != '**********') {
            $user->password = $data['password'];
        }


        $user->save();
        return ['message' => 'تم تعديل معلوماتك بنجاح','name_color'=> $name_color];
        //return redirect("/$parentUri".'/chatRoom/')->with('success', 'User has been updated!!');
        //return "$parentUri == $userId == $user";
    }

    public function create()
    {
        return "create";
    }

    public function test()
    {
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
