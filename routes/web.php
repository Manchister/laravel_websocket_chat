<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
/*Route::get('/test', 'RoomController@test');*/
Route::get('{id}/', 'Auth\LoginController@showLoginForm');

Auth::routes();

Route::get('{id?}/home', 'HomeController@index')->name('home');

Route::resource('{id?}/chatRoom', 'RoomController');
Route::get('/run_socket', function() {
    $output = [];
    \Artisan::call('websocket:init', $output);
    dd($output);
});
