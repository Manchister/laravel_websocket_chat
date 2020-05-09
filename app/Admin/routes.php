<?php

use Encore\Admin\Facades\Admin;
use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->resource('admins', 'AdminController');
    $router->resource('supervisors', 'SupervisorController');
    $router->resource('users', 'SupervisorsUserController');
    $router->resource('rooms', 'SupervisorsRoomController');
    $router->get('/', 'HomeController@index')->name('admin.home');
    //$router->resource('auth/users', 'UserAdminController');
    //$router->resource('supervisor/users', 'UserController');
    //$router->resource('rooms', 'RoomControllers');


});
