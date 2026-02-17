<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Enquiry Report</title>
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
        <h3 style="text-align:left;text-transform: uppercase"> Enquiry Report from {{$sd}} To {{$ed}}</h3>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th> Name </th>
                    <th> Email </th>
                    <th> Mobile </th>
                    <th> Gender </th>
                    <th> Goal</th>
                    <th> Source</th>
                    <th> Enquiry Date </th>
                    <th> Next Follow Up</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $payments)
                    <tr>
                        <td>{{$payments->displayFullName() }}</td>
                        <td> {{$payments->email}} </td>
                        <td> {{$payments->mobile}} </td>
                        <td> {{$payments->sex}} </td>
                        <td> {{$payments->customer_goal}} </td>
                        <td> {{ ucfirst($payments->come_to_know) }}</td>
                        <td> {{ date('M d, Y',strtotime($payments->enquiry_date)) }} </td>
                        <td> {{ date('M d, Y',strtotime($payments->next_follow_up)) }} </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="col-md-1"></div>
    </body>
</html>
