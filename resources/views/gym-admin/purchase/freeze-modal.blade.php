<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <span class="caption-subject font-red-sunglo bold uppercase"><i class="fa fa-extend"></i> Freeze Subscription</span>
</div>
<div class="modal-body">
    <div class="portlet-body">
        {{ html()->form()->open(['id'=>'storeFreeze','class'=>'ajax-form form-horizontal','method'=>'POST']) }}
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

                    <div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label">From Date</label>
                        <div class="col-md-9">
                            <div class="input-icon">
                                <input type="text" class="form-control date-picker" data-date-today-highlight="true" placeholder="Select Freeze Date" name="start_date" id="start_date" value="">
                                <span class="help-block">Freeze Date</span>
                                <div class="form-control-focus"> </div>
                                <i class="icon-calendar"></i>
                            </div>
                        </div>
                    </div>

                    <div class="form-group form-md-line-input ">
                        <label class="col-md-3 control-label">Reason</label>
                        <div class="col-md-9">
                            <div class="input-icon">
                                <input type="text" class="form-control" placeholder="Reason" name="reasons" id="reasons">
                                <div class="form-control-focus"> </div>
                                <span class="help-block">Add Freeze Subscription reason</span>
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
        autoclose: true,
        startDate: new Date(),
    });

    $('#save-form').click(function(){

        var show_url = "{{route('gym-admin.client-purchase.freeze-subscription-store',['#id'])}}";
        var url = show_url.replace('#id', '{{ $purchase->id }}');

        $.easyAjax({
            url: url,
            container:'#storeFreeze',
            type: "POST",
            data:$('#storeFreeze').serialize(),
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
