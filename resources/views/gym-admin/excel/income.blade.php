
<div class="table-responsive">
    <table class="table table-bordered table-striped" id="users">
        <thead>
        <tr>
            <th>{{ __('Item Category') }}</th>
            <th>{{ __('Purchase At') }}</th>
            <th>{{ __('Paid By') }}</th>
            <th>{{ __('Total Amount') }}</th>
            <th>{{ __('Payment Source') }}</th>
            <th>{{ __('Remarks') }}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($incomes as $key =>$s)
            <tr>
                <td>{{ $s->category->title ?? null }}</td>
                <td>{{ $s->item_name }}</td>
                <td>{{ date('M d, Y',strtotime($s->purchase_date)) }} </td>
                <td>{{ $s->supplier->name ?? ''}}</td>
                <td>NPR {{ $s->price}}</td>
                <td> {{ucfirst($s->payment_status)}} </td>
                <td>
                    {{getPaymentTypeForReport($s->payment_source)}}
                </td>
                <td>{{ $s->remarks}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
