@extends('layouts.gym-merchant.gymbasic')

@section('CSS')
    <link rel="stylesheet" href="{{ asset("admin/global/plugins/ladda/ladda-themeless.min.css") }}">
    <link rel="stylesheet" href="{{ asset("admin/global/plugins/bootstrap-select/css/bootstrap-select.min.css") }}">
    <link rel="stylesheet" href="{{ asset("admin/global/plugins/typeahead/typeahead.css") }}">
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
                <span>Create Payment Invoice</span>
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
                                <i class="icon-note font-red"></i><span class="caption-subject font-red bold uppercase">Create Payment Invoice</span></div>


                        </div>
                        <div class="portlet-body">
                            <!-- BEGIN FORM-->
                            {{ html()->form()->open(['id'=>'storePayments','class'=>'ajax-form','method'=>'POST']) }}
                            <input type="hidden" name="type" value="{{$type}}">
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">Bill To</label>
                                            <input type="text" class="form-control" name="client_name" id="client_name" placeholder="Enter Client Name"
                                                   value="{{ ucwords($payment->client->first_name.' '.$payment->client->middle_name.' '.$payment->client->last_name) }}">
                                        </div>

                                    </div>
                                    <div class="col-md-6">

                                        <div class="form-group">
                                            <label class="control-label">Invoice Date</label>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="input-icon">
                                                        <i class="fa fa-calendar"></i>
                                                        <input type="text" class="form-control date-picker" readonly name="invoice_date" id="invoice_date"
                                                               value="{{ $payment->payment_date->format('m/d/Y') }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">Client Email</label>
                                            <input type="text" class="form-control" name="email" id="email" value="{{ $payment->client->email }}"
                                                   placeholder="Enter Client Email">
                                        </div>

                                    </div>
                                    <div class="col-md-6">

                                        <div class="form-group">
                                            <label class="control-label">Mobile</label>

                                            <input type="text" class="form-control" name="mobile" id="mobile" value="{{ $payment->client->mobile }}"
                                                   placeholder="Enter Client Mobile">
                                        </div>

                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label">Client Address</label>
                                            <textarea class="form-control" placeholder="Enter client address" name="client_address"
                                                      id="client_address">{{ $payment->client->address }}</textarea>
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
                                            DISCOUNT
                                        </div>

                                        <div class="col-md-2 bg-dark bg-font-dark text-center"
                                             style="padding: 8px 15px">
                                            PAID AMOUNT
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
                                                           placeholder="Item Name" value="{{ ucwords($item_name) }}" readonly>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="row">
                                                <div class="form-group">
                                                    <label class="control-label hidden-md hidden-lg">Quantity</label>
                                                    <input type="number" min="0" class="form-control quantity" value="1"
                                                           name="quantity[]" placeholder="Quantity" readonly>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="row">
                                                <div class="form-group">
                                                    <label class="control-label hidden-md hidden-lg">Rate</label>
                                                    <input type="number" min="" class="form-control cost_per_item"
                                                           name="cost_per_item[]" value="{{ $item_rate }}" placeholder="Cost per item" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="row">
                                                <div class="form-group">
                                                    <label class="control-label hidden-md hidden-lg">Discount</label>
                                                    <input type="number" min="" class="form-control discount"
                                                           name="discount[]" value="{{ $item_discount }}" placeholder="Cost per item" readonly>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="col-md-2 border-dark  text-center">
                                            <label class="control-label hidden-md hidden-lg">Amount</label>

                                            <p class="form-control-static">{{ $gymSettings->currency->acronym }} <span
                                                        class="amount-html">{{ $item_price }}</span></p>
                                            <input type="hidden" class="amount" name="amount[]" value="{{ $item_price }}">
                                        </div>

                                    </div>

                                    <div id="item-list">

                                    </div>

                                    <div class="col-xs-12 ">


                                        <div class="row">
                                            <div class="col-md-offset-9 col-xs-6 col-md-1 text-right padding-top-5">Subtotal</div>

                                            <p class="form-control-static col-xs-6 col-md-2">
                                                {{ $gymSettings->currency->acronym }} <span class="sub-total">{{ $item_price }}</span>
                                            </p>


                                            <input type="hidden" class="sub-total-field" value="{{ $item_price }}" name="sub_total">
                                        </div>

                                        <div class="row margin-top-5 sbold">
                                            <div class="col-md-offset-9 col-md-1 col-xs-6 text-right padding-top-5">Total</div>

                                            <p class="form-control-static col-xs-6 col-md-2">
                                                {{ $gymSettings->currency->acronym }} <span class="total">{{ $item_price-$discount }}</span>
                                            </p>


                                            <input type="hidden" class="total-field" value="{{ $item_price-$discount }}" name="total">
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

                                        <div class="row margin-top-5">
                                            <div class="form-group col-md-2 col-md-offset-10">
                                                <label class="control-label">Invoice Generated By</label>

                                                <input type="text" readonly class="form-control" name="generated_by" id="generated_by"
                                                       value="{{ ucwords($user->first_name.' '.$user->middle_name.' '.$user->last_name) }}">
                                                <span class="help-block"><em>*This cannot be changed</em></span>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-actions" style="margin-top: 70px">
                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn dark mt-ladda-btn ladda-button" data-style="zoom-in" id="save-form">
                                            <span class="ladda-label"><i class="fa fa-save"></i> SAVE</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        {{ html()->form()->close() }}
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

    <script src="{{ asset("admin/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js") }}"></script>
    <link rel="stylesheet" href="{{ asset("admin/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css") }}">
    <script src="{{ asset("admin/global/plugins/ladda/spin.min.js") }}"></script>
    <script src="{{ asset("admin/global/plugins/ladda/ladda.min.js") }}"></script>
    <script src="{{ asset("admin/pages/scripts/ui-buttons.min.js") }}"></script>
    <script src="{{ asset("admin/global/plugins/bootstrap-select/js/bootstrap-select.min.js") }}"></script>
    <script src="{{ asset("admin/pages/scripts/components-bootstrap-select.min.js") }}"></script>

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

    </script>
@stop
