@extends('layouts.gym-merchant.gymbasic')

@section('CSS')
<link rel="stylesheet" href="{{ asset("admin/global/plugins/bootstrap-datepicker/css/datepicker.css") }}">
<link rel="stylesheet" href="{{ asset("admin/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css") }}">
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
                <span>Profit/Loss Sheet</span>
            </li>
        </ul>
        <!-- END PAGE BREADCRUMBS -->
        <!-- BEGIN PAGE CONTENT INNER -->
        <div class="page-content-inner">
            <div class="row">
                <div class="col-md-12 col-xs-12">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="m-heading-1 border-green m-bordered">
                                <h3>Note</h3>
                                <p>Here, negative (-) represent loss </p>
                            </div>
                        </div>
                    </div>
                    <div class="portlet light portlet-fit">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="icon-note font-red"></i>
                                <span class="caption-subject font-red bold uppercase">Profit/Loss Statement</span>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <form action="{{ route('gym-admin.profit-loss-report.index')}}" method="GET">
                                <div class="form-body"> 
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="input-group left-addon right-addon">
                                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                    <input type="text" class="form-control date-picker" readonly
                                                           id="date" name="date" value="{{ $date }}">
                                                </div>
                                            </div>  
                                        </div>
                                        <div class="col-md-6">
                                            <div class="col-md-12">
                                                <button type="submit" class="btn dark mt-ladda-btn ladda-button" data-style="zoom-in" id="save-form">
                                                    <span class="ladda-label"> Submit</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>  
                                </div> 
                            </form>   
                            <div class="row">
                                <div class="col-md-8">
                                    <table style="width: 100%" class="table table-striped table-bordered table-hover order-column" id="measurement_table">
                                        <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th> Payment Method</th>
                                            <th> Transaction Type</th>
                                            <th> Total</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td colspan="4"> Opening Balance of {{ $date }} : {{ $gymSettings->currency->acronym }} {{$opening_balance}} </td>
                                            </tr>
                                            @foreach($membershipAmounts as $amount)
                                            <tr>
                                                <td> {{$amount->payment_date->toFormattedDateString()}} </td>
                                                <td>
                                                    {{getPaymentTypeForReport($amount->payment_source)}}
                                                </td>
                                                <td> Membership Payment </td>
                                                <td> {{ $gymSettings->currency->acronym }} {{$amount->payment_amount}} </td>
                                            </tr>
                                            @endforeach
                                            
                                            @foreach($productAmounts as $amt)
                                            <tr>
                                                <td> {{$amt->payment_date->toFormattedDateString()}} </td>
                                                <td>
                                                    {{getPaymentTypeForReport($amt->payment_source)}}
                                                </td>
                                                <td> Product Payment </td>
                                                <td> {{ $gymSettings->currency->acronym }} {{$amt->payment_amount}} </td>
                                            </tr>
                                            @endforeach
                                            
                                            @foreach($expenses as $expense)
                                            <tr>
                                                <td> {{ date('M d, Y',strtotime($expense->purchase_date))}} </td>
                                                <td> </td>
                                                <td> Expense </td>
                                                <td> {{ $gymSettings->currency->acronym }} {{$expense->price}} </td>
                                            </tr>
                                            @endforeach
                                            <tr>
                                                <td colspan="4"> Closing Balance : {{ $gymSettings->currency->acronym }} {{$closing_balance}} </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END PAGE CONTENT INNER -->
    </div>
@stop

@section('footer')
<script src="{{ asset("admin/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js") }}"></script>
<link rel="stylesheet" href="{{ asset("admin/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css") }}">
<script src="{{ asset("admin/pages/scripts/components-date-time-pickers.min.js") }}"></script>
<script>
    $('.date-picker').datepicker({
        rtl: App.isRTL(),
        orientation: "left",
        endDate: new Date(),
        autoclose: true
    });
</script>
@stop

