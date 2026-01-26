
<div class="table-responsive">
    <table class="table table-bordered table-striped" id="users">
        <thead>
        <tr>
            <th>{{ __('First Name') }}</th>
            <th>{{ __('Middle Name') }}</th>
            <th>{{ __('Last Name') }}</th>
            <th>{{ __('Purchase Date') }}</th>
            <th>{{ __('Join Date') }}</th>
            <th>{{ __('Purchase Amount') }}</th>
            <th>{{ __('Amount to be Paid') }}</th>
            <th>{{ __('Discount') }}</th>
            <th>{{ __('Locker Num') }}</th>
            <th>{{ __('Remarks') }}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($subscription as $key =>$s)
            <tr>
                <td>{{ ucfirst( $s->first_name) }}</td>
                <td>{{ ucfirst( $s->middle_name) }}</td>
                <td>{{ ucfirst( $s->last_name) }}</td>
                <td>{{ $s->purchase_date }}</td>
                <td>{{ $s->start_date }}</td>
                <td>{{ $s->purchase_amount }}</td>
                <td>{{ $s->amount_to_be_paid }}</td>
                <td>{{ $s->discount }}</td>
                <td>{{ $s->locker_num }}</td>
                <td>{{ $s->remarks }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
