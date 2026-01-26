@extends('layouts.gym-merchant.gymbasic')

@section('CSS')
    {!! HTML::style('admin/global/plugins/bootstrap-daterangepicker/daterangepicker.css') !!}
    {!! HTML::style('admin/global/plugins/datatables/datatables.min.css') !!}
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
                <span>Subscription Report</span>
            </li>
        </ul>
        <!-- END PAGE BREADCRUMBS -->
        <!-- BEGIN PAGE CONTENT INNER -->
        <div class="page-content-inner">

            <div class="row">
                <div class="col-md-12">
                    <div class="m-heading-1 border-green m-bordered">
                        <h3>Subscription Report</h3>
                        <p>This report section provides you list of bookings according : </p>
                        <ul>
                            <li>All Types of Subscription</li>
                            <li>Only Memberships</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="portlet light portlet-fit">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="icon-calendar font-red"></i><span class="caption-subject font-red bold uppercase">Subscription Report</span></div>
                </div>
                <div class="portlet-body">
                    <div class="row">
                        <div class="col-md-offset-2 col-md-5">
                            {!! Form::open(['id'=>'createBookingReport','class'=>'ajax-form']) !!}
                            <div class="form-body">
                                <div class="form-group form-md-line-input">
                                    <select class="bs-select form-control" data-live-search="true" data-size="8" name="membership" id="membership">
                                        <option value="all">All</option>
                                        @foreach($memberships as $membership)
                                            <option value="{{$membership->id}}">{{$membership->title}}</option>
                                        @endforeach
                                    </select>
                                    <label for="title">Select Membership</label>
                                    <span class="help-block"></span>
                                </div>

                                <div class="form-group form-md-line-input ">
                                    <select class="bs-select form-control" data-live-search="true" data-size="8" name="booking_type" id="booking_type">
                                        <option value="all">All Memberships</option>
                                        <option value="expire">Expire Memberships</option>
                                        <option value="renew">Renew Memberships</option>
                                        <option value="freeze">Freeze Memberships</option>
                                        <option value="extend">Extend Memberships</option>
                                        <option value="pending">Pending Memberships</option>
                                    </select>
                                    <label for="title">Select Booking Type</label>
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
                                <h4 class="widget-thumb-heading font-white" id="heading"></h4>
                                <div class="widget-thumb-wrap">
                                    <i class="widget-thumb-icon bg-white-opacity fa fa-inr"></i>
                                    <div class="widget-thumb-body">
                                        <span class="widget-thumb-subtitle font-white">Total</span>
                                        <span class="widget-thumb-body-stat counter" id="count" data-counter="counterup" data-value="">0</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row" id="bookingDataTable" style="display: none">
                <div class="col-md-12">
                    <!-- BEGIN EXAMPLE TABLE PORTLET-->
                    <div class="portlet light ">
                        <div class="portlet-title">
                            <div class="row">
                                <div class="col-md-6 col-sm-12" style="padding:10px;">
                                    <div class="caption font-dark">
                                        <i class="icon-calendar font-red"></i>
                                        <span class="caption-subject font-red bold uppercase"> Subscriptions</span>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12" style="text-align:right;">
                                    <div class="caption font-dark">
                                        <a class="btn btn-success downloadBooking">
                                            <span class="caption-subject font-white bold uppercase"> PDF</span>
                                            <i class="fa fa-download"></i>
                                        </a>
                                        <a class="btn btn-info downloadExcelBooking">
                                            <span class="caption-subject font-white bold uppercase"> Excel</span>
                                            <i class="fa fa-download"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="portlet-body">
                            <table class="table table-striped table-bordered table-hover" id="bookings_table">
                                <thead>
                                <tr>
                                    <th class="min-tablet"> Client Name </th>
                                    <th class="min-tablet"> Title </th>
                                    <th class="min-tablet"> Purchase Amount </th>
                                    <th class="min-tablet"> Start Date </th>
                                    <th class="min-tablet"> Expire Date </th>

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
                days: 60
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
            var booking = $('#booking_type').val();
            var membership = $('#membership').val();
            if(booking == '')
            {
                $.showToastr('Please Select a Type','error');
            }else {
                $.easyAjax({
                    url:"{{route('gym-admin.booking-report.store')}}",
                    container:'#createBookingReport',
                    type:"POST",
                    data:{date_range:dateRange,booking_type:booking,membership:membership,_token:'{{csrf_token()}}'},
                    
                    success:function(res){
                        if(res.status = 'success'){
                            $('#count').attr('data-value',res.total);
                            $('#heading').html(res.report);
                            $('#easyStats').css('display','block');
                            $('#bookingDataTable').css('display','block');
                            load_data_table(res.type,res.start_date,res.end_date,res.membership);
                            load_book_data(res.type,res.start_date,res.end_date,res.membership);
                            $('.counter').counterUp();
                        }
                    }
                });
            }

        });
    </script>
    <script>
        function load_data_table(id,s,e,membership){
            var table = $('#bookings_table');
            var url = "{{route('gym-admin.booking-report.ajax-create',['#id','#s','#e','#membership'])}}";
            url = url.replace('#id',id);
            url = url.replace('#s',s);
            url = url.replace('#e',e);
            url = url.replace('#membership',membership);

            // begin first table
            table.DataTable({
                "sAjaxSource": url,
                bDestroy:true,
                "aoColumns": [
                    {data: 'gym_clients.first_name', name: 'gym_clients.first_name'},
                    {data: 'membership', name: 'membership'},
                    {data: 'amount_to_be_paid', name: 'amount_to_be_paid'},
                    {data: 'start_date', name: 'start_date'},
                    {data: 'expires_on', name: 'expires_on'},
                ]
            });
        }

        function load_book_data(id,s,e,membership){
            $('.downloadBooking').on('click',function(){
                window.location = 'booking-report/download/'+ id + '/' + s + '/' + e + '/' + membership;
            });
            $('.downloadExcelBooking').on('click',function(){
                window.location = 'booking-report/download/excel/'+ id + '/' + s + '/' + e + '/' + membership;
            });
        }
    </script>
@stop
