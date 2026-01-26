<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <span class="caption-subject font-red-sunglo bold uppercase"><i class="icon-plus"></i> Add Payment</span>
</div>
<div class="modal-body">

    <div class="portlet-body">
        {!! Form::open(['id'=>'storePayments','class'=>'ajax-form form-horizontal','method'=>'POST']) !!}
        <div class="row">
            <div class="col-md-12">

                <div class="form-body">
                    <div class="form-group form-md-line-input row">
                        <label class="col-md-3 control-label">Client</label>
                        <div class="col-md-9">
                            <div class="form-control form-control-static">
                                @if($purchase->client->image == '')
                                    <img style="width:50px;height:50px;" class="img-circle" src="{{asset('/fitsigma/images/').'/'.'user.svg'}}" alt="" />
                                @else
                                    <img style="width:50px;height:50px;" class="img-circle" src="{{$profileHeaderPath.$purchase->client->image}}" alt="" />
                                @endif
                                {{ ucwords($purchase->client->fullName) }}
                            </div>

                        </div>
                    </div>
                    <div class="form-group row form-md-line-input">
                        <label class="col-md-3 control-label">Locker Number</label>
                        <div class="col-md-9">
                            <div class="form-control form-control-static">
                                {{ $purchase->locker->locker_num }}
                            </div>

                        </div>
                    </div>

                    <div class="form-group form-md-line-input row">
                        <label class="col-md-3 control-label">Amount <span class="required" aria-required="true"> * </span></label>
                        <div class="col-md-9">
                            <div class="input-group">
                                <span class="input-group-addon">{{ $gymSettings->currency->acronym }} </span>
                                <input type="text" class="form-control" placeholder="Enter amount" id="payment_amount"
                                name="payment_amount" required>
                                <span class="help-block">Enter Amount</span>
                                <div class="form-control-focus"> </div>
                                <span class="input-group-addon">.00</span>
                            </div>

                        </div>
                    </div>

                    <div class="form-group form-md-line-input row">
                        <label class="col-md-3 control-label">Remaining Amount</label>
                        <div class="col-md-9">
                            <div class="input-group">
                                <span class="input-group-addon">{{ $gymSettings->currency->acronym }} </span>
                                <input type="number" disabled class="form-control" placeholder="Enter amount" id="remaining_amount" name="remaining_amount" value="{{ ($purchase->amount_to_be_paid - $purchase->paid_amount) }}">
                                <div class="form-control-focus"> </div>
                                <span class="input-group-addon">.00</span>
                            </div>

                        </div>
                    </div>

                    <input type="hidden" id="remaining_amount_store" value="{{ ($purchase->amount_to_be_paid - $purchase->paid_amount) }}">

                    <div class="form-group form-md-radios">
                        <label class="col-md-3 control-label">Payment Source <span class="required" aria-required="true"> * </span></label>
                        <div class="col-md-9">
                            <div class="md-radio-inline">
                                @foreach($payment_sources as $key=> $source)
                                    <div class="md-radio">
                                        <input type="radio" value="{{$key}}" id="{{$key}}_radio" name="payment_source" class="md-radiobtn">
                                        <label for="{{$key}}_radio">
                                            <span></span>
                                            <span class="check"></span>
                                            <span class="box"></span> <i class="fa fa-{{$source['icon']}}"></i> {{$source['label']}} </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="form-group form-md-line-input ">
                        <label class="control-label col-md-3">Payment Date</label>
                        <div class="col-md-9">
                            <div class="input-group left-addon right-addon">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                <input type="text" class="form-control date-picker" readonly name="payment_date" id="payment_date" value="{{ \Carbon\Carbon::now('Asia/Kathmandu')->format('m/d/Y') }}">
                            </div>
                        </div>
                    </div>

                    <div class="form-group form-md-radios">
                        <label class="control-label col-md-3">More Payment Required</label>
                        <div class="col-md-9">
                            <div class="md-radio-inline">
                                <div class="md-radio">
                                    <input type="radio" value="yes" id="yes_radio"  checked name="payment_required" class="md-radiobtn" readonly>
                                    <label for="yes_radio">
                                        <span></span>
                                        <span class="check"></span>
                                        <span class="box"></span> Yes </label>
                                </div>
                                <div class="md-radio ">
                                    <input type="radio" value="no" id="no_radio" name="payment_required"
                                           class="md-radiobtn" readonly>
                                    <label for="no_radio">
                                        <span></span>
                                        <span class="check"></span>
                                        <span class="box"></span> No </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group form-md-line-input " id="next_payment_div" style="display: block">
                        <label class="col-md-3 control-label">Due Payment Date</label>
                        <div class="col-md-9">
                            <div class="input-group left-addon right-addon">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                <input type="text" class="form-control date-picker" value="{{ \Carbon\Carbon::today()->addDays(1)->format('m/d/Y') }}" readonly name="next_payment_date" id="next_payment_date">
                            </div>
                        </div>
                    </div>

                    <div class="form-group form-md-line-input ">
                        <label class="col-md-3 control-label">Remark</label>
                        <div class="col-md-9">
                            <div class="input-icon">
                                <input type="text" class="form-control" placeholder="Remarks" name="remarks" id="remarks">
                                <div class="form-control-focus"> </div>
                                <span class="help-block">Add payment remark</span>
                                <i class="fa fa-pencil"></i>
                            </div>
                        </div>
                    </div>


                </div>


            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>
<hr>
<div class="modal-footer">
    <div class="form-actions">
        <div class="row">
            <div class="col-md-offset-3 col-md-9">
                <button  type="button" id="save-form" class="btn green">Submit</button>
                <button type="button" class="btn default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

{!! HTML::script('admin/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') !!}
{!! HTML::style('admin/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') !!}
<script>
    $('.date-picker').datepicker({
        rtl: App.isRTL(),
        orientation: "left",
        autoclose: true
    });

    $('#payment_amount').on("input", function() {
        var amount = this.value;
        var remaining = $("#remaining_amount_store").val()-amount;
        $("#remaining_amount").addClass("edited");
        if(parseFloat(remaining) <= 0){
            remaining = 0;
        }
        $("#remaining_amount").val(remaining);
        if(remaining == 0) {
            $("#no_radio").prop('checked', true);
            $('#next_payment_div').css('display','none');
        } else {
            $("#yes_radio").prop('checked', true);
            $('#next_payment_div').css('display','block');
        }
    });


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

    $('#save-form').click(function(){
        var type = $("input[name='payment_required']:checked").val();
        if(type == 'yes' && $('#next_payment_date').val()=='')
        {
            $.showToastr('Next payment date is required','error');
        }else {
            $.easyAjax({
                url: "{{route('gym-admin.reservation-payments.ajax-payment-store', [$purchase->uuid])}}",
                container: '#storePayments',
                type: "POST",
                data: $('#storePayments').serialize(),
                success: function (responce) {
                    if (responce.status == 'success') {
                        $('#reminderModal').modal('hide');
                        load_dataTable();
                    }
                }
            })
        }
    });
</script>
