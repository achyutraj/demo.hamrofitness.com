@extends('layouts.gym-merchant.gymbasic')

@section('CSS')
    {!! HTML::style('admin/global/plugins/ladda/ladda-themeless.min.css') !!}

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
                <span>Take Backup</span>
            </li>
        </ul>
        <!-- END PAGE BREADCRUMBS -->
        <!-- BEGIN PAGE CONTENT INNER -->
        <div class="page-content-inner">
            @if(session()->has('message'))
                <div class="alert alert-message alert-success">
                    {{ session()->get('message') }}
                </div>
            @endif
            <div class="row">
                <div class="col-md-offset-2 col-md-8">
                    <div class="portlet light ">
                        <div class="portlet-title">
                            <div class="caption font-dark">
                                <i class="fa fa-cloud-download font-red"></i>
                                <span class="caption-subject font-red bold uppercase"> Take Backup</span>
                            </div>
                        </div>
                        <div class="portlet-body">

                            <ul class="list-group">
                                <li class="list-group-item"> Customers Backup
                                    <a href="{{route('gym-admin.backup.getbackup',['customer'])}}">
                                    <button type="button" id="customer" style="float: right; padding: 2px 9px 2px;" data-loading-text="Loading..." class="btn blue mt-ladda-btn  ladda-button mt-progress-demo" data-style="slide-left">
                                        <span class="ladda-label" id="customerText"><i class="icon-cloud-download"></i> Download</span>
                                    </button></a>
                                </li>
                                <li class="list-group-item"> Subscriptions Backup
                                    <a href="{{route('gym-admin.backup.getbackup',['subscriptions'])}}">
                                    <button type="button" id ="subscriptions"style="float: right; padding: 2px 9px 2px;" data-loading-text="Loading..." class="btn blue mt-ladda-btn  ladda-button mt-progress-demo" data-style="slide-left">
                                        <span class="ladda-label" id="subscriptionsText"><i class="icon-cloud-download"></i> Download</span>
                                    </button></a>
                                </li>
                                <li class="list-group-item"> Memberships Backup
                                    <a href="{{route('gym-admin.backup.getbackup',['membership'])}}">
                                    <button type="button" id="membership" style="float: right; padding: 2px 9px 2px;" data-loading-text="Loading..." class="btn blue mt-ladda-btn  ladda-button mt-progress-demo" data-style="slide-left">
                                        <span class="ladda-label" id="membershipText"><i class="icon-cloud-download"></i> Download</span>
                                    </button></a>
                                </li>
                                <li class="list-group-item"> Subscription Payments Backup
                                    <a href="{{route('gym-admin.backup.getbackup',['payments'])}}">
                                    <button type="button" id="payments" style="float: right; padding: 2px 9px 2px;" data-loading-text="Loading..." class=" btn blue mt-ladda-btn  ladda-button mt-progress-demo" data-style="slide-left">
                                          <span class="  ladda-label " id="paymentsText"><i class="icon-cloud-download"></i> Download</span>
                                    </button></a>
                                </li>

                                <li class="list-group-item"> Subscription Due Backup
                                    <a href="{{route('gym-admin.backup.getbackup',['dues'])}}">
                                    <button type="button" id="dues" style="float: right; padding: 2px 9px 2px;" data-loading-text="Loading..." class=" btn blue mt-ladda-btn  ladda-button mt-progress-demo" data-style="slide-left">
                                          <span class="  ladda-label " id="duesText"><i class="icon-cloud-download"></i> Download</span>
                                    </button></a>
                                </li>
                                <li class="list-group-item"> Client Attendance Backup
                                    <a href="{{route('gym-admin.backup.getbackup',['attendance'])}}">
                                    <button type="button" id="attendance" style="float: right; padding: 2px 9px 2px;" data-loading-text="Loading..." class=" btn blue mt-ladda-btn  ladda-button mt-progress-demo" data-style="slide-left">
                                        <span class="  ladda-label" id="attendanceText"><i class="icon-cloud-download"></i> Download</span>
                                    </button></a>
                                </li>
                                <li class="list-group-item"> Employee Attendance Backup
                                    <a href="{{route('gym-admin.backup.getbackup',['employeeAttendance'])}}">
                                    <button type="button" id="empAttendance" style="float: right; padding: 2px 9px 2px;" data-loading-text="Loading..." class=" btn blue mt-ladda-btn  ladda-button mt-progress-demo" data-style="slide-left">
                                        <span class="  ladda-label" id="empAttendanceText"><i class="icon-cloud-download"></i> Download</span>
                                    </button></a>
                                </li>
                                <li class="list-group-item"> Enquiries Backup
                                    <a href="{{route('gym-admin.backup.getbackup',['enquiries'])}}">
                                    <button type="button" id="enquiries" style="float: right; padding: 2px 9px 2px;" data-loading-text="Loading..." class=" btn blue mt-ladda-btn  ladda-button mt-progress-demo" data-style="slide-left">
                                        <span class="  ladda-label" id="enquiriesText"><i class="icon-cloud-download"></i> Download</span>
                                    </button></a>
                                </li>


                                <li class="list-group-item"> Locker Backup
                                    <a href="{{route('gym-admin.backup.getbackup',['lockers'])}}">
                                    <button type="button" id="dues" style="float: right; padding: 2px 9px 2px;" data-loading-text="Loading..." class=" btn blue mt-ladda-btn  ladda-button mt-progress-demo" data-style="slide-left">
                                          <span class="  ladda-label " id="lockerText"><i class="icon-cloud-download"></i> Download</span>
                                    </button></a>
                                </li>

                                <li class="list-group-item"> Locker Reservation Backup
                                    <a href="{{route('gym-admin.backup.getbackup',['reservations'])}}">
                                    <button type="button" id="reservations" style="float: right; padding: 2px 9px 2px;" data-loading-text="Loading..." class=" btn blue mt-ladda-btn  ladda-button mt-progress-demo" data-style="slide-left">
                                          <span class="  ladda-label " id="reservationsText"><i class="icon-cloud-download"></i> Download</span>
                                    </button></a>
                                </li>

                                <li class="list-group-item"> Locker Payment Backup
                                    <a href="{{route('gym-admin.backup.getbackup',['lockerPayments'])}}">
                                    <button type="button" id="lockerPayments" style="float: right; padding: 2px 9px 2px;" data-loading-text="Loading..." class=" btn blue mt-ladda-btn  ladda-button mt-progress-demo" data-style="slide-left">
                                          <span class="  ladda-label " id="lockerPaymentsText"><i class="icon-cloud-download"></i> Download</span>
                                    </button></a>
                                </li>

                                <li class="list-group-item"> Locker Due Backup
                                    <a href="{{route('gym-admin.backup.getbackup',['lockerDues'])}}">
                                    <button type="button" id="lockerDues" style="float: right; padding: 2px 9px 2px;" data-loading-text="Loading..." class=" btn blue mt-ladda-btn  ladda-button mt-progress-demo" data-style="slide-left">
                                          <span class="  ladda-label " id="lockerDuesText"><i class="icon-cloud-download"></i> Download</span>
                                    </button></a>
                                </li>

                                <li class="list-group-item"> Product Backup
                                    <a href="{{route('gym-admin.backup.getbackup',['products'])}}">
                                    <button type="button" id="products" style="float: right; padding: 2px 9px 2px;" data-loading-text="Loading..." class=" btn blue mt-ladda-btn  ladda-button mt-progress-demo" data-style="slide-left">
                                          <span class="  ladda-label " id="productText"><i class="icon-cloud-download"></i> Download</span>
                                    </button></a>
                                </li>

                                <li class="list-group-item"> Product Sale Backup
                                    <a href="{{route('gym-admin.backup.getbackup',['productSales'])}}">
                                    <button type="button" id="productSales" style="float: right; padding: 2px 9px 2px;" data-loading-text="Loading..." class=" btn blue mt-ladda-btn  ladda-button mt-progress-demo" data-style="slide-left">
                                          <span class="  ladda-label " id="productSalesText"><i class="icon-cloud-download"></i> Download</span>
                                    </button></a>
                                </li>

                                <li class="list-group-item"> Product Payment Backup
                                    <a href="{{route('gym-admin.backup.getbackup',['productPayments'])}}">
                                    <button type="button" id="productPayments" style="float: right; padding: 2px 9px 2px;" data-loading-text="Loading..." class=" btn blue mt-ladda-btn  ladda-button mt-progress-demo" data-style="slide-left">
                                          <span class="  ladda-label " id="productPaymentsText"><i class="icon-cloud-download"></i> Download</span>
                                    </button></a>
                                </li>

                                <li class="list-group-item"> Product Due Backup
                                    <a href="{{route('gym-admin.backup.getbackup',['productDues'])}}">
                                    <button type="button" id="productDues" style="float: right; padding: 2px 9px 2px;" data-loading-text="Loading..." class=" btn blue mt-ladda-btn  ladda-button mt-progress-demo" data-style="slide-left">
                                          <span class="  ladda-label " id="productDuesText"><i class="icon-cloud-download"></i> Download</span>
                                    </button></a>
                                </li>

                                <li class="list-group-item"> Activity Log Backup
                                    <a href="{{route('gym-admin.backup.getbackup',['activity-log'])}}">
                                    <button type="button" id="activityLog" style="float: right; padding: 2px 9px 2px;" data-loading-text="Loading..." class=" btn blue mt-ladda-btn  ladda-button mt-progress-demo" data-style="slide-left">
                                          <span class="  ladda-label " id="activityLogText"><i class="icon-cloud-download"></i> Download</span>
                                    </button></a>
                                </li>

                                <li class="list-group-item"> Expense Backup
                                    <a href="{{route('gym-admin.backup.getbackup',['expenses'])}}">
                                    <button type="button" id="expenses" style="float: right; padding: 2px 9px 2px;" data-loading-text="Loading..." class=" btn blue mt-ladda-btn  ladda-button mt-progress-demo" data-style="slide-left">
                                          <span class="  ladda-label " id="expensesText"><i class="icon-cloud-download"></i> Download</span>
                                    </button></a>
                                </li>

                                <li class="list-group-item"> Other Income Backup
                                    <a href="{{route('gym-admin.backup.getbackup',['other_incomes'])}}">
                                    <button type="button" id="income" style="float: right; padding: 2px 9px 2px;" data-loading-text="Loading..." class=" btn blue mt-ladda-btn  ladda-button mt-progress-demo" data-style="slide-left">
                                          <span class="  ladda-label " id="incomeText"><i class="icon-cloud-download"></i> Download</span>
                                    </button></a>
                                </li>

                            </ul>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END PAGE CONTENT INNER -->
    </div>
@stop

@section('footer')
    {!! HTML::script('admin/global/plugins/ladda/spin.min.js') !!}
    {!! HTML::script('admin/global/plugins/ladda/ladda.min.js') !!}
    {!! HTML::script('admin/pages/scripts/ui-buttons.min.js') !!}
<script>
$(function(){
            setTimeout(function() {
                $('.alert-message').slideUp();
            }, 3000);
        });
</script>


@stop
