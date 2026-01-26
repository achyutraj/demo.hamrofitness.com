<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Profile Report</title>
    <style>
        .clearfix:after {
            content: "";
            display: table;
            clear: both;
        }
        a {
            color: #0087C3;
            text-decoration: none;
        }

        body {
            position: relative;
            width: 100%;
            height: auto;
            margin: 0 auto;
            color: #555555;
            background: #FFFFFF;
            font-size: 14px;
            font-family: 'sans-serif';
        }

        h2 {
            font-weight: normal;
        }

        h2.name {
            font-size: 1.4em;
            font-weight: normal;
            margin: 0;
        }

        #invoice {
            float: right;
            text-align: right;
        }

        table {
            width: 100%;
        }

        table th,
        table td {
            padding: 5px 10px 7px 10px;
            background: #EEEEEE;
            text-align: center;
            border-bottom: 1px solid #FFFFFF;
        }

        table th {
            white-space: nowrap;
            font-weight: normal;
        }

        table td {
            text-align: right;
        }

        table td h3 {
            color: #57B223;
            font-size: 1.2em;
            font-weight: normal;
            margin: 0 0 0 0;
        }

        table .no {
            color: #FFFFFF;
            font-size: 1.6em;
            background: #57B223;
            width: 3%;
        }

        table .desc {
            text-align: left;
        }

        table .unit {
            background: #DDDDDD;
        }

        table .total {
            background: #57B223;
            color: #FFFFFF;
        }

        table td.unit,
        table td.qty,
        table td.total {
            font-size: 1.2em;
            text-align: center;
        }

        table td.unit {
            width: 35%;
        }

        table td.desc {
            width: 35%;
        }

        table td.qty {
            width: 5%;
        }

        table tfoot td {
            padding: 10px 10px 20px 10px;
            background: #FFFFFF;
            border-bottom: none;
            font-size: 1.2em;
            white-space: nowrap;
            border-bottom: 1px solid #AAAAAA;
        }

        table tfoot tr:first-child td {
            border-top: none;
        }

        table tfoot tr td:first-child {
            border: none;
        }

        table, tr {
            border: 1px solid #0a0a0a;
        }

        table, th, td {
            text-align: center;
            /*padding: 20px;*/
        }
    </style>
