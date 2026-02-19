
<div class="table-responsive">
    <table class="table table-bordered table-striped" id="users">
        <thead>
        <tr>
            <th>{{ __('First Name') }}</th>
            <th>{{ __('Middle Name') }}</th>
            <th>{{ __('Last Name') }}</th>
            @if ($type == 'membership')
            <th>{{ __('Membership') }}</th>
            @else
            <th>{{ __('Product') }}</th>
            @endif
            <th>{{ __('Payment Amount') }}</th>
            <th>{{ __('Date') }}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($data as $key =>$s)
            <tr>
                <td>{{ ucfirst( $s->first_name) }}</td>
                <td>{{ ucfirst( $s->middle_name) }}</td>
                <td>{{ ucfirst( $s->last_name) }}</td>
                @if ($type == 'membership')
                    <td>{{ $s->title }}</td>
                @else
                    @php
                        $arr['product_name'] = json_decode($s->product_name,true);
                        $arr['product_quantity'] = json_decode($s->product_quantity,true);
                        $j= count($arr['product_name']);
                    @endphp
                    <td>
                        @for($i=0;$i<$j;$i++)
                            <?php
                            $product_name = App\Models\Product::find($arr['product_name'][$i]);
                            ?>
                            {{ ucfirst($product_name->name) }} ,Qty: {{ $arr['product_quantity'][$i] }} <br>
                        @endfor
                    </td>
                @endif
                <td>NPR {{ $s->payment_amount }}</td>
                <td>{{ $s->payment_date->format('Y-m-d') }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
