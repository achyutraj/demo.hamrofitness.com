<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Bank Ledger Report</title>
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
    <h3 style="text-align:left;text-transform: uppercase"> Bank Ledger Report from {{$sd}} To {{$ed}}</h3>

    <table class="table table-striped">
        <thead>
        <tr>
            <th> Bank Account </th>
            <th> Transaction Type </th>
            <th> Transaction Method </th>
            <th> Date </th>
            <th> Amount </th>
            <th> Remarks </th>
        </tr>
        </thead>
        <tbody>
        @foreach($data as $d)
            <tr>
                <td>{{$d->name }} <br> {{$d->account_number }}</td>
                <td> {{$d->transaction_type}} </td>
                <td> {{$d->transaction_metdod}} </td>
                <td> {{$d->date}} </td>
                <td> NPR {{$d->amount}} </td>
                <td> {{$d->remarks}} </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
<div class="col-md-1"></div>
</body>
</html>
