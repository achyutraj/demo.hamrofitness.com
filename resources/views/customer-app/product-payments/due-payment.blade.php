@extends('layouts.customer-app.basic')

@section('title')
    HamroFitness | Due Payments
@endsection

@section('CSS')
    <link rel="stylesheet" href="{{ asset("fitsigma_customer/bower_components/datatables/jquery.dataTables.min.css") }}">
@stop

@section('content')
    <div class="row bg-title">
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title">Product Due Payments</h4>
        </div>
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li>Main Menu</li>
                <li>Product Payments</li>
                <li class="active"> Due Payments</li>
            </ol>
        </div>
        <!-- /.col-lg-12 -->
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="white-box">
                <h3 class="box-title m-b-0"><i class="fa fa-money"></i> Product Due Payments</h3>
                <p class="text-muted m-b-30"></p>
                <div class="table-responsive">
                    <table id="purchase_table" class="table table-striped">
                        <thead>
                        <tr>
                            <th class="desktop" style="width: 20%">Product Name</th>
                            <th class="desktop">Purchased At</th>
                            <th class="desktop">Total Price</th>
                            <th class="desktop">Paid Amount</th>
                            <th class="desktop">Remaining Amount</th>
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
    <script src="{{ asset("fitsigma_customer/bower_components/datatables/jquery.dataTables.min.js") }}"></script>
    <script>
        function load_dataTable() {
            var dueTable = $('#purchase_table');
            var table = dueTable.dataTable({
                processing: true,
                serverSide: true,
                ajax: "{{route('customer-app.product-payments.get-due-payment-data') }}",
                columns: [
                    {data: 'product_name', name: 'product_name'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'product_amount', name: 'product_amount'},
                    {data: 'paid_amount', name: 'paid_amount'},
                    {data: 'total_amount', name: 'total_amount'},
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
