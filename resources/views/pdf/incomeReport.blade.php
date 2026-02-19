<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Income Report</title>
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
    <h3 style="text-align:left;text-transform: uppercase"> Income Report from {{$sd}} To {{$ed}}</h3>

    <table class="table table-striped">
        <thead>
        <tr>
            <th> Item Category </th>
            <th> Purchase At </th>
            <th> Paid By </th>
            <th> Price </th>
            <th> Payment Source </th>
            <th> Remarks </th>
        </tr>
        </thead>
        <tbody>
        @foreach($data as $d)
            <tr>
                <td>{{$d->category->title ?? null }}</td>
                <td> {{date('M d, Y',strtotime($d->purchase_date))}} </td>
                <td> {{$d->supplier->name ?? ''}} </td>
                <td> NPR {{$d->price}} </td>
                <td>
                    {{getPaymentTypeForReport($d->payment_source)}}
                </td>
                <td> {{$d->remarks}} </td>

            </tr>
        @endforeach
        </tbody>
    </table>
</div>
<div class="col-md-1"></div>
</body>
</html>
