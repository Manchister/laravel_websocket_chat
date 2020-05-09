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
    <meta name="can_make_private_chat" content="{{ auth()->user()->can('can_make_private_chat') ?? ''}}">
    <meta name="is_room_supervisor" content="{{ auth()->user()->can('is_room_supervisor') ?? ''}}">
    <meta name="is_supervisor" content="{{ auth()->user()->user_level == 3 ?? ''}}">

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
</head>
<body style="direction: rtl">

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
                    class='Button btn btn-secondary btn-lg'{{-- type="button" data-toggle="modal" data-target="#myModal"--}}>{{__('room.Users')}}</button>
            <button id='rooms' class='Button btn btn-secondary btn-lg'>{{__('room.Rooms')}}</button>
        </div>

        <div id="search">
            <label for=""><i class="fa fa-search" aria-hidden="true"></i></label>
            <input type="text" placeholder="{{__('room.Search')}}"/>
        </div>

        <div id="contacts">
            <!-- The Modal -->
            <div class="modal mymodel show" id="myModal">
                <div class="modal-dialog" role="document">
                    <div class="modal-content" style="background-color: #2c3e50">

                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">{{__('room.Choose Room')}}</h4>
                            {{--<button type="button" class="close" data-dismiss="modal">&times;</button>--}}
                        </div>

                        <!-- Modal body -->
                        <div class="modal-body">
                            @if(empty(App\Models\Room::getRooms( auth()->user()->id)))
                                <h1>لم يتم إضافة اي غرف حتى الان...</h1>
                            @endif
                            <ul>
                                {{--قسم الغرف--}}
                                {{--@if(App\Models\Room::getRooms( auth()->user()->id)->count() <= 0)
                                    <h1>not found</h1>
                                @endif--}}
                                @foreach(App\Models\Room::getRooms( auth()->user()->id) as $key => $room)
                                    @if(auth()->user()->is_block_from($room->id, 100))

                                    @else
                                    <li class="contact rooms" data-dismiss="modal">
                                        <span hidden class="room_id">{{$room->url}}</span>
                                        <span hidden class="room_number">{{$room->id}}</span>
                                        <span hidden class="room_name">{{$room->name}}</span>
                                        <span hidden
                                              class="room_avatar">{{ $room->avatar ??  "https://previews.123rf.com/images/littleartvector/littleartvector1901/littleartvector190100024/126067615-interior-background-with-cozy-colorful-living-room-vector-illustration.jpg"}}</span>
                                        <div class="wrap">
                                            {{--<span class="contact-status online"></span>--}}
                                            <img style="height: 40px"
                                                 src="{{ $room->avatar ??  "https://previews.123rf.com/images/littleartvector/littleartvector1901/littleartvector190100024/126067615-interior-background-with-cozy-colorful-living-room-vector-illustration.jpg"}}"
                                                 alt=""/>
                                            <div class="meta">
                                                <p class="name">{{$room->name}}</p>
                                                <p class="preview">{{$room->description}}</p>
                                            </div>
                                        </div>
                                    </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>

                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <button onclick="location.href = '/';" type="button"
                                    class="btn btn-danger"{{-- data-dismiss="modal"--}}>{{__('room.Close')}}</button>

                            <button id="logout-btn-1" class="btn btn-danger" onclick="event.preventDefault();document.getElementById('logout-form-1').submit();"> {{ __('تسجيل الخروج') }}
                            </button>


                            <form id="logout-form-1" action="{{ route('logout') }}" method="POST"
                                  style="display: none;">
                                @csrf
                                <input id="id" type="hidden" name="id" value="{{$id}}" required>
                            </form>
                        </div>

                    </div>
                </div>
            </div>

            <ul id="user_room_sec">
                {{--قسم الغرف--}}

                @foreach(App\Models\Room::getRooms( auth()->user()->id) as $key => $room)
                    @if(auth()->user()->is_block_from($room->id, 100))

                        @else
                    <li class="contact rooms" style="display: none">
                        <span hidden class="room_id">{{$room->url}}</span>
                        <span hidden class="room_number">{{$room->id}}</span>
                        <span hidden class="room_name">{{$room->name}}</span>
                        <span hidden
                              class="room_avatar">{{ $room->avatar ??  "//previews.123rf.com/images/littleartvector/littleartvector1901/littleartvector190100024/126067615-interior-background-with-cozy-colorful-living-room-vector-illustration.jpg"}}</span>
                        <div class="wrap">
                            <span class="contact-status online"></span>
                            <img style="height: 40px"
                                 src="{{ $room->avatar ??  "//previews.123rf.com/images/littleartvector/littleartvector1901/littleartvector190100024/126067615-interior-background-with-cozy-colorful-living-room-vector-illustration.jpg"}}"
                                 alt=""/>
                            <div class="meta">
                                <p class="name">{{$room->name}}</p>
                                <p class="preview">{{$room->description}}</p>
                            </div>
                        </div>
                    </li>
                    @endif
                @endforeach
                <form id="edit-form" action="#" method="POST" style="display: none">
                    @csrf
                    @method('PUT')
                    {{--<li class="contact settings">
                        <div class="wrap">
                            <div class="meta">
                                <p class="name">إسم المستخدم</p>
                                <p class="preview">{{auth()->user()->username}}</p>
                            </div>
                        </div>
                    </li>--}}
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="inputGroup-sizing-default">إسم المستخدم</span>
                        </div>
                        {{--<input type="text" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default">--}}
                        <input type="text" class="form-control" placeholder="{{auth()->user()->username}}" readonly>
                    </div>
                    {{--<li class="contact settings">
                        <div class="wrap">
                            <div class="meta">
                                <input name='name' type="text" class="preview" value="{{auth()->user()->name}}">الاسم</input>
                            </div>
                        </div>
                    </li>--}}
{{--                    <div class="input-group mb-3">--}}
{{--                        <div class="input-group-prepend">--}}
{{--                            <span class="input-group-text" id="inputGroup-sizing-default">الاسم</span>--}}
{{--                        </div>--}}
{{--                        --}}{{--<input type="text" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default">--}}
{{--                        <input aria-label="Default" aria-describedby="inputGroup-sizing-default" name='name' type="text"--}}
{{--                               class="form-control" value="{{auth()->user()->name}}">--}}
{{--                    </div>--}}
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="inputGroup-sizing-default">الاسم المستعار</span>
                        </div>
                        {{--<input type="text" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default">--}}
                        <input aria-label="Default" aria-describedby="inputGroup-sizing-default" name='nick_name'
                               type="text" class="form-control" value="{{auth()->user()->nick_name}}">
                    </div>
                    {{--<li class="contact settings">
                        <div class="wrap">
                            <div class="meta">

                            </div>
                        </div>
                    </li>--}}
                    @if(auth()->user()->can('can_change_name_color') || auth()->user()->user_level == 3)
                        <div class="input-group mb-3" id="changeNameColorDiv">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="inputGroup-sizing-default">لون الاسم</span>
                            </div>
                            {{--<input type="text" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default">--}}
                            <input aria-label="Default" aria-describedby="inputGroup-sizing-default" name='name_color'
                                   type="color" class="form-control" value="">
                        </div>
                    @endif
                    {{--<div class="input-group mb-3">
                        <select class="custom-select" id="inputGroupSelect02">
                            <option selected>إختر...</option>
                            <option value="1">احمر</option>
                            <option value="2">اصفر</option>
                            <option value="3">اخضر</option>
                        </select>
                        <div class="input-group-append">
                            <label class="input-group-text" for="inputGroupSelect02">لون الاسم</label>
                        </div>
                    </div>--}}
                    {{--<li class="contact settings">
                        <div class="wrap">
                            <div class="meta">
                                <input name="password" type="password" class="preview" value="**********">كلمة
                                السر</input>
                            </div>
                        </div>
                    </li>--}}
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="inputGroup-sizing-default">كلمة المرور</span>
                        </div>
                        {{--<input type="text" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default">--}}
                        <input aria-label="Default" aria-describedby="inputGroup-sizing-default" name='password'
                               type="password" class="form-control" value="**********">
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="inputGroup-sizing-default">تأكيد كلمة المرور</span>
                        </div>
                        {{--<input type="text" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default">--}}
                        <input aria-label="Default" aria-describedby="inputGroup-sizing-default"
                               name='password_confirmation' type="password" class="form-control" value="**********">
                    </div>
                    {{--<li class="contact settings">
                        <div class="wrap">
                            <div class="meta">
                                <input name="password_confirmation" type="password" class="preview" value="**********">تاكيد كلمة
                                السر</input>
                            </div>
                        </div>
                    </li>--}}
                    <li class="contact">
                        <div class="wrap">
                            <div class="meta">
                                <input type="submit" class="btn btn-primary btn-block" value="موافق"/>
                            </div>
                        </div>
                    </li>
                </form>
            </ul>
            {{--<li class="contact">
                <div class="wrap">
                    <span class="contact-status online"></span>
                    <img src="http://emilcarlsson.se/assets/louislitt.png" alt="" />
                    <div class="meta">
                        <p class="name">Louis Litt</p>
                        <p class="preview">You just got LITT up, Mike.</p>
                    </div>
                </div>
            </li>
            <li class="contact active">
                <div class="wrap">
                    <span class="contact-status away"></span>
                    <img src="http://emilcarlsson.se/assets/harveyspecter.png" alt="" />
                    <div class="meta">
                        <p class="name">Harvey Specter</p>
                        <p class="preview">Wrong. You take the gun, or you pull out a bigger one. Or, you call their bluff. Or, you do any one of a hundred and forty six other things.</p>
                    </div>
                </div>
            </li>--}}
            {{-- <li class="contact">
                 <div class="wrap">
                     <span class="contact-status busy"></span>
                     <img src="http://emilcarlsson.se/assets/rachelzane.png" alt="" />
                     <div class="meta">
                         <p class="name">Rachel Zane</p>
                         <p class="preview">I was thinking that we could have chicken tonight, sounds good?</p>
                     </div>
                 </div>
             </li>
             <li class="contact">
                 <div class="wrap">
                     <span class="contact-status online"></span>
                     <img src="http://emilcarlsson.se/assets/donnapaulsen.png" alt="" />
                     <div class="meta">
                         <p class="name">Donna Paulsen</p>
                         <p class="preview">Mike, I know everything! I'm Donna..</p>
                     </div>
                 </div>
             </li>
             <li class="contact">
                 <div class="wrap">
                     <span class="contact-status busy"></span>
                     <img src="http://emilcarlsson.se/assets/jessicapearson.png" alt="" />
                     <div class="meta">
                         <p class="name">Jessica Pearson</p>
                         <p class="preview">Have you finished the draft on the Hinsenburg deal?</p>
                     </div>
                 </div>
             </li>
             <li class="contact">
                 <div class="wrap">
                     <span class="contact-status"></span>
                     <img src="http://emilcarlsson.se/assets/haroldgunderson.png" alt="" />
                     <div class="meta">
                         <p class="name">Harold Gunderson</p>
                         <p class="preview">Thanks Mike! :)</p>
                     </div>
                 </div>
             </li>
             <li class="contact">
                 <div class="wrap">
                     <span class="contact-status"></span>
                     <img src="http://emilcarlsson.se/assets/danielhardman.png" alt="" />
                     <div class="meta">
                         <p class="name">Daniel Hardman</p>
                         <p class="preview">We'll meet again, Mike. Tell Jessica I said 'Hi'.</p>
                     </div>
                 </div>
             </li>
             <li class="contact">
                 <div class="wrap">
                     <span class="contact-status busy"></span>
                     <img src="http://emilcarlsson.se/assets/katrinabennett.png" alt="" />
                     <div class="meta">
                         <p class="name">Katrina Bennett</p>
                         <p class="preview">I've sent you the files for the Garrett trial.</p>
                     </div>
                 </div>
             </li>
             <li class="contact">
                 <div class="wrap">
                     <span class="contact-status"></span>
                     <img src="http://emilcarlsson.se/assets/charlesforstman.png" alt="" />
                     <div class="meta">
                         <p class="name">Charles Forstman</p>
                         <p class="preview">Mike, this isn't over.</p>
                     </div>
                 </div>
             </li>
             <li class="contact">
                 <div class="wrap">
                     <span class="contact-status"></span>
                     <img src="http://emilcarlsson.se/assets/jonathansidwell.png" alt="" />
                     <div class="meta">
                         <p class="name">Jonathan Sidwell</p>
                         <p class="preview"><span>You:</span> That's bullshit. This deal is solid.</p>
                     </div>
                 </div>
             </li>--}}

        </div>
        <div id="bottom-bar">
            <button id="logout-btn-2" onclick="event.preventDefault();document.getElementById('logout-form-room').submit();">
                <i class="fa fa-lock fa-fw" aria-hidden="true"></i> <span>{{ __('تسجيل الخروج') }}</span>
            </button>

            <form id="logout-form-room" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
                <input id="room_id" type="hidden" name="id" value="{{$id}}" required>
            </form>
            <button id="settings"><i class="fa fa-cog fa-fw" aria-hidden="true"></i>
                <span>{{__('room.Settings')}}</span></button>
        </div>
    </div>
    <div class="content">
        <div class="contact-profile">
            <img style="height: 40px" id="room_img" src=""
                 alt=""/>
            <p id="room_name"></p>
            {{--<div class="social-media">
                <i class="fa fa-facebook" aria-hidden="true"></i>
                <i class="fa fa-twitter" aria-hidden="true"></i>
                <i class="fa fa-instagram" aria-hidden="true"></i>
            </div>--}}
        </div>
        <div class="messages">
            <ul id="message_box">
                {{--قسم الرسائل--}}
                {{-- <li class="sent" >
                     <img src="http://emilcarlsson.se/assets/mikeross.png" alt=""/>
                     <p>How the hell am I supposed to get a jury to believe you when I am not even sure that I do?!</p>
                 </li>
                 <li class="replies">
                     <img src="http://emilcarlsson.se/assets/harveyspecter.png" alt=""/>
                     <p>When you're backed against the wall, break the god damn thing down.</p>
                 </li>--}}
                {{--<li class="replies">
                    <img src="http://emilcarlsson.se/assets/harveyspecter.png" alt="" />
                    <p>Excuses don't win championships.</p>
                </li>
                <li class="sent">
                    <img src="http://emilcarlsson.se/assets/mikeross.png" alt="" />
                    <p>Oh yeah, did Michael Jordan tell you that?</p>
                </li>
                <li class="replies">
                    <img src="http://emilcarlsson.se/assets/harveyspecter.png" alt="" />
                    <p>No, I told him that.</p>
                </li>
                <li class="replies">
                    <img src="http://emilcarlsson.se/assets/harveyspecter.png" alt="" />
                    <p>What are your choices when someone puts a gun to your head?</p>
                </li>
                <li class="sent">
                    <img src="http://emilcarlsson.se/assets/mikeross.png" alt="" />
                    <p>What are you talking about? You do what they say or they shoot you.</p>
                </li>
                <li class="replies">
                    <img src="http://emilcarlsson.se/assets/harveyspecter.png" alt="" />
                    <p>Wrong. You take the gun, or you pull out a bigger one. Or, you call their bluff. Or, you do any one of a hundred and forty six other things.</p>
                </li>--}}
            </ul>
        </div>
        <div class="message-input">
            <div class="wrap">
                @if(auth()->user()->can('can_write') || auth()->user()->user_level == 3)
                    <input type="text" placeholder="{{__('room.Write your message...')}}">
                @else
                    <input type="text" placeholder="{{__('تم تعليقك من الكتابة مؤقتا...')}}" readonly>
                @endif
                <i class="fa fa-paperclip attachment" aria-hidden="true"></i>
                <button class="submit"><i class="fa fa-paper-plane" aria-hidden="true"></i></button>
            </div>
        </div>
    </div>
</div>
{{--models sec--}}


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
            <form id="user_settings_edit_form" action="#" method="POST" >

            <div class="modal-body" style="text-align: right;">

                <h1 style="margin-bottom: 20px" id="body">إختر وقت الطرد</h1>
                <div class="input-group mb-3" style="direction: ltr;">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="inputGroup-sizing-default">دقيقة/دقائق</span>
                    </div>
                    {{--<input type="text" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default">--}}
                    <input  name="userId" type="hidden">
                    <input  name="roomId" type="hidden">
                    <input  name="roleId" type="hidden">
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
{{--<script src='//production-assets.codepen.io/assets/common/stopExecutionOnTimeout-b2a7b3fe212eaa732349046d8416e00a9dec26eb7fd347590fbced3ab38af52e.js'></script>
<script src='https://code.jquery.com/jquery-2.2.4.min.js'></script>--}}
{{--<script src="{{ asset('js/app.js') }}" defer></script>--}}
<script src="/js/app.js"></script>
<script src="/js/popup_chat.js"></script>
<script src="/toastr/build/toastr.min.js"></script>
<script type="text/javascript">
    $('#edit-form').submit(function (e) {
        e.preventDefault();
        let formData = $("#edit-form").serialize();
        // console.log(formData.toArray());
        $.ajax({
            type: "post",
            url: '{{ url("$id/chatRoom")}}/{{auth()->user()->id}}',
            data: formData,
            success: function (store) {
                // alert(store.message);
                // console.log(store.name_color);
                toastr.success(store.message, 'تم', {timeOut: 5000});
                $('#edit-form').hide();
                $('.contact.rooms').show();
                $('li.sent p.name').css('color',store.name_color);
                $('meta[name=name_color]').attr('content',store.name_color);
            },
            error: function () {
                alert('error')
            }
        });
    });
</script>


</body>
</html>
