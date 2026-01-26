
<div class="table-responsive">
    <table class="table table-bordered table-striped" id="users">
        <thead>
        <tr>
            <th>{{ __('First Name') }}</th>
            <th>{{ __('Middle Name') }}</th>
            <th>{{ __('Last Name') }}</th>
            <th>{{ __('Payment ID') }}</th>
            <th>{{ __('Payment Amount') }}</th>
            <th>{{ __('Payment Source') }}</th>
            <th>{{ __('Locker Number') }}</th>
            <th>{{ __('Payment Date') }}</th>
            <th>{{ __('Remarks') }}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($payments as $key =>$s)
            <tr>
                <td>{{ ucfirst( $s->client->first_name) }}</td>
                <td>{{ ucfirst( $s->client->middle_name) }}</td>
                <td>{{ ucfirst( $s->client->last_name) }}</td>
                <td>{{ $s->payment_id }}</td>
                <td>{{ $s->payment_amount }}</td>
                <td>{{getPaymentTypeForReport($s->payment_source)}}</td>
                <td>{{ $s->reservation->locker->locker_num ?? '---'}}</td>
                <td>{{ $s->payment_date ?? '---' }}</td>
                <td>{{ $s->remarks }}</td>

            </tr>
        @endforeach
        </tbody>
    </table>
</div>
