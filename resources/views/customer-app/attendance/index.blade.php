@extends('layouts.customer-app.basic')

@section('title')
HamroFitness | Customer Attendance
@endsection

@section('CSS')
<link rel="stylesheet" href="{{ asset("fitsigma_customer/bower_components/calendar/dist/fullcalendar.css") }}">
@endsection

@section('content')
    <div class="row bg-title">
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title">Attendance</h4>
        </div>
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li>Main Menu</li>
                <li class="active">Attendance</li>
            </ol>
        </div>
        <!-- /.col-lg-12 -->
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="white-box">
                <h3 class="box-title m-b-0"><i class="zmdi zmdi-calendar-check"></i> Attendance</h3>
                <div id="calendar"></div>
            </div>
        </div>
    </div>
@endsection

@section('JS')
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
                start: new Date('{{Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$att->check_in)->format('F M d Y H:i:s ')}} GMT+0530 (IST)'),
                end: new Date('{{Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$att->check_in)->format('F M d Y H:i:s ')}} GMT+0530 (IST)')
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
