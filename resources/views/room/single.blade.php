<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="id" content="{{ auth()->user()->id ?? ''}}">
    <meta name="nick_name" content="{{ auth()->user()->nick_name ?? ''}}">
    <meta name="name_color" content="{{ auth()->user()->name ?? '#ffffff'}}">
    {{--<meta name="can_write" content="{{ auth()->user()->can('can_write') ?? ''}}">--}}
    {{--<meta name="can_change_name_color" content="{{ auth()->user()->can('can_change_name_color') ?? ''}}">--}}
    {{--<meta name="can_send_pics" content="{{ auth()->user()->can('can_send_pics') ?? ''}}">--}}
    <meta name="is_active" content="{{ auth()->user()->can('active') ?? ''}}">
    <meta name="can_write" content="{{ auth()->user()->can('can_write') ?? ''}}">
    <meta name="can_make_private_chat" content="{{ auth()->user()->can('can_make_private_chat') ?? ''}}">
    <meta name="is_room_supervisor" content="{{ auth()->user()->can('is_room_supervisor') ?? ''}}">
    <meta name="is_supervisor" content="{{ auth()->user()->user_level == 3 ?? ''}}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="{{asset('css/app.css')}}" rel="stylesheet">
    <link href="{{asset('css/popup_chat.css')}}" rel="stylesheet">
    <link href="{{asset('toastr/build/toastr.min.css')}}" rel="stylesheet">
    {{--<script>
        var id = "{{ auth()->user()->id ?? ""}}";
        var nick_name = "{{ auth()->user()->nick_name ?? ""}}";
    </script>--}}
    <title>{{ config('app.name', 'chatRoom') }}</title>
    <!------ Include the above in your HEAD tag ---------->

    {{--<link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>--}}

    {{--<script src="//cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>--}}
<!------ Include the above in your HEAD tag ---------->
    {{--<script src='//production-assets.codepen.io/assets/editor/live/console_runner-079c09a0e3b9ff743e39ee2d5637b9216b3545af0de366d4b9aad9dc87e26bfd.js'></script>
    --}}{{--<script src='//production-assets.codepen.io/assets/editor/live/events_runner-73716630c22bbc8cff4bd0f07b135f00a0bdc5d14629260c3ec49e5606f98fdd.js'></script>--}}{{--
    <script src='//production-assets.codepen.io/assets/editor/live/css_live_reload_init-2c0dc5167d60a5af3ee189d570b1835129687ea2a61bee3513dee3a50c115a77.js'></script>--}}
    <meta charset='UTF-8'>
    <meta name="robots" content="noindex">
    {{--<link rel="shortcut icon" type="image/x-icon"--}}
    {{--href="//production-assets.codepen.io/assets/favicon/favicon-8ea04875e70c4b0bb41da869e81236e54394d63638a1ef12fa558a4a835f1164.ico"/>--}}
    {{--<link rel="mask-icon" type=""--}}
    {{--href="//production-assets.codepen.io/assets/favicon/logo-pin-f2d2b6d2c61838f7e76325261b7195c27224080bc099486ddd6dccb469b8e8e6.svg"--}}
    {{--color="#111"/>--}}
    {{--<link rel="canonical" href="https://codepen.io/emilcarlsson/pen/ZOQZaV?limit=all&page=74&q=contact+"/>--}}
    {{--<link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,600,700,300' rel='stylesheet'--}}
    {{--type='text/css'>--}}

    {{--<script src="https://use.typekit.net/hoy3lrg.js"></script>
    <script>try {
            Typekit.load({async: true});
        } catch (e) {
        }</script>--}}
    <link rel='stylesheet prefetch' href='https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css'>
    <link rel='stylesheet prefetch'
          href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.2/css/font-awesome.min.css'>
    <link rel="stylesheet" href="{{asset ('/css/room.css')}}" type="text/css" media="all"/>

    <link rel="stylesheet" href="https://rawgit.com/mervick/emojionearea/master/dist/emojionearea.css" type="text/css" media="all"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/emojione/assets/3.1/sprites/emojione-sprite-32.css" type="text/css" media="all"/>




    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.rawgit.com/mervick/emojionearea/master/dist/emojionearea.min.css">


    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>

    <script>

    </script>
    <script src="{{ asset('js/popper.min.js') }}"></script>
    <script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="https://cdn.rawgit.com/mervick/emojionearea/master/dist/emojionearea.min.js"></script>
    <script src="{{ asset('js/moment.min.js') }}"></script>
    <script src="{{ asset('js/moment_ar.js') }}"></script>

    <style>

        .span6 {
            float: left;
            width: 48%;
            padding: 1%;
        }

    </style>
</head>

<body style="direction: rtl">

<div id="loader">
    <h1 style="font-size: 28px;" class="text-center text-danger">لقد تم وقف حسابك ...</h1>
    <svg width="100" height="100" viewBox="0 0 44 44" xmlns="http://www.w3.org/2000/svg" stroke="#dc3545">
        <g fill="none" fill-rule="evenodd" stroke-width="2">
            <circle cx="22" cy="22" r="1">
                <animate attributeName="r"
                         begin="0s" dur="1.8s"
                         values="1; 20"
                         calcMode="spline"
                         keyTimes="0; 1"
                         keySplines="0.165, 0.84, 0.44, 1"
                         repeatCount="indefinite"/>
                <animate attributeName="stroke-opacity"
                         begin="0s" dur="1.8s"
                         values="1; 0"
                         calcMode="spline"
                         keyTimes="0; 1"
                         keySplines="0.3, 0.61, 0.355, 1"
                         repeatCount="indefinite"/>
            </circle>
            <circle cx="22" cy="22" r="1">
                <animate attributeName="r"
                         begin="-0.9s" dur="1.8s"
                         values="1; 20"
                         calcMode="spline"
                         keyTimes="0; 1"
                         keySplines="0.165, 0.84, 0.44, 1"
                         repeatCount="indefinite"/>
                <animate attributeName="stroke-opacity"
                         begin="-0.9s" dur="1.8s"
                         values="1; 0"
                         calcMode="spline"
                         keyTimes="0; 1"
                         keySplines="0.3, 0.61, 0.355, 1"
                         repeatCount="indefinite"/>
            </circle>
        </g>
    </svg>
</div>

<?php
use App\Hoss\Hoss;
use Jenssegers\Date\Date;
Date::setLocale('ar');
//dd(Auth::user()->can('active'));
?>
<div id="frame">

    <div id="sidepanel">

        <div id="profile">
            <div class="wrap">
                <img id="profile-img" src="//emilcarlsson.se/assets/mikeross.png" class="online" alt=""/>
                <p>{{ auth()->user()->nick_name}}</p>
                {{--<i class="fa fa-chevron-down expand-button" aria-hidden="true"></i>
                <div id="status-options">
                    <ul>
                        <li id="status-online" class="active"><span class="status-circle"></span> <p>Online</p></li>
                        <li id="status-away"><span class="status-circle"></span> <p>Away</p></li>
                        <li id="status-busy"><span class="status-circle"></span> <p>Busy</p></li>
                        <li id="status-offline"><span class="status-circle"></span> <p>Offline</p></li>
                    </ul>
                </div>
                <div id="expanded">
                    <label for="twitter"><i class="fa fa-facebook fa-fw" aria-hidden="true"></i></label>
                    <input name="twitter" type="text" value="mikeross" />
                    <label for="twitter"><i class="fa fa-twitter fa-fw" aria-hidden="true"></i></label>
                    <input name="twitter" type="text" value="ross81" />
                    <label for="twitter"><i class="fa fa-instagram fa-fw" aria-hidden="true"></i></label>
                    <input name="twitter" type="text" value="mike.ross" />
                </div>--}}
            </div>
        </div>

        <div id="rooms_users_switch">
            <button id='users'
                    class='Button btn btn-secondary btn-lg'
                    onclick="toggleRoomsAndUsers(2);">{{__('room.Users')}}</button>
            <button id='rooms' class='Button btn btn-secondary btn-lg'
                    onclick="toggleRoomsAndUsers(1);">{{__('room.Rooms')}}</button>
        </div>

        <div id="search">
            <label for=""><i class="fa fa-search" aria-hidden="true"></i></label>
            <input type="text" placeholder="{{__('room.Search')}}"/>
        </div>

        <div id="contacts">

            <ul id="users_holder">
                @foreach($room_users as $key => $user)
                    @if($user->isOnline())
                        <div class="dropdown users" id="user_{{$user->id}}">
                            <li class="dropdown-toggle contact" type="li" id="dropdownMenuButton" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false"
                                onclick="loadUserDropdownActions({{$user->id}})">
                                <div class="wrap">
                                    <span class="contact-status online"></span>
                                    <img style="height: 40px"
                                         src="//previews.123rf.com/images/littleartvector/littleartvector1901/littleartvector190100024/126067615-interior-background-with-cozy-colorful-living-room-vector-illustration.jpg"
                                         alt=""/>
                                    <div class="meta">
                                        <p class="name">{{$user->nick_name}}</p>
                                        <p class="preview">متصل</p>
                                    </div>
                                </div>
                            </li>
                            <div style="margin-top: -26%;" class="dropdown-menu" aria-labelledby="dropdownMenuButton"
                                 id="dropdown_menu_{{$user->id}}">
                            </div>
                        </div>
                    @else

                    @endif
                @endforeach
            </ul>

            <ul id="rooms_holder" style="display: none">
                @foreach($rooms as $key => $value)
                    @if(auth()->user()->is_block_from($value->id, 100))

                    @else
                        <li class="contact rooms">
                            <a href="{{route('single_room',['id'=>$id,'room'=>$value->id])}}">
                                <span hidden
                                      class="room_avatar">{{ $value->avatar ??  "//previews.123rf.com/images/littleartvector/littleartvector1901/littleartvector190100024/126067615-interior-background-with-cozy-colorful-living-room-vector-illustration.jpg"}}</span>
                                <div class="wrap">
                                    <span class="contact-status online"></span>
                                    <img style="height: 40px"
                                         src="{{ $value->avatar ??  "//previews.123rf.com/images/littleartvector/littleartvector1901/littleartvector190100024/126067615-interior-background-with-cozy-colorful-living-room-vector-illustration.jpg"}}"
                                         alt=""/>
                                    <div class="meta">
                                        <p class="name">{{$value->name}}</p>
                                        <p class="preview">{{$value->description}}</p>
                                    </div>
                                </div>
                            </a>
                        </li>
                    @endif
                @endforeach
            </ul>

        </div>
        <div id="bottom-bar">
            <button id="logout-btn-2"
                    onclick="event.preventDefault();document.getElementById('logout-form-room').submit();">
                <i class="fa fa-lock fa-fw" aria-hidden="true"></i> <span>{{ __('تسجيل الخروج') }}</span>
            </button>

            <form id="logout-form-room" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
                <input id="room_id" type="hidden" name="id" value="{{$id}}" required>
            </form>
            <button id="settings" type="button" data-toggle="modal" data-target="#settingsModal"><i
                        class="fa fa-cog fa-fw" aria-hidden="true"></i>
                <span>{{__('room.Settings')}}</span></button>
        </div>
    </div>

    <div class="content">
        <div class="contact-profile">
            <img style="height: 40px" id="room_img"
                 src="{{ $room->avatar ??  "https://previews.123rf.com/images/littleartvector/littleartvector1901/littleartvector190100024/126067615-interior-background-with-cozy-colorful-living-room-vector-illustration.jpg"}}"
                 alt=""/>
            <p id="room_name">{{$room->name}}</p>
            {{--<div class="social-media">
                <i class="fa fa-facebook" aria-hidden="true"></i>
                <i class="fa fa-twitter" aria-hidden="true"></i>
                <i class="fa fa-instagram" aria-hidden="true"></i>
            </div>--}}
        </div>
        <div class="messages">
            <ul id="message_box">
                @if(is_array($messages))
                    @foreach($messages as $message)
                        <?php //dd($message); ?>
                        @if($message->user_id == \Illuminate\Support\Facades\Auth::id())
                            <li class="sent" data-id="{{$message->id}}">
                                <img src="http://emilcarlsson.se/assets/mikeross.png" alt=""/>
                                <span>
                                <p class="name"
                                   style=" font-size: 75%; margin: -7px 0 7px 0; color:{{$message->name}}"> {{$message->nick_name}} </p>
                                <p style="word-wrap: break-word;">{!! $message->message !!}</p>
                                <p class="message-time"
                                   style=" font-size: 75%; margin: 0 0 -5px 0; text-align: right; ">
                                    {{Hoss::convertToArabicNumbers(Date::createFromDate($message->created_at)->format('d-m-Y  H:m:s a'))}}
                                </p>
                            </span>
                            </li>
                        @else
                            <li class="replies" data-id="{{$message->id}}">
                                <img src="http://emilcarlsson.se/assets/mikeross.png" alt=""/>
                                <span>
                                <p class="name"
                                   style=" font-size: 75%; margin: -7px 0 7px 0; color:{{$message->name}}"> {{$message->nick_name}} </p>
                                <p style="word-wrap: break-word;">{!! $message->message !!}</p>
                                <p class="message-time"
                                   style=" font-size: 75%; margin: 0 0 -5px 0; text-align: right; ">
                                    {{Hoss::convertToArabicNumbers(Date::createFromDate($message->created_at)->format('d-m-Y  H:m:s a'))}}
                                </p>
                            </span>
                            </li>
                        @endif
                    @endforeach
                @endif
            </ul>
        </div>
        <div class="message-input">
            <div class="wrap input-editor">




                @if(auth()->user()->can('can_write') || auth()->user()->user_level == 3)
                    <input type="text" id="p_msg_input" class="p_msg_input"
                           placeholder="{{__('room.Write your message...')}}">
                    <div id="divOutside" class="divOutside">
                        @else
                            <input type="text" id="p_msg_input" placeholder="{{__('تم تعليقك من الكتابة مؤقتا...')}}" readonly>
                        @endif
                        {{--                <i class="fa fa-paperclip attachment" aria-hidden="true"></i>--}}
                        <button class="submit"><i class="fa fa-paper-plane" aria-hidden="true"></i></button>
                    </div>
            </div>
        </div>

    </div>

    <div id="settingsModal" class="modal fade-in">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="title">إعدادات المستخدم</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body" style="text-align: right;">

                    <form id="edit-form" action="#" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="inputGroup-sizing-default">إسم المستخدم</span>
                            </div>
                            <input type="text" class="form-control"
                                   placeholder="{{auth()->user()->username}}" readonly>
                        </div>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="inputGroup-sizing-default">الاسم المستعار</span>
                            </div>
                            {{--<input type="text" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default">--}}
                            <input aria-label="Default" aria-describedby="inputGroup-sizing-default"
                                   name='nick_name' id="nick_name_input"
                                   type="text" class="form-control"
                                   value="{{auth()->user()->nick_name}}">
                        </div>

                        @if(auth()->user()->can('can_change_name_color') || auth()->user()->user_level == 3)
                            <div class="input-group mb-3" id="changeNameColorDiv-">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-default">لون الاسم</span>
                                </div>
                                {{--<input type="text" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default">--}}
                                @php //dump(auth()->user()->can('can_change_name_color')); @endphp
                                <input aria-label="Default" aria-describedby="inputGroup-sizing-default"
                                       name='name_color'
                                       type="color" class="form-control"
                                       value="{{auth()->user()->name}}">
                            </div>
                        @endif

                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="inputGroup-sizing-default">كلمة المرور</span>
                            </div>
                            {{--<input type="text" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default">--}}
                            <input aria-label="Default" aria-describedby="inputGroup-sizing-default"
                                   name='password'
                                   type="password" class="form-control" value="**********">
                        </div>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="inputGroup-sizing-default">تأكيد كلمة المرور</span>
                            </div>
                            {{--<input type="text" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default">--}}
                            <input aria-label="Default" aria-describedby="inputGroup-sizing-default"
                                   name='password_confirmation' type="password" class="form-control"
                                   value="**********">
                        </div>

                        <li class="contact">
                            <div class="wrap">
                                <div class="meta">
                                    <input type="submit" class="btn btn-primary btn-block"
                                           value="موافق"/>
                                </div>
                            </div>
                        </li>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <!-- The set_timer_block Modal -->
    <div class="modal" id="user_settings_model">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title" id="title">إعدادات الطرد من الغرفة</h4>
                    {{--<button type="button" class="close" data-dismiss="modal">&times;</button>--}}
                </div>

                <!-- Modal body -->
                <form id="user_settings_edit_form" action="#" method="POST">

                    <div class="modal-body" style="text-align: right;">

                        <h1 style="margin-bottom: 20px" id="body">إختر وقت الطرد</h1>
                        <div class="input-group mb-3" style="direction: ltr;">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="inputGroup-sizing-default">دقيقة/دقائق</span>
                            </div>
                            {{--<input type="text" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default">--}}
                            <input name="userId" type="hidden">
                            <input name="roomId" type="hidden">
                            <input name="roleId" type="hidden">
                            <input aria-label="Default" aria-describedby="inputGroup-sizing-default" name='block_time'
                                   type="number" class="form-control" value="0">
                        </div>
                        <h5>ملاحظة: القيمة صفر تعني غير محدود.</h5>

                    </div>
                    <input type="submit" class="btn btn-primary btn-block" value="موافق"/>
                </form>
                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button"
                            class="btn btn-danger" data-dismiss="modal">{{__('room.Close')}}</button>
                    {{--<button id="addcontact" class="btn btn-danger" onclick="event.preventDefault();
                                                         document.getElementById('logout-form-1').submit();"> {{ __('تسجيل الخروج') }}
                    </button>--}}


                    {{--<form id="logout-form-1" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                        <input id="id" type="hidden" name="id" value="{{$id}}" required>
                    </form>--}}
                </div>

            </div>
        </div>
    </div>

    {{--<script>console.log(`<?php dd(\Illuminate\Support\Facades\DB); ?>`)</script>--}}
    {{--<script src='//production-assets.codepen.io/assets/common/stopExecutionOnTimeout-b2a7b3fe212eaa732349046d8416e00a9dec26eb7fd347590fbced3ab38af52e.js'></script>--}}
    {{--<script src='https://code.jquery.com/jquery-2.2.4.min.js'></script>--}}
    {{--<script src="{{ asset('js/jquery-3.1.1.js') }}"></script>--}}
    <script>
        $(document).ready(function() {
            $("#p_msg_input").emojioneArea({
                pickerPosition: "top",
                tonesStyle: "radio"
            });
            setTimeout(addEventListeners2,3000);
        });

    </script>

    <script>
        $(document).ready(()=>$(".messages").scrollTop(10000000000,'slow'));
    </script>

    <script>
        /* PREPARE VARIABLES */

        moment.locale('ar');

        const _token = $('input[name="_token"]').val();
        let usersArr = [];
        let room_id = `{{$room->id}}`;
        let current_user = `{{Auth::user()->id}}`;
        let supervisor = `{{$supervisor}}`;
        let rooms_holder = $('#rooms_holder');
        let users_holder = $('#users_holder');
        let nick_name = $('meta[name=nick_name]').attr('content');
        let name_color = $('meta[name=name_color]').attr('content');
        let isActive = $('meta[name=is_active]').attr('content');
        let canWrite = $('meta[name=can_write]').attr('content');
        // let conversation_id = false;
        // let settings_holder = $('#settings_holder');
    </script>

    <script>
        /* Side Panel Changes */
        function toggleRoomsAndUsers(type) {
            switch (type) {
                case(1):
                    users_holder.css('display', 'none');
                    rooms_holder.css('display', 'block');
                    break;
                case(2):
                    rooms_holder.css('display', 'none');
                    users_holder.css('display', 'block');
                    break;
            }
        }

        $('#edit-form').submit(function (e) {
            e.preventDefault();
            let formData = $("#edit-form").serialize();
            $.ajax({
                type: "post",
                url: '{{ url("$id/chatRoom")}}/{{auth()->user()->id}}',
                data: formData,
                success: function (store) {
                    toastr.success(store.message, 'تم', {timeOut: 5000});
                    $('#settingsModal').modal('toggle');
                    $('li.sent p.name').css('color', store.name_color);
                    $('meta[name=name_color]').attr('content', store.name_color);
                },
                error: function () {
                    alert('error')
                }
            });
        });


    </script>

    <script>
        /* Users Actions Menu */
        function checkUserCanWrite() {
            let _data = {
                room_id: room_id,
            }
            let _url = `{{route('check_user_can_write')}}`;
            $.ajaxSetup({headers: {'X-CSRF-Token': _token}});
            $.ajax({
                url: _url,
                method: 'POST',
                dataType: 'json',
                data: _data,
                cache: false,
                success: function (response) {
                    if (response == true) {
                        toggleMessageInputs(1);
                    } else if (response != true) {
                        toggleMessageInputs(0);
                    }
                }
            });
        }
        function checkUserIsActive() {
            let _data = {
                room_id: room_id,
            }
            let _url = `{{route('check_user_is_active')}}`;
            $.ajaxSetup({headers: {'X-CSRF-Token': _token}});
            $.ajax({
                url: _url,
                method: 'POST',
                dataType: 'json',
                data: _data,
                cache: false,
                success: function (response) {
                    if (response != true) {
                        eventUserDisabled();
                    }
                }
            });
        }
        function checkUserIsBlocked() {
            let _data = {
                room_id: room_id,
                admin:supervisor
            }
            let _url = `{{route('check_user_is_blocked')}}`;
            $.ajaxSetup({headers: {'X-CSRF-Token': _token}});
            $.ajax({
                url: _url,
                method: 'POST',
                dataType: 'json',
                data: _data,
                cache: false,
                success: function (response) {
                    if (response != true) {
                        eventUserDisabled();
                    }
                }
            });
        }

        function eventUserDisabled() {
            let loader = $('#loader');
            loader.show().addClass('loader');
        }

        function toggleMessageInputs(can){
            if(!can){
                $('#p_msg_input').attr('disabled','disabled').attr('readonly','readonly').attr('placeholder','تم تعليقك من الكتابة مؤقتا');
            } else if(can) {
                $('#p_msg_input').removeAttr('disabled').removeAttr('readonly').attr('placeholder','اكتب رسالتك ...');
            }
        }

        function loadUserDropdownActions(user_id) {
            let _data = {
                he: user_id,
                me: current_user,
                room_id: room_id,
            }
            let _url = `{{route('load_user_actions')}}`;
            $.ajaxSetup({headers: {'X-CSRF-Token': _token}});
            $.ajax({
                url: _url,
                method: 'POST',
                dataType: 'json',
                data: _data,
                cache: false,
                success: function (response) {
                    if (response.status === "success") {
                        buildUserDropdownActions(user_id, response.items);
                    } else if (response.status === "error") {
                        toastr.error('حاول لاحقا', 'خطأ', {timeOut: 5000});
                    }
                }
            });
        }

        function buildUserDropdownActions(user_id, items) {
            let items_holder = $('#dropdown_menu_' + user_id);
            items_holder.html(items);
            // items_holder.toggleClass('show');
            onClickAfterUserAppend();
            return;
        }

        function updateUserRole(uid, role, room = null, block_time = null) {
            let _data = {
                user_id: uid,
                role_id: role,
                room_id: room,
                block_time: block_time,
            }
            let _url = `{{route('update_user_role')}}`;
            $.ajaxSetup({headers: {'X-CSRF-Token': _token}});
            $.ajax({
                url: _url,
                method: 'POST',
                dataType: 'json',
                data: _data,
                cache: false,
                success: function (response) {
                    if (response.status === "success") {
                        // reloadDropdownItems(uid, role, response.done);
                        toastr.success("تم التعديل بنجاح", 'تم', {timeOut: 5000});
                    } else if (response.status === "error") {
                        toastr.error('حاول لاحقا', 'خطأ', {timeOut: 5000});
                    }
                }
            });
            return false;
        }

        function cancelBlockMessageSend(_this) {
            let user_id = _this.data('user_id')
            updateUserRole(user_id,2);
        }

        function cancelBlockFromRoom(_this) {
            let user_id = _this.data('user_id')
            updateUserRole(user_id,100);
        }

        function cancelAccountDisabled(_this) {
            let user_id = _this.data('user_id')
            updateUserRole(user_id,1);
        }

        function onClickAfterUserAppend() {


            $('#user_settings_model').on('show.bs.modal', function (e) {
                //get data-id attribute of the clicked element
                let userId = $(e.relatedTarget).data('user-id');
                //let roomId = $(e.relatedTarget).data('room-id');
                let roleId = $(e.relatedTarget).data('role-id');
                let title = $(e.relatedTarget).data('title');
                let body = $(e.relatedTarget).data('body');

                //populate the textbox
                $(e.currentTarget).find('input[name="userId"]').val(userId);
                $(e.currentTarget).find('input[name="roomId"]').val(room_id);
                $(e.currentTarget).find('input[name="roleId"]').val(roleId);
                $(e.currentTarget).find('#title').html(title);
                $(e.currentTarget).find('#body').html(body);
            });

            $('#user_settings_edit_form').submit(function (e) {
                e.preventDefault();
                let data = $('#user_settings_edit_form').serializeArray();
                let userId = data[0]['value'];
                let roomId = room_id;
                let roleId = data[2]['value'];
                let blockTime = data[3]['value'];
                // console.log(userId);
                switch (roleId) {
                    case '1':
                        text = $('#user_' + userId).find('.block_message_send').html();
                        if (text === 'كتم') {
                            $('#user_' + data[0]['value']).find('.block_message_send').html('إلغاء الكتم');
                            $('#user_' + data[0]['value']).find('.block_message_send').attr('href', '#');

                        } else {
                            $('#user_' + data[0]['value']).find('.block_message_send').html('كتم');
                        }
                        // console.log($('#user_' + data[0]['value']).find('.block_message_send'));
                        updateUserRole(userId,2);
                        break;
                    case '2':
                        updateUserRole(userId,100,roomId,blockTime);
                        // $('#user_' + userId).remove();
                        break;
                    case '3':
                        updateUserRole(userId,1);
                        $('#user_' + userId).remove();
                        break;
                }

                $('#user_settings_model').modal('hide');
            });

            $(".private_message").click(function () {
                let userId = $(this).attr('id');

                updateUserRole(userId,5);
            });

            $(".change_color").click(function () {
                let userId = $(this).attr('id');

                updateUserRole(userId,3);
            });

            $(".special_char").click(function () {
                let userId = $(this).attr('id');

                updateUserRole(userId,7);
            });

        }
    </script>

    <script>
        /* PRIVATE CHAT */

        function startPrivateChat(_this) {
            let user_id = _this.data('user_id')
            let username = _this.data('username')
            if ($.inArray(user_id, usersArr) != -1) {
                usersArr.splice($.inArray(user_id, usersArr), 1);
            }
            usersArr.unshift(user_id);
            chatPopup = '<div class="msg_box" style="right:310px" rel="' + user_id + '" id="' + user_id + '">' +
                '<div class="msg_head">' +
                '<div class="close">x</div>' + username + ' </div>' +
                '<div class="msg_wrap"> <div class="msg_body">	<div class="msg_push"></div> </div>' +
                '<div class="msg_footer">' +
                '<div class="msg_input">\n' +
                '            <div class="wrap">\n' +
                '                    <input class="input_press" type="text" placeholder="إكتب..." autofocus="autofocus">\n' +
                '                <button class="msg_input_submit"><i class="fa fa-paper-plane" aria-hidden="true"></i></button>\n' +
                '            </div>\n' +
                '        </div>' +
                '</div> 	</div> 	</div>';

            $("body").append(chatPopup);
            displayChatBox();

            let conversation = new Promise(function (resolve, reject) {
                let cId = getConversationId(user_id)
                if (cId != false) {
                    resolve(cId);
                } else {
                    reject();
                }
            });
            conversation.then(function (result) {
                loadConversation(result, user_id);
            }).catch(function () {
                console.log('no old messages');
            });

        }

        function displayChatBox() {
            i = 270; // start position
            j = 260;  //next position

            $.each(usersArr, function (index, value) {
                if (index < 4) {
                    $('[rel="' + value + '"]').css("right", i);
                    $('[rel="' + value + '"]').show();
                    i = i + j;
                } else {
                    $('[rel="' + value + '"]').hide();
                }
            });
            addEventListeners();
        }

        $(document).on('click', '.msg_head', function () {
            let chatBox = $(this).parents().attr("rel");
            let currentChatBox = $('[rel="' + chatBox + '"] .msg_wrap');
            currentChatBox.slideToggle();
            return false;
        });

        $(document).on('click', '.close', function () {
            var chatbox = $(this).parents().parents().attr("rel");
            $('[rel="' + chatbox + '"]').hide();
            return false;
        });

        function addEventListeners() {
            $('.msg_input_submit').click(function () {
                let msg_box = $(this).parents().find('.msg_box')[0];
                // console.log(msg_box)
                newMessagePopup(msg_box);
                return false;
            });

            $('.msg_box').on('keydown', function (e) {
                if (e.which == 13) {
                    newMessagePopup(this);
                    return false;
                }
            });

            $('.msg_box').on('mouseover', function (e) {
                // console.log($(this).data('conversation_id'));
                setSeen($(this));
            });
        }

        function setSeen(conversation) {
            conversation.off('mouseover');
            let _conversation_id = conversation.data('conversation_id')
            let _data = {
                conversation_id: _conversation_id,
            }
            let _url = `{{route('set_seen')}}`;
            $.ajaxSetup({headers: {'X-CSRF-Token': _token}});
            $.ajax({
                url: _url,
                method: 'POST',
                dataType: 'json',
                data: _data,
                cache: false,
                success: function (response) {
                    // console.log(conversation);
                    if(response.status == 'success') conversation.off('mouseover');
                }
            });
        }

        function newMessagePopup(_this) {
            let message = $(_this).find(".input_press").val();
            if ($.trim(message) == '') return false;
            let receiver_id = $(_this).attr('rel').replace('user_', '');
            $(_this).find('.input_press').val(null);
            return sendPrivateMessage(message, receiver_id, _this);
        }

        function sendPrivateMessage(message, receiver_id, _this) {
            let _data = {
                he: receiver_id,
                me: current_user,
                room_id: room_id,
                message: message
            }
            let _url = `{{route('send_private_message')}}`;
            $.ajaxSetup({headers: {'X-CSRF-Token': _token}});
            $.ajax({
                url: _url,
                method: 'POST',
                dataType: 'json',
                data: _data,
                cache: false,
                success: function (response) {
                    if (response == 1) appendPrivateMessage(message, _this);
                }
            });
        }

        function appendPrivateMessage(message, _this, type = 1) {
            if (type == 1) {
                $(_this).find(".msg_body").append(`<div class="msg-right">${message}</div>`);
            } else if (type == 2) {
                $(_this).find(".msg_body").append(`<div class="msg-left">${message}</div>`);
            }

            $(_this).find(".msg_body").scrollTop($(_this).find(".msg_body").height());
        }


        /* Return Users[] if Users Chatting Me or [] */
        function checkNewPrivateChat() {
            let _data = {
                room_id: room_id,
            }
            let _url = `{{route('check_new_private_chat')}}`;
            $.ajaxSetup({headers: {'X-CSRF-Token': _token}});
            $.ajax({
                url: _url,
                method: 'POST',
                dataType: 'json',
                data: _data,
                cache: false,
                success: function (response) {
                    if(response!==false) return receivePrivateChat(response)
                }
            });
        }

        function receivePrivateChat(_conversations) {
            $.each(_conversations, function (index, value) {

                let conversation_id = value.conversation_id;
                let user_id = value.user_id;
                let username = value.nick_name;

                if ($.inArray(user_id, usersArr) != -1) {
                    usersArr.splice($.inArray(user_id, usersArr), 1);
                }
                usersArr.unshift(user_id);
                chatPopup = '<div class="msg_box" style="right:310px" id="' + user_id + '" rel="' + user_id + '">' +
                    '<div class="msg_head">' +
                    '<div class="close">x</div>' + username + ' </div>' +
                    '<div class="msg_wrap"> <div class="msg_body">	<div class="msg_push"></div> </div>' +
                    '<div class="msg_footer">' +
                    '<div class="msg_input">\n' +
                    '            <div class="wrap">\n' +
                    '                    <input class="input_press" type="text" placeholder="إكتب..." autofocus="autofocus">\n' +
                    '                <button class="msg_input_submit"><i class="fa fa-paper-plane" aria-hidden="true"></i></button>\n' +
                    '            </div>\n' +
                    '        </div>' +
                    '</div> 	</div> 	</div>';

                $("body").append(chatPopup);
                loadConversation(conversation_id, user_id);
                addEventListeners();
            });
        }

        function loadConversation(conversation_id, user_id) {
            let _data = {
                conversation_id: conversation_id,
            }
            let _url = `{{route('load_conversation')}}`;
            $.ajaxSetup({headers: {'X-CSRF-Token': _token}});
            $.ajax({
                url: _url,
                method: 'POST',
                dataType: 'json',
                data: _data,
                cache: false,
                success: function (response) {
                    let chatBox = $(`#${user_id}.msg_box`);
                    chatBox.attr('data-conversation_id',conversation_id);
                    appendConversationMessages(chatBox, response)
                }
            });
        }

        function appendConversationMessages(chatBox, response) {
            $.each(response, function (index, value) {
                // console.log(value)
                if (value.user_id == current_user) {
                    appendPrivateMessage(value.message, chatBox, 1);
                } else {
                    appendPrivateMessage(value.message, chatBox, 2);
                }
            });
        }

        async function getConversationId(user_id) {
            let conversation_id = false;
            let _data = {
                user_id: user_id,
            }
            let _url = `{{route('get_conversation_id')}}`;
            $.ajaxSetup({headers: {'X-CSRF-Token': _token}});
            await $.ajax({
                url: _url,
                method: 'POST',
                dataType: 'json',
                data: _data,
                cache: false,
                success: function (response) {
                    conversation_id = response;
                }
            });
            return conversation_id;
        }


        function receivePrivateMessages() {
            let _data = {
                room_id: room_id,
            }
            let _url = `{{route('receive_private_messages')}}`;
            $.ajaxSetup({headers: {'X-CSRF-Token': _token}});
            $.ajax({
                url: _url,
                method: 'POST',
                dataType: 'json',
                data: _data,
                cache: false,
                success: function (response) {
                    // console.log(response)
                    receivePrivateChat(response);
                }
            });
        }

        function popupChatBoxes(senders) {
            i = 270; // start position
            j = 260;  //next position

            $.each(senders, function (index, value) {
                console.log(index)
                // if (index < 4) {
                $('[rel="' + index + '"]').css("right", i);
                $('[rel="' + index + '"]').show();
                i = i + j;
                // } else {
                //     $('[rel="' + value + '"]').hide();
                // }
            });
            addEventListeners();
        }

        const groupBy = key => array =>
            array.reduce((objectsByKeyValue, obj) => {
                const value = obj[key];
                objectsByKeyValue[value] = (objectsByKeyValue[value] || []).concat(obj);
                return objectsByKeyValue;
            }, {});

        function popup_chat() {
            console.log('ready!');


            $(document).on('click', '.msg_head', function () {
                // console.log('box clicked')
                let chatBox = $(this).parents().attr("rel");
                // console.log(chatBox)
                let currentChatBox = $('[rel="' + chatBox + '"] .msg_wrap');
                // console.log(currentChatBox)
                currentChatBox.slideToggle();
                console.log(currentChatBox)
                return false;
            });


            $(document).on('click', '.close', function () {
                var chatbox = $(this).parents().parents().attr("rel");
                $('[rel="' + chatbox + '"]').hide();
                arr.splice($.inArray(chatbox, arr), 1);
                displayChatBox();
                return false;
            });

            $(".users").click(function () {

                let userID = $(this).attr("id");
                let username = $(this).find(".name").html();

                $(this).find(".private_chat").click(function () {
                    console.log('user presed: ' + userID);
                    if ($.inArray(userID, arr) != -1) {
                        arr.splice($.inArray(userID, arr), 1);
                    }

                    /*<div class="msg_input">
                        <div class="wrap">
                                <input type="text" placeholder="إكتب...">

                            <i class="fa fa-paperclip attachment" aria-hidden="true"></i>
                            <button class="submit"><i class="fa fa-paper-plane" aria-hidden="true"></i></button>
                        </div>
                    </div>*/
                    arr.unshift(userID);
                    chatPopup = '<div class="msg_box" style="right:310px" rel="' + userID + '">' +
                        '<div class="msg_head">' + username +
                        '<div class="close">x</div> </div>' +
                        '<div class="msg_wrap"> <div class="msg_body">	<div class="msg_push"></div> </div>' +
                        '<div class="msg_footer">' +
                        '<div class="msg_input">\n' +
                        '            <div class="wrap">\n' +
                        '                    <input class="input_press" type="text" placeholder="إكتب...">\n' +
                        '              \n' +
                        '                <i class="fa fa-paperclip attachment" aria-hidden="true"></i>\n' +
                        '                <button class="msg_input_submit"><i class="fa fa-paper-plane" aria-hidden="true"></i></button>\n' +
                        '            </div>\n' +
                        '        </div>' +
                        '</div> 	</div> 	</div>';

                    $("body").append(chatPopup);
                    displayChatBox();
                });
            });
            /*$(document).on('click', '#user_box', function() {

             var userID = $(this).attr("id").split(' ')[0];
             var username = $(this).children().text() ;

             if ($.inArray(userID, arr) != -1)
             {
              arr.splice($.inArray(userID, arr), 1);
             }

             arr.unshift(userID);
             chatPopup =  '<div class="msg_box" style="right:270px" rel="'+ userID+'">'+
                            '<div class="msg_head">'+username +
                            '<div class="close">x</div> </div>'+
                            '<div class="msg_wrap"> <div class="msg_body">	<div class="msg_push"></div> </div>'+
                            '<div class="msg_footer"><textarea class="msg_input" rows="4"></textarea></div> 	</div> 	</div>' ;

             $("body").append(  chatPopup  );
             displayChatBox();
            });*/


        }


    </script>

    <script>
        /* Public Chat */

        function appendNewPublicMessage(_msg) {
            name_color = $('meta[name=name_color]').attr('content');
            nick_name = $('meta[name=nick_name]').attr('content');
            $("#message_box").append(`
        <li class="sent" data-id="${_msg.id}">\n\n
            <img src="http://emilcarlsson.se/assets/mikeross.png" alt=""/>\n                    \n
            <span>
                <p class="name" style=" font-size: 75%; margin: -7px 0 7px 0; color:${name_color}"> ${nick_name} </p>
                <p style="word-wrap: break-word;">\n                  \n                    ${_msg.message}\n                    </p>
                <p class="message-time" style=" font-size: 75%; margin: 0 0 -5px 0; text-align: right; ">${moment().format('DD-MM-YYYY HH:mm:ss a')}</p>
            </span>\n
        </li>`);

            $('.message-input input').val(null);

            $('.contact.active .preview').html('<span>You: </span>' + _msg.message);

            // $(".messages").animate({scrollTop: docHeight + 93}, "fast");
            // console.log($(".messages").height());
            // $(".messages").scrollTop($(".messages").height());

            $(".messages").scrollTop(10000000000000);
        }

        function newMessage(){
            // let message = $(".message-input input").val();
            let message = $(".emojionearea-editor").html();
            // console.log(message)
            if ($.trim(message) == '') {
                return false;
            }

            let _data = {
                message: message,
                room_id:room_id,
            }
            let _url = `{{route('send_public_message')}}`;
            $.ajaxSetup({headers: {'X-CSRF-Token': _token}});
            $.ajax({
                url: _url,
                method: 'POST',
                dataType: 'json',
                data: _data,
                cache: false,
                success: function (response) {
                    if(response != null && response!=false) {
                        $(".emojionearea-editor").html('');
                        appendNewPublicMessage(response)
                    }
                }
            });
        }


        function appendReceivedPublicMessages(_data) {

            $.each(_data, function (index, value)  {
                if(value.user_id !== current_user){
                    $("#message_box").append(`
        <li class="replies" data-id=${value.id}>\n\n
            <img src="http://emilcarlsson.se/assets/mikeross.png" alt=""/>\n                    \n
            <span>
                <p class="name" style=" font-size: 75%; margin: -7px 0 7px 0; color:${value.name}"> ${value.nick_name} </p>
                <p style="word-wrap: break-word;">\n                  \n                    ${value.message}\n                    </p>
                <p class="message-time" style="
    font-size: 75%;
    margin: 0 0 -5px 0;
    text-align: right;
">${moment(value.created_at).format('DD-MM-YYYY HH:mm:ss a')}</p>
            </span>\n
        </li>`);

                }

            });

            $(".messages").scrollTop(10000000000);

        }

        function checkNewPublicMessages(){
            let last_msg_id = $('#message_box').children().last().data('id');

            let _data = {
                room_id:room_id,
                last_msg:last_msg_id
            }
            let _url = `{{route('check_new_public_messages')}}`;
            $.ajaxSetup({headers: {'X-CSRF-Token': _token}});
            $.ajax({
                url: _url,
                method: 'POST',
                dataType: 'json',
                data: _data,
                cache: false,
                success: function (response) {
                    console.log(response)
                    if(response != null && response!=false) {
                        appendReceivedPublicMessages(response)
                    }
                }
            });
        }

        $('.submit').click(function () {
            newMessage();
        });
        // $('.p_msg_input .emojionearea-editor').on('keydown', function (e) {
        //     console.log(e.which);
        //     if (e.which == 13) {
        //         newMessage();
        //         return false;
        //     }
        // });
        // $('.p_msg_input').on('keydown', function (e) {
        //     if (e.which == 13) {
        //         newMessage();
        //         return false;
        //     }
        // });
        function addEventListeners2(){
            $('.p_msg_input .emojionearea-editor').on('keydown', function (e) {
                // console.log(e.which);
                if (e.which == 13) {
                    newMessage();
                    return false;
                }
            });
        }

    </script>

    <script>
        /* SET_INTERVALS */
        setInterval(checkNewPrivateChat,4000);
        setInterval(checkNewPublicMessages,4000);
        setInterval(checkUserIsActive,20000);
        setInterval(checkUserCanWrite,20000);
        setInterval(checkUserIsBlocked,4000);
    </script>

    <script>
        /* Disabled */
        function updateUserRole(uid, role, room = null, block_time = null) {
            let _data = {
                user_id: uid,
                role_id: role,
                room_id: room_id,
                block_time: block_time,
            }
            let _url = `{{route('update_user_role')}}`;
            $.ajaxSetup({headers: {'X-CSRF-Token': _token}});
            $.ajax({
                url: _url,
                method: 'POST',
                dataType: 'json',
                data: _data,
                cache: false,
                success: function (response) {
                    if (response.status === "success") {
                        // reloadDropdownItems(uid, role, response.done);
                        toastr.success("تم التعديل بنجاح", 'تم', {timeOut: 5000});
                    } else if (response.status === "error") {
                        toastr.error('حاول لاحقا', 'خطأ', {timeOut: 5000});
                    }
                }
            });
            return false;
        }

        // function reloadDropdownItems(uid, role, done) {
        //     let dropdownItem, itemText;
        //     switch (role) {
        //         case(1):
        //             itemText = 'إيقاف الحساب';
        //             $('#' + uid + '.stop_account').html(itemText);
        //             break;
        //         case(2):
        //             itemText = (done === 0) ? 'كتم' : 'إغلاق الكتم';
        //             $('#' + uid + '.block_message_send').html(itemText);
        //             break;
        //         case(3):
        //             itemText = (done === 0) ? "السماح بتغيير لون الاسم" : "عدم السماح بتغيير لون الاسم";
        //             $('#' + uid + '.change_color').html(itemText);
        //             break;
        //         case(5):
        //             itemText = (done === 0) ? "السماح بإرسال رسائل خاصة" : "عدم السماح بإرسال رسائل خاصة";
        //             $('#' + uid + '.private_message').html(itemText);
        //             break;
        //         case(100):
        //             itemText = 'طرد من الغرفة';
        //             $('#' + uid + '.block_from_room').html(itemText);
        //             break;
        //     }
        //     // return itemText;
        //     console.log(itemText);
        // }
    </script>

    {{--<script src="/js/app.js"></script>--}}
    {{--<script src="/js/popup_chat.js"></script>--}}
    <script src="/toastr/build/toastr.min.js"></script>

    <script type="text/javascript" src="https://rawgit.com/KevinSheedy/jquery.alphanum/master/jquery.alphanum.js"></script>
    @if(Auth::user()->can('can_use_special_characters'))
        <script>
            $("#nick_name_input").alphanum({
                allow              : 'àâäæ',
                disallow           : 'xyz',
                allowSpace         : false,
                allowNumeric       : true,
                allowUpper         : true,
                allowLower         : true,
                allowCaseless      : true,
                allowLatin         : true,
                allowOtherCharSets : true,
                forceUpper         : false,
                forceLower         : false,
                maxLength          : NaN
            });
        </script>
    @else
        <script>
            $("#nick_name_input").alphanum();
        </script>
@endif
</body>
</html>
