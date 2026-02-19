@extends('layouts.gym-merchant.gymbasic')

@section('CSS')
    <link rel="stylesheet" href="{{ asset("admin/global/plugins/ladda/ladda-themeless.min.css") }}">
    <link rel="stylesheet" href="{{ asset("admin/global/plugins/bootstrap-select/css/bootstrap-select.min.css") }}">
    <link rel="stylesheet" href="{{ asset("admin/global/plugins/datatables/datatables.min.css") }}">
    <link rel="stylesheet" href="{{ asset("admin/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css") }}">
    <style>
        .table-scrollable .dataTable td .btn-group, .table-scrollable .dataTable th .btn-group {
            position: relative;
            margin-top: -2px;
        }
    </style>
@stop

@section('content')
    <div class="container-fluid">
        <!-- BEGIN PAGE BREADCRUMBS -->
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <a href="{{route('gym-admin.dashboard.index')}}">Home</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>Employee Biometric</span>
            </li>
        </ul>
        <div class="page-content-inner">
            <div class="row">
                @if(session()->has('message'))
                    <div class="alert alert-message alert-success">
                        {{ session()->get('message') }}
                    </div>
                @endif
                @if(session()->has('danger'))
                        <div class="alert alert-danger alert-success">
                            {{ session()->get('danger') }}
                        </div>
                @endif
                @if (session('errors'))
                    <div class="alert alert-danger">
                        <ul>
                            @foreach (session('errors') as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="col-md-12">
                    <!-- BEGIN EXAMPLE TABLE PORTLET-->
                    <div class="portlet light ">
                        <div class="portlet-title">
                            <div class="caption font-dark">
                                <i class="icon-users font-red"></i>
                                <span class="caption-subject font-red bold uppercase"> Employee Biometric</span>
                            </div>
                            <div class="pull-right">
                                <div class="btn-group">

                                </div>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <table class="table table-striped table-bordered table-100"
                                   id="gym_clients">
                                <thead>
                                <tr>
                                    <th class="desktop">Employee</th>

                                    <th class="desktop">Device Details</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($clients as $client)

                                    <tr>
                                        <td style="text-align: left" width="20%">
                                            Name:<strong> {{$client->fullName}}</strong>
                                            <br>UserPin: <strong>{{$client->customer_id}} </strong>
                                        </td>

                                        <td>
                                            <table class="table table-bordered order-column table-100 nowrap" style="width: 100%"
                                                   id="gym_clients" >
                                                <thead>
                                                <tr>
                                                    <th class="desktop"> Department</th>
                                                    <th class="desktop"> Shift</th>
                                                    <th class="desktop"> Device</th>
                                                    <th class="desktop"> Door Access</th>
                                                    <th class="desktop"> Actions</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($client->devices as $device)
                                                        <tr class="">
                                                            <td>
                                                                @foreach($device->departments as $depart)
                                                                    <p>{{$depart->name}} , </p>
                                                                @endforeach
                                                            </td>
                                                            <td>
                                                                @foreach($client->shifts as $shift)
                                                                    <p>{{$shift->name}} , </p>
                                                                @endforeach
                                                            </td>
                                                            <td>{{ $device->name}}</td>
                                                            <td>
                                                                @if($device->pivot->is_device_deleted == 1 || $device->pivot->is_denied == 1)
                                                                    <span class="label label-danger">Denied</span>
                                                                @else
                                                                    <span class="label label-success">Allowed</span>
                                                                @endif
                                                                {{ $client->clientDeviceSync = null ? 'Not Sync' : '' }}
                                                            </td>
                                                            <td>
                                                                <div class="btn-group">
                                                                    <button class="btn blue btn-xs dropdown-toggle" type="button" data-toggle="dropdown"><i class="fa fa-gears"></i> <span class="hidden-xs hidden-medium">Actions</span>
                                                                        <i class="fa fa-angle-down"></i>
                                                                    </button>
                                                                    <ul class="dropdown-menu pull-right" role="menu">
                                                                        <li>
                                                                            <a class="remove-user" data-device_name = "{{$device->name}}"
                                                                            data-url="{{ route('device.biometrics.clientRemoveFromDevice',['clientId'=>$client->customer_id,'deviceId'=>$device->id]) }}"> <i class="fa fa-trash"></i>Remove</a>
                                                                        </li>
                                                                    @if($client->clientDeviceSync = null)
                                                                    <li>
                                                                        <a href="{{ route('device.biometrics.syncUser',['clientId'=>$client->customer_id]) }}"> <i class="fa fa-plus"></i>Sync</a>
                                                                    </li>
                                                                    @endif
                                                                    @if($device->pivot->is_device_deleted == 1)
                                                                    <li>
                                                                        <a class="renew-user" data-device_name = "{{$device->name}}" data-client_id="{{$client->id}}" data-device_id="{{$device->id}}"
                                                                        data-url="{{ route('device.biometrics.renewUserStore') }}"> <i class="fa fa-recycle"></i>Renew</a>
                                                                    </li>
                                                                    @else
                                                                    <li>
                                                                        <a class="denied-user" data-device_name = "{{$device->name}}"
                                                                        data-url="{{ route('device.biometrics.clientRemoveFromDeviceOnly',['clientId'=>$client->customer_id,'deviceId'=>$device->id]) }}"> <i class="fa fa-stop-circle"></i>Denied</a>
                                                                    </li>
                                                                    @endif
                                                                </ul>
                                                            </div>

                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </td>

                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('footer')
    <script src="{{ asset("admin/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js") }}"></script>
    <link rel="stylesheet" href="{{ asset("admin/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css") }}">
    <script src="{{ asset("admin/global/plugins/ladda/spin.min.js") }}"></script>
    <script src="{{ asset("admin/global/plugins/ladda/ladda.min.js") }}"></script>
    <script src="{{ asset("admin/pages/scripts/ui-buttons.min.js") }}"></script>
    <script src="{{ asset("admin/global/plugins/bootstrap-select/js/bootstrap-select.min.js") }}"></script>
    <script src="{{ asset("admin/pages/scripts/components-bootstrap-select.min.js") }}"></script>
    <script src="{{ asset("admin/global/scripts/datatable.js") }}"></script>
    <script src="{{ asset("admin/pages/scripts/table-datatables-managed.js") }}"></script>
    <script src="{{ asset("admin/global/plugins/datatables/datatables.min.js") }}"></script>
    <script src="{{ asset("admin/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js") }}"></script>
    <script>
        var table = $('#gym_clients');
        table.dataTable({
            responsive: true,
        });
        $(function () {
            setTimeout(function () {
                $('.alert-message').slideUp();
            }, 3000);
        });
    </script>
    <script>
       var UIBootbox = function () {
       var deviceData = function () {
            $('#gym_clients').on('click', '.remove-user', function () {
                var deviceUrl = $(this).data('url');
                var deviceName = $(this).data('device_name');

                bootbox.confirm({
                    message: "Do you want to delete this employee from "+ deviceName + "?",
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

        var deniedUserData = function () {
            $('#gym_clients').on('click', '.denied-user', function () {
                var deviceUrl = $(this).data('url');
                var deviceName = $(this).data('device_name');
                bootbox.confirm({
                    message: "Do you want to denied this employee from "+ deviceName + "?",
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
                deviceData(),
                deniedUserData(),
            }
        }
    }();
    jQuery(document).ready(function () {
        UIBootbox.init()
    });
    </script>
@stop
