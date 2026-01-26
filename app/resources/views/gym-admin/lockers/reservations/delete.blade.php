@extends('layouts.gym-merchant.gymbasic')

@section('CSS')
    {!! HTML::style('admin/global/plugins/datatables/datatables.min.css') !!}
    {!! HTML::style('admin/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css') !!}
    {!! HTML::style('admin/global/plugins/datatables/Responsive-2.0.2/css/responsive.bootstrap.css') !!}
    {!! HTML::style('admin/global/plugins/datatables/Responsive-2.0.2/css/responsive.dataTables.css') !!}

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
                <a href="{{route('gym-admin.lockers.index')}}">Lockers</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <a href="{{route('gym-admin.reservations.index')}}">Reservations</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>Deleted Reservations</span>
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
                                <span class="caption-subject font-red bold uppercase"> Deleted Reservations</span>
                            </div>
                            <div class="pull-right">
                                <div class="btn-group">
                                    <a class="btn btn-danger add-pending-btn-gap" href="{{ route('gym-admin.reservations.pending') }}">Pending
                                        Reservation ({{ $pendingCount }})</a>
                                    <a id="addTarget" href="{{route('gym-admin.reservations.index')}}" class="btn sbold dark"> Reservation
                                        <i class="fa fa-list"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <table class="table table-bordered table-hover order-column table-100" id="deleted_purchase_table">
                                <thead>
                                <tr>
                                    <th class="desktop"> Client</th>
                                    <th class="desktop"> Locker</th>
                                    <th class="desktop"> Amount</th>
                                    <th class="desktop"> Remain Amt</th>
                                    <th class="desktop"> Start Date</th>
                                    <th class="desktop"> Expires On</th>
                                    <th class="desktop"> Deleted At</th>
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

    {!! HTML::script('admin/global/plugins/datatables/DataTables-1.10.11/media/js/jquery.dataTables.js') !!}
    {!! HTML::script('admin/global/plugins/datatables/DataTables-1.10.11/media/js/dataTables.bootstrap.js') !!}
    {!! HTML::script('admin/global/plugins/datatables/Responsive-2.0.2/js/dataTables.responsive.js') !!}
    {!! HTML::script('admin/global/plugins/datatables/Responsive-2.0.2/js/responsive.bootstrap.js') !!}

    <script>

        var clientTable = $('#deleted_purchase_table');

        var table = clientTable.dataTable({
            processing: true,
            serverSide: true,
            ajax: "{{route('gym-admin.reservations.ajax-deleted-data')}}",
            columns: [
                {data: 'client_id', name: 'client_id'},
                {data: 'locker_id', name: 'locker_id'},
                {data: 'amount_to_be_paid', name: 'amount_to_be_paid'},
                {data: 'remaining', name: 'remaining'},
                {data: 'start_date', name: 'start_date'},
                {data: 'end_date', name: 'end_date'},
                {data: 'deleted_at', name: 'deleted_at'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ],
            lengthMenu: [
                [25, 50, 75 , 100, -1],
                ['25', '50','75' ,'100', 'All']
            ],
            pageLength: 25,
        });

        $('#deleted_purchase_table').on('click', '.restore-reservation', function () {
            var id = $(this).data('id');
            bootbox.confirm({
                message: "Do you want to restore this reservation?",
                buttons: {
                    confirm: {
                        label: "Yes",
                        className: "btn-primary"
                    }
                },
                callback: function (result) {
                    if (result) {
                        var url = "{{route('gym-admin.reservations.restore',':id')}}";
                        url = url.replace(':id', id);
                        $.easyAjax({
                            url: url,
                            type: "GET",
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

        $('#deleted_purchase_table').on('click', '.remove-reservation', function () {
            var id = $(this).data('id');
            bootbox.confirm({
                message: "Do you want to remove this reservation permanently?",
                buttons: {
                    confirm: {
                        label: "Yes",
                        className: "btn-primary"
                    }
                },
                callback: function (result) {
                    if (result) {
                        var url = "{{route('gym-admin.reservations.permanent_delete',':id')}}";
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

    </script>

@stop
