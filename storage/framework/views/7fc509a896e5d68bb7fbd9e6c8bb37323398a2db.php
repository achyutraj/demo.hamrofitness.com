<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <span class="caption-subject font-red-sunglo bold uppercase"><i class="fa fa-extend"></i> Extend Subscription</span>
</div>
<div class="modal-body">
    <div class="portlet-title">
        <div>Note: Use <b>Extend From</b> Field for expired Subscription to be extended.
                    For <b>Active Subscription</b> no need to select <b>Extend From</b> Field as application will auto use active expiry date
                </div>
    </div>
    <div class="portlet-body">
        <?php echo Form::open(['id'=>'storeExtend','class'=>'ajax-form form-horizontal','method'=>'POST']); ?>

        <div class="row">
            <div class="col-md-12">
                <div class="form-body">
                    <div class="form-group form-md-line-input row">
                        <label class="col-md-3 control-label">Client</label>
                        <div class="col-md-9">
                            <div class="form-control form-control-static">
                                <?php if($purchase->client->image == ''): ?>
                                    <img style="width:50px;height:50px;" class="img-circle" src="<?php echo e(asset('/fitsigma/images/').'/'.'user.svg'); ?>" alt="" />
                                <?php else: ?>
                                    <img style="width:50px;height:50px;" class="img-circle" src="<?php echo e($profileHeaderPath.$purchase->client->image); ?>" alt="" />
                                <?php endif; ?>
                                <?php echo e(ucwords($purchase->client->first_name.' '.$purchase->client->middle_name.' '.$purchase->client->last_name)); ?>

                            </div>
                        </div>
                    </div>
                    <div class="form-group row form-md-line-input">
                        <label class="col-md-3 control-label">Purchase</label>
                        <div class="col-md-9">
                            <div class="form-control form-control-static">
                                <?php echo e(ucwords($purchase->membership->title)); ?>

                                [<?php echo e($purchase->membership->duration); ?> <?php echo e($purchase->membership->duration_type); ?>]
                                        <?php echo e($gymSettings->currency->acronym); ?> <?php echo e($purchase->membership->price); ?>

                            </div>

                        </div>
                    </div>

                    <div class="form-group form-md-line-input ">
                        <label class="col-md-3 control-label">Extend Days</label>
                        <div class="col-md-9">
                            <div class="input-icon">
                                <input type="text" class="form-control" placeholder="Extend Day" name="days" id="days">
                                <span class="help-block">Extend Days</span>
                                <div class="form-control-focus"> </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label">Extend From</label>
                        <div class="col-md-9">
                            <div class="input-icon">
                                <input type="text" class="form-control date-picker" data-date-today-highlight="true" placeholder="Select Extend From Date" name="extend_from" id="extend_from" value="">
                                <span class="help-block">Extend From Date</span>
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
                                <span class="help-block">Add Extend Subscription reason</span>
                                <i class="fa fa-pencil"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php echo Form::close(); ?>

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

<?php echo HTML::script('admin/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js'); ?>

<?php echo HTML::style('admin/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css'); ?>

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

    $('#save-form').click(function(){

        var show_url = "<?php echo e(route('gym-admin.client-purchase.extend-subscription-store',['#id'])); ?>";
        var url = show_url.replace('#id', '<?php echo e($purchase->id); ?>');

        $.easyAjax({
            url: url,
            container:'#storeExtend',
            type: "POST",
            data:$('#storeExtend').serialize(),
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
<?php /**PATH /var/www/stage.hamrofitness.com/public_html/resources/views/gym-admin/purchase/extend_subscription_modal.blade.php ENDPATH**/ ?>