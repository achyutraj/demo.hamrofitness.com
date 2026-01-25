
<div class="table-responsive">
    <table class="table table-bordered table-striped" id="users">
        <thead>
        <tr>
            <th>{{ __('First Name') }}</th>
            <th>{{ __('Middle Name') }}</th>
            <th>{{ __('Last Name') }}</th>
            @if($id=='all' || $id=='allProduct' || $id=='lockerPayments')
            <th>{{ __('Amount') }}</th>
            <th>{{ __('Payment Method') }}</th>
            <th>{{ __('Date') }}</th>
            <th>{{ __('Remarks') }}</th>
            @endif
            @if($id=='dueProducts')
                <th>{{ __('Remaining') }}</th>
                <th>{{ __('Paid Amount') }}</th>
                <th>{{ __('Last Payment Date') }}</th>
                <th>{{ __('Customer Type') }}</th>
            @endif
            @if($id=='debtors' || $id == 'lockerDues')
                <th>{{ __('Remaining') }}</th>
                <th>{{ __('Paid Amount') }}</th>
                <th>{{ __('Last Payment Date') }}</th>
                <th>{{ __('Remarks') }}</th>
            @endif
        </tr>
        </thead>
        <tbody>
        @foreach($payment as $key =>$s)
            <tr>
                <td>{{ ucfirst( $s->first_name) }}</td>
                <td>{{ ucfirst( $s->middle_name) }}</td>
                <td>{{ ucfirst( $s->last_name) }}</td>
                @if($id=='all' || $id=='allProduct' || $id == 'lockerPayments')
                    <td>NPR {{ $s->payment_amount }}</td>
                    <td>
                        {{getPaymentTypeForReport($s->payment_source)}}
                    </td>
                    <td>{{ $s->payment_date->format('Y-m-d') }}</td>
                    <td>{{ $s->remarks }}</td>
                @endif
                @if($id=='dueProducts')
                    <td>NPR {{ ($s->amount_to_be_paid - $s->paid_amount) }}</td>
                    <td>NPR {{ $s->paid_amount }}</td>
                    <td>{{ $s->next_payment_date }}</td>
                    <td>{{ $s->customer_type }}</td>
                @endif
                @if($id=='debtors' || $id == 'lockerDues')
                    <td>NPR {{ ($s->amount_to_be_paid - $s->paid_amount) }}</td>
                    <td>NPR {{ $s->paid_amount }}</td>
                    <td>{{ $s->next_payment_date->format('Y-m-d') }}</td>
                    <td>{{ $s->remarks }}</td>
                @endif
            </tr>
        @endforeach
        </tbody>
    </table>
</div>