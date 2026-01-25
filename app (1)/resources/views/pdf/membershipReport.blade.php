<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Membership Report</title>
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
    <h3 style="text-align:left;text-transform: uppercase"> Membership Payment Report from {{$sd}} To {{$ed}}</h3>

    <table class="table table-striped">
        <thead>
        <tr>
            <th> Name</th>
            <th> Amount</th>
            <th> Source</th>
            <th> Payment Date</th>
            <th> Payment ID</th>
            <th> Payment Type</th>
        </tr>
        </thead>
        <tbody>
        @foreach($data as $d)
            <tr>
                <td>{{$d->first_name }} {{ $d->middle_name }} {{$d->last_name }}</td>
                <td> NPR {{$d->payment_amount}} </td>
                <td>
                    {{getPaymentTypeForReport($d->payment_source)}}
                </td>
                <td>{{ date('M d, Y',strtotime($d->payment_date)) }}</td>
                <td> {{$d->payment_id}} </td>
                <td> {{$d->membership}} </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
<div class="col-md-1"></div>
</body>
</html>
