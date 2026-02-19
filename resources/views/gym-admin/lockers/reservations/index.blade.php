@extends('layouts.gym-merchant.gymbasic')

@section('CSS')
    <link rel="stylesheet" href="{{ asset("admin/global/plugins/ladda/ladda-themeless.min.css") }}">
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
                <span>Locker Reservations</span>
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
                                <span class="caption-subject font-red bold uppercase"> Reservations</span>
                            </div>
                            <div class="pull-right">
                                <div class="btn-group">
                                    <a class="btn btn-warning add-pending-btn-gap" href="{{ route('gym-admin.reservations.pending') }}">Pending
                                        Reservations ({{$pendingCount}})</a>
                                    <a class="btn btn-danger add-pending-btn-gap" href="{{ route('gym-admin.reservations.deleted') }}">Deleted
                                        Reservations ({{$deletedCount}})</a>
                                    @if($user->can("add_reservations"))
                                    <a id="addTarget" href="{{route('gym-admin.reservations.create')}}" class="btn sbold dark"> Add New
                                        <i class="fa fa-plus"></i>
                                    </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <table class="table table-bordered table-hover order-column table-100" id="reserved_table">
                                <thead>
                                <tr>
                                    <th class="desktop"> Client</th>
                                    <th class="desktop"> Locker No.</th>
                                    <th class="desktop"> Amount</th>
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
        var clientTable = $('#reserved_table');

        var table = clientTable.dataTable({
            processing: true,
            serverSide: true,
            ajax: "{{route('gym-admin.reservations.ajax-create')}}",
            columns: [
                {data: 'client_id', name: 'client_id'},
                {data: 'locker_id', name: 'locker_id'},
                {data: 'amount_to_be_paid', name: 'amount_to_be_paid'},
                {data: 'paid_amount', name: 'paid_amount'},
                {data: 'start_date', name: 'start_date'},
                {data: 'next_payment_date', name: 'next_payment_date'},
                {data: 'end_date', name: 'end_date'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ],
            lengthMenu: [
                [25, 50, 75 , 100, -1],
                ['25', '50','75' ,'100', 'All']
            ],
            pageLength: 25,
        });

        $('#reserved_table').on('click', '.remove-reservation', function () {
            var id = $(this).data('id');
            bootbox.confirm({
                message: "Do you want to delete this reservation?",
                buttons: {
                    confirm: {
                        label: "Yes",
                        className: "btn-primary"
                    }
                },
                callback: function (result) {
                    if (result) {
                        var url = "{{route('gym-admin.reservations.destroy',':id')}}";
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

        $('#reserved_table').on('click', '.add-payment', function () {
            var id = $(this).data('id');
            var show_url = "{{route('gym-admin.reservation-payments.add-payment-model',['#id'])}}";
            var url = show_url.replace('#id', id);
            $('#modelHeading').html('Add Payment');
            $.ajaxModal("#reminderModal", url);
        });

        //send locker reminder
        $('#reserved_table').on('click', '.show-locker-reminder', function () {
            var id = $(this).data('id');
            var show_url = "{{route('gym-admin.reservations.show-locker-reminder-modal',['#id'])}}";
            var url = show_url.replace('#id', id);
            $('#modelHeading').html('Select');
            $.ajaxModal("#reminderModal", url);
        });

        //renew reservations
        $('#reserved_table').on('click', '.renew-reservation', function () {
            var id = $(this).data('id');
            var show_url = "{{route('gym-admin.reservations.renew-reservation-modal',['#id'])}}";
            var url = show_url.replace('#id', id);
            $('#modelHeading').html('Renew Reservation');
            $.ajaxModal("#reminderModal", url);
        });
    </script>

@stop
