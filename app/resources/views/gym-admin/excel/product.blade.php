
<div class="table-responsive">
    <table class="table table-bordered table-striped" id="users">
        <thead>
        <tr>
            <th>{{ __('Name') }}</th>
            <th>{{ __('Brand Name') }}</th>
            <th>{{ __('Supplier') }}</th>
            <th>{{ __('Price') }}</th>
            <th>{{ __('Expiry Date') }}</th>
            <th>{{ __('Total Quantity') }}</th>
            <th>{{ __('InStock Quantity') }}</th>
            <th>{{ __('Sold Quantity') }}</th>
            <th>{{ __('Expired Quantity') }}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($products as $key =>$product)
            <tr>
                <td>{{ ucfirst($product->name) }}</td>
                <td>{{$product->brand_name}}</td>
                <td>{{$product->suppliers->name ?? ''}}</td>
                <td>NPR {{$product->price}}</td>
                <td>{{ $product->expire_date ?? '---'}}</td>
                <td>{{$product->quantity}}</td>
                <td> {{$product->quantity - ($product->quantity_expired + $product->quantity_sold)}}</td>
                <td> {{$product->quantity_sold}} </td>
                <td> {{$product->quantity_expired}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
