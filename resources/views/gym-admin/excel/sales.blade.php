
<div class="table-responsive">
    <table class="table table-bordered table-striped" id="users">
        <thead>
        <tr>
            <th>{{ __('First Name') }}</th>
            <th>{{ __('Middle Name') }}</th>
            <th>{{ __('Last Name') }}</th>
            <th>{{ __('Purchase Date') }}</th>
            <th>{{ __('Total Amount') }}</th>
            <th>{{ __('Amount to be Paid') }}</th>
            <th>{{ __('Product') }}</th>
            <th>{{ __('Discount') }}</th>
            <th>{{ __('Quantitiy') }}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($subscription as $key =>$s)
            @php
                $data = '';
                $qty = '';
                $discount = '';

                $arr['product_name'] = json_decode($s->product_name,true);
                $arr['qty'] = json_decode($s->product_quantity,true);
                $arr['discount'] = json_decode($s->product_discount,true);
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

                for($i=0; $i < count($arr['qty']) ;$i++){
                    if($i == 0){
                        $qty = $arr['qty'][$i];
                    }else{
                        $qty .= ', ' . $arr['qty'][$i];
                    }
                }

                for($i=0; $i < count( $arr['discount']) ;$i++){
                    if($i == 0){
                        $discount = $arr['discount'][$i];
                    }else{
                        $discount .= ', '.$arr['discount'][$i];
                    }
                }
            @endphp
            <tr>
                <td>{{ ucfirst( $s->first_name) }}</td>
                <td>{{ ucfirst( $s->middle_name) }}</td>
                <td>{{ ucfirst( $s->last_name) }}</td>
                <td>{{ $s->created_at }}</td>
                <td>{{ $s->total_amount }}</td>
                <td>{{ $s->total_amount - $s->paid_amount }}</td>
                <td>{{ $data }}</td>
                <td>{{ $discount }}</td>
                <td>{{ $qty }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
