@extends('layouts.gym-merchant.gymbasic')

@section('CSS')
    {!! HTML::style('admin/global/plugins/ladda/ladda-themeless.min.css') !!}
    {!! HTML::style('admin/global/plugins/bootstrap-select/css/bootstrap-select.min.css') !!}
    {!! HTML::style('admin/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') !!}

    <style>
        table thead th, table tbody td {
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
            white-space: nowrap;
            width: 100%;
            height: 100%;
            margin: 5px;
            text-align: center;
            /* Starting position */
            -moz-transform: translateX(100%);
            -webkit-transform: translateX(100%);
            transform: translateX(100%);
            /* Apply animation to this element */
            -moz-animation: scroll-left 13s linear infinite;
            -webkit-animation: scroll-left 13s linear infinite;
            animation: scroll-left 13s linear infinite;
        }

        /* Move it (define the animation) */
        @-moz-keyframes scroll-left {
            0% {
                -moz-transform: translateX(100%);
            }
            100% {
                -moz-transform: translateX(-100%);
            }
        }

        @-webkit-keyframes scroll-left {
            0% {
                -webkit-transform: translateX(100%);
            }
            100% {
                -webkit-transform: translateX(-100%);
            }
        }

        @keyframes scroll-left {
            0% {
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
                <span>Sell</span>
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
                            <div class="col-md-3" style="padding: 5px; font-size: 16px;">
                                <div class="caption font-dark">
                                    <i class="fa fa-dropbox font-red"></i>
                                    <span class="caption-subject font-red bold uppercase"> Product Sales Create</span>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="marquee" behavior="scroll" direction="left">
                                    <p class="bold uppercase">
                                        @foreach($products as $product)
                                            @if(($product->quantity - ($product->quantity_expired + $product->quantity_damaged + $product->quantity_sold)) == 0)
                                                <span style="color: green;">{{$product->name}}</span> <span
                                                    style="color: red"> :  Out Of Stock</span> <span> @if(!$loop->last) | @endif </span>
                                            @endif
                                            <span style="color: green;">{{$product->name}}</span> <span
                                                class="font-red"> : Rs {{$product->price}}</span> <span> @if(!$loop->last) | @endif </span>
                                        @endforeach
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <form action="{{route('gym-admin.sales.store')}}" method="post"
                                  enctype="multipart/form-data" autocomplete="off">
                                {{csrf_field()}}
                                {{-- Select Customer type and select or write customer --}}
                                <div class="form-row row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label><h4>Select Customer Type</h4></label>
                                            <select class="form-control customerType" name="customer_type" required>
                                                <option value="">Select Customer Type</option>
                                                <option value="client">Gym Clients</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div id="customerTypeList" style="display: none;"></div>
                                        </div>
                                    </div>
                                </div>
                                @php
                                    $count = 0;
                                @endphp
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
                                        <tr>
                                            <td>
                                                <div class="form-group" style="margin-left: 5px;margin-right: 5px;">
                                                    <select class="form-control productType select2" id="selectProduct"
                                                            name="product[]" required>
                                                        <option value="">Select Product</option>
                                                        @foreach($products as $product)
                                                            <option value="{{$product->id}}"
                                                                    @if(($product->quantity - ($product->quantity_expired + $product->quantity_damaged + $product->quantity_sold)) == 0) disabled @endif>{{$product->name}} @if(($product->quantity - ($product->quantity_expired + $product->quantity_damaged + $product->quantity_sold)) == 0)
                                                                    (out of stock) @endif</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </td>
                                            <td>
                                                <input type="number" class="form-control quantityPrice"
                                                       id="priceQuantity" name="product_price[]" readonly>
                                            </td>
                                            <td>
                                                <div class="row">
                                                    <div class="col-md-6"><input type="text" id="quantityProduct"
                                                                                 style="width: 100px"
                                                                                 class="form-control productQuantity"
                                                                                 name="product_quantity[]" required>
                                                    </div>
                                                    <div class="col-md-6" style="padding: 10px;"><span
                                                            class="quantityData font-bold" id="dataQuantity"></span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control discountPercent"
                                                       name="product_discount[]" min="0" id="discountProduct" value="0">
                                            </td>
                                            <td><input type="number" class="form-control subTotal" id="amountTotal"
                                                       name="amount[]" min="0" value="0" readonly></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <div class="totalValue">
                                        <div class="row">
                                            <div class="col-md-6 col-sm-12">
                                                <a class="btn btn-primary btn-sm addNew"><i class="fa fa-plus"></i> Add
                                                    New Product</a>
                                                <button class="btn btn-success" type="submit"><i class="fa fa-save"></i>
                                                    Save
                                                </button>
                                                <span class="count" style="display: none;">0</span>
                                            </div>
                                            <div class="col-md-6 col-sm-12">
                                                <div class="row">
                                                    <div class="total font-bold" align="center"
                                                         style="font-size: 18px;">Total
                                                        : {{ $gymSettings->currency->acronym}}
                                                        <input type="number"
                                                               style="width: 40%; border: none; background: #fff; margin-top: -31px; text-align: right; margin-left: 80px; font-size: 18px;"
                                                               name="total" value="0" class="form-control totalSum"
                                                               readonly></div>
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
          $(document).ready(function () {
            $('.select2').select2();
            $('.date-picker').datepicker({
                rtl: App.isRTL(),
                orientation: "left",
                autoclose: true,
                format: 'yyyy-mm-dd'
            });
            $(".addNew").click(function () {
                $('.count').html(function (i, val) {
                    return val * 1 + 1
                });
                let counter = $('.count').text();
                $('#tableBody tr:last').after(`
                        <tr>
                            <td>
                                <div class="form-group" style="margin-left: 5px;margin-right: 5px;">
                                    <select class="bs-select form-control productType${counter} select2" name="product[]" required>
                                        <option value="">Select Product</option>
                                        @foreach($products as $product)
                <option value="{{$product->id}}"@if(($product->quantity - ($product->quantity_expired + $product->quantity_damaged + $product->quantity_sold)) == 0) disabled @endif>{{$product->name}} @if(($product->quantity - ($product->quantity_expired + $product->quantity_damaged + $product->quantity_sold)) == 0) (out of stock) @endif</option>
                                                            @endforeach
                </select>
            </div>
        </td>
        <td>
            <input type="number" class="form-control quantityPrice${counter}" name="product_price[]" readonly>
                            </td>
                            <td>
                                <div class="row">
                                    <div class="col-md-6"><input type="text" style="width: 100px" class="form-control productQuantity${counter}" name="product_quantity[]" required></div>
                                    <div class="col-md-6" style="padding: 10px;"><span class="quantityData${counter} font-bold"></span></div>
                                </div>
                            </td>
                            <td>
                                <input type="text" class="form-control discountPercent${counter}" min="0" name="product_discount[]" value="0" >
                            </td>
                            <td><input type="number" class="form-control subTotal" name="amount[]" min="0" id="amountTotal${counter}" value="0" readonly></td>
                            <td>
                                <a class="font-bold removeData${counter}" style="font-size: 20px;"><i class="fa fa-minus" style="color: red"></i></a>
                            </td>
                        </tr>
                `);

                // Initialize select2 for the new select element
                $("select.productType" + counter).select2();

                /* product select and list the availability and other info */
                $("select.productType" + counter).change(function () {
                    let selectedProduct = $(this).children("option:selected").val();
                    @foreach($products as $product)
                        qty = <?php echo $product->quantity - ($product->quantity_expired + $product->quantity_damaged + $product->quantity_sold) ?>;
                    items = <?php echo $product->id;  ?>;
                    if (items == selectedProduct) {
                        $('.quantityData' + counter).empty();
                        @if(($product->quantity - ($product->quantity_expired + $product->quantity_damaged + $product->quantity_sold)) < 50)
                        $('.quantityData' + counter).css('color', 'red');
                        @else
                        $('.quantityData' + counter).css('color', 'green');
                        @endif
                        $('.quantityData' + counter).append(`
                                ( {{$product->quantity - ($product->quantity_expired + $product->quantity_damaged + $product->quantity_sold)}} left )
                            `);
                        $('.quantityPrice' + counter).css('text-align', 'center').val({{$product->price}});
                        $('.productQuantity'+counter).attr('max', qty);
                        $('.productQuantity' + counter).keyup(function () {
                            amountTotal = parseInt($('#amountTotal' + counter).val(), 10);
                            totalAmount = (parseInt($('.totalSum').val(), 10) - amountTotal);
                            let prices = $('.quantityPrice' + counter).val();
                            let quantities = $(this).val();
                            let discount = $('.discountPercent' + counter).val();
                            let x = (prices * quantities);
                            subTotal = (x - discount);
                            total = (totalAmount + subTotal);
                            $('input[name="total"]').val(total);
                            $('#amountTotal' + counter).val(subTotal);
                        });
                        $('.discountPercent' + counter).keyup(function () {
                            amountTotal = parseInt($('#amountTotal' + counter).val(), 10);
                            totalAmount = (parseInt($('.totalSum').val(), 10) - amountTotal);
                            let prices = $('.quantityPrice' + counter).val();
                            let quantities = $('.productQuantity' + counter).val();
                            let discount = $(this).val();
                            let x = (prices * quantities);
                            subTotal = (x - discount);
                            total = (totalAmount + subTotal);
                            $('input[name="total"]').val(total);
                            $('#amountTotal' + counter).val(subTotal);
                            // calculateTotal();
                        });
                    }
                    @endforeach
                });
                $('.removeData' + counter).on('click', function () {
                        total = (parseInt($('.totalSum').val(), 10) - parseInt($('#amountTotal' + counter).val(), 10));
                        $('input[name="total"]').val(total);
                        $(this).closest('tr').remove();
                    }
                );
            });
        });


    </script>
    <script>
        /* select customer type and list customers according to type */
        $("select.customerType").change(function () {
            let selectedLevel = $(this).children("option:selected").val();
            if (selectedLevel === "local") {
                $('#customerTypeList').empty();
                $('#customerTypeList').css('display', 'block').append(`
                    <label><h4>Write Local Customer Name</h4></label>
                    <input type="text" class="form-control" placeholder="Write Local Customer Name" name="customer_name" required>
                    <div class="form-control-focus"></div>
                `);
                $('#clientList').css('display', 'none');
            } else if (selectedLevel === "employ") {
                $('#customerTypeList').empty();
                $('#customerTypeList').css('display', 'block').append(`
                    <label><h4>Select Gym Employees</h4></label>
                    <select id="clientList" name="customer_name" required>
                        <option value="">Select Employ</option>
                        @foreach($employees as $employ)
                <option value="{{$employ->id}}">{{$employ->first_name. ' ' . $employ->middle_name . ' ' . $employ->last_name}}</option>
                        @endforeach
                </select>
               `);
                $('#clientList').addClass('bs-select form-control').attr('data-live-search', 'true').attr('data-size', '8');
            } else if (selectedLevel === "client") {
                $('#customerTypeList').empty();
                $('#customerTypeList').css('display', 'block').append(`
                    <label><h4>Select Gym Clients</h4></label>
                    <select id="clientList" data-live-search="true" data-size="8" name="customer_name" required>
                        <option value="">Select Gym Clients</option>
                        @foreach($clients as $employ)
                <option value="{{$employ->customer_id}}">{{$employ->first_name. ' ' . $employ->middle_name . ' ' . $employ->last_name}}</option>
                        @endforeach
                </select>
`);
                $('#clientList').addClass('bs-select form-control').attr('data-live-search', 'true').attr('data-size', '8');
            }
        });
    </script>
    <script>
        /* product select and list the availability and other info */
        $("select#selectProduct").change(function () {
            let selectedProduct = $(this).children("option:selected").val();
            @foreach($products as $product)
                items = <?php echo $product->id;  ?>;
            if (items == selectedProduct) {
                $('.quantityData').empty();
                @if(($product->quantity - ($product->quantity_expired + $product->quantity_damaged + $product->quantity_sold)) < 50)
                $('.quantityData').css('color', 'red');
                @else
                $('.quantityData').css('color', 'green');
                @endif
                $('.quantityData').append(`
                            ( {{$product->quantity - ($product->quantity_expired + $product->quantity_damaged + $product->quantity_sold)}} left )
                        `);
                $('.quantityPrice').css('text-align', 'center').val({{$product->price}});
                $('.productQuantity').attr('max',{{ $product->quantity - ($product->quantity_expired + $product->quantity_damaged + $product->quantity_sold) }})
                $('.productQuantity').keyup(function () {
                    amountTotal = parseInt($('#amountTotal').val(), 10);
                    totalAmount = (parseInt($('.totalSum').val(), 10) - amountTotal);
                    let prices = $('.quantityPrice').val();
                    let quantities = $(this).val();
                    let discount = $('.discountPercent').val();
                    let x = (prices * quantities);
                    subTotal = (x - discount);
                    total = (subTotal + totalAmount);
                    $('#amountTotal').val(parseInt(subTotal), 10);
                    $('.totalSum').val(total);
                });
                $('.discountPercent').keyup(function () {
                    let prices = $('.quantityPrice').val();
                    let quantities = $('.productQuantity').val();
                    let discount = $(this).val();
                    let x = (prices * quantities);
                    subTotal = (x - discount);
                    $('#amountTotal').val(subTotal);
                    $('.totalSum').val(subTotal);
                });
            }
            @endforeach
        });
    </script>
@endsection
