@extends('gym-admin.message.index')
@section('inbox')
    <div class="inbox-header">
        <h1 class="pull-left">Inbox</h1>
    </div>
    <div class="inbox-content">
        <a href="javascript:;" class="btn red view-btn btn-block view-employee-messages">Employee Conversations</a>
        <a href="javascript:;" class="btn red view-btn btn-block view-customer-messages">Customer Conversations</a>
    </div>
@endsection

@push('detail-scripts')
    <script>
        $('a.view-customer-messages').on('click', function () {
            window.location = "{{ url('gym-admin/messages/customer') }}";
        });
        $('a.view-employee-messages').on('click', function () {
            window.location = "{{ url('gym-admin/messages/employee') }}";
        });
    </script>
@endpush