@extends('gym-admin.sms.index')
@section('sms')
    <div class="inbox-header">
        <h2 class="pull-left">SMS</h2>
        <h4 class="pull-right">SMS Credit: {{$credit_balance}}</h4>
    </div>
    <div class="inbox-content">
        @if($user->is_admin == 1)
        <a href="javascript:;" data-title="View" class="btn info view-btn btn-block view-admin-smses">
            View All SMS sent to Branch Manager </a>
        @endif
        <a href="javascript:;" data-title="View" data-url="customer" class="btn red view-btn btn-block view-customer-smses">
            View All SMS sent to Customers </a>
        <a href="javascript:;" data-title="View" class="btn green view-btn btn-block view-employee-smses">
            View All SMS sent to Employees </a>
    </div>
@endsection

@push('detail-scripts')
    <script>
        $('a.view-customer-smses').on('click', function () {
            window.location = "{{ url('gym-admin/sms/customers') }}";
        });
        $('a.view-admin-smses').on('click', function () {
            window.location = "{{ url('gym-admin/sms/admins') }}";
        });
        $('a.view-employee-smses').on('click', function () {
            window.location = "{{ url('gym-admin/sms/employees') }}";
        });
    </script>
@endpush
