<?php

namespace App\Http\Middleware;

use App\Admin\Controllers\AuthController;
use Illuminate\Support\Facades\Lang;
use Closure;
use Encore\Admin\Facades\Admin;
use Illuminate\Support\Facades\Auth;


class CheckIsAdmin extends AuthController
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if (Admin::user())return $next($request);
        if (!Auth::attempt(['username' => $request->get('username', null), 'password' => $request->get('password', null)])) {

            return $next($request);
        }
        if (!Auth::attempt(['username' => $request->get('username', null), 'password' => $request->get('password', null), 'is_admin' => 1])) {

            return back()->withInput()->withErrors([
                $this->username() => $this->getFailedLoginMessage(),
            ]);
            //return redirect('home');
        }

        return $next($request);
    }
    protected function getFailedLoginMessage()
    {
        return Lang::has('auth.notAdmin')
            ? trans('auth.notAdmin')
            : 'These credentials do not match our records.';
    }
}
