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
                <a href="{{route('gym-admin.dashboard.index')}}">Home</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>Payments</span>
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
                                <span class="caption-subject font-red bold uppercase"> Payments</span>
                            </div>
                            <div class="pull-right">
                                <div class="btn-group">
                                    <a href="{{route('gym-admin.membership-payment.create')}}" id="add_payment" class="action btn dark"> add <span
                                            class="hidden-xs"></span>
                                        <i class="fa fa-plus"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <ul class="nav nav-tabs">
                                <li class="active"><a data-toggle="tab" href="#all">All Payment</a></li>
                                <li><a data-toggle="tab" href="#deleted">Deleted Payment</a></li>
                            </ul>

                            <div class="tab-content">
                                <div id="all" class="tab-pane fade in active">
                                    <table class="table table-striped table-bordered table-hover table-100" id="mem-payments">
                                        <thead>
                                        <tr>
                                            <th class="max-desktop"> Name</th>
                                            <th class="desktop"> Membership</th>
                                            <th class="desktop"> Amount</th>
                                            <th class="desktop"> Source</th>
                                            <th class="desktop"> Payment Date</th>
                                            <th class="desktop"> Payment ID</th>
                                            <th class="desktop"> Actions</th>
                                        </tr>
                                        </thead>
                                    </table>
                                </div>
                                <div id="deleted" class="tab-pane fade">
                                    <table class="table table-striped table-bordered table-hover table-100"
                                           id="mem-payments_deleted">
                                        <thead>
                                        <tr>
                                            <th class="max-desktop"> Name</th>
                                            <th class="desktop"> Membership</th>
                                            <th class="desktop"> Amount</th>
                                            <th class="desktop"> Source</th>
                                            <th class="desktop"> Payment Date</th>
                                            <th class="desktop"> Payment ID</th>
                                            <th class="desktop"> Deleted On</th>
                                        </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END EXAMPLE TABLE PORTLET-->
                </div>
            </div>
        </div>
        <!-- END PAGE CONTENT INNER -->
    </div>

    {{--Model--}}

    <div class="modal fade bs-modal-md in" id="gymPaymemtModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
                    <button type="button" class="btn blue">Save changes</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <div class="modal fade bs-modal-md in" id="receiptModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
    <script src="{{ asset("admin/global/plugins/bootbox/bootbox.min.js") }}"></script>
    <script>
        jQuery(document).ready(function () {
            load_dataTable();
            loaddeleted_dataTable();
        });

        function loaddeleted_dataTable() {
            var memberPaymentDeleteTable = $('#mem-payments_deleted');

            var table = memberPaymentDeleteTable.dataTable({
                processing: true,
                serverSide: true,
                destroy: true,
                ordering: true,
                ajax: "{{ route('gym-admin.membership-payment.ajax-create-deleted') }}",
                columns: [
                    {data: 'gym_clients.first_name', name: 'gym_clients.first_name'},
                    {data: 'membership', name: 'membership'},
                    {data: 'payment_amount', name: 'payment_amount'},
                    {data: 'payment_source', name: 'payment_source'},
                    {data: 'payment_date', name: 'payment_date'},
                    {data: 'payment_id', name: 'payment_id'},
                    {data: 'deleted_at', name: 'deleted_at'},
                ],
                order: [[4, 'desc']],
                lengthMenu: [
                    [25, 50, 75 , 100, -1],
                    ['25', '50','75' ,'100', 'All']
                ],
                pageLength: 25,
            });
        }


        function load_dataTable() {
            var memberPaymentTable = $('#mem-payments');

            var table = memberPaymentTable.dataTable({
                processing: true,
                serverSide: true,
                destroy: true,
                ordering: true,
                ajax: "{{ route('gym-admin.membership-payment.ajax-create') }}",
                columns: [
                    {data: 'gym_clients.first_name', name: 'gym_clients.first_name'},
                    {data: 'membership', name: 'membership'},
                    {data: 'payment_amount', name: 'payment_amount'},
                    {data: 'payment_source', name: 'payment_source'},
                    {data: 'payment_date', name: 'payment_date'},
                    {data: 'payment_id', name: 'payment_id'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
                order: [[4, 'desc']],
                lengthMenu: [
                    [25, 50, 75 , 100, -1],
                    ['25', '50','75' ,'100', 'All']
                ],
                pageLength: 25,
            });
        }

    </script>
    <script>
        $('#mem-payments').on('click', '.remove-payment', function () {
            var id = $(this).data('payment-id');
            bootbox.confirm({
                message: "Do you want to delete this payment?",
                buttons: {
                    confirm: {
                        label: "Yes",
                        className: "btn-primary"
                    }
                },
                callback: function (result) {
                    if (result) {
                        var url = "{{route('gym-admin.membership-payment.destroy',':id')}}";
                        url = url.replace(':id', id);
                        $.easyAjax({
                            url: url,
                            type: "DELETE",
                            data: {id: id, _token: '{{ csrf_token() }}'},
                            success: function () {
                                load_dataTable();
                            }
                        });
                    } else {
                        console.log('cancel');
                    }
                }
            })
        });

        $('#mem-payments').on('click', '.view-receipt', function () {
            var paymentId = $(this).data('payment-id');
            var show_url = "{{route('gym-admin.membership-payment.view-receipt',['#paymentId'])}}";
            var url = show_url.replace('#paymentId', paymentId);
            $('#modelHeading').html('Receipt');
            $.ajaxModal("#receiptModal", url);
        });

        $('#mem-payments').on('click', '.email-receipt', function () {
            var paymentId = $(this).data('payment-id');
            var url_update = "{{route('gym-admin.membership-payment.email-receipt',[':id'])}}";
            var url = url_update.replace(':id', paymentId);
            $.easyAjax({
                url: url,
                type: 'GET',
                data: {paymentId: paymentId},
                success: function (response) {
                    $('#payment_for_area').html(response.data);
                }
            })
        });
    </script>
@stop
