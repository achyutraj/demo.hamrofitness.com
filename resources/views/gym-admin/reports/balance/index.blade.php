@extends('layouts.gym-merchant.gymbasic')

@section('CSS')
    <link rel="stylesheet" href="{{ asset("admin/global/plugins/bootstrap-daterangepicker/daterangepicker.css") }}">
    <link rel="stylesheet" href="{{ asset("admin/global/plugins/bootstrap-select/css/bootstrap-select.min.css") }}">

    <link rel="stylesheet" href="{{ asset("admin/global/plugins/ladda/ladda-themeless.min.css") }}">
    <link rel="stylesheet" href="{{ asset("admin/global/plugins/datatables/datatables.min.css") }}">
    <link rel="stylesheet" href="{{ asset("admin/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css") }}">
@stop

@section('content')
    <div class="container-fluid"      >
        <!-- BEGIN PAGE BREADCRUMBS -->
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <a href="{{route('gym-admin.dashboard.index')}}">Home</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>Balance Report</span>
            </li>
        </ul>
        <!-- END PAGE BREADCRUMBS -->
        <!-- BEGIN PAGE CONTENT INNER -->
        <div class="page-content-inner">
            <div class="row">
                <div class="col-md-12">
                    <div class="m-heading-1 border-green m-bordered">
                        <h3>Balance Report</h3>
                        <p>This section provides you the report on your income and expense in provided date range</p>
                    </div>
                </div>
            </div>

            <div class="portlet light portlet-fit">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-phone font-red"></i><span class="caption-subject font-red bold uppercase">Balance Report</span>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="row">
                        <div class="col-md-offset-2 col-md-5">
                            {{ html()->form->open(['id'=>'createBalanceReport','class'=>'ajax-form']) !!}
                            <div class="form-body">
                                <div class="form-group">
                                    <div id="reportrange" class="btn default">
                                        <i class="fa fa-calendar"></i> &nbsp;
                                        <span id="date">{{\Carbon\Carbon::now('Asia/Kathmandu')->toFormattedDateString()}} - {{\Carbon\Carbon::now('Asia/Kathmandu')->toFormattedDateString()}} </span>
                                        <b class="fa fa-angle-down"></b>
                                    </div>
                                </div>
                                <div class="form-actions" style="margin-top: 70px">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <button type="button" class="btn dark mt-ladda-btn ladda-button" data-style="zoom-in" id="save-form">
                                                <span class="ladda-label"><i class="icon-arrow-up"></i> Submit</span>
                                            </button>
                                            <button type="reset" class="btn default">Reset</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{ html()->form->close() !!}
                        </div>
                        <div class="col-md-4" id="easyStats" style="display: none;margin-top: 50px;margin-left: 20px">
                            <div class="widget-thumb widget-bg-color-dark-light text-uppercase margin-bottom-20 ">
                                <h4 class="widget-thumb-heading font-white" id="heading"></h4>
                                <div class="widget-thumb-wrap">
                                    <i class="widget-thumb-icon bg-white-opacity icon-present"></i>
                                    <div class="widget-thumb-body">
                                        <span class="widget-thumb-subtitle font-white">Total Income</span>
                                        <span class="widget-thumb-body-stat counter" id="countIncome" data-counter="counterup" data-value="">0</span>
                                    </div>
                                </div>
                                <hr>
                                <div class="widget-thumb-wrap">
                                    <i class="widget-thumb-icon bg-white-opacity icon-present"></i>
                                    <div class="widget-thumb-body">
                                        <span class="widget-thumb-subtitle font-white">Total Expense</span>
                                        <span class="widget-thumb-body-stat counter" id="countExpense" data-counter="counterup" data-value="">0</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="tab-content">
                    <div class="row">
                        <div class="col-md-12">
                            <!-- BEGIN EXAMPLE TABLE PORTLET-->
                            <div class="portlet light ">
                                <div class="portlet-title">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-12" style="padding:10px;">
                                            <div class="caption font-dark">
                                                <i class=" fa {{ $gymSettings->currency->symbol }} font-red"></i>
                                                <span class="caption-subject font-red bold uppercase"> Membership Income</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12" style="text-align:right;">
                                            <div class="caption font-dark">
                                                <a class="btn btn-success downloadMemBalance">
                                                    <span class="caption-subject font-white bold uppercase"> PDF</span>
                                                    <i class="fa fa-download"></i>
                                                </a>
                                                <a class="btn btn-info downloadExcelMemBalance">
                                                    <span class="caption-subject font-white bold uppercase"> Excel</span>
                                                    <i class="fa fa-download"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="portlet-body">
                                    <table class="table table-striped table-bordered table-hover order-column table-100"
                                           id="mem-payments">
                                        <thead>
                                        <tr>
                                            <th class="max-desktop"> Name</th>
                                            <th class="desktop"> Amount</th>
                                            <th class="desktop"> Source</th>
                                            <th class="desktop"> Payment Date</th>
                                            <th class="desktop"> Payment ID</th>
                                            <th class="desktop"> Payment For</th>
                                        </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                            <!-- END EXAMPLE TABLE PORTLET-->
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <!-- BEGIN EXAMPLE TABLE PORTLET-->
                            <div class="portlet light ">
                                <div class="portlet-title">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-12" style="padding:10px;">
                                            <div class="caption font-dark">
                                                <i class=" fa {{ $gymSettings->currency->symbol }} font-red"></i>
                                                <span class="caption-subject font-red bold uppercase"> Product Income</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12" style="text-align:right;">
                                            <div class="caption font-dark">
                                                <a class="btn btn-success downloadProductBalance">
                                                    <span class="caption-subject font-white bold uppercase"> PDF</span>
                                                    <i class="fa fa-download"></i>
                                                </a>
                                                <a class="btn btn-info downloadExcelProductBalance">
                                                    <span class="caption-subject font-white bold uppercase"> Excel</span>
                                                    <i class="fa fa-download"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="portlet-body">
                                    <table class="table table-striped table-bordered table-hover order-column table-100"
                                           id="product-payments">
                                        <thead>
                                        <tr>
                                            <th class="max-desktop"> Name</th>
                                            <th class="desktop"> Amount</th>
                                            <th class="desktop"> Source</th>
                                            <th class="desktop"> Payment Date</th>
                                            <th class="desktop"> Payment ID</th>
                                            <th class="desktop"> Product</th>
                                        </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                            <!-- END EXAMPLE TABLE PORTLET-->
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <!-- BEGIN EXAMPLE TABLE PORTLET-->
                            <div class="portlet light ">
                                <div class="portlet-title">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-12" style="padding:10px;">
                                            <div class="caption font-dark">
                                                <i class=" fa {{ $gymSettings->currency->symbol }} font-red"></i>
                                                <span class="caption-subject font-red bold uppercase"> Locker Reservation Income</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12" style="text-align:right;">
                                            <div class="caption font-dark">
                                                <a class="btn btn-success downloadLockerBalance">
                                                    <span class="caption-subject font-white bold uppercase"> PDF</span>
                                                    <i class="fa fa-download"></i>
                                                </a>
                                                <a class="btn btn-info downloadExcelLockerBalance">
                                                    <span class="caption-subject font-white bold uppercase"> Excel</span>
                                                    <i class="fa fa-download"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="portlet-body">
                                    <table class="table table-striped table-bordered table-hover order-column table-100"
                                           id="locker-payments">
                                        <thead>
                                        <tr>
                                            <th class="max-desktop"> Name</th>
                                            <th class="desktop"> Amount</th>
                                            <th class="desktop"> Source</th>
                                            <th class="desktop"> Payment Date</th>
                                            <th class="desktop"> Payment ID</th>
                                            <th class="desktop"> Locker</th>
                                        </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                            <!-- END EXAMPLE TABLE PORTLET-->
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <!-- BEGIN EXAMPLE TABLE PORTLET-->
                            <div class="portlet light ">
                                <div class="portlet-title">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-12" style="padding:10px;">
                                            <div class="caption font-dark">
                                                <i class=" fa {{ $gymSettings->currency->symbol }} font-red"></i>
                                                <span class="caption-subject font-red bold uppercase"> Other Income</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12" style="text-align:right;">
                                            <div class="caption font-dark">
                                                <a class="btn btn-success downloadIncomeBalance">
                                                    <span class="caption-subject font-white bold uppercase"> PDF</span>
                                                    <i class="fa fa-download"></i>
                                                </a>
                                                <a class="btn btn-info downloadExcelIncomeBalance">
                                                    <span class="caption-subject font-white bold uppercase"> Excel</span>
                                                    <i class="fa fa-download"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="portlet-body">
                                    <table class="table table-striped table-bordered table-hover order-column table-100"
                                           id="income_table">
                                        <thead>
                                        <tr>
                                            <th class="max-desktop"> Item Name</th>
                                            <th class="desktop"> Purchase At</th>
                                            <th class="desktop"> Paid By</th>
                                            <th class="desktop"> Price</th>
                                            <th class="desktop"> Payment Source</th>
                                            <th class="desktop"> Remarks</th>
                                        </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                            <!-- END EXAMPLE TABLE PORTLET-->
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <!-- BEGIN EXAMPLE TABLE PORTLET-->
                            <div class="portlet light ">
                                <div class="portlet-title">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-12" style="padding:10px;">
                                            <div class="caption font-dark">
                                                <i class=" fa {{ $gymSettings->currency->symbol }} font-red"></i>
                                                <span class="caption-subject font-red bold uppercase"> Expense</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12" style="text-align:right;">
                                            <div class="caption font-dark">
                                                <a class="btn btn-success downloadExpenseBalance">
                                                    <span class="caption-subject font-white bold uppercase"> PDF</span>
                                                    <i class="fa fa-download"></i>
                                                </a>
                                                <a class="btn btn-info downloadExcelExpenseBalance">
                                                    <span class="caption-subject font-white bold uppercase"> Excel</span>
                                                    <i class="fa fa-download"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="portlet-body">
                                    <table class="table table-striped table-bordered table-hover order-column table-100"
                                           id="expense_table">
                                        <thead>
                                        <tr>
                                            <th class="max-desktop"> Item Category</th>
                                            <th class="max-desktop"> Item Name</th>
                                            <th class="desktop"> Purchase At</th>
                                            <th class="desktop"> Supplier</th>
                                            <th class="desktop"> Price</th>
                                            <th class="desktop"> Status</th>
                                            <th class="desktop"> Source</th>
                                            <th class="desktop"> Remarks</th>

                                        </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                            <!-- END EXAMPLE TABLE PORTLET-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END PAGE CONTENT INNER -->
    </div>

@stop

@section('footer')
    <script src="{{ asset("admin/global/plugins/bootstrap-daterangepicker/moment.min.js") }}"></script>
    <script src="{{ asset("admin/global/plugins/jquery-easypiechart/jquery.easypiechart.min.js") }}"></script>
    <script src="{{ asset("admin/global/plugins/bootstrap-daterangepicker/daterangepicker.js") }}"></script>
    <script src="{{ asset("admin/global/plugins/counterup/jquery.counterup.js") }}"></script>
    <script src="{{ asset("admin/global/plugins/counterup/jquery.waypoints.min.js") }}"></script>
    <script src="{{ asset("admin/global/plugins/bootstrap-select/js/bootstrap-select.min.js") }}"></script>
    <script src="{{ asset("admin/pages/scripts/components-bootstrap-select.min.js") }}"></script>

    <script src="{{ asset("admin/global/scripts/datatable.js") }}"></script>
    <script src="{{ asset("admin/pages/scripts/table-datatables-managed.js") }}"></script>
    <script src="{{ asset("admin/global/plugins/datatables/datatables.min.js") }}"></script>
    <script src="{{ asset("admin/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js") }}"></script>

    <script>
        $('#reportrange').daterangepicker({
                opens: (App.isRTL() ? 'left' : 'right'),
                startDate: moment(),
                endDate: moment(),
                dateLimit: {
                    days: 1000
                },
                showDropdowns: true,
                showWeekNumbers: true,
                timePicker: false,
                timePickerIncrement: 1,
                timePicker12Hour: true,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract('days', 1), moment().subtract('days', 1)],
                    'Last 7 Days': [moment().subtract('days', 6), moment()],
                    'Last 30 Days': [moment().subtract('days', 29), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')]
                },
                buttonClasses: ['btn'],
                applyClass: 'green',
                cancelClass: 'default',
                format: 'MM/DD/YYYY',
                separator: ' to ',
                locale: {
                    applyLabel: 'Apply',
                    fromLabel: 'From',
                    toLabel: 'To',
                    customRangeLabel: 'Custom Range',
                    daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
                    monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                    firstDay: 1
                }
            },
            function (start, end) {
                $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            }
        );

        $('#save-form').click(function(){
            var dateRange = $('#reportrange span').html();
            $.easyAjax({
                url:"{{route('gym-admin.balance-report.store')}}",
                container:'#createBalanceReport',
                type:"POST",
                data:{date_range: dateRange, _token: '{{csrf_token()}}'},
                success:function(res){
                    if(res.status = 'success') {
                        $('#countIncome').attr('data-value',res.totalIncome);
                        $('#countExpense').attr('data-value',res.totalExpense);
                        $('#heading').html(res.report);
                        $('#easyStats').css('display','block');
                        load_mem_table(res.start_date,res.end_date);
                        load_product_table(res.start_date,res.end_date);
                        load_locker_table(res.start_date,res.end_date);
                        load_data_table(res.start_date,res.end_date);
                        load_income_table(res.start_date,res.end_date);

                        load_mem_data(res.start_date,res.end_date);
                        load_product_data(res.start_date,res.end_date);
                        load_locker_data(res.start_date,res.end_date);
                        load_balance_data(res.start_date,res.end_date);
                        load_income_data(res.start_date,res.end_date);
                        $('#countIncome').counterUp();
                        $('#countExpense').counterUp();
                    }
                }
            });

        });

        function load_mem_data(s,e){
            $('.downloadMemBalance').on('click',function(){
                window.location = 'mem-balance-report/download/'+ s + '/' + e;
            });
            $('.downloadExcelMemBalance').on('click',function(){
                window.location = 'mem-balance-report/download/excel/'+ s + '/' + e;
            });
        }
        function load_locker_data(s,e){
            $('.downloadLockerBalance').on('click',function(){
                window.location = 'locker-balance-report/download/'+ s + '/' + e;
            });
            $('.downloadExcelLockerBalance').on('click',function(){
                window.location = 'locker-balance-report/download/excel/'+ s + '/' + e;
            });
        }
        function load_product_data(s,e){
            $('.downloadProductBalance').on('click',function(){
                window.location = 'product-balance-report/download/'+ s + '/' + e;
            });
            $('.downloadExcelProductBalance').on('click',function(){
                window.location = 'product-balance-report/download/excel/'+ s + '/' + e;
            });
        }
        function load_balance_data(s,e){
            $('.downloadExpenseBalance').on('click',function(){
                window.location = 'expense-balance-report/download/'+ s + '/' + e;
            });
            $('.downloadExcelExpenseBalance').on('click',function(){
                window.location = 'expense-balance-report/download/excel/'+ s + '/' + e;
            });
        }
        function load_income_data(s,e){
            $('.downloadIncomeBalance').on('click',function(){
                window.location = 'income-balance-report/download/'+ s + '/' + e;
            });
            $('.downloadExcelIncomeBalance').on('click',function(){
                window.location = 'income-balance-report/download/excel/'+ s + '/' + e;
            });
        }

        function load_data_table(id,s,e){
            var table = $('#expense_table');
            var url = "{{route('gym-admin.balance-report.ajax-create',['#id','#s','#e'])}}";
            url = url.replace('#id',id);
            url = url.replace('#s',s);
            url = url.replace('#e',e);
            // begin first table
            table.DataTable({
                "sAjaxSource": url,
                bDestroy:true,
                "aoColumns": [
                    {data: 'category_id', name: 'category_id'},
                    {data: 'item_name', name: 'item_name'},
                    {data: 'purchase_date', name: 'purchase_date'},
                    {data: 'supplier_id', name: 'supplier_id'},
                    {data: 'price', name: 'price'},
                    {data: 'payment_status', name: 'payment_status'},
                    {data: 'payment_source', name: 'payment_source'},
                    {data: 'remarks', name: 'remarks'},
                ]
            });
        }

        function load_mem_table(id,s,e){
            var table = $('#mem-payments');
            var url = "{{route('gym-admin.balance-report.ajax-create-mem',['#id','#s','#e'])}}";
            url = url.replace('#id',id);
            url = url.replace('#s',s);
            url = url.replace('#e',e);
            // begin first table
            table.DataTable({
                "sAjaxSource": url,
                bDestroy:true,
                "aoColumns": [
                    {data: 'gym_clients.first_name', name: 'gym_clients.first_name'},
                    {data: 'payment_amount', name: 'payment_amount'},
                    {data: 'payment_source', name: 'payment_source'},
                    {data: 'payment_date', name: 'payment_date'},
                    {data: 'payment_id', name: 'payment_id'},
                    {data: 'membership', name: 'membership'},
                ]
            });
        }

        function load_product_table(id,s,e){
            var table = $('#product-payments');
            var url = "{{route('gym-admin.balance-report.ajax-create-product',['#id','#s','#e'])}}";
            url = url.replace('#id',id);
            url = url.replace('#s',s);
            url = url.replace('#e',e);
            // begin first table
            table.DataTable({
                "sAjaxSource": url,
                bDestroy:true,
                "aoColumns": [
                    {data: 'gym_clients.first_name', name: 'gym_clients.first_name'},
                    {data: 'payment_amount', name: 'payment_amount'},
                    {data: 'payment_source', name: 'payment_source'},
                    {data: 'payment_date', name: 'payment_date'},
                    {data: 'payment_id', name: 'payment_id'},
                    {data: 'product_name', name: 'product_name'},
                ]
            });
        }

        function load_locker_table(id,s,e){
            var table = $('#locker-payments');
            var url = "{{route('gym-admin.balance-report.ajax-create-locker',['#id','#s','#e'])}}";
            url = url.replace('#id',id);
            url = url.replace('#s',s);
            url = url.replace('#e',e);
            // begin first table
            table.DataTable({
                "sAjaxSource": url,
                bDestroy:true,
                "aoColumns": [
                    {data: 'gym_clients.first_name', name: 'gym_clients.first_name'},
                    {data: 'payment_amount', name: 'payment_amount'},
                    {data: 'payment_source', name: 'payment_source'},
                    {data: 'payment_date', name: 'payment_date'},
                    {data: 'payment_id', name: 'payment_id'},
                    {data: 'locker_num', name: 'locker_num'},
                ]
            });
        }

        function load_income_table(id,s,e){
            var table = $('#income_table');
            var url = "{{route('gym-admin.balance-report.ajax-create-income',['#id','#s','#e'])}}";
            url = url.replace('#id',id);
            url = url.replace('#s',s);
            url = url.replace('#e',e);
            // begin first table
            table.DataTable({
                "sAjaxSource": url,
                bDestroy:true,
                "aoColumns": [
                    {data: 'category_id', name: 'category_id'},
                    {data: 'purchase_date', name: 'purchase_date'},
                    {data: 'supplier_id', name: 'supplier_id'},
                    {data: 'price', name: 'price'},
                    {data: 'payment_source', name: 'payment_source'},
                    {data: 'remarks', name: 'remarks'},
                ]
            });
        }

    </script>
@stop
