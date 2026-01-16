
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
            <th>{{ __('Product Name') }}</th>
            <th>{{ __('Payment Date') }}</th>
            <th>{{ __('Remarks') }}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($payments as $key =>$s)
            @php
                $data = '';
                $arr['product_name'] = json_decode($s->product_sale->product_name,true);
                for($i=0; $i < count( $arr['product_name']) ;$i++){
                    $pro = \App\Models\Product::find($arr['product_name'][$i]);
                    if($pro != null){
                        if($i == 0){
                            $data = $pro->name ?? '';
                        }else{
                            $data = $data.', '.$pro->name ?? '';
                        }
                    }
                }
            @endphp
            <tr>
                <td>{{ ucfirst( $s->client->first_name) }}</td>
                <td>{{ ucfirst( $s->client->middle_name) }}</td>
                <td>{{ ucfirst( $s->client->last_name) }}</td>
                <td>{{ $s->payment_id }}</td>
                <td>{{ $s->payment_amount }}</td>
                <td>{{getPaymentTypeForReport($s->payment_source)}}</td>
                <td>{{$data}}</td>
                <td>{{ $s->payment_date ?? '---' }}</td>
                <td>{{ $s->remarks }}</td>

            </tr>
        @endforeach
        </tbody>
    </table>
</div>
