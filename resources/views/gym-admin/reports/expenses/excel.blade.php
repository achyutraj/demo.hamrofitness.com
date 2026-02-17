
<div class="table-responsive">
   <table class="table table-bordered table-striped" id="users">
            <thead>
            <tr>
                <th> Item Category </th>
                @if($type == 'expense')
                <th> Item Name </th>
                @endif
                <th> Purchase At </th>
                @if($type == 'expense')
                <th> Supplier </th>
                @else
                <th> Paid By </th>
                @endif
                <th> Price </th>
                @if($type == 'expense')
                <th> Payment Status </th>
                @endif
                <th> Payment Source </th>
                <th> Remarks</th>
            </tr>
            </thead>
            <tbody>
            @foreach($data as $s)
                <tr>
                    <td>{{ $s->category->title ?? null }}</td>
                    @if($type == 'expense')
                    <td>{{ $s->item_name }}</td>
                    @endif
                    <td>{{ date('M d, Y',strtotime($s->purchase_date)) }} </td>
                    <td>{{ $s->supplier->name ?? ''}}</td>
                    <td>NPR {{ $s->price}}</td>
                    @if($type == 'expense')
                        <td> {{ucfirst($s->payment_status)}} </td>
                    @endif
                    <td>
                        {{getPaymentTypeForReport($s->payment_source)}}
                    </td>
                    <td>{{ $s->remarks}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
</div>
