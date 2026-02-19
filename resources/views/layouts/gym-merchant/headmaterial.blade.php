<head>
    <meta charset="utf-8"/>
    <title>Business Admin | {{ $title }}</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport"/>
    <meta content="Gym Management System" name="description"/>
    <meta content="INITIATIVE" name="author"/>
    {{-- Favicons--}}
    <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('favicon/apple-icon-57x57.png') }}">
    <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('favicon/apple-icon-60x60.png') }}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('favicon/apple-icon-72x72.png') }}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('favicon/apple-icon-76x76.png') }}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('favicon/apple-icon-114x114.png') }}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('favicon/apple-icon-120x120.png') }}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('favicon/apple-icon-144x144.png') }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('favicon/apple-icon-152x152.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon/apple-icon-180x180.png') }}">
    <link rel="icon" type="image/png" sizes="192x192"  href="{{ asset('favicon/android-icon-192x192.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('favicon/favicon-96x96.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('favicon//manifest.json') }}">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{ asset('favicon/ms-icon-144x144.png') }}">
    <meta name="theme-color" content="#ffffff">
    {{-- Favicons end--}}
    <meta name="csrf-token" content="{{ csrf_token() }}"/>

    <!-- BEGIN GLOBAL MANDATORY STYLES -->
<link rel="stylesheet" href="{{ asset("admin/global/plugins/font-awesome/css/font-awesome.min.css") }}">
<link rel="stylesheet" href="{{ asset("admin/global/css/font-awesome-animation.min.css") }}">

<link rel="stylesheet" href="{{ asset("admin/global/plugins/simple-line-icons/simple-line-icons.min.css") }}">
<link rel="stylesheet" href="{{ asset("admin/global/plugins/bootstrap/css/bootstrap.css") }}">
<link rel="stylesheet" href="{{ asset("admin/global/plugins/uniform/css/uniform.default.css") }}">
<link rel="stylesheet" href="{{ asset("admin/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css") }}">
<!-- END GLOBAL MANDATORY STYLES -->
    <!-- BEGIN PAGE LEVEL PLUGINS -->
<link rel="stylesheet" href="{{ asset("admin/global/plugins/bootstrap-daterangepicker/daterangepicker-bs3.css") }}">
<!-- END PAGE LEVEL PLUGINS -->
    <!-- BEGIN THEME GLOBAL STYLES -->
    {{-- @vite('admin/global/css/components-md.min.css') --}}

<link rel="stylesheet" href="{{ asset("admin/global/css/components-md.min.css") }}">


{{-- {{ HTML::style('admin/global/css/components-md.min.css',array('id'=>'style_components')) }} --}}
<link rel="stylesheet" href="{{ asset("admin/global/css/plugins-md.min.css") }}">
<link rel="stylesheet" href="{{ asset("admin/global/css/md-loader.css") }}">
<link rel="stylesheet" href="{{ asset("admin/global/plugins/select2/select2.min.css") }}">
<!-- END THEME GLOBAL STYLES -->
    <!-- BEGIN THEME LAYOUT STYLES -->
<link rel="stylesheet" href="{{ asset("admin/admin/layout3/css/layout.css") }}">
<link rel="stylesheet" href="{{ asset("admin/global/plugins/froiden-helper/helper.css") }}">
<link rel="stylesheet" href="{{ asset("admin/global/plugins/bootstrap-toastr/toastr.min.css") }}">

<link rel="stylesheet" href="{{ asset("fitsigma_customer/bower_components/sidebar-nav/dist/sidebar-nav.min.css") }}">
<!-- animation CSS -->
<link rel="stylesheet" href="{{ asset("fitsigma_customer/css/animate.css") }}">
<!-- Custom CSS -->
<link rel="stylesheet" href="{{ asset("fitsigma_customer/css/style.css") }}">
<!-- color CSS -->
<link rel="stylesheet" href="{{ asset("fitsigma_customer/css/colors/megna.css") }}">
<!--helper CSS-->
    <link rel="stylesheet" href="{{ asset("admin/global/plugins/froiden-helper/helper.css") }}">
    <link rel="stylesheet" href="{{ asset("fitsigma_customer/css/custom.css") }}">
    <style>
        .sidebar #side-menu .user-pro {
            background: url({{ asset('fitsigma_customer/images/profile-menu.png') }}) center center/cover no-repeat;
        }

        .modal-backdrop {
            z-index: 99;
        }

        .modal-dialog {
            z-index: 100;
        }

        body.modal-open .modal .modal-dialog {
            margin-top: 5%;
        }

        .sidebar {
            z-index: 97;
        }

        .navbar {
            z-index: 98;
        }
    </style>

    @yield('CSS')
    <link rel="stylesheet" href="{{ asset("admin/admin/layout3/css/custom.css?v=1.6") }}">
    <!-- END THEME LAYOUT STYLES -->
</head>

<!-- END HEAD -->
