<!DOCTYPE html>

<html>
<head>
    <meta charset="utf-8"/>
    <title>HamroFitness | Merchant Lock Screen | Hamrosoftware</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport"/>
    <meta content="" name="description"/>
    <meta content="" name="author"/>
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css"/>
{!! HTML::style('admin/global/plugins/font-awesome/css/font-awesome.min.css') !!}
{!! HTML::style('admin/global/plugins/simple-line-icons/simple-line-icons.min.css') !!}
{!! HTML::style('admin/global/plugins/bootstrap/css/bootstrap.min.css') !!}
{!! HTML::style('admin/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css') !!}
<!-- END GLOBAL MANDATORY STYLES -->
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <!-- END PAGE LEVEL PLUGINS -->
    <!-- BEGIN THEME GLOBAL STYLES -->
{!! HTML::style('admin/global/css/components-md.min.css') !!}
{!! HTML::style('admin/global/css/plugins-md.min.css') !!}
<!-- END THEME GLOBAL STYLES -->
    <!-- BEGIN PAGE LEVEL STYLES -->
{!! HTML::style('admin/pages/css/lock-2.min.css') !!}
<!-- END PAGE LEVEL STYLES -->
    <!-- BEGIN THEME LAYOUT STYLES -->
    <!-- END THEME LAYOUT STYLES -->

    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon/favicon-16x16.png') }}">

    <style>
        .size-icon {
            font-size: 18px;
        }

    </style>
</head>
<!-- END HEAD -->

<body>
@yield('content')
<!--[if lt IE 9]>
{!! HTML::script('admin/global/plugins/respond.min.js') !!}
{!! HTML::script('admin/global/plugins/excanvas.min.js') !!}
<![endif]-->
<!-- BEGIN CORE PLUGINS -->
{!! HTML::script('admin/global/plugins/jquery.min.js') !!}
{!! HTML::script('admin/global/plugins/bootstrap/js/bootstrap.min.js') !!}
{!! HTML::script('admin/global/plugins/js.cookie.min.js') !!}
{!! HTML::script('admin/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js') !!}
{!! HTML::script('admin/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js') !!}
{!! HTML::script('admin/global/plugins/jquery.blockui.min.js') !!}
{!! HTML::script('admin/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js') !!}
<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
{!! HTML::script('admin/global/plugins/backstretch/jquery.backstretch.min.js') !!}
<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN THEME GLOBAL SCRIPTS -->
{!! HTML::script('admin/global/scripts/app.js') !!}
<!-- END THEME GLOBAL SCRIPTS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<!-- END PAGE LEVEL SCRIPTS -->
<!-- BEGIN THEME LAYOUT SCRIPTS -->
<!-- END THEME LAYOUT SCRIPTS -->
<script>
    $('#login-form').on('submit', function (event) {
        event.preventDefault();
        $.ajax({
            url: "{{ route('merchant.lockLogin') }}",
            type: 'POST',
            data: $('#login-form').serialize(),
            container: '#login-form',
            success: function (response) {
                if (response.success === false) {
                    $('#error-message').addClass("alert alert-danger");
                    $('#error-message').html(response.message);
                } else {
                    $('#error-message').addClass("alert alert-success");
                    $('#error-message').html(response.message);
                    window.location.href = response.url;
                }
            }
        });
        return false;
    });

    $('.view').on('click',function(){
        var p = document.getElementById('password');
        if(p.getAttribute("type") == 'password'){
            p.setAttribute('type', 'text');
        }else{
            p.setAttribute('type', 'password');
        }
    })

    var image_1 = '{{ asset("admin/pages/media/bg/1.png") }}';
    var image_2 = '{{ asset("admin/pages/media/bg/2.png") }}';
    var image_3 = '{{ asset("admin/pages/media/bg/3.png") }}';

    $.backstretch([
        image_1,
        image_2,
        image_3
    ], {
        fade: 1000,
        duration: 8000
    });
</script>
</body>

</html>
