@extends('layouts.gym-merchant.gymbasic')

@section('CSS')
    <link rel="stylesheet" href="{{ asset("admin/global/plugins/ladda/ladda-themeless.min.css") }}">
    <link rel="stylesheet" href="{{ asset("admin/global/plugins/bootstrap-select/css/bootstrap-select.min.css") }}">
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
                <a href="{{ route('gym-admin.membership-payment.index') }}">Payments</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>Add Payment</span>
            </li>
        </ul>
        <!-- END PAGE BREADCRUMBS -->
        <!-- BEGIN PAGE CONTENT INNER -->
        <div class="page-content-inner">

            <div class="row">
                <div class="col-md-7 col-xs-12">

                    <div class="portlet light portlet-fit">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="icon-plus font-red"></i><span class="caption-subject font-red bold uppercase">Add Payment</span></div>
                        </div>
                        <div class="portlet-body">
                            <!-- BEGIN FORM-->
                            {{ html()->form->open(['id'=>'storePayments','class'=>'ajax-form','method'=>'POST']) !!}
                            <div class="form-body">

                                <div class="form-group form-md-line-input ">
                                    <select class="bs-select form-control" data-live-search="true" data-size="8" name="client" id="client">
                                        <option value="">Select Client</option>
                                        @foreach($clients as $client)
                                            <option value="{{$client->customer_id}}" @if(count($clients) == 1) selected @endif>{{$client->fullName}}</option>
                                        @endforeach
                                    </select>
                                    <label for="title">Client Name <span class="required" aria-required="true"> * </span></label>
                                    <span class="help-block"></span>
                                </div>

                                @if($userMembership)
                                    <div class="form-group form-md-line-input ">
                                        <select  class="form-control" name="purchase_id" id="purchase_id">
                                            @foreach($purchases as $purc)
                                            <option value="{{$purc->id}}">{{ ucwords($purc->membership->title) }} [{{ $purc->membership->duration }} {{ $purc->membership->duration_type }}] - [Purchased on: {{$purc->purchase_date->format('d-M')}}]</option>
                                            @endforeach
                                        </select>
                                        <label for="title">Payment For</label>
                                        <span class="help-block"></span>
                                    </div>
                                @endif
                                <div id="payment_for_area">

                                </div>

                                <div class="form-group form-md-line-input form-md-floating-label">
                                    <div class="input-group left-addon right-addon">
                                        <span class="input-group-addon">{{ $gymSettings->currency->acronym }}</span>
                                        <input type="text" class="form-control" name="payment_amount" id="payment_amount">
                                        <span class="help-block">Enter Amount</span>
                                        <span class="input-group-addon">.00</span>
                                        <label for="price">Payment Amount <span class="required" aria-required="true"> * </span></label>
                                    </div>
                                </div>

                                <div id="remaining_div">
                                    <div class="form-group form-md-line-input form-md-floating-label">
                                        <div class="input-group left-addon right-addon">
                                            <span class="input-group-addon">{{ $gymSettings->currency->acronym }}</span>
                                            <input disabled type="number" min="0" class="form-control" name="remaining_amount" value="{{ $amount ?? 0 }}" id="remaining_amount">
                                            <input disabled type="hidden" class="form-control" name="remaining_amount_store" value="{{ $amount ?? 0 }}" id="remaining_amount_store">
                                            <span class="input-group-addon">.00</span>
                                            <label for="price">Remaining Amount</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group form-md-line-input">
                                    <div class="form-group form-md-radios">
                                        <label>Payment Source? <span class="required" aria-required="true"> * </span></label>
                                        <div class="md-radio-inline">
                                            @foreach($paymentSources as $key=> $source)
                                            <div class="md-radio">
                                                <input type="radio" value="{{$key}}" id="{{$key}}_radio" name="payment_source" class="md-radiobtn">
                                                <label for="{{$key}}_radio">
                                                    <span></span>
                                                    <span class="check"></span>
                                                    <span class="box"></span> <i class="fa fa-{{$source['icon']}}"></i> {{$source['label']}} </label>
                                            </div>
                                            @endforeach
                                        </div>

                                    <span class="help-block"></span>
                                </div>


                                <div class="form-group form-md-line-input ">
                                    <div class="input-group left-addon right-addon">
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                        <input type="text" class="form-control date-picker" readonly name="payment_date" id="payment_date"
                                               value="{{ \Carbon\Carbon::today()->format('m/d/Y') }}">
                                        <label for="payment_date">Payment Date</label>
                                    </div>
                                </div>

                                <div id="onlyMembership">
                                    <div class="form-group form-md-line-input">
                                        <div class="form-group form-md-radios">
                                            <label>More Payment Required</label>
                                            <div class="md-radio-inline">
                                                <div class="md-radio">
                                                    <input type="radio" value="yes" id="yes_radio" name="payment_required" class="md-radiobtn" >
                                                    <label for="yes_radio">
                                                        <span></span>
                                                        <span class="check"></span>
                                                        <span class="box"></span> Yes </label>
                                                </div>
                                                <div class="md-radio ">
                                                    <input type="radio" value="no" id="no_radio" name="payment_required" class="md-radiobtn">
                                                    <label for="no_radio">
                                                        <span></span>
                                                        <span class="check"></span>
                                                        <span class="box"></span> No </label>
                                                </div>
                                            </div>
                                        </div>
                                        <span class="help-block"></span>
                                    </div>

                                    <div class="form-group form-md-line-input " id="next_payment_div">
                                        <div class="input-group left-addon right-addon">
                                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                            <input type="text" class="form-control date-picker" data-date-today-highlight="true" value="{{ \Carbon\Carbon::today()->addDays(1)->format('m/d/Y') }}" readonly name="next_payment_date" id="next_payment_date">
                                            <label for="payment_date">Due Payment Date</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group form-md-line-input ">
                                            <div class="input-icon">
                                                <input type="text" class="form-control" placeholder="Remarks" name="remarks" id="remarks">
                                                <label for="form_control_1">Remark</label>
                                                <span class="help-block">Add payment remark</span>
                                                <i class="fa fa-pencil"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="form-actions" style="margin-top: 70px">
                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="button" class="btn dark mt-ladda-btn ladda-button" data-style="zoom-in" id="save-form">
                                            <span class="ladda-label"><i class="fa fa-save"></i> SAVE</span>
                                        </button>
                                        <button type="reset" class="btn default">Reset</button>
                                    </div>
                                </div>
                            </div>
                        {{ html()->form->close() !!}
                        <!-- END FORM-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END PAGE CONTENT INNER -->
    </div>

    <div class="modal fade" id="basic" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body"></div>
                <div class="modal-footer">
                    <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
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

        $("document").ready(function () {
           $('#next_payment_div').css('display', 'none');
        });

        $('#storePayments').on('change', '#purchase_id', function () {
            var price = $('#purchase_id :selected').data('price');
            $('#payment_amount').val();
            $('#payment_amount').attr('max',price);
            $('#remaining_amount').val(price);
            $('#remaining_amount_store').val(price);
        });

        $('#payment_amount').on("input", function () {
            var amount = this.value;
            var clientId = $("#client").val();
            var remaining = $("#remaining_amount_store").val() - amount;
            $("#remaining_amount").addClass("edited");
            if (parseFloat(remaining) < 0) {
                remaining = 0;
            }
            $("#remaining_amount").val(remaining);
            if(remaining == 0){
                $("#no_radio").attr('checked','checked');
                $('#next_payment_div').css('display', 'none');
            }else{
                $("#yes_radio").attr('checked','checked');
                $('#next_payment_div').css('display', 'block');
            }
        });

        $("input[name='payment_required']").change(function () {
            var type = $("input[name='payment_required']:checked").val();
            var remainingAmount = $('#remaining_amount').val();
            if (type == 'yes') {
                if (remainingAmount == 0) {
                    $('.modal-title').text('Note');
                    $('.modal-body').text('You have no remaining but still saying there is some payment left.');
                    $('#basic').modal('show');
                }
            } else {
                if (remainingAmount > 0) {
                    $('.modal-title').text('Note');
                    $('.modal-body').text('You have set remaining to no but still there is some amount remaining.');
                    $('#basic').modal('show');
                    $('#next_payment_div').css('display', 'block');
                }
            }
        });

        $('#client').change(function () {
            var clientId = $(this).val();
            if (clientId == "") return false;
            var url = "{{route('gym-admin.gympurchase.clientPurchases',[':id'])}}";
            url = url.replace(':id', clientId);

            $.easyAjax({
                url: url,
                type: 'GET',
                data: {clientID: clientId},
                success: function (response) {
                    $('#payment_for_area').html(response.data);
                    $('#payment_amount').val(0);
                    $('#remaining_amount').val(0);
                    $('#remaining_amount_store').val(0);
                }
            })
        });
    </script>
    <script>
        $('#save-form').click(function () {
            var type = $("input[name='payment_required']:checked").val();

            $.easyAjax({
                url: "{{route('gym-admin.membership-payment.store')}}",
                container: '#storePayments',
                type: "POST",
                data: $('#storePayments').serialize(),
                success: function (responce) {
                    if (responce.status == 'success') {
                        clear_form_elements('storePayments')
                    }
                }
            })
        });

    </script>
@stop
