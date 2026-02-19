@extends('layouts.customer-app.basic')

@section('title')
HamroFitness | Locker Reservation
@endsection

@section('CSS')
<link rel="stylesheet" href="{{ asset("fitsigma_customer/bower_components/datatables/jquery.dataTables.min.css") }}">
@endsection

@section('content')
    <div class="row bg-title">
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title">Locker Reservation</h4>
        </div>
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li>Main Menu</li>
                <li class="active">Locker Reservation</li>
            </ol>
        </div>
        <!-- /.col-lg-12 -->
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="white-box">
                <h3 class="box-title m-b-0"><i class="fa {{$gymSettings->currency->symbol}}"></i> Locker Reservation</h3>
                <div class="row">
                    <div class="col-md-6">
                        <a class="btn btn-sm btn-success waves-effect" href="{{ route('customer-app.reservations.create') }}"><i class="zmdi zmdi-plus zmdi-hc-fw fa-fw"></i>Add Locker Reservation</a>
                    </div>
                    <div class="col-md-6"></div>
                </div>
                <p class="text-muted m-b-30"></p>
                <div class="table-responsive">
                    <table id="subscriptionTable" class="table table-striped">
                        <thead>
                        <tr>
                            <th>Locker</th>
                            <th>Payments</th>
                            <th>Start Date</th>
                            <th>Expires On</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('JS')
<script src="{{ asset("fitsigma_customer/bower_components/datatables/jquery.dataTables.min.js") }}"></script>
<script>
    var table = $('#subscriptionTable');
    table.dataTable({
        "responsive": true,
        "serverSide": true,
        "processing": true,
        "cache": false,
        "ajax": "{{ route('customer-app.reservations.get-data') }}",
        "aoColumns": [
            {'data': 'locker_id', 'name': 'locker_id'},
            {'data': 'amount_to_be_paid', 'name': 'amount_to_be_paid'},
            {'data': 'start_date', 'name': 'start_date'},
            {'data': 'end_date', 'name': 'end_date'},
            {'data': 'status', 'name': 'status'},
            {'data': 'action', 'name': 'action'}
        ],
    });

    table.on('click','.view-reservation', function () {
        var id = $(this).data('pk');
        var redirectUrl = "{{ route('customer-app.reservations.show', ['#id']) }}";
        var url = redirectUrl.replace('#id', id);
        $.ajaxModal('#customerShowModal', url);
    });

    table.on('click','.delete-reservation', function () {
        var id = $(this).data('pk');
        $('.modal-title').html('Delete Locker Reservation');
        $('.modal-body').html('Do you want to delete reservation?');
        $('#customerDeleteModal').modal("show");
        $('#customerDeleteModal').find('#deleteModalBtn').off('click').on('click', function() {
            var redirectUrl = "{{ route('customer-app.reservations.destroy', ['#id']) }}";
            var url = redirectUrl.replace('#id', id);
            $.easyAjax({
                type: 'DELETE',
                url: url,
                success: function () {
                    table.fnDraw();
                    $('#customerDeleteModal').modal("hide");
                }
            });
        });
    });
</script>
@endsection
