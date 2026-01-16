@extends('layouts.gym-merchant.gymbasic')

@section('CSS')
    {!! HTML::style('admin/global/plugins/ladda/ladda-themeless.min.css') !!}
    {!! HTML::style('admin/global/plugins/bootstrap-select/css/bootstrap-select.min.css') !!}
    {!! HTML::style('admin/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') !!}
    <style>
        table thead th,table tbody td{
            text-align: center;
        }
        .marquee {
            height: 30px;
            overflow: hidden;
            position: relative;
            color: black;
        }
        .marquee p {
            position: absolute;
            white-space:nowrap;
            width: 100%;
            height: 100%;
            margin: 5px;
            text-align: center;
            /* Starting position */
            -moz-transform:translateX(100%);
            -webkit-transform:translateX(100%);
            transform:translateX(100%);
            /* Apply animation to this element */
            -moz-animation: scroll-left 13s linear infinite;
            -webkit-animation: scroll-left 13s linear infinite;
            animation: scroll-left 13s linear infinite;
        }
        /* Move it (define the animation) */
        @-moz-keyframes scroll-left {
            0%   { -moz-transform: translateX(100%); }
            100% { -moz-transform: translateX(-100%); }
        }
        @-webkit-keyframes scroll-left {
            0%   { -webkit-transform: translateX(100%); }
            100% { -webkit-transform: translateX(-100%); }
        }
        @keyframes scroll-left {
            0%   {
                -moz-transform: translateX(100%); /* Browser bug fix */
                -webkit-transform: translateX(100%); /* Browser bug fix */
                transform: translateX(100%);
            }
            100% {
                -moz-transform: translateX(-100%); /* Browser bug fix */
                -webkit-transform: translateX(-100%); /* Browser bug fix */
                transform: translateX(-100%);
            }
        }
    </style>
@stop

