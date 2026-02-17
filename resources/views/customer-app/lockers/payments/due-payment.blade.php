@extends('layouts.customer-app.basic')

@section('title')
    HamroFitness | Due Payments
@endsection

@section('CSS')
    {!! HTML::style('fitsigma_customer/bower_components/datatables/jquery.dataTables.min.css') !!}
@stop

@section('content')
    <div class="row bg-title">
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title">Locker Due Payments</h4>
        </div>
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li>Main Menu</li>
                <li>Locker Payments</li>
                <li class="active"> Due Payments</li>
            </ol>
        </div>
        <!-- /.col-lg-12 -->
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="white-box">
                <h3 class="box-title m-b-0"><i class="fa fa-money"></i> Locker Due Payments</h3>
                <p class="text-muted m-b-30"></p>
                <div class="table-responsive">
                    <table id="purchase_table" class="table table-striped">
                        <thead>
                        <tr>
                            <th class="desktop" style="width: 20%">Locker Number</th>
                            <th class="desktop">Purchased At</th>
                            <th class="desktop">Total Price</th>
                            <th class="desktop">Paid Amount</th>
                            <th class="desktop">Remain Amount</th>
                            <th class="desktop">Due Payment Date</th>
                            <th class="desktop">Action</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

@stop

@section('JS')
    {!! HTML::script('fitsigma_customer/bower_components/datatables/jquery.dataTables.min.js') !!}
    <script>
        function load_dataTable() {
            var dueTable = $('#purchase_table');
            var table = dueTable.dataTable({
                processing: true,
                serverSide: true,
                ajax: "{{route('customer-app.locker-payments.get-due-payment-data') }}",
                columns: [
                    {data: 'locker_id', name: 'locker_id'},
                    {data: 'start_date', name: 'start_date'},
                    {data: 'amount_to_be_paid', name: 'amount_to_be_paid'},
                    {data: 'paid_amount', name: 'paid_amount'},
                    {data: 'remain_amt', name: 'remain_amt'},
                    {data: 'next_payment_date', name: 'next_payment_date'},
                    {data: 'action', name: 'action'},
                ]
            });
        }
    </script>
    <script>
        $(document).ready(function () {
            load_dataTable();
        });
    </script>
@stop
