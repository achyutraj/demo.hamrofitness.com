<div class="form-group form-md-line-input ">
    <select  class="form-control" name="product_sale_id" id="product_sale_id">
        <option selected disabled>Select Product Sale</option>
        @forelse($purchases as $purc)
        @php
            $product_name = '';
            $arr['product_name'] = json_decode($purc->product_name,true);
            $j= count($arr['product_name']);
            for($i=0;$i<$j;$i++){
                $product_name .= App\Models\Product::find($arr['product_name'][$i])->name.',';
            }
            
        @endphp
            <option data-price="{{ $purc->diff }}" @if(isset($payment) && $purc->id == $payment->product_sale_id) selected @endif value="{{$purc->id}}">
                {{ $product_name }} - [Purchased on: {{$purc->created_at->format('d-M')}}]</option>
        @empty
            <option value="">No product purchase by this client</option>
        @endforelse
    </select>
    <label for="title">Payment For</label>
    <span class="help-block"></span>
</div>