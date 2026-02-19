@extends('layouts.gym-merchant.gymbasic')

@section('CSS')
    <link rel="stylesheet" href="{{ asset("admin/global/plugins/datatables/datatables.min.css") }}">
    <link rel="stylesheet" href="{{ asset("admin/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css") }}">
    <style>
        /* Child row toggle cell */
        td.details-control {
            text-align: center;
            cursor: pointer;
            width: 24px;
        }
        td.details-control i {
            font-size: 16px;
            color: #666;
        }
        tr.shown td.details-control i {
            color: #e7505a;
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
                <span>Due Payments</span>
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
                                <i class=" fa {{ $gymSettings->currency->symbol }} font-red"></i>
                                <span class="caption-subject font-red bold uppercase"> Due Payments</span>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <table class="table table-striped table-bordered table-hover table-100" style="width: 100%" id="due-payments">
                                <thead>
                                <tr>
                                    <th class="desktop" style="width:24px;"></th>
                                    <th class="desktop"> Name</th>
                                    <th class="desktop"> Subscription</th>
                                    <th class="desktop"> Paid Amount</th>
                                    <th class="desktop"> Remain Amount</th>
                                    <th class="desktop"> Due Date</th>
                                    <th class="desktop"> Actions</th>
                                    <th class="desktop" style="display:none;"> Remarks</th>
                                    <th class="desktop" style="display:none;"> Amt With Discount</th>
                                    <th class="desktop" style="display:none;"> Discount</th>
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

    <script src="{{ asset("admin/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js") }}"></script>
    <link rel="stylesheet" href="{{ asset("admin/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css") }}">
    <script src="{{ asset("admin/global/scripts/datatable.js") }}"></script>
    <script src="{{ asset("admin/pages/scripts/table-datatables-managed.js") }}"></script>
    <script src="{{ asset("admin/global/plugins/datatables/datatables.min.js") }}"></script>
    <script src="{{ asset("admin/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js") }}"></script>
    <script src="{{ asset("admin/global/plugins/bootstrap-maxlength/bootstrap-maxlength.js") }}"></script>
    <script src="{{ asset("admin/pages/scripts/components-bootstrap-maxlength.min.js") }}"></script>

    <script>

        var clientTable = $('#due-payments');

        var table = clientTable.dataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('gym-admin.client-purchase.ajax-dues') }}",
            columns: [
                {className: 'details-control', orderable: false, data: null, defaultContent: '<i class="fa fa-plus-square-o"></i>', searchable: false},
                {data: 'full_name', name: 'gym_clients.first_name'},
                {data: 'membership', name: 'gym_memberships.title'},
                {data: 'paid', name: 'gym_client_purchases.paid_amount'},
                {data: 'remaining_amount', name: 'gym_client_purchases.amount_to_be_paid'},
                {data: 'due_date', name: 'gym_client_purchases.next_payment_date'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
                {data: 'remarks', name: 'gym_client_purchases.remarks', visible: false},
                {data: 'purchase_amount', name: 'gym_client_purchases.purchase_amount', visible: false},
                {data: 'discount', name: 'gym_client_purchases.discount', visible: false},
            ],
            order: [[5, 'desc']],
            scrollX: false,
            lengthMenu: [
                [25, 50, 75 , 100, -1],
                ['25', '50','75' ,'100', 'All']
            ],
            pageLength: 25,
        });

        function format(d) {
            // d is the full row data from the server
            var name = d.full_name || '-';
            var membership = d.membership || '-';
            var purchaseAmt = d.purchase_amount || '-';
            var discount = d.discount || '-';
            var paid = d.paid || '-';
            var remaining = d.remaining_amount || '-';
            var dueDate = d.due_date || '-';
            var remarks = d.remarks || '-';

            return '<div class="row">'
                + '<div class="col-md-12">'
                + '<table class="table table-condensed">'
                + '<tr><td><strong>Name</strong></td><td>' + name + '</td></tr>'
                + '<tr><td><strong>Subscription</strong></td><td>' + membership + '</td></tr>'
                + '<tr><td><strong>Amt With Discount</strong></td><td>' + purchaseAmt + '</td></tr>'
                + '<tr><td><strong>Discount</strong></td><td>' + discount + '</td></tr>'
                + '<tr><td><strong>Paid Amount</strong></td><td>' + paid + '</td></tr>'
                + '<tr><td><strong>Remain Amount</strong></td><td>' + remaining + '</td></tr>'
                + '<tr><td><strong>Due Date</strong></td><td>' + dueDate + '</td></tr>'
                + '<tr><td><strong>Remarks</strong></td><td>' + remarks + '</td></tr>'
                + '</table>'
                + '</div>'
                + '</div>';
        }

        $('#due-payments tbody').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = table.api().row(tr);
            var icon = $(this).find('i');

            if (row.child.isShown()) {
                row.child.hide();
                tr.removeClass('shown');
                icon.removeClass('fa-minus-square-o').addClass('fa-plus-square-o');
            } else {
                row.child(format(row.data())).show();
                tr.addClass('shown');
                icon.removeClass('fa-plus-square-o').addClass('fa-minus-square-o');
            }
        });

        $('#due-payments').on('click', '.show-reminder', function () {
            var id = $(this).data('id');
            var show_url = "{{route('gym-admin.client-purchase.show-model',['#id'])}}";
            var url = show_url.replace('#id', id);
            $('#modelHeading').html('Add Reminder');
            $.ajaxModal("#reminderModal", url);
        });

        $('#due-payments').on('click', '.add-payment', function () {
            var id = $(this).data('id');
            var show_url = "{{route('gym-admin.membership-payments.add-payment-modal',['#id'])}}";
            var url = show_url.replace('#id', id);
            $('#modelHeading').html('Add Payment');
            $.ajaxModal("#reminderModal", url);
        });

    </script>

@stop
