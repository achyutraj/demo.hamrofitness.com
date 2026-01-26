@extends('layouts.gym-merchant.gymbasic')

@section('CSS')
    {!! HTML::style('admin/global/plugins/datatables/datatables.min.css') !!}
    {!! HTML::style('admin/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css') !!}
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
        <!-- END PAGE BREADCRUMBS -->
        <!-- BEGIN PAGE CONTENT INNER -->
        <div class="page-content-inner">
            <div class="row">
                <div class="col-md-12">
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
                            <table class="table table-striped table-bordered table-hover order-column" style="width: 100%"
                                   id="gym_clients">
                                <thead>
                                <tr>
                                    <th class="max-desktop"> UserPin</th>
                                    <th class="max-desktop"> Name</th>
                                    <th class="desktop"> Department</th>
                                    <th class="desktop"> Shift</th>
                                    <th class="desktop"> Device</th>
                                    <th class="desktop"> Door Access</th>
                                    <th class="desktop"> Actions</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <!-- END EXAMPLE TABLE PORTLET-->
                </div>
            </div>
        </div>
        <!-- END PAGE CONTENT INNER -->
    </div>
@stop

@section('footer')
    {!! HTML::script('admin/global/scripts/datatable.js') !!}
    {!! HTML::script('admin/pages/scripts/table-datatables-managed.js') !!}
    {!! HTML::script('admin/global/plugins/datatables/datatables.min.js') !!}
    {!! HTML::script('admin/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js') !!}
    {!! HTML::script('admin/global/plugins/ladda/spin.min.js') !!}
    {!! HTML::script('admin/global/plugins/ladda/ladda.min.js') !!}
    {!! HTML::script('admin/pages/scripts/ui-buttons.min.js') !!}
    {!! HTML::script('admin/global/plugins/bootbox/bootbox.min.js') !!}

    <script>

        var clientTable = $('#gym_clients');

        var table = clientTable.dataTable({
            processing: true,
            serverSide: true,
            ajax: "{{route('device.employee_biometrics.ajax_create')}}",
            columns: [
                {data: 'customer_id', name: 'customer_id'},
                {data: 'first_name', name: 'first_name'},
                {data: 'department', name: 'department'},
                {data: 'shift', name: 'shift'},
                {data: 'device', name: 'device'},
                {data: 'door_access', name: 'door_access'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });

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
       var deviceData = function () {
            $('#gym_clients').on('click', '.remove-user', function () {
                var deviceUrl = $(this).data('url');
                bootbox.confirm({
                    message: "Do you want to delete this employee from device?",
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
                bootbox.confirm({
                    message: "Do you want to denied this employee from device?",
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
                deniedUserData()
            }
        }
    }();
    jQuery(document).ready(function () {
        UIBootbox.init()
    });
    </script>
@stop
