
<div class="table-responsive">
    <table class="table table-bordered table-striped" id="users">
        <thead>
        <tr>
            <th>{{ __('Bank Name') }}</th>
            <th>{{ __('Account No.') }}</th>
            <th>{{ __('Transaction Type') }}</th>
            <th>{{ __('Transaction Method') }}</th>
            <th>{{ __('Date') }}</th>
            <th>{{ __('Amount') }}</th>
            <th>{{ __('Remarks') }}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($data as $key =>$s)
            <tr>
                <td>{{ ucfirst( $s->name) }}</td>
                <td>{{ $s->account_number }}</td>
                <td>{{ $s->transaction_type }}</td>
                <td>{{ $s->transaction_method }}</td>
                <td>{{ $s->date->format('Y-m-d') }}</td>
                <td>NPR {{ $s->amount }}</td>
                <td>{{ $s->remarks }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
