<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8"/>
    <link rel="icon" type="image/png" href="#">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>

    <title>{{ config('app.name', 'chatRoom') }}</title>

    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport'/>
    <meta name="viewport" content="width=device-width"/>

    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="{{asset ('/css/login_register.css')}}" type="text/css" media="all"/> <!-- Style-CSS -->
    <!--     Fonts and icons     -->
    <link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300' rel='stylesheet' type='text/css'>
</head>

<body style="direction: rtl">
<nav class="navbar navbar-ct-transparent navbar-fixed-top" role="navigation-demo" id="register-navbar">
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        {{--<div class="navbar-header">
            <a class="navbar-brand" href="javascript:void(0)">HTML BoilerPlate</a>
        </div>--}}

    </div><!-- /.container-->
</nav>

<div class="wrapper">
    <div class="register-background">
        <div class="filter-black"></div>
        <div class="container">
            <div class="row">
                <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1 ">
                    <div class="register-card">
                        <h3 class="title">{{__('auth.Sign Up Page')}}</h3>
                        <form class="form-horizontal" method="POST" action="{{ route('register') }}">
                            {{ csrf_field() }}
                            <label>{{__('auth.Name')}}</label>
                            <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}"
                                   required autofocus>
                            @if ($errors->has('name'))
                                <span class="help-block">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                            @endif
                            <label>{{__('auth.UserName')}}</label>
                            <input id="username" type="text" class="form-control" name="username"
                                   value="{{ old('username') }}" required autofocus>
                            @if ($errors->has('username'))
                                <span class="help-block">
                                            <strong>{{ $errors->first('username') }}</strong>
                                        </span>
                            @endif
                            <label>{{__('auth.nick_name')}}</label>
                            <input id="nick_name" type="text" class="form-control" name="nick_name"
                                   value="{{ old('nick_name') }}" required>

                            @if ($errors->has('nick_name'))
                                <span class="help-block">
                                            <strong>{{ $errors->first('nick_name') }}</strong>
                                        </span>
                            @endif
                            <label>{{__('auth.E-Mail Address')}}</label>
                            <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}"
                                   required>

                            @if ($errors->has('email'))
                                <span class="help-block">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                            @endif


                            <label>{{__('auth.Password')}}</label>
                            <input id="password" type="password" class="form-control" name="password" required>

                            @if ($errors->has('password'))
                                <span class="help-block">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                            @endif

                            <label>{{__('auth.Confirm Password')}}</label>
                            <input id="password-confirm" type="password" class="form-control"
                                   name="password_confirmation" required>
                            <input id="room_id" type="hidden" name="room_id" value="{{$id}}" required>
                            <input type="submit" class="btn btn-primary btn-block" value="{{__('auth.Sign Up')}}"/>

                        </form>
                        <div class="forgot">
                            <a href="{{ route('login') }}" class="btn btn-simple btn-danger">{{__('auth.have Account')}}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer register-footer text-center">
            <h6>&copy;
                <script>document.write(new Date().getFullYear())</script>
                | {{ config('app.name', 'chatRoom') }}</h6>
        </div>
    </div>
</div>

</body>

<script src="http://code.jquery.com/jquery-3.2.1.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" type="text/javascript"></script>

</html>