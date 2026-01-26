
<div class="table-responsive">
    <table class="table table-bordered table-striped" id="users">
        <thead>
        <tr>
            <th>{{ __('First Name') }}</th>
            <th>{{ __('Middle Name') }}</th>
            <th>{{ __('Last Name') }}</th>
            <th>{{ __('Membership') }}</th>
            <th>{{ __('Purchase Date') }}</th>
            <th>{{ __('Join Date') }}</th>
            <th>{{ __('Purchase Amount') }}</th>
            <th>{{ __('Remain Amount') }}</th>
            <th>{{ __('Discount') }}</th>
            <th>{{ __('Due Payment Date') }}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($dues as $key =>$s)
            <tr>
                <td>{{ ucfirst( $s->first_name) }}</td>
                <td>{{ ucfirst( $s->middle_name) }}</td>
                <td>{{ ucfirst( $s->last_name) }}</td>
                <td>{{ $s->membership }}</td>
                <td>{{ $s->purchase_date ?? '---' }}</td>
                <td>{{ $s->start_date ?? '---' }}</td>
                <td>{{ $s->amount_to_be_paid }}</td>
                <td>{{ $s->amount_to_be_paid - $s->paid}}</td>
                <td>{{ $s->discount }}</td>
                <td>{{ $s->due_date ?? '---' }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
