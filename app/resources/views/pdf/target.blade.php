<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Target Report</title>
    <style>
        table, tr {
            border: 1px solid #0a0a0a;
        }

        table, th, td {
            text-align: center;
            padding: 10px;
        }
    </style>
</head>
<body>
@if($type == 'membership')
    <div class="col-md-3"></div>
    <div class="col-md-6">
        <h3 style="text-align:left"> {{$heading}} Target from {{$startDate}} To {{$endDate}}</h3>
        <table class="table table-striped">
            <thead>
            <tr>
                <th>Name</th>
                <th>Membership</th>
                <th>Payment Amount</th>
                <th>Date</th>
            </tr>
            </thead>
            <tbody>
            @foreach($sales as $sale)
                <tr>
                    <td>{{$sale->first_name. ' ' .$sale->middle_name. ' ' .$sale->last_name }}</td>
                    <td>{{$sale->title}}</td>
                    <td>NPR {{$sale->payment_amount}}</td>
                    <td>{{$sale->payment_date->toFormattedDateString()}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="col-md-3"></div>
@endif

@if($type == 'product')
    <div class="col-md-3"></div>
    <div class="col-md-6">
        <h2 style="text-align:left"> {{$heading}} Target from {{$startDate}} To {{$endDate}}</h2>
        <table class="table table-striped">
            <thead>
            <tr>
                <th>Name</th>
                <th>Product</th>
                <th>Payment Amount</th>
                <th>Date</th>
            </tr>
            </thead>
            <tbody>
            @foreach($sales as $sale)
                @php
                    $arr['product_name'] = json_decode($sale->product_name,true);
                    $arr['product_quantity'] = json_decode($sale->product_quantity,true);
                    $j= count($arr['product_name']);
                @endphp
                <tr>
                    <td>{{$sale->first_name. ' ' .$sale->middle_name. ' ' .$sale->last_name }}</td>
                    <td>
                        @for($i=0;$i<$j;$i++)
                                <?php
                                $product_name = App\Models\Product::find($arr['product_name'][$i]);
                                ?>
                            {{ ucfirst($product_name->name) }} ,Qty: {{ $arr['product_quantity'][$i] }} <br>
                        @endfor
                    </td>
                    <td>NPR {{$sale->payment_amount}}</td>
                    <td>{{ date('M d, Y',strtotime($sale->payment_date)) }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="col-md-3"></div>
@endif
</body>
</html>
