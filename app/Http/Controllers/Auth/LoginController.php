<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Parent_;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/rooms';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('check.userId')->except('logout');
        $this->middleware('guest')->except('logout');

    }
    public function showLoginForm(Request $request, $id = 'none')
    {
        if (DB::table(config('admin.database.admin_uris_table'))->where('uri', $id)->count() <= 0)
        {
            return redirect('/');
        };
        //$this->redirectTo = $id."/chatRoom";
        return view('auth.login', ['id' => $id]);
    }
    public function login(Request $request)
    {
        $this->validateLogin($request);

        $id = $request->get('id', 'default');


        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            if (Auth::user()->user_level == 3)
            {
                $created_by = Auth::id();
            }
            else
            {
                $created_by = Auth::user()->created_by;
            }

            if (DB::table(config('admin.database.admin_uris_table'))->where('uri', $id)->where('user_id', $created_by)->count() <= 0)
            {
                $this->guard()->logout();

                $request->session()->invalidate();

                $request->session()->regenerateToken();

                return back()->withInput()->withErrors([
                    'username' => "هذا المستخدم غير مسجل في سجلاتنا.",
                ]);
            };
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    public function username()

    {

        return 'username';

    }

    /*protected function authenticated(Request $request, $user)
    {
        redirect('/homeee');
    }*/

    protected function sendLoginResponse(Request $request)
    {
        $id = $request->get('id', 'default');

        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        if ($response = $this->authenticated($request, $this->guard()->user())) {

            return $response;
        }

        return $request->wantsJson()
            ? new Response('', 204)
            : redirect()->intended($id.$this->redirectTo);
    }
    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        if ($response = $this->loggedOut($request)) {
            return $response;
        }

        return $request->wantsJson()
            ? new Response('', 204)
            : redirect($request->get('id', 'default'));
    }
}
