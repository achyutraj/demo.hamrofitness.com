@extends('layouts.gym-merchant.gymbasic')

@section('CSS')
    <link rel="stylesheet" href="{{ asset("admin/global/plugins/ladda/ladda-themeless.min.css") }}">
    <link rel="stylesheet" href="{{ asset("admin/global/plugins/datatables/datatables.min.css") }}">
    <link rel="stylesheet" href="{{ asset("admin/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css") }}">
    <style>
        .table-scrollable .dataTable td .btn-group, .table-scrollable .dataTable th .btn-group {
            position: relative;
            margin-top: -2px;
        }
        .white{
            color: #ffffff;
        }
    </style>
@stop

@section('content')
    <div class="container-fluid"  >
        <!-- BEGIN PAGE BREADCRUMBS -->
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <a href="{{ route('gym-admin.dashboard.index') }}">Home</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>Body Measurement</span>
            </li>
        </ul>
        <!-- END PAGE BREADCRUMBS -->
        <!-- BEGIN PAGE CONTENT INNER -->
        <div class="page-content-inner">
            <div class="row">
                <div class="col-md-12">

                    <div class="portlet light portlet-fit">
                        <div class="portlet-title col-xs-12">
                            <div class="caption col-sm-10 col-xs-12">
                                <i class="icon-bar-chart font-red"></i><span class="caption-subject font-red bold uppercase">Body Measurement</span>
                            </div>

                            <div class="actions col-sm-2 col-xs-12">
                                <a href="{{ route('gym-admin.measurements.create') }}" class="btn dark"> Add <span class="hidden-xs"></span>
                                    <i class="fa fa-plus"></i>
                                </a>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <table style="width: 100%" class="table table-striped table-bordered table-hover order-column" id="measurement_table">
                                <thead>
                                <tr>
                                    <th class="max-desktop"> Client </th>
                                    <th class="desktop"> Added By </th>
                                    <th class="desktop"> Date </th>
                                    <th class="desktop"> Total Measurement </th>
                                    <th class="desktop"> Action </th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
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

    <script>
        var lockerTable = $('#measurement_table');

        var table = lockerTable.dataTable({
            processing: true,
            serverSide: true,
            ajax: "{{route('gym-admin.measurements.ajax-create')}}",
            columns: [
                {data: 'first_name', name: 'first_name'},
                {data: 'added_by', name: 'added_by'},
                {data: 'entry_date', name: 'entry_date'},
                {data: 'count', name: 'count'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });


    </script>
@stop

