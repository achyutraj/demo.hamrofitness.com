<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Branch Renew Report</title>
    <style>
        table {
            width: 100%;
            table-layout: fixed;
            font-size: 10px;
        }
        table, tr {
            border: 1px solid #0a0a0a;
        }
        th, td {
            word-wrap: break-word;
        }

        table, th, td {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="col-md-12">
        <h3 style="text-align:left;text-transform: uppercase"> Branch Renew Report from {{$sd}} To {{$ed}}</h3>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th> Branch Name </th>
                    <th> Owner Name </th>
                    <th> Email </th>
                    <th> Phone </th>
                    <th> Address </th>
                    <th> Start Date </th>
                    <th> Has Device </th>
                    <th> Package Offered </th>
                    <th> Package Amount </th>
                    <th> Renew Created Date</th>
                    <th> Renew Start Date </th>
                    <th> Renew End Date </th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $branch)
                <tr>
                    <td>{{$branch->title}}</td>
                    <td>{{$branch->owner_incharge_name}}</td>
                    <td>{{$branch->email}}</td>
                    <td>{{$branch->phone}}</td>
                    <td>{{$branch->address}}</td>
                    <td>{{ date('M d, Y',strtotime($branch->start_date)) }}</td>
                    <td>{{ $branch->has_device ? 'Yes' : 'No' }}</td>
                    <td>{{ $branch->package_offered }} Months</td>
                    <td>{{ $branch->package_amount }}</td>
                    <td>{{ date('M d, Y',strtotime($branch->created_at)) }}</td>
                    <td>{{ date('M d, Y',strtotime($branch->renew_start_date)) }}</td>
                    <td>{{ date('M d, Y',strtotime($branch->renew_end_date)) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
