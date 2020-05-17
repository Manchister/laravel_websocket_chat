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
</head>

<body style="direction: rtl">

<div class="rooms">
   <div class="card text-center" style="background-color: #2c3e50">
        <div class="card-header">
            <h4 class="card-title">{{__('room.Choose Room')}}</h4>
        </div>
        <div class="card-body">
            @if(empty($rooms))
                <h1>لم يتم إضافة اي غرف حتى الان...</h1>
            @endif
            <ul>
                @foreach($rooms as $key => $room)
                    @if(auth()->user()->is_block_from($room->id, 100))

                    @else
                        <li class="contact rooms" data-dismiss="modal">
                            <a href="{{route('single_room',['id'=>$id,'room'=>$room->id])}}">
                                <span hidden class="room_id">{{$room->url}}</span>
                                <span hidden class="room_number">{{$room->id}}</span>
                                <span hidden class="room_name">{{$room->name}}</span>
                                <span hidden
                                      class="room_avatar">{{ $room->avatar ??  "https://previews.123rf.com/images/littleartvector/littleartvector1901/littleartvector190100024/126067615-interior-background-with-cozy-colorful-living-room-vector-illustration.jpg"}}</span>
                                <div class="wrap single-room">
                                    {{--<span class="contact-status online"></span>--}}
                                    <img style="height: 40px"
                                         src="{{ $room->avatar ??  "https://previews.123rf.com/images/littleartvector/littleartvector1901/littleartvector190100024/126067615-interior-background-with-cozy-colorful-living-room-vector-illustration.jpg"}}"
                                         alt=""/>
                                    <div class="meta">
                                        <p class="name">{{$room->name}}</p>
                                        <p class="preview">{{$room->description}}</p>
                                    </div>
                                </div>
                            </a>
                        </li>
                    @endif
                @endforeach
            </ul>
        </div>
        <div class="card-footer text-muted">
            <button onclick="location.href = '/';" type="button"
                    class="btn btn-danger"{{-- data-dismiss="modal"--}}>{{__('room.Close')}}</button>

            <button id="logout-btn-1" class="btn btn-danger"
                    onclick="event.preventDefault();document.getElementById('logout-form-1').submit();"> {{ __('تسجيل الخروج') }}
            </button>


            <form id="logout-form-1" action="{{ route('logout') }}" method="POST"
                  style="display: none;">
                @csrf
                <input id="id" type="hidden" name="id" value="{{$id}}" required>
            </form>
        </div>
    </div>
</div>

<script src="{{ asset('js/jquery-3.1.1.js') }}"></script>
{{--<script src='//production-assets.codepen.io/assets/common/stopExecutionOnTimeout-b2a7b3fe212eaa732349046d8416e00a9dec26eb7fd347590fbced3ab38af52e.js'></script>
<script src='https://code.jquery.com/jquery-2.2.4.min.js'></script>--}}
{{--<script src="{{ asset('js/app.js') }}" defer></script>--}}
{{--<script src="/js/app.js"></script>--}}
{{--<script src="/js/popup_chat.js"></script>--}}
<script src="/toastr/build/toastr.min.js"></script>


</body>
</html>
