
<div class="table-responsive">
    <table class="table table-bordered table-striped" id="users">
        <thead>
        <tr>
            <th>{{ __('Customer Name') }}</th>
            <th>{{ __('Customer Type') }}</th>
            <th>{{ __('Product Name') }}</th>
            <th>{{ __('Purchased At') }}</th>
            <th>{{ __('Total Price') }}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($data as $key =>$s)
            @php
                  $arr['product_name'] = json_decode($s->product_name,true);
                  $arr['product_amount'] = json_decode($s->product_amount,true);
                  $total = array_sum($arr['product_amount']);
                  $j= count($arr['product_name']);
            @endphp
            <tr>
                <td>{{ ucfirst( $s->customer_name) }}</td>
                <td>{{ $s->customer_type }}</td>
                <td>
                    @for($i=0;$i<$j;$i++)
                            <?php
                            $product_name = App\Models\Product::find($arr['product_name'][$i]);
                            ?>
                        {{ ucfirst($product_name->name) }} <br>
                    @endfor
                </td>
                <td>{{ $s->created_at->format('Y-m-d') }}</td>
                <td>NPR {{ $total }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
