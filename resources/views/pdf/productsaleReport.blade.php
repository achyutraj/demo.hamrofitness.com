<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Product Sale Report</title>
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
<div class="col-md-1"></div>
<div class="col-md-10">
    <h3 style="text-align:left;text-transform: uppercase"> Product Sale Report from {{$sd}} To {{$ed}}</h3>

    <table class="table table-striped">
        <thead>
        <tr>
            <th> Customer Name </th>
            <th> Customer Type </th>
            <th> Product Name </th>
            <th> Purchased At </th>
            <th> Total Price </th>
        </tr>
        </thead>
        <tbody>
        @foreach($data as $product)
            @php
                $arr['product_name'] = json_decode($product->product_name,true);
                $arr['product_amount'] = json_decode($product->product_amount,true);
                $total = array_sum($arr['product_amount']);
                $j= count($arr['product_name']);
            @endphp
            <tr>
                <td>{{$product->customer_name}}</td>
                <td>{{$product->customer_type}}</td>
                <td>
                    @for($i=0;$i<$j;$i++)
                            <?php
                            $product_name = App\Models\Product::find($arr['product_name'][$i]);
                            ?>
                        {{ ucfirst($product_name->name) }} <br>
                    @endfor
                </td>
                <td>
                    {{$product->created_at->format('Y-m-d')}}
                </td>
                <td>
                    NPR {{$total}}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
<div class="col-md-1"></div>
</body>
</html>
