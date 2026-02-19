@extends('layouts.gym-merchant.gymbasic')

@section('CSS')
    <link rel="stylesheet" href="{{ asset("admin/global/plugins/bootstrap-daterangepicker/daterangepicker.css") }}">
    <link rel="stylesheet" href="{{ asset("admin/global/plugins/bootstrap-select/css/bootstrap-select.min.css") }}">

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
                <span>ProductSale Report</span>
            </li>
        </ul>
        <!-- END PAGE BREADCRUMBS -->
        <!-- BEGIN PAGE CONTENT INNER -->
        <div class="page-content-inner">
            <div class="row">
                <div class="col-md-12">
                    <div class="m-heading-1 border-green m-bordered">
                        <h3>Product Sale Report</h3>
                    </div>
                </div>
            </div>

            <div class="portlet light portlet-fit">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-phone font-red"></i><span class="caption-subject font-red bold uppercase">Product Sale Report</span>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="row">
                        <div class="col-md-offset-2 col-md-5">
                            {{ html()->form->open(['id'=>'createProductSaleReport','class'=>'ajax-form']) !!}
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
                                        <span class="widget-thumb-subtitle font-white">Total</span>
                                        <span class="widget-thumb-body-stat counter" id="counter" data-counter="counterup" data-value="">0</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row" id="targetDataTable" style="display: none">
                <div class="col-md-12">
                    <!-- BEGIN EXAMPLE TABLE PORTLET-->
                    <div class="portlet light ">
                        <div class="portlet-title">
                            <div class="row">
                                <div class="col-md-6 col-sm-12" style="padding:10px;">
                                    <div class="caption font-dark">
                                        <i class="icon-target font-red"></i>
                                        <span class="caption-subject font-red bold uppercase"> ProductSale Report</span>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12" style="text-align:right;">
                                    <div class="caption font-dark">
                                        <a class="btn btn-success downloadProductSale">
                                            <span class="caption-subject font-white bold uppercase"> PDF</span>
                                            <i class="fa fa-download"></i>
                                        </a>
                                        <a class="btn btn-info downloadExcelProductSale">
                                            <span class="caption-subject font-white bold uppercase"> Excel</span>
                                            <i class="fa fa-download"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <table class="table table-striped table-bordered table-hover order-column responsive" id="productsale_table">
                                <thead>
                                <tr>
                                    <th class="all"> Customer Name </th>
                                    <th class="min-tablet"> Customer Type </th>
                                    <th class="min-tablet"> Product Name </th>
                                    <th class="min-tablet"> Purchased At </th>
                                    <th class="min-tablet"> Total Price </th>
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
                //minDate: '01/01/2012',
                //maxDate: '12/31/2014',
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
    </script>

    <script>
        $('#save-form').click(function(){
            var dateRange = $('#reportrange span').html();
            $.easyAjax({
                url: "{{route('gym-admin.product-sale-report.store')}}",
                container:'#createProductSaleReport',
                type:"POST",
                data:{date_range: dateRange, _token: '{{csrf_token()}}'},
                success:function(res){
                    if(res.status = 'success') {
                        $('#counter').attr('data-value',res.total);
                        $('#heading').html(res.report);
                        $('#easyStats').css('display','block');
                        $('#targetDataTable').css('display','block');
                        load_data_table(res.start_date,res.end_date);
                        load_productsell_data(res.start_date,res.end_date);
                        $('.counter').counterUp();
                    }
                }
            });

        });
    </script>
    <script>
        function load_productsell_data(s,e){
            $('.downloadProductSale').on('click',function(){
                window.location = 'product-sale-report/download/'+ s + '/' + e;
            });
            $('.downloadExcelProductSale').on('click',function(){
                window.location = 'product-sale-report/download/excel/'+ s + '/' + e;
            });
        }
    </script>
    <script>
        function load_data_table(id,s,e){
            var table = $('#productsale_table');
            var url = "{{route('gym-admin.product-sale-report.ajax-create',['#id','#s','#e'])}}";
            url = url.replace('#id',id);
            url = url.replace('#s',s);
            url = url.replace('#e',e);
            // begin first table
            table.DataTable({
                "sAjaxSource": url,
                bDestroy:true,
                "aoColumns": [
                    {data: 'customer_name', name: 'customer_name'},
                    {data: 'customer_type', name: 'customer_type'},
                    {data: 'product_name', name: 'product_name'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'product_amount', name: 'product_amount'},
                ]
            });
        }

    </script>
@stop
