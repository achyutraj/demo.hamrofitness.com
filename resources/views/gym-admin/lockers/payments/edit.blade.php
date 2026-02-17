@extends('layouts.gym-merchant.gymbasic')

@section('CSS')
    {!! HTML::style('admin/global/plugins/ladda/ladda-themeless.min.css') !!}
    {!! HTML::style('admin/global/plugins/bootstrap-select/css/bootstrap-select.min.css') !!}
@stop

@section('content')
    <div class="container-fluid"  >
        <!-- BEGIN PAGE BREADCRUMBS -->
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <a href="{{ route('gym-admin.dashboard.index') }}">Home</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <a href="{{ route('gym-admin.lockers.index') }}">Locker</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <a href="{{ route('gym-admin.reservation-payments.index') }}">Payments</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>Edit Payment</span>
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
                                <i class="icon-pencil font-red"></i><span class="caption-subject font-red bold uppercase">Edit Payment</span>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <!-- BEGIN FORM-->
                            {!! Form::open(['id'=>'updatePayments','class'=>'ajax-form','method'=>'POST']) !!}
                            <div class="form-body">
                                <input type="hidden" name="_method" value="put">

                                <div class="form-group form-md-line-input ">
                                    {{ $payment->client->fullName }}
                                </div>

                                <div class="form-group form-md-line-input ">
                                    <select  class="bs-select form-control" name="reservation_id" id="reservation_id">
                                        <option value="{{ $payment->reservation_id }}"> {{ $payment->reservation->locker->locker_num }}</option>
                                    </select>
                                    <label for="title">Locker Number</label>
                                    <span class="help-block"></span>
                                </div>

                                <div class="form-group form-md-line-input form-md-floating-label">
                                    <div class="input-group left-addon right-addon">
                                        <span class="input-group-addon">{{ $gymSettings->currency->acronym }} </span>
                                        <input type="number" readonly min="0" class="form-control" name="amount_to_be_paid" id="amount_to_be_paid" value="{{$purchases->amount_to_be_paid}}">
                                        <span class="help-block">Enter Amount</span>
                                        <span class="input-group-addon">.00</span>
                                        <label for="price">Amount To be Paid</label>
                                    </div>
                                </div>

                                <div class="form-group form-md-line-input form-md-floating-label">
                                    <div class="input-group left-addon right-addon">
                                        <span class="input-group-addon">{{ $gymSettings->currency->acronym }} </span>
                                        <input type="text" class="form-control" name="payment_amount" id="payment_amount" value="{{$payment->payment_amount}}">
                                        <span class="help-block">Enter Amount</span>
                                        <span class="input-group-addon">.00</span>
                                        <label for="price">Payment Amount</label>
                                    </div>
                                </div>

                                <div id="remaining_div">
                                    <div class="form-group form-md-line-input form-md-floating-label">
                                        <div class="input-group left-addon right-addon">
                                            <span class="input-group-addon">{{ $gymSettings->currency->acronym }} </span>
                                            <input disabled type="text" class="form-control" name="remaining_amount" id="remaining_amount" value="{{ $remaining_amount }}">
                                            <input disabled type="hidden" class="form-control" name="remaining_amount_store" id="remaining_amount_store" value="{{ $remaining_amount }}">
                                            <span class="input-group-addon">.00</span>
                                            <label for="price">Remaining Amount</label>
                                        </div>
                                    </div>
                                </div>


                                <div class="form-group form-md-line-input">
                                    <div class="form-group form-md-radios">
                                        <label>Payment Source</label>
                                        <div class="md-radio-inline">
                                            @foreach($payment_sources as $key=> $source)
                                                <div class="md-radio">
                                                    <input type="radio" value="{{$key}}" id="{{$key}}_radio" name="payment_source" class="md-radiobtn"
                                                    @if($payment->payment_source == $key) checked @endif>
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
                                        <input type="text" readonly class="form-control date-picker" value="{{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$payment->payment_date)->format('m/d/Y')}}" name="payment_date" id="payment_date">
                                        <label for="payment_date">Payment Date</label>
                                    </div>
                                </div>

                                <div class="form-group form-md-line-input">
                                    <div class="form-group form-md-radios">
                                        <label>More Payment Required</label>
                                        <div class="md-radio-inline">
                                            <div class="md-radio">
                                                <input type="radio" value="yes" id="yes_radio" name="payment_required" class="md-radiobtn" @if($purchases->payment_required == 'yes') checked @endif>
                                                <label for="yes_radio">
                                                    <span></span>
                                                    <span class="check"></span>
                                                    <span class="box"></span> Yes </label>
                                            </div>
                                            <div class="md-radio ">
                                                <input type="radio" value="no" id="no_radio" name="payment_required" class="md-radiobtn"  @if($purchases->payment_required == 'no') checked @endif>
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
                                        <input type="text" class="form-control date-picker" readonly name="next_payment_date" id="next_payment_date" @if($payment->next_date != null) value="{{\Carbon\Carbon::createFromFormat('m/d/Y',$payment->next_date)}}" @endif>
                                        <label for="payment_date">Due Payment Date</label>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group form-md-line-input ">
                                            <div class="input-icon">
                                                <input type="text" class="form-control" placeholder="Remarks" name="remarks" id="remarks" value="{{$payment->remarks}}">
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
                            {!! Form::close() !!}
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
    {!! HTML::script('admin/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') !!}
    {!! HTML::style('admin/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') !!}
    {!! HTML::script('admin/global/plugins/ladda/spin.min.js') !!}
    {!! HTML::script('admin/global/plugins/ladda/ladda.min.js') !!}
    {!! HTML::script('admin/pages/scripts/ui-buttons.min.js') !!}
    {!! HTML::script('admin/global/plugins/bootstrap-select/js/bootstrap-select.min.js') !!}
    {!! HTML::script('admin/pages/scripts/components-bootstrap-select.min.js') !!}
    <script>


        $('.date-picker').datepicker({
            rtl: App.isRTL(),
            orientation: "left",
            autoclose: true
        });
        @if($purchases->payment_required == 'yes')
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
                    $('.modal-body').text('You have set remaining to yes but there is no amount remaining.');
                    $('#reminderModal').modal('show');
                }
                $('#next_payment_div').css('display','block');
            }else {
                if(remainingAmount > 0) {
                    $('.modal-title').text('Note');
                    $('.modal-body').text('You have set remaining to no but still there is some amount remaining.');
                    $('#reminderModal').modal('show');
                }
                $('#next_payment_div').css('display','none');
            }
        });

        $('#payment_amount').on("keyup", function() {
            var amount = this.value;
            var old_amount ={{$payment->payment_amount}};
            var remaining = $("#remaining_amount_store").val()-(amount-old_amount);
            $("#remaining_amount").addClass("edited");
            if(parseFloat(remaining) < 0){
                remaining = 0;
            }
            $("#remaining_amount").val(remaining);
        });

        $('#save-form').click(function(){
            var url_update = "{{route('gym-admin.reservation-payments.update',[':id'])}}";
            var url = url_update.replace(':id','{{$payment->uuid}}');
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
            var remainingAmount = $('#remaining_amount').val();
            var total = remainingAmount - $(this).val();
            if(total > 0) {
                $('#yes_radio').prop("checked", true);
                $('#next_payment_div').css('display','block');
            } else {
                $('#no_radio').prop("checked", true);
                $('#next_payment_div').css('display','none');
            }
        });
    </script>
@stop
