<?php

namespace App\Http\Controllers;

use App as AppAlias;
use App\Admin\Models\UserRole;
use App\Models\BlockUsers;
use App\Models\Message;
use App\Models\Room;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Session\SessionManager;
use Illuminate\Support\Facades\DB;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

/**
 * @author Rohit Dhiman | @aimflaiims
 */
class WebSocketController extends Controller implements MessageComponentInterface
{
    protected $clients;
    private $subscriptions;
    private $users;
    private $userresources;
    private $usersroom;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
        $this->subscriptions = [];
        $this->users = [];
        $this->userresources = [];
        $this->usersroom = [];
    }

    private function register($user, ConnectionInterface $conn)
    {
        /*if (isset($user->id)) {

            if (isset($this->userresources[$user->id])) {
                if (!in_array($conn->resourceId, $this->userresources[$user->id])) {
                    //$this->userresources[$data->userId][] = $conn->resourceId;
                    $this->userresources[$user->id]['resourceId'] = $conn->resourceId;
                    $this->userresources[$user->id]['userName'] = $user->nick_name;
                }
            } else {
                $this->userresources[$user->id] = [];
                $this->userresources[$user->id]['resourceId'] = $conn->resourceId;
                $this->userresources[$user->id]['userName'] = $user->nick_name;
            }
        }*/
        //$conn->send(json_encode($this->users));

    }

    private function groupChat($user, $data, $conn, $onSubscribe = false)
    {

        $resourceId = $conn->resourceId;
        // $conn->send(json_encode($this->subscriptions));
        if (isset($this->subscriptions[$conn->resourceId])) {
            $target = $this->subscriptions[$conn->resourceId];
            foreach ($this->subscriptions as $id => $channel) {
                if ($channel == $target && $id != $resourceId) {
                    if ($onSubscribe && is_null($data)) {
                        $result['type'] = "un_subscribe_notif";
                        $result['id'] = $user['id'];

                    } else if ($onSubscribe && !is_null($data)) {
                        $result['type'] = "subscribe_notif";;
                        $result['id'] = $user->id;
                        $result['nick_name'] = $user->nick_name;
                        $result['cantWrite'] = $user->is_block_from($data->roomId, 2);
                        $result['cantChangeColor'] = $user->is_block_from($data->roomId, 3);
                        $result['cantSendPrivateMessage'] = $user->is_block_from($data->roomId, 5);
                    } else {
                        $result['id'] = 1;
                        $result['type'] = "room_chat";
                        $result['message'] = $data->message;
                        $result['from'] = $user->id;
                        $result['nick_name'] = $user->nick_name;
                        $result['name'] = $user->name;
                        $result['created_at'] = date('Y-m-d H:i:s');
                    }

                    $this->users[$id]->send(json_encode($result));
                }
            }
            if (!$onSubscribe) {
                Message::addMessage($user->id, $data->roomId, $data->message);
            }
        }
    }

    private function auth_user($conn)
    {
        $session = (new SessionManager(AppAlias::getInstance()))->driver();
        // Get the cookies
        $cookies_header = $conn->httpRequest->getHeader('Cookie');
        $cookies = \GuzzleHttp\Psr7\parse_header($cookies_header)[0];
        // Get the laravel's one
        $cookie = urldecode($cookies[Config::get('session.cookie')]);
        // get the user session id from it
        $session_id = Crypt::decryptString($cookie);
        // Set the session id to the session handler
        $session->setId($session_id);
        $conn->session = $session;
        $conn->session->start();
        $user_id = $conn->session->get(Auth::getName());
        return !empty($user_id) ? \App\Models\User::find($user_id) : null;
    }

    /**
     * [onOpen description]
     * @method onOpen
     * @param ConnectionInterface $conn [description]
     * @return [JSON]                    [description]
     * @example connection               var conn = new WebSocket('ws://localhost:8090');
     */

    public function onOpen(ConnectionInterface $conn)
    {
        $user = $this->auth_user($conn);
        if (is_null($user) || !isset($user->id)) {
            $conn->close();
            return;
        }

        $this->clients->attach($conn);
        //$this->users[$conn->resourceId] = $conn;
        $this->users[$conn->resourceId] = $conn;
        $this->register($user, $conn);


        //print_r($user->nick_name);
    }

    function userSettings($user, $data, ConnectionInterface $conn)
    {


        if (!$user->can('is_room_supervisor') && !($user->user_level == 3)) return;
        $roleId = null;
        $roleSlug = null;

        switch ($data->roleId) {
            case "1";
                // cant_write
                $roleId = 2;
                $roleSlug = 'cantWrite';
                break;
            case "2";
                // block_from_room
                $roleId = 100;
                $roleSlug = 'block_from_room';
                if (is_null($roleId)) return;
                if ($roleId == 1 && $user->user_level != 3) return;
                $userBlocked = DB::table('block_users')->where([
                    ['user_id', '=', $data->userId],
                    ['role_id', '=', $roleId],
                    ['room_id', '=', $data->roomId],
                ]);
                if ($userBlocked->exists()) {
                    //user has role
                    $this->userresources[$data->channel][$data->userId][$roleSlug] = false;
                    $userBlocked->delete();
                } else {
                    $blockTime = (isset($data->blockTime)) ? $data->blockTime : 0;
                    $blockUsers = new BlockUsers();
                    $blockUsers->room_id = $data->roomId;
                    $blockUsers->user_id = $data->userId;
                    $blockUsers->role_id = $roleId;
                    $blockUsers->blocker_id = $user->id;
                    $blockUsers->block_until = $blockTime;

                    $blockUsers->save();
                    $this->userresources[$data->channel][$data->userId][$roleSlug] = true;
                    unset($this->userresources[$data->channel][$data->userId]);
                    $this->users[$data->userId]->close();
                }
                return;
                break;
            case "3";
                // change_color
                $roleId = 3;
                $roleSlug = 'cantChangeColor';
                break;
            case "4";
                // private_message
                $roleId = 5;
                $roleSlug = 'cantSendPrivateMessage';
                break;
            case "5";
                // stop_account
                $roleId = 1;
                $roleSlug = 'active';
                break;
        }
        if (is_null($roleId)) return;
        if ($roleId == 1 && $user->user_level != 3) return;
//        if (!$user->can('is_room_supervisor') && !($user->user_level == 3))return;

        $userRole = DB::table('user_role_users')->where([
            ['user_id', '=', $data->userId],
            ['role_id', '=', $roleId],
        ]);
        if ($userRole->exists()) {
            //user has role
            $result['type'] = 'userSettings';
            $result['data'] = '$userRole->exists()';
            $conn->send(json_encode($result));
            $userRole->delete();
            $this->userresources[$data->channel][$data->userId][$roleSlug] = true;
        } else {
            $addRole = DB::table('user_role_users')->insert([
                ['user_id', '=', $data->userId],
                ['role_id', '=', $roleId],]);
            $result['type'] = 'userSettings';
            $result['data'] = '$addRoleapp';
            $conn->send(json_encode($result));
            $this->userresources[$data->channel][$data->userId][$roleSlug] = false;
        }


        $result['type'] = 'userSettings';
        $result['data'] = 'تم تغيير الاعدادات بنجاح';
        $conn->send(json_encode($result));
    }

    function subscribe($user, $data, ConnectionInterface $conn)
    {
//        $result['type'] = 'userSettings';$result['data'] = 'function subscribe';$conn->send(json_encode($result));dd("stop");


        if ($user->is_block_from($data->roomId, 100))return;

        if (isset($user->id)) {

            if (isset($this->userresources[$data->channel][$user->id])) {
                if (!in_array($conn->resourceId, $this->userresources[$data->channel][$user->id])) {
                    //$this->userresources[$data->userId][] = $conn->resourceId;
                    $this->userresources[$data->channel][$user->id]['resourceId'] = $conn->resourceId;
                    $this->userresources[$data->channel][$user->id]['userName'] = $user->nick_name;
                    $this->userresources[$data->channel][$user->id]['cantWrite'] = $user->is_block_from($data->roomId, 2);
                    $this->userresources[$data->channel][$user->id]['cantChangeColor'] = $user->is_block_from($data->roomId, 3);
                    $this->userresources[$data->channel][$user->id]['cantSendPrivateMessage'] = $user->is_block_from($data->roomId, 5);
                }
            } else {
                $this->userresources[$data->channel][$user->id] = [];
                $this->userresources[$data->channel][$user->id]['resourceId'] = $conn->resourceId;
                $this->userresources[$data->channel][$user->id]['userName'] = $user->nick_name;
                $this->userresources[$data->channel][$user->id]['cantWrite'] = $user->is_block_from($data->roomId, 2);
                $this->userresources[$data->channel][$user->id]['cantChangeColor'] = $user->is_block_from($data->roomId, 3);
                $this->userresources[$data->channel][$user->id]['cantSendPrivateMessage'] = $user->is_block_from($data->roomId, 5);
            }
        }

        $userId = $user->id;

        /*$counts = array_count_values($this->subscriptions);
        $filtered = array_filter($this->subscriptions, function ($value) use ($counts, $data) {
            return $counts[$value] = $data->channel;
        });
        $users = [];
        foreach ($filtered as $key => $value)
        {
            $users[$key] = $this->userresources[$key];
            //array_push($users, $this->userresources[$key]);
        }*/
        $result['type'] = 'room_users';
        $result['data'] = $this->userresources[$data->channel];
        $result['testData'] = '';
        $conn->send(json_encode($result));

        $this->subscriptions[$conn->resourceId] = $data->channel;
        $result['type'] = 'room_messages';
        $result['data'] = Message::with('user')->where("room_id", $data->roomId)->get()->toJson();

        $conn->send(json_encode($result));
        $this->groupChat($user, $data, $conn, true);
    }

    function privateMessage($user, $data)
    {
        $userId = $user->id;
        $username = $user->nick_name;
        $message = $data->message;
        $roomId = $data->roomId;
        $sendTo = $this->userresources[$roomId][$data->to]['resourceId'];

        $result['type'] = 'privateMessage';
        $result['data']['userID'] = $userId;
        $result['data']['message'] = $message;
        $result['data']['username'] = $username;


        $this->users[$sendTo]->send(json_encode($result));
//        if (isset($this->userresources[$data->to])) {
//            foreach ($this->userresources[$data->to] as $key => $resourceId) {
//                if (isset($this->users[$resourceId])) {
//                    $this->users[$resourceId]->send($msg);
//                }
//            }
//            $conn->send(json_encode($this->userresources[$data->to]));
//        }
//
//        if (isset($this->userresources[$data->from])) {
//            foreach ($this->userresources[$data->from] as $key => $resourceId) {
//                if (isset($this->users[$resourceId]) && $conn->resourceId != $resourceId) {
//                    $this->users[$resourceId]->send($msg);
//                }
//            }
//        }
    }

    /**
     * [onMessage description]
     * @method onMessage
     * @param ConnectionInterface $conn [description]
     * @param  [JSON.stringify]              $msg  [description]
     * @return [JSON]                    [description]
     * @example subscribe                conn.send(JSON.stringify({command: "subscribe", channel: "global"}));
     * @example groupchat                conn.send(JSON.stringify({command: "groupchat", message: "hello glob", channel: "global"}));
     * @example message                  conn.send(JSON.stringify({command: "message", to: "1", from: "9", message: "it needs xss protection"}));
     * @example register                 conn.send(JSON.stringify({command: "register", userId: 9}));
     */
    public function onMessage(ConnectionInterface $conn, $msg)
    {
//        $result['type'] = 'userSettings';$result['data'] = 'function onMessage';$conn->send(json_encode($result));dd("stop");


        $user = $this->auth_user($conn);
        $userId = $user->id;
        $data = json_decode($msg);
        if (isset($data->command)) {
            switch ($data->command) {
                case "userSettings":
                    $this->userSettings($user, $data, $conn);
                    break;
                case "subscribe":
                    $this->subscribe($user, $data, $conn);
                    break;
                case "groupchat":
                    $this->groupChat($user, $data, $conn);
                    break;
                case "privateMessage":
                    //
                    $this->privateMessage($user, $data);
                    break;
                case "register":
                    //
//                    if (isset($data->userId)) {
//
//                        if (isset($this->userresources[$data->userId])) {
//                            if (!in_array($conn->resourceId, $this->userresources[$data->userId])) {
//                                //$this->userresources[$data->userId][] = $conn->resourceId;
//                                $this->userresources[$data->userId]['resourceId'] = $conn->resourceId;
//                                $this->userresources[$data->userId]['userName'] = $data->userName;
//                            }
//                        } else {
//                            $this->userresources[$data->userId] = [];
//                            $this->userresources[$data->userId]['resourceId'] = $conn->resourceId;
//                            $this->userresources[$data->userId]['userName'] = $data->userName;
//                        }
//                    }
//                    $conn->send(json_encode($this->users));
//                    $conn->send(json_encode($this->userresources));
                    break;
                default:
                    $example = array(
                        'methods' => [
                            "subscribe" => '{command: "subscribe", channel: "global"}',
                            "groupchat" => '{command: "groupchat", message: "hello glob", channel: "global"}',
                            "message" => '{command: "message", to: "1", message: "it needs xss protection"}',
                            "register" => '{command: "register", userId: 9}',
                        ],
                    );
                    $conn->send(json_encode($example));
                    break;
            }
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        echo $conn->resourceId . ' is disconnected';
        foreach ($this->userresources as $key => $userData) {

            foreach ($userData as $userId => $value) {
                if ($this->userresources[$key][$userId]['resourceId'] == $conn->resourceId) {
                    echo 'user id ' . $userId . ' is disconnected';
                    $user = $this->userresources[$key][$userId];
                    $userData = $user;
                    $userData['id'] = $userId;
                    $this->groupChat($userData, null, $conn, true);
                    unset($user);
                }

            }
        }
        $this->clients->detach($conn);
        echo "Connection {$conn->resourceId} has disconnected\n";
        unset($this->users[$conn->resourceId]);
        unset($this->subscriptions[$conn->resourceId]);
        //unset($this->userresources[$userId]);


        /*foreach ($this->userresources as &$userId) {
            foreach ($userId as $key => $resourceId) {
                if ($resourceId==$conn->resourceId) {
                    unset( $userId[ $key ] );
                }
            }
        }*/
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }
}
