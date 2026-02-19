<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Finance Report</title>
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
<div class="col-md-3"></div>
<div class="col-md-6">
    <h3 style="text-align:left;text-transform: uppercase"> {{$id}} Finance Report from {{$sd}} To {{$ed}}</h3>

    <table class="table table-striped">
        <thead>
        <tr>
            <th> Payer Name</th>
            @if($id == 'all' || $id == 'allProduct' || $id == 'lockerPayments')
                <th> Amount</th>
                <th> Payment Method</th>
                <th> Date</th>
                <th> Remarks</th>
            @elseif($id == 'dueProducts')
                <th> Customer Type</th>
                <th> Remaining</th>
                <th> Paid Amount</th>
                <th> Last Payment Date</th>
            @else
                <th> Remaining</th>
                <th> Paid Amount</th>
                <th> Last Payment Date</th>
                <th> Remarks</th>
            @endif
        </tr>
        </thead>
        <tbody>
        @foreach($payment as $payments)
            <tr>
                <td>{{$payments->first_name. ' ' .$payments->middle_name. ' ' .$payments->last_name }}</td>
                @if($id == 'all' || $id == 'allProduct' || $id == 'lockerPayments')
                    <td> {{$payments->payment_amount}} </td>
                    <td>
                        {{getPaymentTypeForReport($payments->payment_source)}}
                    </td>
                    <td> {{$payments->payment_date->format('Y-m-d')}} </td>
                    <td>{{ $payments->remarks }}</td>
                @elseif($id == 'dueProducts')
                    <td>{{ $payments->customer_type }}</td>
                    <td> NPR {{($payments->amount_to_be_paid - $payments->paid_amount)}} </td>
                    <td> NPR {{$payments->paid_amount}} </td>
                    <td> {{ date('M d, Y',strtotime($payments->next_payment_date)) }} </td>
                @else
                    <td> NPR {{($payments->amount_to_be_paid - $payments->paid_amount)}} </td>
                    <td> NPR {{$payments->paid_amount}} </td>
                    <td> {{ date('M d, Y',strtotime($payments->next_payment_date)) }} </td>
                    <td>{{ $payments->remarks }}</td>
                @endif
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
<div class="col-md-3"></div>
</body>
</html>
