<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ucfirst($type)}} Report</title>
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
    <h3 style="text-align:left;text-transform: uppercase"> {{ucfirst($type)}} Report from {{$sd}} To {{$ed}}</h3>

    <table class="table table-striped">
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
<div class="col-md-1"></div>
</body>
</html>
