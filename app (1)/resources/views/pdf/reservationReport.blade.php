<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Locker Reservation Report</title>
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
    <h3 style="text-align:left;text-transform: uppercase"> Locker Reservation Report from {{$sd}} To {{$ed}}</h3>

    <table class="table table-striped">
        <thead>
        <tr>
            <th class="all"> Client  </th>
            <th class="all"> Locker </th>
            <th class="min-tablet"> Paid Amount </th>
            <th class="min-tablet"> Start Date </th>
            <th class="min-tablet"> Expire On </th>
        </tr>
        </thead>
        <tbody>
        @foreach($booking as $d)
            <tr>
                <td>{{$d->client->fullName ?? '' }} </td>
                <td>{{$d->locker->locker_num ?? '' }} </td>
                <td> NPR {{$d->paid_amount}} </td>
                <td> {{$d->start_date->toFormattedDateString()}} </td>
                <td> {{$d->end_date->toFormattedDateString()}} </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
<div class="col-md-1"></div>
</body>
</html>
