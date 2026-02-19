@extends('layouts.customer-app.basic')

@section('title')
    HamroFitness | Locker Payments
@endsection

@section('CSS')
    <link rel="stylesheet" href="{{ asset("fitsigma_customer/bower_components/datatables/jquery.dataTables.min.css") }}">
@endsection

@section('content')
    <div class="row bg-title">
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title">Locker Payments</h4>
        </div>
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li>Main Menu</li>
                <li>Locker Payments</li>
                <li class="active"> Payments</li>
            </ol>
        </div>
        <!-- /.col-lg-12 -->
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="white-box">
                <h3 class="box-title m-b-0"><i class="fa fa-money"></i> Locker Payments</h3>
                <div class="row">
                    @if($gymSettings->payment_status == "enabled")
                    <div class="col-md-6">
                        <a class="btn btn-sm btn-success waves-effect" href="{{ route('customer-app.locker-payments.create',['id' => null,]) }}"><i class="zmdi zmdi-plus zmdi-hc-fw fa-fw"></i>Add Payment</a>
                    </div>
                    @endif
                    <div class="col-md-6"></div>
                </div>
                <p class="text-muted m-b-30"></p>
                <div class="table-responsive">
                    <table id="paymentTable" class="table table-striped">
                        <thead>
                        <tr>
                            <th>Locker</th>
                            <th>Amount</th>
                            <th>Source</th>
                            <th>Payment Date</th>
                            <th>Payment ID</th>
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

        var paymentTable = $('#paymentTable');

        var table = paymentTable.dataTable({
            processing: true,
            serverSide: true,
            ajax: "{{route('customer-app.locker-payments.get-payment-data')}}",
            columns: [
                {data: 'locker', name: 'locker'},
                {data: 'payment_amount', name: 'payment_amount'},
                {data: 'payment_source', name: 'payment_source'},
                {data: 'payment_date', name: 'payment_date'},
                {data: 'payment_id', name: 'payment_id'}
            ]
        });
    </script>
@endsection
