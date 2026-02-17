<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Payroll Report</title>
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
    <h3 style="text-align:left;text-transform: uppercase"> Payroll Report from {{$sd}} To {{$ed}}</h3>

    <table class="table table-striped">
        <thead>
        <tr>
            <th class="desktop"> Date</th>
            <th class="desktop"> Employ Name</th>
            <th class="desktop"> Salary</th>
            <th class="desktop"> Allowance</th>
            <th class="desktop"> Deduction</th>
            <th class="desktop"> Net Pay</th>
        </tr>
        </thead>
        <tbody>
        @foreach($data as $d)
            <tr>
                <td>{{ date('M d, Y',strtotime($d->created_at)) }}</td>
                <td>{{$d->employes->fullName}}</td>
                <td>NPR {{$d->salary}}</td>
                <td>NPR {{$d->allowance}}</td>
                <td>NPR {{$d->deduction}}</td>
                <td>NPR {{$d->total}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
<div class="col-md-1"></div>
</body>
</html>
