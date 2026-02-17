@extends('layouts.gym-merchant.gymbasic')

@section('CSS')
    {!! HTML::style('admin/global/plugins/bootstrap-daterangepicker/daterangepicker.css') !!}
    {!! HTML::style('admin/global/plugins/bootstrap-select/css/bootstrap-select.min.css') !!}
    {!! HTML::style('admin/global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css') !!}
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
                <span>Bio Attendance Report</span>
            </li>
        </ul>
        <!-- END PAGE BREADCRUMBS -->
        <!-- BEGIN PAGE CONTENT INNER -->
        <div class="page-content-inner">
            {{--Info Section --}}
            <div class="row">
                <div class="col-md-12">
                    <div class="m-heading-1 border-green m-bordered">
                        <h3>Bio Attendance Report</h3>

                        <p>This report section provides you features like: </p>
                        <ul>
                            <li>List the attendance of Clients.</li>
                            <li>Show the attendance of Employees.</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="portlet light portlet-fit">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-clock-o font-red"></i><span class="caption-subject font-red bold uppercase">Attendance Report</span>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="row">
                        <div class="col-md-offset-2 col-md-5">
                            {!! Form::open(['id'=>'createAttendanceReport','class'=>'ajax-form']) !!}
                            <div class="form-body">
                                <div class="form-group form-md-line-input ">
                                    <select class="bs-select form-control" data-live-search="true" data-size="8"
                                            name='device' id="device">
                                        <option selected disabled>Select Device</option>
                                        @foreach($devices as $device)
                                            <option value="{{ $device->id }}">{{ $device->name.'-'.$device->serial_num }}</option>
                                        @endforeach
                                    </select>
                                    <label for="title">Select Attendance Report</label>
                                    <span class="help-block"></span>
                                </div>
                                <div class="form-group form-md-line-input ">
                                    <select class="bs-select form-control" data-live-search="true" data-size="8"
                                            name='type' id="type">
                                        <option value="">Select</option>
                                        @if(count($customers) > 1)
                                            <option value="client">All Customers</option>
                                        @endif
                                        @foreach($customers as $customer)
                                            <option value="customer|{{ $customer->id }}">{{ $customer->first_name.' '.$customer->middle_name.' '.$customer->last_name }} - Customer</option>
                                        @endforeach
                                    </select>
                                    <label for="title">Select Attendance Report</label>
                                    <span class="help-block"></span>
                                </div>
                                <div class="form-group form-md-line-input">
                                    <div id="reportrange_attendance" class="btn default">
                                        <i class="fa fa-calendar"></i> &nbsp;
                                        <span id="date_attendance">{{\Carbon\Carbon::now('Asia/Kathmandu')->toFormattedDateString()}}
                                            - {{\Carbon\Carbon::now('Asia/Kathmandu')->toFormattedDateString()}} </span>
                                        <b class="fa fa-angle-down"></b>
                                    </div>
                                </div>
                                <div class="form-actions" style="margin-top: 70px">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <button type="button" class="btn dark mt-ladda-btn ladda-button"
                                                    data-style="zoom-in" id="save-form">
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
                                    <i class="widget-thumb-icon bg-white-opacity fa fa-users"></i>

                                    <div class="widget-thumb-body">
                                        <span class="widget-thumb-subtitle font-white">Total</span>
                                        <span class="widget-thumb-body-stat counter" id="count" data-counter="counterup"
                                              data-value="">0</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row" id="attendanceDataTable" style="display: none;">
                <div class="col-md-12">
                    <!-- BEGIN EXAMPLE TABLE PORTLET-->
                    <div class="portlet light ">
                        <div class="portlet-title">
                            <div class="row">
                                <div class="col-md-6 col-sm-12" style="padding:10px;">
                                    <div class="caption font-dark">
                                        <i class="icon-target font-red"></i>
                                        <span class="caption-subject font-red bold uppercase">Bio Attendance Report</span>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12" style="text-align:right;">
                                    <div class="caption font-dark">
                                        <a class="btn btn-success downloadAttendance">
                                            <span class="caption-subject font-white bold uppercase"> PDF</span>
                                            <i class="fa fa-download"></i>
                                        </a>
                                        <a class="btn btn-info downloadExcelAttendance">
                                            <span class="caption-subject font-white bold uppercase"> Excel</span>
                                            <i class="fa fa-download"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <table class="table-100 table table-striped table-bordered table-hover order-column responsive"
                                   id="attendance">
                                <thead>
                                <tr>
                                    <th class="all"> UserPin</th>
                                    <th class="min-tablet"> UserName</th>
                                    <th class="min-tablet"> Verify Mode</th>
                                    <th class="min-tablet"> DeviceSN</th>
                                    <th class="min-tablet"> Check In</th>
                                    <th class="min-tablet"> CheckInOut</th>

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
    {!! HTML::script('admin/global/plugins/select2/select2.js') !!}
    {!! HTML::script('admin/global/plugins/jquery-easypiechart/jquery.easypiechart.min.js') !!}
    {!! HTML::script('admin/global/plugins/bootstrap-daterangepicker/daterangepicker.js') !!}
    {!! HTML::script('admin/global/plugins/counterup/jquery.counterup.js') !!}
    {!! HTML::script('admin/global/plugins/counterup/jquery.waypoints.min.js') !!}
    {!! HTML::script('admin/global/plugins/bootstrap-select/js/bootstrap-select.min.js') !!}
    {!! HTML::script('admin/pages/scripts/components-bootstrap-select.min.js') !!}
    {!! HTML::script('admin/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js') !!}

    {!! HTML::script('admin/global/scripts/datatable.js') !!}
    {!! HTML::script('admin/pages/scripts/table-datatables-managed.js') !!}
    {!! HTML::script('admin/global/plugins/datatables/datatables.min.js') !!}
    {!! HTML::script('admin/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js') !!}

    <script>
        $('#reportrange_attendance').daterangepicker({
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
                $('#reportrange_attendance span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            }
        );

        $('.timepicker-default').timepicker({
            autoclose: true,
            showSeconds: true,
            minuteStep: 1
        });

        $('.timepicker-no-seconds').timepicker({
            autoclose: true,
            minuteStep: 5,
            defaultTime: false
        });
        $('.timepicker').parent('.input-group').on('click', '.input-group-btn', function (e) {
            e.preventDefault();
            $(this).parent('.input-group').find('.timepicker').timepicker('showWidget');
        });
    </script>

    <script>
        $('#save-form').click(function () {
            var type = $("#type option:selected").val();
            var device = $("#device option:selected").val();
            var dateRange = $('#reportrange_attendance span').html();
            $.easyAjax({
                url: "{{route('device.attendance-report.store')}}",
                container: '#createAttendanceReport',
                type: "POST",
                data: {date_range: dateRange, device: device, type: type, _token: '{{csrf_token()}}'},
                success: function (res) {
                    if (res.status = 'success') {
                        $('#input_days').removeClass('has-error');
                        $('#count').attr('data-value', res.total);
                        $('#heading').html(res.report);
                        $('#easyStats').css('display', 'block');
                        $('#attendanceDataTable').css('display', 'block');
                        load_data_table(res.deviceId,res.type, res.start_date, res.end_date);
                        $('.counter').counterUp();
                    }
                }
            });
        });
    </script>
    <script>
        function load_data_table(deviceId,id, s, e) {
            var table = $('#attendance');
            var url = "{{route('device.attendance-report.ajax-create',['#deviceId','#id','#s','#e'])}}";
            url = url.replace('#deviceId', deviceId);
            url = url.replace('#id', id);
            url = url.replace('#s', s);
            url = url.replace('#e', e);
            // begin first table
            table.DataTable({
                "sAjaxSource": url,
                bDestroy:true,
                "aoColumns": [
                    {data: 'user_pin', name: 'user_pin'},
                    {data: 'user_name', name: 'user_name'},
                    {data: 'verify_mode', name: 'verify_mode'},
                    {data: 'serial_num', name: 'serial_num'},
                    {data: 'check_in', name: 'check_in'},
                    {data: 'check_in_out', name: 'check_in_out'},
                ]
            });
        }
    </script>
@stop
