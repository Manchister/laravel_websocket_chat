<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <link rel="icon" type="image/png" href="../assets/paper_img/favicon.ico">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>

    <title>HTML BoilerPlate Login</title>

    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport'/>
    <meta name="viewport" content="width=device-width"/>
    <meta name="author" content="Sreejith BS">

    <!-- Style-CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="{{asset ('/css/login_register.css')}}" type="text/css" media="all"/>

    <!--     Fonts and icons     -->
    <link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300' rel='stylesheet' type='text/css'>
</head>

<body style="direction: rtl">
<nav class="navbar navbar-ct-transparent navbar-fixed-top" role="navigation-demo" id="register-navbar">
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->

    </div><!-- /.container-->
</nav>

<div class="wrapper">
    <div class="register-background">
        <div class="filter-black"></div>
        <div class="container">
            <h1 style="
                        color: white;
                        text-align: center;
                        font-size: 300%;">{{ config('app.name', 'chatRoom') }}</h1>
        </div>
        <div class="footer register-footer text-center">
            <h6>&copy;
                <script>document.write(new Date().getFullYear())</script>
                | {{ config('app.name', 'chatRoom') }}</h6>
            <span style="color:#ffffff;font-size:14px;">developed by Manchister</span>
        </div>
    </div>
</div>

</body>

<script src="http://code.jquery.com/jquery-3.2.1.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" type="text/javascript"></script>

</html>