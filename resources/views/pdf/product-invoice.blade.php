<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

    <title>Product Invoice</title>
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
            padding: 10px 0;
            margin-bottom: 20px;
            border-bottom: 1px solid #AAAAAA;
        }

        #logo {
            float: left;
            margin-top: 11px;
        }

        #logo img {
            height: 55px;
            margin-bottom: 15px;
        }

        #company {
            float: right;
            text-align: right;
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

        #invoice {
            float: right;
            text-align: right;
        }

        #invoice h1 {
            color: #0087C3;
            font-size: 2.4em;
            line-height: 1em;
            font-weight: normal;
            margin: 0 0 10px 0;
        }

        #invoice .date {
            font-size: 1.1em;
            color: #777777;
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
            width: 20%;
        }

        table td.desc {
            width: 35%;
        }

        table td.qty {
            width: 10%;
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
        }


    </style>
</head>
<body>
<header class="clearfix">
    <table cellpadding="0" cellspacing="0" class="billing">
        <tr>
            <td id="logo" style="text-align: left">
                @if($gymSettings->front_image != '')
                    {{ html()->img(asset('/uploads/gym_setting/master/').'/'.$gymSettings->front_image, 'Logo')->attributes(array("class" => "logo-style")) }}
                @else
                    {{ html()->img(asset('/fitsigma/images').'/'.'fitness-plus.png', 'Logo')->attributes(array("class" => "logo-style")) }}
                @endif
            </td>
        </tr>
        <tr>
            <td>
                <div id="invoiced_to">
                    <small>Billed To:</small>
                    <h2 class="name">{{ ucwords($invoice->customer_name) }}</h2>
                    <div>{{ nl2br($invoice->customer->email) }}</div>
                    <div>{{ nl2br($invoice->customer->phone) }}</div>
                </div>
            </td>
            <td id="company">
                <small>Generated By:</small>
                <h2 class="name">{{ ucwords($merchantBusiness->business->title ?? '') }}</h2>
                <div>@if(!is_null($merchantBusiness->business->address)){{ nl2br($merchantBusiness->business->address) }}@endif</div>
                <div>@if(!is_null($merchantBusiness->business->phone)){{ $merchantBusiness->business->phone }}@endif</div>
                @if(!is_null($gymSettings))
                    <div>Tax({{ $gymSettings->gstin }})%</div>
                @endif

            </td>
        </tr>
    </table>
</header>
<main style="padding: 5px;">
    <div id="details" class="clearfix">
        <div id="invoice">
            <h1>Invoice #{{ $invoice->id }}</h1>
            <div class="date">Date:  {{ \Carbon\Carbon::today()->format('Y-m-d') }}</div>
        </div>

    </div>
    <table border="0" cellspacing="0" cellpadding="0">
        <thead>
        <tr>
            <th class="no">#</th>
            <th class="desc">Item</th>
            <th class="qty">Quantity</th>
            <th class="unit">Price</th>
            <th class="unit">Discount</th>
            <th class="unit">Paid Amount</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $arr['product_name'] = json_decode($invoice->product_name, true);
        $arr['product_price'] = json_decode($invoice->product_price, true);
        $arr['product_quantity'] = json_decode($invoice->product_quantity, true);
        $arr['product_discount'] = json_decode($invoice->product_discount, true);
        $arr['product_amount'] = json_decode($invoice->product_amount, true);
        $total = array_sum($arr['product_amount']);
        $j = count($arr['product_name']);
        ?>
            <tr style="page-break-inside: avoid;">
                <td class="no">
                    @for($i=0;$i<$j;$i++)
                        {{($i+1)}} <br>
                    @endfor
                </td>
                <td class="desc">
                    <h3>
                        @for($i=0;$i<$j;$i++)
                                <?php
                                $product_name = App\Models\Product::find($arr['product_name'][$i]);
                                ?>
                            {{ucfirst($product_name->name)}} <br>
                        @endfor
                    </h3>
                </td>
                <td class="qty">
                    <h3>
                        @for($i=0;$i<$j;$i++)
                            {{$arr['product_quantity'][$i]}} <br>
                        @endfor
                    </h3>
                </td>
                <td class="unit">
                    <h3>
                        @for($i=0;$i<$j;$i++)
                            NPR {{$arr['product_price'][$i]}} <br>
                        @endfor
                    </h3>
                </td>
                <td class="unit">
                    <h3>
                        @for($i=0;$i<$j;$i++)
                            NPR {{$arr['product_discount'][$i]}} <br>
                        @endfor
                    </h3>
                </td>
                <td class="unit">
                    @for($i=0;$i<$j;$i++)
                        NPR {{$arr['product_amount'][$i]}} <br>
                    @endfor
                </td>
            </tr>
        </tbody>
        <tfoot>
        <tr dontbreak="true">
            <td colspan="5">Grand Total</td>
            <td>NPR {{ round($total, 2) }}</td>
        </tr>
        </tfoot>
    </table>
</main>
</body>
</html>
