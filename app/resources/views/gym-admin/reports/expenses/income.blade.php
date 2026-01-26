@extends('layouts.gym-merchant.gymbasic')

@section('CSS')
    {!! HTML::style('admin/global/plugins/bootstrap-daterangepicker/daterangepicker.css') !!}
    {!! HTML::style('admin/global/plugins/bootstrap-select/css/bootstrap-select.min.css') !!}
    {!! HTML::style('admin/global/plugins/ladda/ladda-themeless.min.css') !!}
    {!! HTML::style('admin/global/plugins/datatables/datatables.min.css') !!}
    {!! HTML::style('admin/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css') !!}
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
                <span>Extra Income Report</span>
            </li>
        </ul>
        <!-- END PAGE BREADCRUMBS -->
        <!-- BEGIN PAGE CONTENT INNER -->
        <div class="page-content-inner">
            <div class="row">
                <div class="col-md-12">
                    <div class="m-heading-1 border-green m-bordered">
                        <h3>Extra Income Report</h3>
                        <p>This report section provides you list of extra income collection .</p>
                    </div>
                </div>
            </div>
            <div class="portlet light portlet-fit">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-dollar font-red"></i><span class="caption-subject font-red bold uppercase">Extra Collection Report</span>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="row">
                        <div class="col-md-offset-2 col-md-5">
                            {!! Form::open(['id'=>'createReport','class'=>'ajax-form']) !!}
                            <div class="form-body">
                                <div class="form-group form-md-line-input">
                                    <select class="bs-select form-control" data-live-search="true" data-size="8" name="category" id="category">
                                        <option value="all">All</option>
                                        @foreach($categories as $category)
                                            <option value="{{$category->id}}">{{$category->title}}</option>
                                        @endforeach
                                    </select>
                                    <label for="title">Select Income Category</label>
                                    <span class="help-block"></span>
                                </div>

                                <div class="form-group form-md-line-input" id="payment" >
                                    <select class="bs-select form-control" data-live-search="true" data-size="8" name="payment_source" id="payment_source">
                                        <option value="all">All</option>
                                        @foreach($paymentSources as $key => $source)
                                            <option value="{{$key}}">{{$source['label']}}</option>
                                        @endforeach
                                    </select>
                                    <label for="title">Select Payment Source</label>
                                    <span class="help-block"></span>
                                </div>

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
                            {!! Form::close() !!}
                        </div>
                        <div class="col-md-4" id="easyStats" style="display: none;margin-top: 50px;margin-left: 20px">
                            <div class="widget-thumb widget-bg-color-dark-light text-uppercase margin-bottom-20 ">
                                <div class="widget-thumb-wrap">
                                    <i class="widget-thumb-icon bg-white-opacity icon-present"></i>
                                    <div class="widget-thumb-body">
                                        <span class="widget-thumb-subtitle font-white">Total Income</span>
                                        <span class="widget-thumb-body-stat counter" id="countIncome" data-counter="counterup" data-value="">0</span>
                                    </div>
                                </div>
                                <hr>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row" id="targetDataTable">
                <div class="col-md-12">
                    <!-- BEGIN EXAMPLE TABLE PORTLET-->
                    <div class="portlet light ">
                        <div class="portlet-title">
                            <div class="row">
                                <div class="col-md-6 col-sm-12" style="padding:10px;">
                                    <div class="caption font-dark">
                                        <i class="icon-chart font-red"></i>
                                        <span class="caption-subject font-red bold uppercase"> Extra Collection Report</span>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12" style="text-align:right;">
                                    <div class="caption font-dark">
                                        <a class="btn btn-xs btn-success downloadIncome">
                                            <span class="caption-subject font-white bold uppercase"> PDF</span>
                                            <i class="fa fa-download"></i>
                                        </a>
                                        <a class="btn btn-xs btn-info downloadExcelIncome">
                                            <span class="caption-subject font-white bold uppercase"> Excel</span>
                                            <i class="fa fa-download"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <table class="table table-striped table-bordered table-hover" id="income_table">
                                <thead>
                                <tr>
                                    <th class="min-tablet"> Title </th>
                                    <th class="min-tablet"> Purchase At </th>
                                    <th class="min-tablet"> Paid By </th>
                                    <th class="min-tablet"> Total Amount </th>
                                    <th class="min-tablet"> Payment Source </th>
                                    <th class="min-tablet"> Remarks </th>
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
    {!! HTML::script('admin/global/plugins/bootstrap-daterangepicker/moment.min.js') !!}
    {!! HTML::script('admin/global/plugins/jquery-easypiechart/jquery.easypiechart.min.js') !!}
    {!! HTML::script('admin/global/plugins/bootstrap-daterangepicker/daterangepicker.js') !!}
    {!! HTML::script('admin/global/plugins/counterup/jquery.counterup.js') !!}
    {!! HTML::script('admin/global/plugins/counterup/jquery.waypoints.min.js') !!}
    {!! HTML::script('admin/global/plugins/bootstrap-select/js/bootstrap-select.min.js') !!}
    {!! HTML::script('admin/pages/scripts/components-bootstrap-select.min.js') !!}

    {!! HTML::script('admin/global/scripts/datatable.js') !!}
    {!! HTML::script('admin/pages/scripts/table-datatables-managed.js') !!}
    {!! HTML::script('admin/global/plugins/datatables/datatables.min.js') !!}
    {!! HTML::script('admin/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js') !!}
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
    </script>
    <script>
        $('#save-form').click(function(){
            var dateRange = $('#reportrange span').html();
            var category = $('#category').val();
            var payment_source = $('#payment_source').val();
            $.easyAjax({
                url: "{{route('gym-admin.reports.income.store')}}",
                container:'#createReport',
                type:"POST",
                data:{date_range: dateRange,category:category, payment_source:payment_source, _token: '{{csrf_token()}}'},
                success:function(res){
                    if(res.status = 'success') {
                        $('#countIncome').attr('data-value',res.total);
                        $('#heading').html(res.report);
                        $('#easyStats').css('display','block');
                        $('#targetDataTable').css('display','block');
                        load_income_data(res.start_date,res.end_date,res.category,res.payment_source);
                        download_income_data(res.start_date,res.end_date,res.category,res.payment_source);
                        $('.counter').counterUp();
                    }
                }
            });

        });
    </script>
    <script>
        function load_income_data(s,e,category,payment_source){
            var table = $('#income_table');
            var url = "{{route('gym-admin.reports.income.ajax_create_income',['#s','#e','#category','#payment_source'])}}";
            url = url.replace('#s',s);
            url = url.replace('#e',e);
            url = url.replace('#category',category);
            url = url.replace('#payment_source',payment_source);

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


        function download_income_data(s,e,category,payment_source){
            $('.downloadIncome').on('click',function(){
                window.location = 'collection-report/download/'+ s + '/' + e + '/' + category + '/' +payment_source ;
            });
            $('.downloadExcelIncome').on('click',function(){
                window.location = 'collection-report/download/excel/'+ s + '/' + e + '/' + category + '/' +payment_source;
            });
        }
    </script>
@stop
