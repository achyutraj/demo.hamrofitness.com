@extends('layouts.gym-merchant.gymbasic')

@section('CSS')
    {!! HTML::style('admin/global/plugins/ladda/ladda-themeless.min.css') !!}
    {!! HTML::style('admin/global/plugins/bootstrap-select/css/bootstrap-select.min.css') !!}
    {!! HTML::style('admin/global/plugins/typeahead/typeahead.css') !!}
    <style>
        .item-type-padding {
            padding-left: 12px;
        }
    </style>
@stop

@section('content')
    <div class="container-fluid">
        <!-- BEGIN PAGE BREADCRUMBS -->
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <a href="{{ route('gym-admin.dashboard.index') }}">Home</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <a href="{{ route('gym-admin.gym-invoice.index') }}">Invoices</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>Create Invoice</span>
            </li>
        </ul>
        <!-- END PAGE BREADCRUMBS -->
        <!-- BEGIN PAGE CONTENT INNER -->
        <div class="page-content-inner">

            <div class="row">
                <div class="col-xs-12">

                    <div class="portlet light portlet-fit">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="icon-note font-red"></i><span class="caption-subject font-red bold uppercase">Create Invoice</span>
                            </div>


                        </div>
                        <div class="portlet-body">
                            <!-- BEGIN FORM-->
                            {!! Form::open(['id'=>'storePayments','class'=>'ajax-form','method'=>'POST']) !!}
                            <div class="form-body">
                                <input type="hidden" name="type" value="{{$type}}">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">Bill To<span class="required"
                                                                                       aria-required="true"> * </span></label>
                                            <input type="text" class="form-control client_name" name="client_name" id="client_name"
                                                   placeholder="Enter Client Name">
                                        </div>

                                    </div>
                                    <div class="col-md-6">

                                        <div class="form-group">
                                            <label class="control-label">Invoice Date <span class="required"
                                                                                            aria-required="true"> * </span></label>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="input-icon">
                                                        <i class="fa fa-calendar"></i>
                                                        <input type="text" readonly data-provide="datepicker"
                                                            data-date-end-date="0d" data-date-today-highlight="true"
                                                            class="form-control date-picker"
                                                            value="{{ \Carbon\Carbon::today()->format('m/d/Y') }}"
                                                            name="invoice_date" id="invoice_date">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">Client Email <span class="required"
                                                aria-required="true"> * </span> </label>
                                            <input type="text" class="form-control email" name="email" id="email"
                                                   placeholder="Enter Client Email">
                                        </div>

                                    </div>
                                    <div class="col-md-6">

                                        <div class="form-group">
                                            <label class="control-label">Mobile <span class="required"
                                                aria-required="true"> * </span></label>

                                            <input type="text" class="form-control phone" name="mobile" id="mobile"
                                                   placeholder="Enter Client Mobile">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label">Client Address <span class="required"
                                                                                              aria-required="true"> * </span></label>
                                            <textarea class="form-control client_address" placeholder="Enter client address"
                                                      name="client_address" id="client_address"></textarea>
                                        </div>
                                    </div>
                                </div>

                                <hr>

                                <div class="row">

                                    <div class="col-xs-12  visible-md visible-lg">

                                        <div class="col-md-3 bg-dark bg-font-dark" style="padding: 8px 15px">
                                            ITEM
                                        </div>

                                        <div class="col-md-2 bg-dark bg-font-dark" style="padding: 8px 15px">
                                            QUANTITY
                                        </div>

                                        <div class="col-md-2 bg-dark bg-font-dark" style="padding: 8px 15px">
                                            RATE
                                        </div>

                                        <div class="col-md-2 bg-dark bg-font-dark" style="padding: 8px 15px">
                                            DISCOUNT(%)
                                        </div>

                                        <div class="col-md-2 bg-dark bg-font-dark text-center"
                                             style="padding: 8px 15px">
                                            AMOUNT
                                        </div>

                                        <div class="col-md-1 bg-dark bg-font-dark" style="padding: 8px 15px">
                                            &nbsp;
                                        </div>

                                    </div>

                                    <div class="col-xs-12 item-row margin-top-5">

                                        <div class="col-md-3">
                                            <div class="row">
                                                <div class="form-group">
                                                    <label class="control-label hidden-md hidden-lg">Item Name</label>
                                                    <input type="text" class="form-control item_name" name="item_name[]"
                                                           placeholder="Item Name">
                                                </div>
                                            </div>

                                        </div>

                                        <div class="col-md-2">

                                            <div class="form-group">
                                                <label class="control-label hidden-md hidden-lg">Quantity</label>
                                                <input type="number" min="0" class="form-control quantity" value="0"
                                                       name="quantity[]" placeholder="Quantity">
                                            </div>

                                        </div>

                                        <div class="col-md-2">
                                            <div class="row">
                                                <div class="form-group">
                                                    <label class="control-label hidden-md hidden-lg">Rate</label>
                                                    <input type="number" min="" class="form-control cost_per_item"
                                                           name="cost_per_item[]" value="0" placeholder="Cost per item">
                                                </div>
                                            </div>

                                        </div>

                                        <div class="col-md-2">
                                            <div class="row">
                                                <div class="form-group item-type-padding">
                                                    <label class="control-label hidden-md hidden-lg">Discount</label>
                                                    <input type="number" min="0" max="100" class="form-control discount"
                                                           name="discount[]" value="0" placeholder="Discount">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-2 border-dark  text-center">
                                            <label class="control-label hidden-md hidden-lg">Amount</label>

                                            <p class="form-control-static">{{ $gymSettings->currency->acronym }} <span
                                                        class="amount-html">0</span></p>
                                            <input type="hidden" class="amount" name="amount[]">
                                        </div>

                                        <div class="col-md-1 text-right visible-md visible-lg">
                                            <button class="btn remove-item btn-icon-only red"><i
                                                        class="fa fa-remove"></i></button>
                                        </div>
                                        <div class="col-md-1 hidden-md hidden-lg">
                                            <div class="row">
                                                <button class="btn btn-block remove-item red"><i
                                                            class="fa fa-remove"></i> Remove
                                                </button>
                                            </div>
                                        </div>

                                    </div>

                                    <div id="item-list">

                                    </div>

                                    <div class="col-xs-12 margin-top-5">
                                        <button class="btn blue" id="add-item"><i class="fa fa-plus"></i> Add Item
                                        </button>
                                    </div>

                                    <div class="col-xs-12 ">
                                        <div class="row">
                                            <div class="col-md-offset-9 col-xs-6 col-md-1 text-right padding-top-5">
                                                Subtotal
                                            </div>

                                            <p class="form-control-static col-xs-6 col-md-2">
                                               {{ $gymSettings->currency->acronym}} <span class="sub-total">0</span>
                                            </p>


                                            <input type="hidden" class="sub-total-field" name="sub_total">
                                        </div>

                                        <div class="row">
                                            <div class="col-md-offset-9 col-md-1 text-right padding-top-5">
                                                Tax ({{$tax[0]->gstin}})%
                                            </div>
                                            <p class="form-control-static col-xs-6 col-md-2">
                                               {{ $gymSettings->currency->acronym }} <span class="tax">0</span>
                                            </p>
                                            <input type="hidden" class="tax-field" name="tax">
                                        </div>

                                        <div class="row margin-top-5 sbold">
                                            <div class="col-md-offset-9 col-md-1 col-xs-6 text-right padding-top-5">
                                                Total
                                            </div>

                                            <p class="form-control-static col-xs-6 col-md-2">
                                               {{ $gymSettings->currency->acronym }} <span class="total">0</span>
                                            </p>


                                            <input type="hidden" class="total-field" name="total">
                                        </div>

                                        <div class="row margin-top-5">
                                            <div class="form-group col-md-2 col-md-offset-10">
                                                <label class="control-label">Invoice Generated By</label>

                                                <input type="text" readonly class="form-control" name="generated_by"
                                                       id="generated_by"
                                                       value="{{ ucwords($user->first_name.' '.$user->middle_name.' '.$user->last_name) }}">
                                                <span class="help-block"><em>*This cannot be changed</em></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label">Remarks <span class="required"
                                                                                              aria-required="true"> </span></label>
                                            <textarea class="form-control" placeholder="Enter Invoice Remarks"
                                                      name="remarks" id="remarks"></textarea>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                            <div class="form-actions" style="margin-top: 70px">
                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="button" class="btn dark mt-ladda-btn ladda-button"
                                                data-style="zoom-in" id="save-form">
                                            <span class="ladda-label"><i class="fa fa-save"></i> SAVE</span>
                                        </button>
                                        <button type="reset" class="btn default reset-btn">Reset</button>
                                    </div>
                                </div>
                            </div>
                            {!! Form::close() !!}
                                    <!-- END FORM-->
                        </div>
                    </div>


                </div>
            </div>
        </div>
        <!-- END PAGE CONTENT INNER -->
    </div>
