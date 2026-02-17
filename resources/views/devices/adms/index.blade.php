@extends('layouts.gym-merchant.gymbasic')

@section('CSS')
    {!! HTML::style('css/cropper.css')!!}
    {!! HTML::style('admin/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css') !!}
    {!! HTML::style('admin/global/plugins/ladda/ladda-themeless.min.css') !!}
    {!! HTML::style('admin/pages/css/profile.min.css') !!}
    <style>
        .error-msg {
            color: red;
            display: none;
        }
        .table-scrollable {
            width: 100%;
            overflow-x: hidden;
            overflow-y: hidden;
            border: 1px solid #e7ecf1;
            margin: 10px 0!important;
        }

    </style>
@stop

@section('content')
    <div class="container-fluid">
        <!-- BEGIN PAGE BREADCRUMBS -->
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <a href="{{ route('gym-admin.dashboard.index') }}">Home</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>Device</span>
            </li>
        </ul>
        <!-- END PAGE BREADCRUMBS -->
        <!-- BEGIN PAGE CONTENT INNER -->
        <div class="page-content-inner">
            <div class="row">
                <div class="col-md-12">
                    <!-- BEGIN PROFILE SIDEBAR -->
                    @foreach($devices as $device)
                    <div class="profile-sidebar">
                        <!-- PORTLET MAIN -->
                        <div class="portlet light profile-sidebar-portlet ">
                            <div class="profile-usertitle">
                                <div class="profile-usertitle-name"> {{ $device['Name'] }} - {{ $device['BranchCode'] }}</div>
                            </div>

                        </div>
                        <!-- END PORTLET MAIN -->
                        <!-- PORTLET MAIN -->
                        <div class="portlet light ">
                            <!-- STAT -->
                            <div class="row list-separated profile-stat">
                                <div class="col-xs-12">
                                    <div class="col-md-6 col-sm-12">
                                        <div class="uppercase profile-stat-title"> {{$device['UserCount']}} </div>
                                        <div class="uppercase profile-stat-text"> Total Users</div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="uppercase profile-stat-title"> {{$device['FPCount']}} </div>
                                        <div class="uppercase profile-stat-text"> Total FingerPrint</div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="uppercase profile-stat-title"> {{$device['TransCount']}} </div>
                                        <div class="uppercase profile-stat-text"> Total Transaction</div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="uppercase profile-stat-title"> {{$device['ClientCode']}} </div>
                                        <div class="uppercase profile-stat-text"> Client Code</div>
                                    </div>
                                </div>
                            </div>

                            <!-- END STAT -->
                            <div>
                                <div class="margin-top-20 profile-desc-link">
                                    <i class="fa fa-clock-o"></i>
                                    <a href="javascript:;">Last Active: {{ $device['LastActivity']}}</a>
                                </div>
                                <div class="margin-top-20 profile-desc-link">
                                    <i class="fa fa-check-circle"></i>
                                    <a href="javascript:;">Status: {{ $device['DeviceStatus']}}</a>
                                </div>
                                <div class="margin-top-20 profile-desc-link">
                                    <i class="fa fa-info-circle"></i>
                                    <a href="javascript:;">Ip: {{ $device['IP']}}</a>
                                </div>
                                <div class="margin-top-20 profile-desc-link">
                                    <i class="fa fa-envelope"></i>
                                    <a href="javascript:;">SN: {{ $device['SN']}}</a>
                                </div>
                                <div class="margin-top-20 profile-desc-link">
                                    <i class="fa fa-fax"></i>
                                    <a href="javascript:;">Model: {{ $device['DeviceModel']}}</a>
                                </div>
                                <div class="margin-top-20 profile-desc-link">
                                    <i class="fa fa-gear"></i>
                                    <a href="javascript:;">Device Function: {{ $device['DevFuns']}}</a>
                                </div>
                            </div>
                        </div>
                        <!-- END PORTLET MAIN -->
                    </div>
                    @endforeach
                    <!-- END BEGIN PROFILE SIDEBAR -->

                </div>
            </div>
        </div>
        <!-- END PAGE CONTENT INNER -->
    </div>


@stop

@section('footer')
    {!! HTML::script('admin/global/plugins/moment.min.js') !!}
    {!! HTML::script('admin/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js') !!}
    {!! HTML::script('admin/global/plugins/ladda/spin.min.js') !!}
    {!! HTML::script('admin/global/plugins/ladda/ladda.min.js') !!}
    {!! HTML::script('admin/pages/scripts/ui-buttons.min.js') !!}
    {!! HTML::script('admin/pages/scripts/profile.min.js') !!}

@stop
