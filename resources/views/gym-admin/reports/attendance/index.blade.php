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
                <span>Attendance Report</span>
            </li>
        </ul>
        <!-- END PAGE BREADCRUMBS -->
        <!-- BEGIN PAGE CONTENT INNER -->
        <div class="page-content-inner">
            {{--Info Section --}}
            <div class="row">
                <div class="col-md-12">
                    <div class="m-heading-1 border-green m-bordered">
                        <h3>Attendance Report</h3>

                        <p>This report section provides you features like: </p>
                        <ul>
                            <li>List the attendance of Clients.</li>
                            <li>List the attendance of Employees.</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="portlet light portlet-fit">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-clock-o font-red"></i><span class="caption-subject font-red bold uppercase">Attendance Report</span>
                    </div>
                    <div class="pull-right">
                        <div class="btn-group">
                             <a href="{{ route('device.adms.logs') }}" class="btn btn-success">
                                <i class="fa fa-cogs"></i>
                                <span class="hide-menu">ADMS Real-time Sync</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="row">
                        <div class="col-md-offset-2 col-md-5">
                            {!! Form::open(['id'=>'createAttendanceReport','class'=>'ajax-form']) !!}
                            <div class="form-body">
                                <div class="form-group form-md-line-input ">
                                    <select class="bs-select form-control" data-live-search="true" data-size="8"
                                            name='type' id="type" required>
                                        <optgroup label="Business Analytics">
                                            <option value="regular_active_client">Regular Active Clients</option>
                                            <option value="irregular_active_client">Irregular Active Clients (High Absenteeism)</option>
                                            <option value="high_attendance">High Attendance Clients</option>
                                        </optgroup>
                                        <optgroup label="General Reports">
                                            @if(count($customers) > 0)
                                                <option value="client">All Customers</option>
                                            @endif
                                            @if(count($employees) > 0)
                                                <option value="employ">All Employees</option>
                                            @endif
                                        </optgroup>
                                        <optgroup label="Individual Customer Reports">
                                            @foreach($customers as $customer)
                                                <option value="customer|{{ $customer->id }}">{{ $customer->first_name.' '.$customer->middle_name.' '.$customer->last_name }}</option>
                                            @endforeach
                                        </optgroup>
                                        <optgroup label="Individual Employee Reports">
                                            @foreach($employees as $employee)
                                                <option value="employee|{{ $employee->id }}">{{ $employee->first_name.' '.$employee->middle_name.' '.$employee->last_name }}</option>
                                            @endforeach
                                        </optgroup>
                                    </select>
                                    <label for="title">Select Attendance Report</label>
                                    <span class="help-block"></span>
                                </div>
                                <div class="form-group form-md-line-input" id="min_present_days_field" style="display:none;">
                                    <input type="number" class="form-control" name="min_present_days" id="min_present_days" min="1" value="10">
                                    <label for="min_present_days">Minimum Present Days (Required)</label>
                                    <span class="help-block">Show only clients with at least this many present days</span>
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
                                        <span class="caption-subject font-red bold uppercase"> Attendance Report</span>
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
                            <table class="table table-striped table-bordered table-hover order-column responsive"
                                   id="attendance">
                                <thead>
                                <tr>
                                    <th class="all"> Name</th>
                                    <th class="min-tablet"> Email</th>
                                    <th class="min-tablet"> Mobile</th>
                                    <th class="min-tablet"> Gender</th>
                                    <th class="min-tablet"> Check In</th>
                                    <th class="min-tablet"> Check Out</th>
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
                //minDate: '01/01/2012',
                //maxDate: '12/31/2014',
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
        // Show/hide configurable fields based on report type selection
        $('#type').on('change', function() {
            var selectedType = $(this).val();

            // Hide all configurable fields first
            $('#min_present_days_field').hide();

            // Show relevant fields based on selection
            if (selectedType === 'high_attendance') {
                $('#min_present_days_field').show();
            }
        });

        $('#save-form').click(function () {
            var type = $("#type option:selected").val();
            var dateRange = $('#reportrange_attendance span').html();
            var formData = {
                date_range: dateRange,
                type: type,
                _token: '{{csrf_token()}}'
            };

            // Add optional parameters for new report types
            if (type === 'high_attendance') {
                formData.min_present_days = $('#min_present_days').val() || 10;
            }

            $.easyAjax({
                url: "{{route('gym-admin.attendance-report.store')}}",
                container: '#createAttendanceReport',
                type: "POST",
                data: formData,
                success: function (res) {
                    if (res.status == 'success') {
                        $('#input_days').removeClass('has-error');
                        $('#count').attr('data-value', res.total);
                        $('#heading').html(res.report);
                        $('#easyStats').css('display', 'block');
                        $('#attendanceDataTable').css('display', 'block');
                        load_data_table(res.type, res.start_date, res.end_date);
                        load_attendance_data(res.type, res.start_date, res.end_date);
                        $('.counter').counterUp();
                    }
                }
            });
        });
    </script>
    <script>
        function load_attendance_data(id, s, e) {
            $('.downloadAttendance').on('click', function () {
                window.location = 'attendance-report/download/' + id + '/' + s + '/' + e;
            });
            $('.downloadExcelAttendance').on('click', function () {
                window.location = 'attendance-report/download/excel/' + id + '/' + s + '/' + e;
            });
        }
    </script>
    <script>
        function load_data_table(id, s, e) {
            var table = $('#attendance');
            var url = "{{route('gym-admin.attendance-report.ajax-create',['#id','#s','#e'])}}";
            url = url.replace('#id', id);
            url = url.replace('#s', s);
            url = url.replace('#e', e);

            // Add query parameters for configurable fields
            if (id === 'high_attendance') {
                var minPresentDays = $('#min_present_days').val() || 10;
                url += '?min_present_days=' + minPresentDays;
            }

            // Define columns based on report type
            var columns;
            var tableHeaders;

            if (id === 'regular_active_client' || id === 'high_attendance') {
                // Regular active or high attendance clients
                columns = [
                    {data: 'first_name', name: 'first_name'},
                    {data: 'email', name: 'email'},
                    {data: 'mobile', name: 'mobile'},
                    {data: 'gender', name: 'gender'},
                    {data: 'present_days', name: 'present_days'},
                    {data: 'last_attendance', name: 'last_attendance'},
                    {data: 'status', name: 'status'}
                ];

                // Update table headers
                tableHeaders = `
                    <tr>
                        <th class="all">Name</th>
                        <th class="min-tablet">Email</th>
                        <th class="min-tablet">Mobile</th>
                        <th class="min-tablet">Gender</th>
                        <th class="min-tablet">Present Days</th>
                        <th class="min-tablet">Last Attendance</th>
                        <th class="min-tablet">Status</th>
                    </tr>
                `;
            } else if (id === 'irregular_active_client') {
                // Irregular active clients with absent days
                columns = [
                    {data: 'first_name', name: 'first_name'},
                    {data: 'email', name: 'email'},
                    {data: 'mobile', name: 'mobile'},
                    {data: 'gender', name: 'gender'},
                    {data: 'present_days', name: 'present_days'},
                    {data: 'absent_days', name: 'absent_days'},
                    {data: 'last_attendance', name: 'last_attendance'},
                    {data: 'status', name: 'status'}
                ];

                // Update table headers
                tableHeaders = `
                    <tr>
                        <th class="all">Name</th>
                        <th class="min-tablet">Email</th>
                        <th class="min-tablet">Mobile</th>
                        <th class="min-tablet">Gender</th>
                        <th class="min-tablet">Present Days</th>
                        <th class="min-tablet">Absent Days</th>
                        <th class="min-tablet">Last Attendance</th>
                        <th class="min-tablet">Status</th>
                    </tr>
                `;
            } else {
                // Regular attendance reports columns
                columns = [
                    {data: 'first_name', name: 'first_name'},
                    {data: 'email', name: 'email'},
                    {data: 'mobile', name: 'mobile'},
                    {data: 'gender', name: 'gender'},
                    {data: 'check_in', name: 'check_in'},
                    {data: 'check_out', name: 'check_out'}
                ];

                // Update table headers
                tableHeaders = `
                    <tr>
                        <th class="all">Name</th>
                        <th class="min-tablet">Email</th>
                        <th class="min-tablet">Mobile</th>
                        <th class="min-tablet">Gender</th>
                        <th class="min-tablet">Check In</th>
                        <th class="min-tablet">Check Out</th>
                    </tr>
                `;
            }

            // Update table headers dynamically
            $('#attendance thead').html(tableHeaders);

            // Initialize DataTable
            table.DataTable({
                "sAjaxSource": url,
                bDestroy: true,
                "aoColumns": columns
            });
        }
    </script>
@stop
