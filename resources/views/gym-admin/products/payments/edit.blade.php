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
                <a href="{{ route('gym-admin.product-payments.index') }}">Product Payments</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>Edit Product Payment</span>
            </li>
        </ul>
        <!-- END PAGE BREADCRUMBS -->
        <!-- BEGIN PAGE CONTENT INNER -->
        <div class="page-content-inner">
            <div class="row">
                <div class="col-md-7">

                    <div class="portlet light portlet-fit">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="icon-pencil font-red"></i><span class="caption-subject font-red bold uppercase">Edit Product Payment</span></div>


                        </div>
                        <div class="portlet-body">
                            <!-- BEGIN FORM-->
                            {{ html()->form->open(['id'=>'updatePayments','class'=>'ajax-form','method'=>'POST']) !!}
                            <div class="form-body">
                                <input type="hidden" name="_method" value="put">

                                <div class="form-group form-md-line-input ">
                                    {{ $payment->client->first_name.' '.$payment->client->middle_name.' '.$payment->client->last_name }}
                                </div>
                                <input type="hidden" name="client" value="{{$payment->client->first_name    }}">

                                <div class="form-group form-md-line-input " id="purchase_select">
                                    @php
                                        $product_name = '';
                                        $arr['product_name'] = json_decode($payment->product_sale->product_name,true);
                                        $j= count($arr['product_name']);
                                        for($i=0;$i<$j;$i++){
                                            $product = App\Models\Product::find($arr['product_name'][$i]);
                                            if($product != null){
                                                $product_name .= $product->name;
                                            }
                                        }

                                    @endphp
                                    <select class="bs-select form-control" data-live-search="true" data-size="8" name="product_sale_id"
                                            id="product_sale_id">
                                        @forelse($purchases as $purc)
                                            <option @if($purc->id == $payment->product_sale_id) selected
                                                    @endif value="{{$purc->id}}">{{ $product_name }} - [Purchased
                                                on: {{$purc->created_at->format('d-M')}}]
                                            </option>
                                        @empty
                                            <option value="">No product purchase by this client</option>
                                        @endforelse
                                    </select>
                                    <label for="title">Payment For</label>
                                    <span class="help-block"></span>
                                </div>
                                <div class="form-group form-md-line-input form-md-floating-label">
                                    <div class="input-group left-addon right-addon">
                                        <span class="input-group-addon">{{ $gymSettings->currency->acronym }} </span>
                                        <input type="text" class="form-control" name="payment_amount" id="payment_amount"
                                               value="{{$payment->payment_amount}}">
                                        <span class="help-block">Enter Amount</span>
                                        <span class="input-group-addon">.00</span>
                                        <label for="price">Payment Amount</label>
                                    </div>
                                </div>

                                <div id="remaining_div">
                                    <div class="form-group form-md-line-input form-md-floating-label">
                                        <div class="input-group left-addon right-addon">
                                            <span class="input-group-addon">{{ $gymSettings->currency->acronym }} </span>
                                            <input disabled type="text" class="form-control" name="remaining_amount" id="remaining_amount"
                                                   value="{{ $remaining_amount }}">
                                            <input disabled type="hidden" class="form-control" name="remaining_amount_store" id="remaining_amount_store"
                                                   value="{{ $remaining_amount }}">
                                            <span class="input-group-addon">.00</span>
                                            <label for="price">Remaining Amount</label>
                                        </div>
                                    </div>
                                </div>


                                <div class="form-group form-md-line-input">
                                    <div class="form-group form-md-radios">
                                        <label>Payment Source?</label>
                                        <div class="md-radio-inline">
                                            @foreach($paymentSources as $key=> $source)
                                                <div class="md-radio">
                                                    <input type="radio" value="{{$key}}" id="{{$key}}_radio" name="payment_source" class="md-radiobtn"
                                                    @if($payment->payment_source == $key) checked @endif >
                                                    <label for="{{$key}}_radio">
                                                        <span></span>
                                                        <span class="check"></span>
                                                        <span class="box"></span> <i class="fa fa-{{$source['icon']}}"></i> {{$source['label']}} </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <span class="help-block"></span>
                                </div>

                                <div class="form-group form-md-line-input ">
                                    <div class="input-group left-addon right-addon">
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                        <input type="text" readonly class="form-control date-picker"
                                               value="{{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$payment->payment_date)->format('m/d/Y')}}"
                                               name="payment_date" id="payment_date">
                                        <label for="payment_date">Payment Date</label>
                                    </div>
                                </div>

                                <div id="onlyMembership" @if($payment->payment_type != null) style="display: none" @endif>
                                    <div class="form-group form-md-line-input">
                                        <div class="form-group form-md-radios">
                                            <label>More Payment Required</label>
                                            <div class="md-radio-inline">
                                                <div class="md-radio">
                                                    <input type="radio" value="yes" id="yes_radio" name="payment_required" class="md-radiobtn"
                                                           @if($payment->payment_required == 'yes') checked @endif>
                                                    <label for="yes_radio">
                                                        <span></span>
                                                        <span class="check"></span>
                                                        <span class="box"></span> Yes </label>
                                                </div>
                                                <div class="md-radio ">
                                                    <input type="radio" value="no" id="no_radio" name="payment_required" class="md-radiobtn"
                                                           @if($payment->payment_required == 'no' || $payment->payment_required == '') checked @endif>
                                                    <label for="no_radio">
                                                        <span></span>
                                                        <span class="check"></span>
                                                        <span class="box"></span> No </label>
                                                </div>
                                            </div>
                                        </div>
                                        <span class="help-block"></span>
                                    </div>

                                    <div class="form-group form-md-line-input " id="next_payment_div" style="display: none">
                                        <div class="input-group left-addon right-addon">
                                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                            <input type="text" class="form-control date-picker" readonly name="next_payment_date" id="next_payment_date" @if($payment->payment_required == 'yes') value="{{\Carbon\Carbon::createFromFormat('Y-m-d',$payment->next_date)->format('m/d/Y')}}" @endif>
                                            <label for="payment_date">Due Payment Date</label>
                                        </div>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group form-md-line-input ">
                                            <div class="input-icon">
                                                <input type="text" class="form-control" placeholder="Remarks" name="remarks" id="remarks"
                                                       value="{{$payment->remarks}}">
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
        @if($payment->payment_required == 'yes')
        $('#next_payment_div').css('display','block');
        @else
        $('#next_payment_div').css('display','none');
        @endif

        $("input[name='payment_required']").change(function(){
            var type = $("input[name='payment_required']:checked").val();
            var remainingAmount = $('#remaining_amount').val();
            if(type == 'yes')
            {
                if(remainingAmount == 0) {
                    $('.modal-title').text('Note');
                    $('.modal-body').text('You have checked remaining payment to yes, as there are no remaining payment');
                    $('#basic').modal('show');
                }
                $('#next_payment_div').css('display','block');
            }else {
                if(remainingAmount > 0) {
                    $('.modal-title').text('Note');
                    $('.modal-body').text('You have checked remaining payment to no, as there are remaining payment');
                    $('#basic').modal('show');
                }
                $('#next_payment_div').css('display','none');
            }
        });
        $("#payment_type").change(function(){
            var type = $("#payment_type option:selected").val();

            if(type != 'membership'){
                $('#purchase_select').css('display','none');
                $('#onlyMembership').css('display','none');
            }else {
                $('#purchase_select').css('display','block');
                $('#onlyMembership').css('display','block');
            }
        });


        $('#payment_amount').on("input", function() {
            var amount = this.value;
            var old_amount ={{$payment->payment_amount}};
            var url = "{{route('gym-admin.product-payments.productEditPayment',[':id'])}}";
            url = url.replace(':id','{{$payment->client->id}}');

            var remaining = $("#remaining_amount_store").val()-(amount-old_amount);
            $("#remaining_amount").addClass("edited");
            if(parseFloat(remaining) < 0){
                remaining = 0;
            }
            $("#remaining_amount").val(remaining);
        });

        $('#storePayments').on('change', '#product_sale_id', function () {
            var purchaseId = $(this).val();
            var url = "{{route('gym-admin.product-payments.remainingPayment',[':id'])}}";
            url = url.replace(':id',purchaseId);
            $.easyAjax({
                url : url,
                type:'GET',
                data: { purchaseId: purchaseId},
                success:function(response)
                {
                    $('#remaining_amount').val(response);
                    $('#remaining_amount_store').val(response);
                }
            })
        });


        function remaining(amount,url,old_amount)
        {
            $.easyAjax({
                url : url,
                type:'GET',
                data: { amount:amount,old_amount:old_amount},
                success:function(response)
                {
                    $("#remaining_amount").addClass("edited");
                    if(parseFloat(response.payment.diff) < 0){
                        response.payment.diff = 0;
                    }
                    $("#remaining_amount").val(response.payment.diff);
                    $("#remaining_amount_store").val(response.payment.diff);
                    if(response.payment.diff > 0)
                    {
                        $('#onlyMembership').css('display','block');
                        $('#next_payment_div').css('display','block');
                        $("#next_payment_date").datepicker( "setDate" , '+'+response.payment.emi_days+'d'  );
                    }
                    else
                    {
                        $('#onlyMembership').css('display','none');
                        $('#next_payment_div').css('display','none');
                    }
                }
            })
        }


    </script>
    <script>
        $('#save-form').click(function(){
            var url_update = "{{route('gym-admin.product-payments.update',[':id'])}}";
            var url = url_update.replace(':id','{{$payment->id}}');
            var type = $("input[name='payment_required']:checked").val();
            if(type == 'yes' && $('#next_payment_date').val()=='')
            {
                $.showToastr('Next payment date is required','error');
            }else
            {
                $.easyAjax({
                    url:url,
                    container:'#updatePayments',
                    type: "POST",
                    data:$('#updatePayments').serialize()
                })
            }

        });

        $('#payment_amount').keyup(function(){
            var remainingAmount = $('#remaining_amount_store').val();
            var total = remainingAmount - $(this).val();
            if(total > 0) {
                $('#yes_radio').prop("checked", true);
            } else {
                $('#no_radio').prop("checked", true);
            }
        });
    </script>
@stop
