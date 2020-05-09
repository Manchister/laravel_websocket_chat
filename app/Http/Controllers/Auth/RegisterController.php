<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;
    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/chatRoom';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('check.userId');
        $this->middleware('guest');
    }

    public function showRegistrationForm(Request $request, $id = 'none')
    {
        
        return view('auth.register', ['id' => $id]);
    }
    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
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

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */


    protected function create(array $data)
    {
        $admin_id = DB::table(config('admin.database.admin_uris_table'))
            ->where('uri', '=', $data['room_id'])->select('user_id')->get()->first()->{'user_id'};
        $userCreat = User::create([
            'name' => $data['name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'nick_name' => $data['nick_name'],
            'password' => Hash::make($data['password']),
            'created_by' => $admin_id,
        ]);
        //print_r($data);

        return $userCreat;

    }
    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $id = $request->get('room_id', 'default');
        if (DB::table(config('admin.database.admin_uris_table'))->where('uri', $id)->count() <= 0)
        {
            return back()->withInput()->withErrors([
                'room_id' => "لايوجد مشرف بهذا الاسم: "."$id",
            ]);
        };
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        $this->guard()->login($user);

        if ($response = $this->registered($request, $user)) {
            return $response;
        }

        return $request->wantsJson()
            ? new Response('', 201)
            : redirect($id.$this->redirectTo);
    }
}
