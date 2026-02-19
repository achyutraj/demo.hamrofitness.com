@extends('layouts.gym-merchant.gymbasic')

@section('CSS')
    <link rel="stylesheet" href="{{ asset("fitsigma_customer/bower_components/calendar/dist/fullcalendar.css") }}">
@stop

@section('content')
    <div class="container-fluid"  >
        <!-- BEGIN PAGE BREADCRUMBS -->
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <a href="{{ route('gym-admin.dashboard.index') }}">Home</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>Attendance</span>
            </li>
        </ul>
        <!-- END PAGE BREADCRUMBS -->
        <!-- BEGIN PAGE CONTENT INNER -->

        <div class="page-content-inner">
            <div class="row">
                <div class="col-sm-12">
                    <div class="white-box">
                        <h3 class="box-title m-b-0"><i class="zmdi zmdi-calendar-check"></i> Attendance</h3>
                        <div id="calendar"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END PAGE CONTENT INNER -->
    </div>

@endsection

@section('footer')
    <script src="{{ asset("fitsigma_customer/bower_components/moment/moment.js") }}"></script>
    <script src="{{ asset("fitsigma_customer/bower_components/calendar/dist/fullcalendar.min.js") }}"></script>
    <script src="{{ asset("fitsigma_customer/bower_components/calendar/jquery-ui.min.js") }}"></script>
    <script>
        $('#calendar').fullCalendar({ //re-initialize the calendar
            defaultView: 'month', // change default view with available options from http://arshaw.com/fullcalendar/docs/views/Available_Views/
            editable: false,
            droppable: false, // this allows things to be dropped onto the calendar !!!
            events: [
                    @foreach($attendance as $att)
                {
                    title: "CheckIn",
                    start: new Date('{{Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$att->check_in)->format('F M d Y H:i:s ')}} GMT+0545 (IST)'),
                    end: new Date('{{Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$att->check_in)->format('F M d Y H:i:s ')}} GMT+0545 (IST)')
                },
                @if(!is_null($att->check_out))
                {
                    title: "CheckOut",
                    start: new Date('{{Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$att->check_out)->format('F M d Y H:i:s ')}} GMT+0545 (IST)'),
                    end: new Date('{{Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$att->check_out)->format('F M d Y H:i:s ')}} GMT+0545 (IST)')
                },
                @endif
                @endforeach
            ]
        });
    </script>
@endsection