<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Attendance Report</title>
    <style>
        table, tr {
            border: 1px solid #0a0a0a;
        }

        table, th, td {
            text-align: center;
            padding: 5px;
        }
    </style>
</head>
<body>
    <div class="col-md-3"></div>
    <div class="col-md-6">
        <h3 style="text-align:left;text-transform: uppercase"> Attendance Report {{$id}} from {{$sd}} To {{$ed}}</h3>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th> Name </th>
                    <th> Email </th>
                    <th> Mobile </th>
                    <th> Gender </th>
                    <th> Checked In Time </th>
                    <th> Checked Out Time </th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $d)
                    <tr>
                        <td>{{ucfirst($d->first_name). ' ' .ucfirst($d->middle_name). ' ' .ucfirst($d->last_name) }}</td>
                        <td> {{$d->email}} </td>
                        <td> {{$d->mobile}} </td>
                        <td> {{$d->gender}} </td>
                        <td> {{ date('M d , Y  H:i:s a', strtotime($d->check_in))}} </td>
                        <td> @if($d->check_out != null){{ date('M d , Y  H:i:s a', strtotime($d->check_out))}} @endif</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="col-md-3"></div>
    </body>
</html>
