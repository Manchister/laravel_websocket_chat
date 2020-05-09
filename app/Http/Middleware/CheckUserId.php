<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckUserId
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

//        if ($request->method() != "GET" && Auth::check())
//        {
//
//            $id = $request->get('id', 'default');
//            $created_by = $request->user()->created_by;
//            Auth::logout();
//            return back()->withInput()->withErrors([
//                'username' => "$id ==== $created_by",
//            ]);
//                $id = $request->get('id', 'default');
//                $created_by = $request->user()->created_by;
//
//                if (DB::table(config('admin.database.admin_uris_table'))->where('uri', $id)->where('user_id', $created_by)->count() <= 0)
//                {
//                    return back()->withInput()->withErrors([
//                        'username' => "هذا المستخدم غير مسجل في سجلاتنا.",
//                    ]);
//                };
//
//        }


        if (Auth::check())
        {

            $adminUrl = $request->route()->parameter('id');
            return redirect("$adminUrl/chatRoom");
        }

        /*$adminUrl = $request->route()->parameter('id');
        if (empty($adminUrl) || is_null($adminUrl))$adminUrl = 'none';
        //echo $adminUrl;
        if (DB::table(config('admin.database.admin_uris_table'))->where('uri', $adminUrl)->count() <= 0 && $request->method() == "GET")
        {
            //echo DB::table('users')->where('admin_url', $request->get('id', 1))->count();
            return redirect("$adminUrl/");
           // return response()->view('auth.register', [], 500);
        };
        if ($request->method() != "GET")
        {
            if (DB::table(config('admin.database.admin_uris_table'))->where('uri', $request->all()['room_id'])->count() <= 0){
                return redirect("$adminUrl/");
               // return response()->view('auth.register', [], 500);
            }
        };*/

        return $next($request);
    }
}
