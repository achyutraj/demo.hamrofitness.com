<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <span class="caption-subject font-red-sunglo bold uppercase"><i class="icon-refresh"></i> Renew Subscription</span>
</div>
<div class="modal-body">

    <div class="portlet-body">
        {{ html()->form()->open(['id'=>'storePayments','class'=>'ajax-form form-horizontal','method'=>'POST']) }}
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
                                {{ ucwords($purchase->client->first_name.' '.$purchase->client->middle_name.' '.$purchase->client->last_name) }}
                            </div>
                        </div>
                    </div>
                    <div class="form-group row form-md-line-input">
                        <label class="col-md-3 control-label">Purchase</label>
                        <div class="col-md-9">
                            <div class="form-control form-control-static">
                                    {{ ucwords($purchase->membership->title) }}
                                    [{{ $purchase->membership->duration }} {{ $purchase->membership->duration_type }}]
                                        {{ $gymSettings->currency->acronym }} {{ $purchase->membership->price}}
                            </div>

                        </div>
                    </div>

                    <div class="form-group form-md-line-input form-md-floating-label">
                        <label class="col-md-3 control-label">Cost</label>
                        <div class="col-md-9">
                            <div class="input-group left-addon right-addon">
                                <span class="input-group-addon">{{ $gymSettings->currency->acronym }}</span>
                                <input type="number" min="0" value="{{ $purchase->purchase_amount }}" class="form-control" name="purchase_amount" id="purchase_amount">
                                <span class="help-block">Membership Cost</span>
                                <span class="input-group-addon">.00</span>
                                <div class="form-control-focus"> </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group form-md-line-input form-md-floating-label">
                        <label class="control-label col-md-3">Discount</label>
                        <div class="col-md-9">
                            <div class="input-group left-addon right-addon">
                                <span class="input-group-addon">{{ $gymSettings->currency->acronym }}</span>
                                <input type="number" value="0" class="form-control" name="discount" id="discount" min="0">
                                <span class="help-block">Discount Amount</span>
                                <span class="input-group-addon">.00</span>
                                <div class="form-control-focus"> </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group form-md-line-input form-md-floating-label">
                        <label class="control-label col-md-3">Amount</label>
                        <div class="col-md-9">
                            <div class="input-group left-addon right-addon">
                                <span class="input-group-addon">{{ $gymSettings->currency->acronym }}</span>
                                <input type="number" min="0" value="{{ $purchase->purchase_amount }}" class="form-control" name="amount_to_be_paid" id="amount_to_be_paid">
                                <span class="help-block">Amount to be Paid</span>
                                <div class="form-control-focus"> </div>
                                <span class="input-group-addon">.00</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group form-md-line-input ">
                        <label class="col-md-3 control-label">Purchase Date</label>
                        <div class="col-md-9">
                            <div class="input-icon">
                                <input type="text" readonly class="form-control date-picker" placeholder="Select Purchase Date" name="purchase_date" id="purchase_date" value="{{ \Carbon\Carbon::now('Asia/Kathmandu')->format('m/d/Y') }}">
                                <span class="help-block">Purchase Date</span>
                                <i class="icon-calendar"></i>
                                <div class="form-control-focus"> </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label">Start Date</label>
                        <div class="col-md-9">
                            <div class="input-icon">
                                <input type="text" readonly class="form-control date-picker" placeholder="Select Start Date" name="start_date" id="start_date" value="{{ \Carbon\Carbon::now('Asia/Kathmandu')->format('m/d/Y') }}">
                                <span class="help-block">Start Date</span>
                                <div class="form-control-focus"> </div>
                                <i class="icon-calendar"></i>
                            </div>
                        </div>
                    </div>

                    <div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label">Due Payment Date</label>
                        <div class="col-md-9">
                            <div class="input-icon">
                                <input type="text" readonly class="form-control date-picker" placeholder="Select Due Payment Date" name="next_payment_date" id="next_payment_date" value="{{ \Carbon\Carbon::now()->addDays(1)->format('m/d/Y') }}">
                                <span class="help-block">Due Payment Date</span>
                                <div class="form-control-focus"> </div>
                                <i class="icon-calendar"></i>
                            </div>
                        </div>
                    </div>

                    <div class="form-group form-md-line-input ">
                        <label class="col-md-3 control-label">Remark</label>
                        <div class="col-md-9">
                            <div class="input-icon">
                                <input type="text" class="form-control" placeholder="Remarks" name="remark" id="remark">
                                <div class="form-control-focus"> </div>
                                <span class="help-block">Add payment remark</span>
                                <i class="fa fa-pencil"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{ html()->form()->close() }}
    </div>
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

<script src="{{ asset("admin/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js") }}"></script>
<link rel="stylesheet" href="{{ asset("admin/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css") }}">
<script>
    $('.date-picker').datepicker({
        rtl: App.isRTL(),
        orientation: "left",
        autoclose: true
    });

    $('#amount_to_be_paid').keyup(function () {
        var cost = $('#purchase_amount').val();
        var discount = parseInt(cost)-parseInt($(this).val());
        $('#discount').val(discount);
    });

    $('#discount').keyup(function() {
        var cost =  $('#purchase_amount').val();
        var amount = cost - $(this).val();
        if(amount < cost){
            if(amount <= 0) {
                $('#amount_to_be_paid').val(0);
            } else {
                $('#amount_to_be_paid').val(amount);
            }
        }else{
            $('#msg').html("<p class='text-danger'>Amount should be less than cost.</p>");
        }

    });

    $('#save-form').click(function(){

        var show_url = "{{route('gym-admin.client-purchase.renew-subscription-store',['#id'])}}";
        var url = show_url.replace('#id', '{{ $purchase->id }}');

        $.easyAjax({
            url: url,
            container:'#storePayments',
            type: "POST",
            data:$('#storePayments').serialize(),
            formReset:true,
            success:function(response){
                if(response.status == 'success'){
                    $('#reminderModal').modal('hide');
                    load_dataTable();
                }
            }
        })
    });
</script>