@section('content')
    <div class="container-fluid">
        <!-- BEGIN PAGE BREADCRUMBS -->
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <a href="{{route('gym-admin.dashboard.index')}}">Home</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <a href="{{route('gym-admin.sales.index')}}">Product Sales</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>Edit Product Sales</span>
            </li>
        </ul>
        <!-- END PAGE BREADCRUMBS -->
        <!-- BEGIN PAGE CONTENT INNER -->
        <div class="page-content-inner">
            <div class="row">
                <div class="col-md-12">
                    <!-- BEGIN EXAMPLE TABLE PORTLET-->
                    <div class="portlet light ">
                        <div class="portlet-title">
                            <div class="caption font-dark">
                                <i class="fa fa-dropbox font-red"></i>
                                <span class="caption-subject font-red bold uppercase"> Product Sales Edit</span>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <form action="{{route('gym-admin.sales.update',$products->id)}}" method="post" enctype="multipart/form-data" autocomplete="off">
                                {{csrf_field()}}
                                {{-- Select Customer type and select or write customer --}}
                                <div class="form-row row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label><h4>Select Customer Type</h4></label>
                                            <input class="form-control customerType" type="text" value="{{$products->customer_type}}" name="customer_type" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label><h4>{{$products->customer_type}} Customer Name</h4></label>
                                            <input class="form-control customerType" type="text" value="{{$products->customer_name}}" name="customer_name" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label><h4>Due Payment Date</h4></label>
                                            <input class="form-control date-picker" type="text" value="{{ $products->next_payment_date }}" name="next_payment_date">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row row">
                                    <table class="table table-striped table-bordered table-hover table-checkable order-column table-100">
                                        <thead>
                                        <tr>
                                            <th class="desktop" style="width: 30%">Product Name</th>
                                            <th class="desktop" style="width: 20%">Price (Rs / per piece)</th>
                                            <th class="desktop" style="width: 20%">Quantity</th>
                                            <th class="desktop" style="width: 10%">Discount(Amt)</th>
                                            <th class="desktop" style="width: 20%">Amount</th>
                                        </tr>
                                        </thead>
                                        <tbody id="tableBody">
                                        @php
                                            $arr['product_name'] = json_decode($products->product_name,true);
                                            $arr['product_price'] = json_decode($products->product_price,true);
                                            $arr['product_quantity'] = json_decode($products->product_quantity,true);
                                            $arr['product_discount'] = json_decode($products->product_discount,true);
                                            $arr['product_amount'] = json_decode($products->product_amount,true);
                                            $total = array_sum($arr['product_amount']);
                                            $j= count($arr['product_name']);
                                        @endphp
                                        @for($i=0;$i<$j;$i++)
                                            <?php
                                            $product_name = App\Models\Product::find($arr['product_name'][$i]);
                                            ?>
                                            <tr>
                                                <td>
                                                    <input class="form-control" class="productType{{$i}}" type="text" value=" {{ $product_name->name }}" readonly>
                                                    <input class="form-control" class="productType{{$i}}" type="hidden" value=" {{ $arr['product_name'][$i] }}" name="product[]">
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control quantityPrice{{$i}}" value="{{$arr['product_price'][$i]}}" name="product_price[]" readonly>
                                                </td>
                                                <td>
                                                    <input type="text" id="quantityProduct" class="form-control productQuantity{{$i}}" value="{{$arr['product_quantity'][$i]}}" name="product_quantity[]" required>
                                                </td>
                                                <td>
                                                    <input type="number" min="0" class="form-control discountPercent{{$i}}" name="product_discount[]" id="discountProduct" value="{{$arr['product_discount'][$i]}}">
                                                </td>
                                                <td><input type="number" class="form-control subTotal{{$i}}" id="amountTotal" name="amount[]" value="{{$arr['product_amount'][$i]}}" readonly></td>
                                            </tr>
                                        @endfor
                                        </tbody>
                                    </table>
                                    <div class="totalValue">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <button class="btn btn-success" type="submit"><i class="fa fa-save"></i> Update</button>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="total font-bold" align="center" style="font-size: 18px;">Total : {{ $gymSettings->currency->acronym}}
                                                        <input type="number" style="width: 40%; border: none; background: #fff; margin-top: -31px; text-align: right; font-size: 18px; margin-left: 80px;" name="total" value="{{$total}}" class="form-control totalSum" readonly></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('footer')
    {!! HTML::script('admin/global/plugins/ladda/spin.min.js') !!}
    {!! HTML::script('admin/global/plugins/ladda/ladda.min.js') !!}
    {!! HTML::script('admin/pages/scripts/ui-buttons.min.js') !!}
    {!! HTML::script('admin/global/plugins/bootstrap-select/js/bootstrap-select.min.js') !!}
    {!! HTML::script('admin/pages/scripts/components-bootstrap-select.min.js') !!}
    {!! HTML::script('admin/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') !!}

    <script>
        $('.date-picker').datepicker({
            rtl: App.isRTL(),
            orientation: "left",
            autoclose: true,
            format: 'yyyy-mm-dd'
        });
        i =  <?php echo $j;?> ;
        for(let counter=0;counter<i; counter++){
            $('.productQuantity'+counter).keyup(function () {
                amountTotal = parseInt($('.subTotal'+counter).val(),10);
                totalAmount = (parseInt($('.totalSum').val(), 10) - amountTotal);
                let prices = $('.quantityPrice'+counter).val();
                let quantities = $(this).val();
                let discount = $('.discountPercent'+counter).val();
                let x = (prices * quantities);
                subTotal = (x - discount);
                total = (totalAmount + subTotal);
                $('input[name="total"]').val(total);
                $('.subTotal'+counter).val(subTotal);
            });
            $('.discountPercent'+counter).keyup(function () {
                amountTotal = parseInt($('.subTotal'+counter).val(),10);
                totalAmount = (parseInt($('.totalSum').val(), 10) - amountTotal);
                let prices = $('.quantityPrice'+counter).val();
                let quantities = $('.productQuantity'+counter).val();
                let discount = $(this).val();
                let x = (prices * quantities);
                subTotal = (x - discount);
                total = (totalAmount + subTotal);
                $('input[name="total"]').val(total);
                $('.subTotal'+counter).val(subTotal);
            });
        }
    </script>
@endsection
