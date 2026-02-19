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
                    @if($id == 'regular_active_client' || $id == 'high_attendance')
                        <th> Present Days </th>
                        <th> Last Attendance </th>
                        <th> Status </th>
                    @elseif($id == 'irregular_active_client')
                        <th> Present Days </th>
                        <th> Absent Days </th>
                        <th> Total Sub Days </th>
                        <th> Last Attendance </th>
                        <th> Status </th>
                    @else
                        <th> Checked In Time </th>
                        <th> Checked Out Time </th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($data as $d)
                    <tr>
                        <td>{{ucfirst($d->first_name). ' ' .ucfirst($d->middle_name). ' ' .ucfirst($d->last_name) }}</td>
                        <td> {{$d->email}} </td>
                        <td> {{$d->mobile}} </td>
                        <td> {{ucfirst($d->gender)}} </td>
                        @if($id == 'regular_active_client' || $id == 'high_attendance')
                            <td> {{ $d->present_days ?? 0 }} </td>
                            <td> {{ $d->last_attendance ? date('M d, Y', strtotime($d->last_attendance)) : 'N/A' }} </td>
                            <td>
                                @php
                                    $presentDays = $d->present_days ?? 0;
                                    if ($presentDays >= 20) {
                                        echo 'Regular';
                                    } elseif ($presentDays >= 10) {
                                        echo 'Moderate';
                                    } else {
                                        echo 'Irregular';
                                    }
                                @endphp
                            </td>
                        @elseif($id == 'irregular_active_client')
                            <td> {{ $d->present_days ?? 0 }} </td>
                            <td> {{ $d->absent_days ?? 0 }} </td>
                            <td> {{ $d->total_subscription_days ?? 0 }} </td>
                            <td> {{ $d->last_attendance ? date('M d, Y', strtotime($d->last_attendance)) : 'N/A' }} </td>
                            <td>
                                @php
                                    $totalDays = $d->total_subscription_days ?? 1;
                                    $presentDays = $d->present_days ?? 0;
                                    $rate = ($presentDays / $totalDays) * 100;
                                    if ($rate >= 70) {
                                        echo 'Regular';
                                    } elseif ($rate >= 40) {
                                        echo 'Moderate';
                                    } else {
                                        echo 'Irregular';
                                    }
                                @endphp
                            </td>
                        @else
                            <td> {{ isset($d->check_in) ? date('M d , Y  H:i:s a', strtotime($d->check_in)) : '' }} </td>
                            <td> @if(isset($d->check_out) && $d->check_out != null){{ date('M d , Y  H:i:s a', strtotime($d->check_out))}} @endif</td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="col-md-3"></div>
    </body>
</html>
