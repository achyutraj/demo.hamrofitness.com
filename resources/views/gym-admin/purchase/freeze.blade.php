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
                <a href="{{route('gym-admin.client-purchase.index')}}">Subscription</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>Freeze Subscriptions</span>
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
                                <i class="fa {{ $gymSettings->currency->symbol }} font-red"></i>
                                <span class="caption-subject font-red bold uppercase">Freeze Subscriptions</span>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <table class="table table-bordered table-hover order-column table-100" id="purchase_table">
                                <thead>
                                <tr>
                                    <th class="desktop"> Client</th>
                                    <th class="desktop"> Subscription</th>
                                    <th class="desktop"> Freeze Days</th>
                                    <th class="desktop"> Start Date</th>
                                    <th class="desktop"> End Date</th>
                                    <th class="desktop"> Added By</th>
                                    <th class="desktop"> Reason</th>
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

    <script src="{{ asset("admin/global/scripts/datatable.js") }}"></script>
    <script src="{{ asset("admin/pages/scripts/table-datatables-managed.js") }}"></script>
    <script src="{{ asset("admin/global/plugins/datatables/datatables.min.js") }}"></script>
    <script src="{{ asset("admin/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js") }}"></script>
    <script src="{{ asset("admin/global/plugins/ladda/spin.min.js") }}"></script>
    <script src="{{ asset("admin/global/plugins/ladda/ladda.min.js") }}"></script>
    <script src="{{ asset("admin/pages/scripts/ui-buttons.min.js") }}"></script>

    <script>
        var clientTable = $('#purchase_table');

        var table = clientTable.dataTable({
            processing: true,
            serverSide: true,
            ajax: "{{route('gym-admin.client-purchase.ajax-freeze-subscription')}}",
            columns: [
                {data: 'client_id', name: 'client_id'},
                {data: 'purchase_id', name: 'purchase_id'},
                {data: 'days', name: 'days'},
                {data: 'start_date', name: 'start_date'},
                {data: 'end_date', name: 'end_date'},
                {data: 'added_by', name: 'added_by'},
                {data: 'reasons', name: 'reasons'},
                {data: 'actions', name: 'actions',orderable:false,searchable:false},
            ],
            lengthMenu: [
                [25, 50, 75 , 100, -1],
                ['25', '50','75' ,'100', 'All']
            ],
            pageLength: 25,
        });

        $('#purchase_table').on('click', '.unfreeze', function () {
            var id = $(this).data('uuid');
            bootbox.confirm({
                message: "Do you want to unfreeze this subscription?",
                buttons: {
                    confirm: {
                        label: "Yes",
                        className: "btn-primary"
                    }
                },
                callback: function (result) {
                    if (result) {
                        var url = "{{route('gym-admin.client-purchase.freeze-subscription-update',':id')}}";
                        url = url.replace(':id', id);
                        $.easyAjax({
                            url: url,
                            type: "POST",
                            data: {id: id, _token: '{{ csrf_token() }}'},
                            success: function () {
                                location.reload();
                            }
                        });
                    }
                    else {
                        console.log('cancel');
                    }
                }
            })
        });

    </script>

@stop
