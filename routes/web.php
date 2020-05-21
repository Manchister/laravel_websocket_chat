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
})->name('domain');
/*Route::get('/test', 'RoomController@test');*/
Route::get('{id}/', 'Auth\LoginController@showLoginForm');

Auth::routes();

Route::get('{id?}/home', 'HomeController@index')->name('home');

Route::resource('{id?}/chatRoom', 'RoomController');
//Route::get('{id?}/anonymousChat', 'RoomController@anonymous')->name('anonymous');
Route::post('/anonymousChat', 'RoomController@anonymous')->name('anonymous');
Route::get('{id?}/rooms', 'RoomController@rooms')->name('rooms');
Route::get('{id?}/rooms/{room?}', 'RoomController@single')->name('single_room');
Route::post('/lu_actions', 'RoomController@loadUserActions')->name('load_user_actions');
Route::post('/check_user_can_write', 'RoomController@checkUserCanWrite')->name('check_user_can_write');
Route::post('/check_user_is_active', 'RoomController@checkUserIsActive')->name('check_user_is_active');
Route::post('/check_user_is_blocked', 'RoomController@checkUserIsBlocked')->name('check_user_is_blocked');

Route::post('/send_private_message', 'RoomController@sendPrivateMessage')->name('send_private_message');
Route::post('/check_new_private_chat', 'RoomController@checkNewPrivateChat')->name('check_new_private_chat');
Route::post('/load_conversation', 'RoomController@loadConversation')->name('load_conversation');
Route::post('/get_conversation_id', 'RoomController@getConversationId')->name('get_conversation_id');
Route::post('/set_seen', 'RoomController@setSeen')->name('set_seen');

Route::post('/send_public_message', 'RoomController@sendPublicMessage')->name('send_public_message');
Route::post('/check_new_public_messages', 'RoomController@checkNewPublicMessages')->name('check_new_public_messages');



Route::post('/receive_private_messages', 'RoomController@receivePrivateMessage')->name('receive_private_messages');
Route::post('/update_user_role', 'RoomController@updateUserRole')->name('update_user_role');

Route::get('/run_socket', function() {
    $output = [];
    \Artisan::call('websocket:init', $output);
    dd($output);
});
