@extends('layouts.gym-merchant.gymbasic')

@section('CSS')
    <link rel="stylesheet" href="{{ asset("admin/global/plugins/ladda/ladda-themeless.min.css") }}">
    <link rel="stylesheet" href="{{ asset("admin/global/plugins/datatables/datatables.min.css") }}">
    <link rel="stylesheet" href="{{ asset("admin/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css") }}">
    <style>
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
                <a href="{{route('gym-admin.dashboard.index')}}">Home</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>Client Subscriptions</span>
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
                                <span class="caption-subject font-red bold uppercase">Renew Subscriptions</span>
                            </div>
                            <div class="pull-right">
                                <div class="btn-group">
                                    <a href="{{route('gym-admin.client-purchase.index')}}" class="btn btn-success">
                                        All Subscriptions
                                    </a>
                                    <a id="addTarget" href="{{route('gym-admin.client-purchase.create')}}" class="btn sbold dark"> Add New
                                        <i class="fa fa-plus"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <table class="table table-striped table-bordered table-hover row-border order-column nowrap" style="width: 100%"
                                   id="purchase_table">
                                <thead>
                                <tr>
                                    <th class="desktop"> Client</th>
                                    <th class="desktop"> Subscription</th>
                                    <th class="desktop"> Purchase Amt</th>
                                    <th class="desktop"> Remain Amt</th>
                                    <th class="desktop"> Start Date</th>
                                    <th class="desktop"> Next payment</th>
                                    <th class="desktop"> Expires On</th>
                                    <th class="desktop"> Action</th>
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

    {{--Model--}}

    <div class="modal fade bs-modal-md in" id="reminderModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" id="modal-data-application">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <span class="caption-subject font-red-sunglo bold uppercase" id="modelHeading"></span>
                </div>
                <div class="modal-body">
                    Loading...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn default" data-dismiss="modal">Close</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    {{--Model End--}}
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
            ajax: "{{route('gym-admin.client-purchase.ajax-create',['type'=>'renew'])}}",
            columns: [
                {data: 'gym_clients.first_name', name: 'gym_clients.first_name'},
                {data: 'membership', name: 'membership'},
                {data: 'amount_to_be_paid', name: 'amount_to_be_paid'},
                {data: 'paid_amount', name: 'paid_amount'},
                {data: 'start_date', name: 'start_date'},
                {data: 'next_payment_date', name: 'next_payment_date'},
                {data: 'expires_on', name: 'expires_on'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ],
            order: [[6, 'desc']],
            scrollX: true,
            lengthMenu: [
                [25, 50, 75 , 100, -1],
                ['25', '50','75' ,'100', 'All']
            ],
            pageLength: 25,
        });

        $('#purchase_table').on('click', '.remove-purchase', function () {
            var id = $(this).data('id');
            bootbox.confirm({
                message: "Do you want to delete this purchase?",
                buttons: {
                    confirm: {
                        label: "Yes",
                        className: "btn-primary"
                    }
                },
                callback: function (result) {
                    if (result) {

                        var url = "{{route('gym-admin.client-purchase.destroy',':id')}}";
                        url = url.replace(':id', id);

                        $.easyAjax({
                            url: url,
                            type: "DELETE",
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

        $('#purchase_table').on('click', '.add-payment', function () {
            var id = $(this).data('id');
            var show_url = "{{route('gym-admin.membership-payments.add-payment-modal',['#id'])}}";
            var url = show_url.replace('#id', id);
            $('#modelHeading').html('Add Payment');
            $.ajaxModal("#reminderModal", url);
        });

        $('#purchase_table').on('click', '.renew-subscription', function () {
            var id = $(this).data('id');
            var show_url = "{{route('gym-admin.client-purchase.renew-subscription-modal',['#id'])}}";
            var url = show_url.replace('#id', id);
            $('#modelHeading').html('Renew Subscription');
            $.ajaxModal("#reminderModal", url);
        });

        //send subscription reminder
        $('#purchase_table').on('click', '.show-subscription-reminder', function () {
            var id = $(this).data('id');
            var show_url = "{{route('gym-admin.client-purchase.show-subscription-reminder-modal',['#id'])}}";
            var url = show_url.replace('#id', id);
            $('#modelHeading').html('Send Renew Reminder');
            $.ajaxModal("#reminderModal", url);
        });

        $('#purchase_table').on('click', '.extend-subscription', function () {
            var id = $(this).data('id');
            var show_url = "{{route('gym-admin.client-purchase.extend-subscription-modal',['#id'])}}";
            var url = show_url.replace('#id', id);
            $('#modelHeading').html('Extend Subscription');
            $.ajaxModal("#reminderModal", url);
        });

        $('#purchase_table').on('click', '.freeze-subscription', function () {
            var id = $(this).data('id');
            var show_url = "{{route('gym-admin.client-purchase.freeze-subscription-modal',['#id'])}}";
            var url = show_url.replace('#id', id);
            $('#modelHeading').html('Freeze Subscription');
            $.ajaxModal("#reminderModal", url);
        });

    </script>

@stop