</head>
<body>
<div class="row">
    <div class="col-md-2 clearfix"></div>
    <div class="col-md-8">
        <div class="">
            <h3 style="text-align:left;text-transform: uppercase"> Profile Information</h3>
            <table class="table table-striped">
                <tbody>
                    <tr>
                        <td>Join Date: </td>
                        <td>{{ date('M d, Y',strtotime($client->joining_date))  }}</td>
                    </tr>
                    <tr>
                        <td>Name: </td>
                        <td>{{$client->first_name }} {{ $client->middle_name }} {{ $client->last_name }}</td>
                    </tr>
                    <tr>
                        <td>Email: </td>
                        <td>{{$client->email }}</td>
                    </tr>
                    <tr>
                        <td>Phone: </td>
                        <td>{{$client->mobile }} @if(!is_null($client->emergency_contact)) <p>Emergency No. {{ $client->emergency_contact }}</p>@endif</td>
                    </tr>
                    <tr>
                        <td>Gender: </td>
                        <td>{{$client->gender }}</td>
                    </tr>
                    @if(!is_null($client->dob))
                    <tr>
                        <td>Date of Birth: </td>
                        <td>{{ date('M d, Y',strtotime($client->dob))  }}</td>
                    </tr>
                    @endif
                    @if(!is_null($client->dob))
                    <tr>
                        <td>Age: </td>
                        <td>{{$age }} Years Old</td>
                    </tr>
                    @endif
                    <tr>
                        <td>Blood Group: </td>
                        <td>{{ strtoupper($client->blood_group) }}</td>
                    </tr>
                    <tr>
                        <td>Martial Status: </td>
                        <td>{{($client->marital_status == 'no') ? 'Unmarried' : 'Married' }}
                            @if($client->marital_status == 'yes') <p> Anniversary: {{ date('M d, Y',strtotime($client->anniversary))  }}</p>@endif
                        </td>
                    </tr>
                    @if($client->height != 0)
                    <tr>
                        <td>Height: </td>
                        <td>{{$client->height_feet }} Ft. {{$client->height_inches }} Inches</td>
                    </tr>
                    @endif
                    @if($client->height != 0.0)
                    <tr>
                        <td>Weight: </td>
                        <td>{{$client->weight }} Kg</td>
                    </tr>
                    @endif
                    <tr>
                        <td>Occupation: </td>
                        <td>{{$client->occupation }} <br> {{ $client->occupation_details }}</td>
                    </tr>

                </tbody>
            </table>
        </div>
        @if(count($memberships) > 0)
            <h3 style="text-align:left;text-transform: uppercase"> Membership</h3>
            <table class="table table-striped">
                <thead>
                <tr>
                    <th> Name</th>
                    <th> Price</th>
                    <th> Status</th>
                    <th> Join Date</th>
                </tr>
                </thead>
                <tbody>
                @foreach($memberships as $mem)
                    <tr>
                        <td>{{$mem->membership->title ?? '' }}</td>
                        <th> NPR {{$mem->amount_to_be_paid }} </th>
                        <th> {{ucfirst($mem->status)}} </th>
                        <th> {{ date('M d, Y',strtotime($mem->start_date)) }} </th>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif

        @if(count($payments) > 0)
            <h3 style="text-align:left;text-transform: uppercase"> Membership Payment</h3>
            <table class="table table-striped">
                <thead>
                <tr>
                    <th class="max-desktop"> Paid</th>
                    <th class="desktop"> Payment For</th>
                    <th class="desktop"> Source</th>
                    <th class="desktop"> Payment Date</th>
                    <th class="desktop"> Payment ID</th>
                </tr>
                </thead>
                <tbody>
                @foreach($payments as $payment)
                    <tr>
                        <th> NPR. {{$payment->payment_amount}} </th>
                        <th> {{$payment->purchase->membership->title ?? ''}} </th>
                        <th> {{ucfirst($payment->payment_source)}} </th>
                        <th> {{ date('M d, Y',strtotime($payment->payment_date))}} </th>
                        <th>{{ $payment->payment_id }}</th>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif

        @if(count($dues) > 0)
            <h3 style="text-align:left;text-transform: uppercase"> Membership Due</h3>
            <table class="table table-striped">
                <thead>
                <tr>
                    <th class="max-desktop"> Remaining</th>
                    <th class="desktop"> Payment To</th>
                    <th class="desktop"> Purchased At</th>
                    <th class="desktop"> Due Payment Date</th>
                </tr>
                </thead>
                <tbody>
                @foreach($dues as $due)
                    <tr>
                        <th> NPR.{{$due->amount_to_be_paid - $due->paid_amount}} </th>
                        <th> {{$due->membership->title ?? ''}} </th>
                        <th> {{ date('M d, Y',strtotime($due->purchase_date))}} </th>
                        <th>
                            @if ((($due->amount_to_be_paid - $due->paid_amount) > 0))
                                @if (isset($due->next_payment_date))
                                    {{ $due->next_payment_date->toFormattedDateString() . ' Due' }}
                                @else
                                    {{ __('No Payment Received') }}
                                @endif
                            @else
                                {{ __('Payment Complete') }}
                            @endif
                        </th>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif

        @if(count($productPayments) > 0)
            <h3 style="text-align:left;text-transform: uppercase"> Product Payment</h3>
            <table class="table table-striped">
                <thead>
                <tr>
                    <th class="max-desktop"> Paid</th>
                    <th class="desktop"> Payment For</th>
                    <th class="desktop"> Quantity</th>
                    <th class="desktop"> Source</th>
                    <th class="desktop"> Payment Date</th>
                    <th class="desktop"> Payment ID</th>
                </tr>
                </thead>
                <tbody>
                @foreach($productPayments as $payment)
                    @php
                        $arr['product_name'] = json_decode($payment->product_sale->product_name,true);
                        $arr['product_quantity'] = json_decode($payment->product_sale->product_quantity,true);
                        $j= count($arr['product_name']);
                    @endphp
                    <tr>
                        <th> NPR. {{$payment->payment_amount}} </th>
                        <th>
                            @for($i=0;$i<$j;$i++)
                                    <?php
                                    $product_name = App\Models\Product::find($arr['product_name'][$i]);
                                    ?>
                                {{ $product_name->name }} <br>
                            @endfor
                        </th>
                        <th>
                            @for($i=0;$i<$j;$i++)
                                {{$arr['product_quantity'][$i]}} <br>
                            @endfor
                        </th>
                        <th> {{ucfirst($payment->payment_source)}} </th>
                        <th> {{ date('M d, Y',strtotime($payment->payment_date))}} </th>
                        <th> {{$payment->payment_id}} </th>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
        @if(count($productDues) > 0)
            <h3 style="text-align:left;text-transform: uppercase"> Product Due</h3>
            <table class="table table-striped">
                <thead>
                <tr>
                    <th class="max-desktop"> Remaining</th>
                    <th class="desktop"> Payment To</th>
                    <th class="desktop"> Purchased At</th>
                    <th class="desktop"> Due Payment Date</th>
                </tr>
                </thead>
                <tbody>
                @foreach($productDues as $due)
                    @php
                        $arr['product_name'] = json_decode($due->product_name,true);
                        $j= count($arr['product_name']);
                    @endphp
                    <tr>
                        <th> NPR.{{$due->total_amount - $due->paid_amount}} </th>
                        <th>
                            @for($i=0;$i<$j;$i++)
                                    <?php
                                    $product_name = App\Models\Product::find($arr['product_name'][$i]);
                                    ?>
                                {{ $product_name->name }} <br>
                            @endfor
                        </th>
                        <th> {{ date('M d, Y',strtotime($due->created_at))}} </th>
                        <th>
                            @if ((($due->total_amount - $due->paid_amount) > 0))
                                @if (isset($due->next_payment_date))
                                    {{ $due->next_payment_date . ' Due'}}
                                @else
                                    {{'No Payment Received'}}
                                @endif
                            @else
                                {{ 'Payment Complete' }}
                            @endif
                        </th>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif

        @if(count($reservations) > 0)
            <h3 style="text-align:left;text-transform: uppercase"> Locker Reservation</h3>
            <table class="table table-striped">
                <thead>
                <tr>
                    <th> Locker Number</th>
                    <th> Price</th>
                    <th> Status</th>
                    <th> Join Date</th>
                </tr>
                </thead>
                <tbody>
                @foreach($reservations as $reserv)
                    <tr>
                        <td>{{$reserv->locker->locker_num ?? '' }} - ({{$reserv->locker->lockerCategory->title ?? '' }})</td>
                        <th> NPR {{$reserv->amount_to_be_paid }} </th>
                        <th> {{ucfirst($reserv->status)}} </th>
                        <th> {{ date('M d, Y',strtotime($reserv->start_date)) }} </th>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif

        @if(count($locker_payments) > 0)
            <h3 style="text-align:left;text-transform: uppercase"> Locker Payment</h3>
            <table class="table table-striped">
                <thead>
                <tr>
                    <th class="desktop"> Paid</th>
                    <th class="desktop"> Locker Number</th>
                    <th class="desktop"> Source</th>
                    <th class="desktop"> Payment Date</th>
                    <th class="desktop"> Payment ID</th>
                </tr>
                </thead>
                <tbody>
                @foreach($locker_payments as $payment)
                    <tr>
                        <th> NPR. {{$payment->payment_amount}} </th>
                        <td>{{$payment->reservation->locker->locker_num ?? '' }} - ({{$payment->reservation->locker->lockerCategory->title ?? '' }})</td>
                        <th> {{ucfirst($payment->payment_source)}} </th>
                        <th> {{ date('M d, Y',strtotime($payment->payment_date))}} </th>
                        <th> {{$payment->payment_id}} </th>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif

        @if(count($locker_dues) > 0)
            <h3 style="text-align:left;text-transform: uppercase"> Locker Due</h3>
            <table class="table table-striped">
                <thead>
                <tr>
                    <th class="desktop"> Remaining</th>
                    <th class="desktop"> Payment To</th>
                    <th class="desktop"> Purchased At</th>
                    <th class="desktop"> Due Payment Date</th>
                </tr>
                </thead>
                <tbody>
                @foreach($locker_dues as $due)
                    <tr>
                        <th> NPR.{{$due->amount_to_be_paid - $due->paid_amount}} </th>
                        <td>{{$due->locker->locker_num ?? '' }} - ({{$due->locker->lockerCategory->title ?? '' }})</td>
                        <th> {{ date('M d, Y',strtotime($due->start_date))}} </th>
                        <th>
                            @if ((($due->amount_to_be_paid - $due->paid_amount) > 0))
                                @if (isset($due->next_payment_date))
                                    {{ $due->next_payment_date->toFormattedDateString() }}
                                @else
                                    {{'No Payment Received'}}
                                @endif
                            @else
                                {{ 'Payment Complete' }}
                            @endif
                        </th>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </div>
    <div class="col-md-2 clearfix"></div>
</div>
</body>
</html>
