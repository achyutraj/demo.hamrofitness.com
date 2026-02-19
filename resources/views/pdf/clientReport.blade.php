<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Client Report</title>
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
    <div class="col-md-1"></div>
    <div class="col-md-10">
        <h2 style="text-align:left;text-transform: uppercase"> {{$id}} Clients from {{$sd}} To {{$ed}}</h2>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th> Name </th>
                    <th> Email </th>
                    <th> Mobile </th>
                    <th> Gender </th>
                    @if($id == 'birthday')
                        <th> Birthday </th>
                    @endif
                    @if($id == 'expire')
                        <th> Membership </th>
                        <th> Start Date </th>
                        <th> Expire On </th>
                    @else
                        <th> Joined Date </th>
                        <th> Address </th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($clients as $client)
                    <tr>
                        <td>{{$client->first_name. ' ' .$client->middle_name. ' ' .$client->last_name }}</td>
                        <td>{{$client->email}}</td>
                        <td>{{$client->mobile}}</td>
                        <td>{{$client->gender}}</td>
                        @if($id == 'birthday')
                            <td>{{$client->dob->format('Y-m-d')}}</td>
                        @endif
                        @if($id == 'expire')
                            <td>{{$client->membership}}</td>
                            <td>{{$client->start_date}}</td>
                            <td>{{$client->expires_on}}</td>
                        @else
                            <td>{{$client->joining_date}}</td>
                            <td>{{$client->address}}</td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="col-md-1"></div>
    </body>
</html>