@stop

@section('footer')

    {!! HTML::script('admin/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') !!}
    {!! HTML::style('admin/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') !!}
    {!! HTML::script('admin/global/plugins/ladda/spin.min.js') !!}
    {!! HTML::script('admin/global/plugins/ladda/ladda.min.js') !!}
    {!! HTML::script('admin/pages/scripts/ui-buttons.min.js') !!}
    {!! HTML::script('admin/global/plugins/bootstrap-select/js/bootstrap-select.min.js') !!}
    {!! HTML::script('admin/pages/scripts/components-bootstrap-select.min.js') !!}
    {!! HTML::script('admin/global/plugins/typeahead/handlebars.min.js') !!}
    {!! HTML::script('admin/global/plugins/typeahead/typeahead.bundle.min.js') !!}

    <script>
        var ComponentsTypeahead = function () {

            var handleTwitterTypeahead = function () {

                // Example #1
                // instantiate the bloodhound suggestion engine
                var numbers = new Bloodhound({
                    datumTokenizer: function (d) {
                        return Bloodhound.tokenizers.whitespace(d.title);
                    },
                    queryTokenizer: Bloodhound.tokenizers.whitespace,
                    local: [
                            @foreach($memberships as $mem)
                            {
                            title: '{{ ucfirst($mem->title) }} - NPR {{ $mem->price}}',
                        },
                        @endforeach
                    ]
                });

                // initialize the bloodhound suggestion engine
                numbers.initialize();
                
                // instantiate the typeahead UI
                $('.item_name').typeahead(null, {
                    displayKey: 'title',
                    hint: (App.isRTL() ? false : true),
                    source: numbers.ttAdapter()
                });
            }

            return {
                //main function to initiate the module
                init: function () {
                    handleTwitterTypeahead();
                }
            };

        }();

        jQuery(document).ready(function () {
            ComponentsTypeahead.init();
        });
    </script>

    <script>
        var ClientsComponentsTypeahead = function () {

            var handleTwitterTypeahead = function () {
                // instantiate the bloodhound suggestion engine
                var numbers = new Bloodhound({
                    datumTokenizer: function (d) {
                        return Bloodhound.tokenizers.whitespace(d.name);
                    },
                    queryTokenizer: Bloodhound.tokenizers.whitespace,
                    local: [
                            @foreach($clients as $mem)
                            {
                            name: '{{ ucfirst($mem->first_name." ".$mem->middle_name." ".$mem->last_name) }}',
                            address: '{{ $mem->address }}',
                            mobile: '{{ $mem->mobile }}',
                            email: '{{ $mem->email }}',
                        },
                        @endforeach
                    ]
                });
                // initialize the bloodhound suggestion engine
                numbers.initialize();
                // instantiate the typeahead UI
                $('#client_name').typeahead(null, {
                    displayKey: 'name',
                    hint: (App.isRTL() ? false : true),
                    source: numbers.ttAdapter()
                }).on('typeahead:selected', function(event, suggestion) {
                    $('#client_address').val(suggestion.address);
                    $('#email').val(suggestion.email);
                    $('#mobile').val(suggestion.mobile);
                });
            }

            return {
                //main function to initiate the module
                init: function () {
                    handleTwitterTypeahead();
                }
            };

        }();

        jQuery(document).ready(function () {
            ClientsComponentsTypeahead.init();
        });
    </script>

    <script>
        $('.date-picker').datepicker({
            rtl: App.isRTL(),
            orientation: "left",
            autoclose: true
        });

        $('#save-form').click(function () {
            $.easyAjax({
                url: "{{route('gym-admin.gym-invoice.save-invoice')}}",
                container: '#storePayments',
                type: "POST",
                redirect: true,
                data: $('#storePayments').serialize()
            })
        });

        $('#add-item').click(function () {
            var item = '<div class="col-xs-12 item-row margin-top-5">'

                    + '<div class="col-md-3">'
                    + '<div class="row">'
                    + '<div class="form-group">'
                    + '<label class="control-label hidden-md hidden-lg">Item Name</label>'
                    + '<input type="text" class="form-control item_name" name="item_name[]" placeholder="Item Name">'
                    + '</div>'
                    + '</div>'
                    + '</div>'

                    + '<div class="col-md-2">'
                    + '<div class="form-group">'
                    + '<label class="control-label hidden-md hidden-lg">Quantity</label>'
                    + '<input type="number" min="0" class="form-control quantity" value="0" name="quantity[]" placeholder="Quantity">'
                    + '</div>'


                    + '</div>'

                    + '<div class="col-md-2">'
                    + '<div class="row">'
                    + '<div class="form-group">'
                    + '<label class="control-label hidden-md hidden-lg">Rate</label>'
                    + '<input type="number" min="0" class="form-control cost_per_item" value="0" name="cost_per_item[]" placeholder="Cost per item">'
                    + '</div>'
                    + '</div>'

                    + '</div>'

                    +'<div class="col-md-2">'
                    +'<div class="row">'
                    +'<div class="form-group item-type-padding">'
                    +'<label class="control-label hidden-md hidden-lg">Discount</label>'
                    +'<input type="number" min="" class="form-control discount" name="discount[]" value="0" min="0" max="100" placeholder="Discount">'
                    +'</div>'
                    +'</div>'
                    +'</div>'

                    + '<div class="col-md-2 text-center">'
                    + '<label class="control-label hidden-md hidden-lg">Amount</label>'
                    + '<p class="form-control-static">{{ $gymSettings->currency->acronym }} <span class="amount-html">0</span></p>'
                    + '<input type="hidden" class="amount" name="amount[]">'
                    + '</div>'

                    + '<div class="col-md-1 text-right visible-md visible-lg">'
                    + '<button class="btn remove-item btn-icon-only red"><i class="fa fa-remove"></i></button>'
                    + '</div>'

                    + '<div class="col-md-1 hidden-md hidden-lg">'
                    + '<div class="row">'
                    + '<button class="btn btn-block remove-item red"><i class="fa fa-remove"></i> Remove</button>'
                    + '</div>'
                    + '</div>'

                    + '</div>';

            $('.item_name').typeahead('destroy'); //need to destroy & reinitialize for dynamic element
            $(item).hide().appendTo("#item-list").fadeIn(500);
            ComponentsTypeahead.init();

        });

        $('#storePayments').on('click', '.remove-item', function () {
            $(this).closest('.item-row').fadeOut(300, function () {
                $(this).remove();
                calculateTotal();
            });
        });

        $('#storePayments').on('keyup', '.quantity,.cost_per_item,.discount', function () {
            var discount_amt = 0;
            var quantity = $(this).closest('.item-row').find('.quantity').val();

            var perItemCost = $(this).closest('.item-row').find('.cost_per_item').val();

            var discount = $(this).closest('.item-row').find('.discount').val();

            var amount = (quantity * perItemCost); // multiply rate * qty

            if(discount != 0){
                discount_amt = (discount/100) * amount;
                discount_amt = (amount - discount_amt);
                $(this).closest('.item-row').find('.amount').val(discount_amt.toFixed(2));
                $(this).closest('.item-row').find('.amount-html').html(discount_amt.toFixed(2));
            }else{
                $(this).closest('.item-row').find('.amount').val(amount.toFixed(2));
                $(this).closest('.item-row').find('.amount-html').html(amount.toFixed(2));
            }
            calculateTotal();
        });

        function calculateTotal() {

            var subtotal = 0;
            var tax = 0;
            var tax_rate = '{{$tax[0]->gstin}}';

            //region Check Item Type
            $(".quantity").each(function (index, element) {
                var amount1 = $(this).closest('.item-row').find('.amount').val();
                subtotal = parseFloat(subtotal) + parseFloat(amount1);
                tax = ((tax_rate/100)*subtotal);
                tax.toFixed(2);
            });
            //endregion

            //region Sub Total
            $('.sub-total').html(subtotal.toFixed(2));
            $('.sub-total-field').val(subtotal.toFixed(2));
            //endregion

            //region Tax
            $('.tax').html(tax.toFixed(2));
            $('.tax-field').val(tax.toFixed(2));
            //endregion

            var total = subtotal + tax;

            //region Total Amount
            $('.total').html(total.toFixed(2));
            $('.total-field').val(total.toFixed(2));
            //endregion
        }

        $('.reset-btn').click(function () {
            $('.amount-html').text('');
            $('.sub-total').text('');
            $('.total').text('');
        });

    </script>
@stop