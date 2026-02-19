@extends('layouts.gym-merchant.gymbasic')

@section('CSS')
    <link rel="stylesheet" href="{{ asset("admin/global/plugins/datatables/datatables.min.css") }}">
    <link rel="stylesheet" href="{{ asset("admin/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css") }}">
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
                <span>Employees</span>
            </li>
        </ul>
        <!-- END PAGE BREADCRUMBS -->
        <!-- BEGIN PAGE CONTENT INNER -->

        <div class="page-content-inner">
            <div class="row">
                <div class="col-md-12">

                    @if (session()->has('message'))
                        <div class="alert alert-message alert-success">
                            {{ session()->get('message') }}
                        </div>
                    @endif

                    <!-- BEGIN EXAMPLE TABLE PORTLET-->
                    <div class="portlet light ">
                        <div class="portlet-title">
                            <div class="caption font-dark">
                                <i class="fa fa-users font-red"></i>
                                <span class="caption-subject font-red bold uppercase"> Employees</span>
                            </div>
                            <div class="pull-right">
                                <div class="btn-group">
                                    @if ($user->can('add_biometrics') && $common_details->has_device == 1)
                                        <a href="{{ route('device.employee_biometrics.index') }}"
                                            class="btn btn-success btn-sm">
                                            Employee Biometric
                                            <i class="fa fa-plus"></i>
                                        </a>
                                    @endif

                                    <a id="sample_editable_1_new" href="{{ route('gym-admin.users.createEmployee') }}"
                                        class="btn sbold dark btn-sm">
                                        Add New
                                        <i class="fa fa-plus"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <table class="table table-striped table-bordered table-hover table-100" id="manage-branches">
                                <thead>
                                    <tr>
                                        <th class="max-desktop"> Name</th>
                                        <th class="desktop"> Gender</th>
                                        <th class="desktop"> Email</th>
                                        <th class="desktop"> Mobile</th>
                                        <th class="desktop"> Position</th>
                                        <th class="desktop"> Sync Status</th>
                                        <th class="desktop"> Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($employees as $employ)
                                        <tr>
                                            <td>{{ $employ->fullName }}</td>
                                            <td>{{ ucfirst($employ->gender) }}</td>
                                            <td>{{ $employ->email }}</td>
                                            <td>{{ $employ->mobile }}</td>
                                            <td>{{ ucfirst($employ->position) }}</td>
                                            <td>
                                                @if ($employ->sync_status === true)
                                                    <span class="label label-success">Synced</span>
                                                    @if ($employ->last_sync)
                                                        <br><small>{{ \Carbon\Carbon::parse($employ->last_sync)->format('M d, Y H:i') }}</small>
                                                    @endif
                                                @elseif($employ->sync_status === false)
                                                    <span class="label label-danger">Failed</span>
                                                    @if ($employ->last_sync)
                                                        <br><small>{{ \Carbon\Carbon::parse($employ->last_sync)->format('M d, Y H:i') }}</small>
                                                    @endif
                                                @else
                                                    <span class="label label-warning">Not Synced</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <button class="btn blue btn-xs dropdown-toggle" type="button"
                                                        data-toggle="dropdown">
                                                        <i class="fa fa-gears"></i> <span class="hidden-xs">Action</span>
                                                        <i class="fa fa-angle-down"></i>
                                                    </button>
                                                    <ul class="dropdown-menu pull-right" role="menu">
                                                        <li>
                                                            <a href="{{ route('gym-admin.users.editEmployee',$employ->id) }}">
                                                                <i class="fa fa-edit"></i> Edit</a>
                                                        </li>
                                                        @if ($employ->sync_status !== true && $common_details->has_device == 1)
                                                            <li>
                                                                <a data-url="{{ route('gym-admin.users.resyncEmployee', $employ->id) }}"
                                                                    class="resync-employee">
                                                                    <i class="fa fa-refresh"></i> Resync</a>
                                                            </li>
                                                        @endif
                                                        <li>
                                                            <a data-url="{{ route('gym-admin.users.deleteEmployee', $employ->id) }}"
                                                                class="remove-user">
                                                                <i class="fa fa-trash"></i> Delete</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
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
    <script src="{{ asset("admin/global/scripts/datatable.js") }}"></script>
    <script src="{{ asset("admin/pages/scripts/table-datatables-managed.js") }}"></script>
    <script src="{{ asset("admin/global/plugins/datatables/datatables.min.js") }}"></script>
    <script src="{{ asset("admin/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js") }}"></script>
    <script src="{{ asset("admin/global/plugins/bootbox/bootbox.min.js") }}"></script>

    <script>
        var table = $('#manage-branches');
        table.dataTable({
            responsive: true,
            pageLength: 25,
            lengthMenu: [
                [25, 50, 75 , 100, -1],
                ['25', '50','75' ,'100', 'All']
            ],
        });
        $(function() {
            setTimeout(function() {
                $('.alert-message').slideUp();
            }, 3000);
        });
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var UIBootbox = function() {
            var branchData = function() {
                $('.remove-user').on('click', function() {
                    var url = $(this).data('url');
                    bootbox.confirm({
                        message: "Do you want to delete this employee?",
                        buttons: {
                            confirm: {
                                label: "Yes",
                                className: "btn-primary"
                            }
                        },
                        callback: function(result) {
                            if (result) {
                                $.easyAjax({
                                    url: url,
                                    type: 'POST',
                                    data: {
                                        '_method': 'delete',
                                        '_token': '{{ csrf_token() }}'
                                    },
                                    success: function() {
                                        location.reload();
                                    }
                                });
                            } else {
                                console.log('cancel');
                            }
                        }
                    })
                });

                $('.resync-employee').on('click', function() {
                    var url = $(this).data('url');
                    bootbox.confirm({
                        message: "Do you want to resync this employee with biometric devices?",
                        buttons: {
                            confirm: {
                                label: "Yes",
                                className: "btn-primary"
                            }
                        },
                        callback: function(result) {
                            if (result) {
                                $.easyAjax({
                                    url: url,
                                    type: 'POST',
                                    data: {
                                        '_token': '{{ csrf_token() }}'
                                    },
                                    success: function(response) {
                                        if (response.status === 'success') {
                                            location.reload();
                                        }
                                    }
                                });
                            } else {
                                console.log('cancel');
                            }
                        }
                    })
                });
            };
            return {
                init: function() {
                    branchData()
                }
            }
        }();
        jQuery(document).ready(function() {
            UIBootbox.init()
        });
    </script>
@stop
