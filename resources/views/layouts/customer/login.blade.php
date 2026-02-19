<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon/favicon-16x16.png') }}">

    <title>@yield('title')</title>



    <link href="{{ asset('fonts/stylesheet.css') }}" rel="stylesheet">

<link rel="stylesheet" href="{{ asset("admin/global/plugins/font-awesome/css/font-awesome.min.css") }}">

<link rel="stylesheet" href="{{ asset("admin/global/plugins/bootstrap/css/bootstrap.min.css") }}">

<link rel="stylesheet" href="{{ asset("admin/global/css/components-md.min.css") }}">

<link rel="stylesheet" href="{{ asset("admin/pages/css/auth.css") }}">

    @yield('CSS')
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>

    <body class="hold-transition login-page">

    @yield('content')
    <!-- jQuery -->
        <script src="{{ asset("admin/global/plugins/respond.min.js") }}"></script>
        <script src="{{ asset("admin/global/plugins/excanvas.min.js") }}"></script>
        <![endif]-->
        <!-- BEGIN CORE PLUGINS -->
        <script src="{{ asset("admin/global/plugins/jquery.min.js") }}"></script>
        <script src="{{ asset("admin/global/plugins/bootstrap/js/bootstrap.min.js") }}"></script>
        <script src="{{ asset("admin/global/plugins/js.cookie.min.js") }}"></script>
        <script src="{{ asset("admin/global/plugins/jquery.blockui.min.js") }}"></script>
        <script src="{{ asset("admin/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js") }}"></script>
    <!-- END CORE PLUGINS -->
        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <script src="{{ asset("admin/global/plugins/jquery-validation/js/jquery.validate.min.js") }}"></script>
        <script src="{{ asset("admin/global/plugins/jquery-validation/js/additional-methods.min.js") }}"></script>
        <script src="{{ asset("admin/global/plugins/backstretch/jquery.backstretch.min.js") }}"></script>
    <!-- END PAGE LEVEL PLUGINS -->


        @yield('JS')

        <script>
            $('.view').on('click',function(){
                var p = document.getElementById('password');
                if(p.getAttribute("type") == 'password'){
                    p.setAttribute('type', 'text');
                }else{
                    p.setAttribute('type', 'password');
                }
            })
            var image_1;

            $('.login-bg').backstretch([
                    image_1
                ], {
                    fade: 1000,
                    duration: 8000
                }
            );

            var image_1 = '{{ asset("admin/pages/media/bg/1.png") }}';
            var image_2 = '{{ asset("admin/pages/media/bg/2.png") }}';
            var image_3 = '{{ asset("admin/pages/media/bg/3.png") }}';


            $.backstretch([
                image_2,
                image_1,
                image_3
            ], {
                fade: 1000,
                duration: 8000
            });
        </script>
    </body>

</html>
