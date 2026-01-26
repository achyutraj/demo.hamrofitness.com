<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{$type}} Subscription Report</title>
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
    <h3 style="text-align:left;text-transform: uppercase">{{$type}} Subscription Report from {{$sd}} To {{$ed}}</h3>

    <table class="table table-striped">
        <thead>
        <tr>
            <th class="all"> Client Name </th>
            <th class="min-tablet"> Title </th>
            <th class="min-tablet"> Purchase Amount </th>
            <th class="min-tablet"> Start Date </th>
            <th class="min-tablet"> Expire Date </th>
        </tr>
        </thead>
        <tbody>
        @foreach($booking as $d)
            <tr>
                <td>{{$d->first_name }} {{ $d->middle_name }} {{ $d->last_name }} </td>
                <td> {{$d->membership}} </th>
                <td> NPR {{$d->amount_to_be_paid}} </td>
                <td> {{date('M d, Y',strtotime($d->start_date))}} </td>
                <td> {{!is_null($d->expires_on) ? date('M d, Y',strtotime($d->expires_on)) : ''}} </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
<div class="col-md-1"></div>
</body>
</html>
