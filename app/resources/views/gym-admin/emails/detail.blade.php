@extends('gym-admin.emails.index')
@section('email')
<div class="inbox-header">
    <h1 class="pull-left">Email</h1>
</div>
<div class="inbox-content">
    <a href="javascript:;" data-title="View" class="btn red view-btn btn-block view-customer-smses">
        View All Emails sent to Customers </a>
    <!-- <a href="javascript:;" data-title="View" class="btn red view-btn btn-block view-admin-smses">
        View All Emails sent to Admins </a> -->
    <a href="javascript:;" data-title="View" class="btn red view-btn btn-block view-employee-smses">
        View All Emails sent to Employees </a>
</div>
@endsection

@push('detail-scripts')
    <script>
        $('a.view-customer-smses').on('click', function () {
            window.location = "{{ url('gym-admin/emails/customers') }}";
        });
        $('a.view-admin-smses').on('click', function () {
            window.location = "{{ url('gym-admin/emails/admins') }}";
        });
        $('a.view-employee-smses').on('click', function () {
            window.location = "{{ url('gym-admin/emails/employees') }}";
        });
    </script>
@endpush
