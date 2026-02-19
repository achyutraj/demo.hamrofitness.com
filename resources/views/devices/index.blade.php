@extends('layouts.gym-merchant.gymbasic')
@section('CSS')
    <style>
        h4, h5 {
            font-weight: 600;
        }

        .danger {
            color: red;
        }
        #department.bs-select{
            width: 500px;
        }
    </style>
@endsection
@section('content')
    <div class="container-fluid">
        <!-- BEGIN PAGE BREADCRUMBS -->
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <a href="{{route('gym-admin.dashboard.index')}}">Home</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>Device Management</span>
            </li>
        </ul>
        <!-- END PAGE BREADCRUMBS -->
        <!-- BEGIN PAGE CONTENT INNER -->
        <div class="page-content-inner">
            <div class="row">
                <div class="col-md-12">
                    <!-- BEGIN EXAMPLE TABLE PORTLET-->
                    <div class="portlet light ">
                        <div class="portlet-title">
                            <div class="caption font-dark">
                                <i class="fa fa-fax font-red"></i>
                                <span class="caption-subject font-red bold uppercase"> Device Management</span>
                            </div>
                        </div>

                        <div class="portlet-body">
                            <div class="table-toolbar">
                                @if(session()->has('message'))
                                    <div class="alert alert-success">
                                        {{ session()->get('message') }}
                                    </div>
                                @endif
                                @if(session()->has('danger'))
                                    <div class="alert alert-danger">
                                        {{ session()->get('danger') }}
                                    </div>
                                @endif
                                <div class="asset-tab">
                                    <ul class="nav nav-tabs">
                                        <li class="active">
                                            <a data-toggle="tab" href="#branchList">Shift</a>
                                        </li>
                                        <li>
                                            <a data-toggle="tab" href="#departmentList">Department</a>
                                        </li>
                                        <li>
                                            <a data-toggle="tab" href="#deviceList">Device</a>
                                        </li>
                                    </ul>

                                    <div class="tab-content">
                                        <div id="branchList" class="tab-pane fade in active">
                                            @include('devices.shifts.table')
                                        </div>
                                        <div id="departmentList" class="tab-pane fade">
                                            @include('devices.departments.table')
                                        </div>
                                        <div id="deviceList" class="tab-pane fade">
                                            @include('devices.device_info.table')
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('footer')
<script href="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap.min.js"></script>
<script src="{{ asset("admin/global/plugins/bootbox/bootbox.min.js") }}"></script>
<script src="{{ asset("fitsigma_customer/bower_components/datatables/jquery.dataTables.min.js") }}"></script>
<script>
    $(document).ready(function () {
        $('#paymentTable').DataTable();
    });

    $('.bs-select').select2();

    $(function () {
        setTimeout(function () {
            $('.alert-message').slideUp();
        }, 3000);
    });
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var UIBootbox = function () {
        var branchData = function () {
            $(".branch-delete").click(function () {
                var branchUrl = $(this).data('branch-url');
                bootbox.confirm({
                    message: "Do you want to delete this shift?",
                    buttons: {
                        confirm: {
                            label: "Yes",
                            className: "btn-primary"
                        }
                    },
                    callback: function(result){
                        if(result){
                            $.easyAjax({
                                url: branchUrl,
                                type: 'POST',
                                data: {
                                    '_method': 'delete' , '_token': '{{ csrf_token() }}'
                                },
                                success: function(){
                                    location.reload();
                                }
                            });
                        }
                        else {
                            console.log('cancel');
                        }
                    }
                })

            })
        };
        var departmentData = function () {
            $(".department-delete").click(function () {
                var departmentUrl = $(this).data('department-url');
                bootbox.confirm({
                    message: "Do you want to delete this department?",
                    buttons: {
                        confirm: {
                            label: "Yes",
                            className: "btn-primary"
                        }
                    },
                    callback: function(result){
                        if(result){
                            $.easyAjax({
                                url: departmentUrl,
                                type: 'POST',
                                data: {
                                    '_method': 'delete' , '_token': '{{ csrf_token() }}'
                                },
                                success: function(){
                                    location.reload();
                                }
                            });
                        }
                        else {
                            console.log('cancel');
                        }
                    }
                })

            })
        };
        var deviceData = function () {
            $(".device-delete").click(function () {
                var deviceUrl = $(this).data('device-url');
                bootbox.confirm({
                    message: "Do you want to delete this device?",
                    buttons: {
                        confirm: {
                            label: "Yes",
                            className: "btn-primary"
                        }
                    },
                    callback: function(result){
                        if(result){
                            $.easyAjax({
                                url: deviceUrl,
                                type: 'POST',
                                data: {
                                    '_method': 'delete' , '_token': '{{ csrf_token() }}'
                                },
                                success: function(){
                                    location.reload();
                                }
                            });
                        }
                        else {
                            console.log('cancel');
                        }
                    }
                })

            })
        };
         var deviceAttendanceLogData = function () {
            $(".clear-device-log").click(function () {
                var deviceUrl = $(this).data('device-url');
                bootbox.confirm({
                    message: "Do you want to delete this device attendance log?",
                    buttons: {
                        confirm: {
                            label: "Yes",
                            className: "btn-primary"
                        }
                    },
                    callback: function(result){
                        if(result){
                            $.easyAjax({
                                url: deviceUrl,
                                type: 'POST',
                                data: {
                                    '_method': 'delete' , '_token': '{{ csrf_token() }}'
                                },
                                success: function(){
                                    location.reload();
                                }
                            });
                        }
                        else {
                            console.log('cancel');
                        }
                    }
                })

            })
        };
        return {
            init: function () {
                branchData()
                departmentData()
                deviceData()
                deviceAttendanceLogData()
            }
        }
    }();
    jQuery(document).ready(function () {
        UIBootbox.init()
    });
</script>
@stop
