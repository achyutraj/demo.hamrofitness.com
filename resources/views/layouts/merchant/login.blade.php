<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title>HamroFitness</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport"/>
    <meta content="" name="description"/>
    <meta content="" name="author"/>

    <link href="{{ asset('fonts/stylesheet.css') }}" rel="stylesheet">

    {!! HTML::style('admin/global/plugins/font-awesome/css/font-awesome.min.css') !!}

    {!! HTML::style('admin/global/plugins/bootstrap/css/bootstrap.min.css') !!}

    {!! HTML::style('admin/global/css/components-md.min.css') !!}

    {!! HTML::style('admin/pages/css/auth.css') !!}


    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon/favicon-16x16.png') }}">

    <style>
        .alert {
            margin-top: 10px;
            margin-bottom: 10px;
        }

        .user-login-5 .alert {
            margin-top: 0;
        }
        .view {
            float: right;
            margin-top: -34px;
        }
    </style>
    @yield('css')
</head>
<!-- END HEAD -->

<body class="hold-transition login-page">
<!-- BEGIN : LOGIN PAGE 5-2 -->
@yield('content')
<!-- BEGIN CORE PLUGINS -->
{!! HTML::script('admin/global/plugins/jquery.min.js') !!}
{!! HTML::script('admin/global/plugins/bootstrap/js/bootstrap.min.js') !!}
{!! HTML::script('admin/global/plugins/js.cookie.min.js') !!}
{!! HTML::script('admin/global/plugins/jquery.blockui.min.js') !!}
{!! HTML::script('admin/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js') !!}
<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
{!! HTML::script('admin/global/plugins/jquery-validation/js/jquery.validate.min.js') !!}
{!! HTML::script('admin/global/plugins/jquery-validation/js/additional-methods.min.js') !!}
{!! HTML::script('admin/global/plugins/backstretch/jquery.backstretch.min.js') !!}
<!-- END PAGE LEVEL PLUGINS -->

@yield('js')
</body>

</html>
