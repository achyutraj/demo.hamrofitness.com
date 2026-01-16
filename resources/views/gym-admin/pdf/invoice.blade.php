<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title> Invoice</title>
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
            font-family: 'DejaVu Sans', sans-serif;
        }

        h2 {
            font-weight: normal;
        }

        header {
            margin-bottom: 10px;
            border-bottom: 1px solid #AAAAAA;
        }

        #logo {
            float: left;
            margin-top: 11px;
        }

        #company {
            float: right;
            text-align: right;
            line-height: 0.25;
        }

        #details {
            margin-bottom: 50px;
        }

        #client {
            padding-left: 6px;
            float: left;
        }

        #client .to {
            color: #777777;
        }

        h2.name {
            font-size: 1.4em;
            font-weight: normal;
            margin: 0;
        }

        table {
            width: 100%;
            border-spacing: 0;
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

        .status {
            margin-top: 15px;
            padding: 1px 8px 5px;
            font-size: 1.3em;
            width: 80px;
            color: #fff;
            text-align: center;
            display: inline-block;
        }

        .status.unpaid {
            background-color: #E7505A;
        }

        .status.paid {
            background-color: #26C281;
        }

        .status.cancelled {
            background-color: #95A5A6;
        }

        .status.error {
            background-color: #F4D03F;
        }

        table tr.tax .desc {
            text-align: right;
            color: #1BA39C;
        }

        table tr.discount .desc {
            text-align: right;
            color: #E43A45;
        }

        table tr.tax .desc {
            text-align: right;
            color: #1d0707;
        }

        table tr.subtotal .desc {
            text-align: right;
            color: #1d0707;
        }

        table tbody tr:last-child td {
            border: none;
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

        #thanks {
            font-size: 2em;
            margin-bottom: 50px;
        }

        #notices {
            padding-left: 6px;
            border-left: 6px solid #0087C3;
        }

        #notices .notice {
            font-size: 1.2em;
        }

        footer {
            color: #777777;
            width: 100%;
            height: 30px;
            position: absolute;
            bottom: 0;
            border-top: 1px solid #AAAAAA;
            padding: 8px 0;
            text-align: center;
        }

        table.billing td {
            background-color: #fff;
        }

        table td div#invoiced_to {
            text-align: left;
            line-height: 0.25;
        }
        .main-body{
            margin:10px
        }
        .footer-text{
            text-align: center;
        }

    </style>
</head>
<body>
<header class="clearfix">
    <table cellpadding="0" cellspacing="0" class="billing">
        <tr>
            <td id="logo" style="text-align: left">
                @if(is_null($settings))
                    <img src="{{ $gymSettingPath.'fitsigma-logo-full.png' }}" class="logo-default img-responsive image-change">
                @else
                    @if($settings->front_image != '')
                        @if($settings->local_storage == '0')
                            {!! HTML::image($gymSettingPath.$settings->front_image, 'Hamrofitness',array('class' => 'img-responsive inline-block', 'style' => 'height: 60px;')) !!}
                        @else
                            {!! HTML::image(asset('/uploads/gym_setting/master/').'/'.$settings->front_image, 'Hamrofitness',array('class' => 'img-responsive inline-block', 'style' => 'height: 60px;')) !!}
                        @endif
                    @else
                        {!! HTML::image(asset('/uploads/gym_setting/master/').'/'.$settings->image, 'Hamrofitness',array('class' => 'img-responsive inline-block', 'style' => 'height: 60px;')) !!}
                    @endif
                @endif
            </td>
        </tr>

        <tr>
            <td>
                <div id="invoiced_to">
                    <p>Billed To:</p>
                    <h4 class="name">{{ ucwords($invoice->client_name) }}</h4>
                    <h5>{!! nl2br($invoice->client_address) !!}</h5>
                    <h5>{{$invoice->mobile}}</h5>
                    <h5></h5>
                    <p>Invoice #{{ $invoice->invoice_number }}</p> 
                </div>
            </td>
            <td id="company">
                <p>Generated By:</p>
                <h4 class="name">{{ ucwords($user->username) }}</h4>
                <h5>{{ ucwords($merchantBusiness->business->title) }}</h5>
                <h5>@if(!is_null($merchantBusiness->business->address)){!! nl2br($merchantBusiness->business->address) !!}@endif</h5>
                <h5>@if(!is_null($merchantBusiness->business->phone2)){{ $merchantBusiness->business->phone2 }}@endif</h5>
                <p>Date: {{ $invoice->invoice_date->format("dS M Y") }} </p>
            </td>
        </tr>
    </table>
</header>
<main>
    <div class="main-body clearfix">
        <table border="0" cellspacing="0" cellpadding="0">
            <thead>
            <tr>
                <th class="no">#</th>
                <th class="desc">Item</th>
                <th class="qty">Quantity</th>
                <th class="unit">Purchase</th>
                <th class="unit">Discount (%)</th>
                <th class="unit">Total </th>
                <th class="unit">Paid </th>
            </tr>
            </thead>
            <tbody>
            <?php $count = 0; ?>
            @foreach($invoice->items as $item)
                <?php $discount = ($item->discount_amount != "") ? $item->discount_amount : 0  ?>
                <tr style="page-break-inside: avoid;">
                    <td class="no">{{ ++$count }}</td>
                    <td class="desc"><h3>{{ ucfirst($item->item_name) }}</h3></td>
                    <td class="qty"><h3>{{ $item->quantity }}</h3></td>
                    <td class="unit"><h3>{{ $gymSettings->currency->acronym }} {{ $item->cost_per_item }}</h3></td>
                    <td class="unit"><h3>{{ $gymSettings->currency->acronym }} {{ $discount }}</h3></td>
                    <td class="unit"><h3>{{ $gymSettings->currency->acronym }} {{ ($item->cost_per_item * $item->quantity) - $discount }}</h3></td>
                    <td class="unit">{{ $gymSettings->currency->acronym }} {{ $item->amount }}</td>
                </tr>
            @endforeach
            @if($invoice->remarks != null)
            <tr>
                <td class="text-center" style="text-align: left !important;"  colspan="7">Remarks: {{$invoice->remarks}}</td>
            </tr>
            @endif
            </tbody>
            <tfoot>
            <tr dontbreak="true">
                <td colspan="6">Sub Total</td>
                <td>{{ $gymSettings->currency->acronym }} {{ round($invoice->sub_total, 2) }}</td>
            </tr>
            @if($invoice->tax > 0)
            <tr dontbreak="true">
                <td colspan="6">Tax</td>
                <td>{{ $gymSettings->currency->acronym }} {{ round($invoice->tax, 2) }}</td>
            </tr>
            @endif
            <tr dontbreak="true">
                <td colspan="6">Grand Total</td>
                <td>{{ $gymSettings->currency->acronym }} {{ round($invoice->total, 2) }}</td>
            </tr>
            </tfoot>
        </table>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <em>Invoice generated by: {{ ucwords($invoice->generated_by) }}</em>
            <p class="text-center">This is not a Tax Invoice <br>Thank You !!!</p>
        </div>
    </div>
</main>
</body>
</html>
