
<div class="table-responsive">
    <table class="table table-bordered table-striped" id="users">
        <thead>
        <tr>
            <th>{{ __('First Name') }}</th>
            <th>{{ __('Middle Name') }}</th>
            <th>{{ __('Last Name') }}</th>
            <th>{{ __('Product Name') }}</th>
            <th>{{ __('Purchase Date') }}</th>
            <th>{{ __('Total Amount') }}</th>
            <th>{{ __('Remain Amount') }}</th>
            <th>{{ __('Due Payment Date') }}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($dues as $key =>$s)
            @php
                $data = '';
                $arr['product_name'] = json_decode($s->product_name,true);
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
                <td>{{ ucfirst( $s->first_name) }}</td>
                <td>{{ ucfirst( $s->middle_name) }}</td>
                <td>{{ ucfirst( $s->last_name) }}</td>
                <td>{{ $data }}</td>
                <td>{{ $s->created_at ?? '---' }}</td>
                <td>{{ $s->total_amount }}</td>
                <td>{{ $s->total_amount - $s->paid_amount}}</td>
                <td>{{ $s->next_payment_date ?? '---' }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
