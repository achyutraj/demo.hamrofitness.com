<?php $__env->startSection('CSS'); ?>
    <?php echo HTML::style('admin/global/plugins/ladda/ladda-themeless.min.css'); ?>

    <?php echo HTML::style('admin/global/plugins/bootstrap-select/css/bootstrap-select.min.css'); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-fluid"  >
        <!-- BEGIN PAGE BREADCRUMBS -->
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <a href="<?php echo e(route('gym-admin.dashboard.index')); ?>">Home</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <a href="<?php echo e(route('gym-admin.client.index')); ?>">Clients</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <a href="<?php echo e(route('gym-admin.client-purchase.index')); ?>">Subscription</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>Add</span>
            </li>
        </ul>
        <!-- END PAGE BREADCRUMBS -->
        <!-- BEGIN PAGE CONTENT INNER -->
        <div class="page-content-inner">

            <div class="row">
                <div class="col-md-8 col-xs-12">

                    <div class="portlet light portlet-fit">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="icon-plus font-red"></i><span class="caption-subject font-red bold uppercase">Add New Subscription</span></div>


                        </div>
                        <div class="portlet-body">
                            <!-- BEGIN FORM-->
                            <?php echo Form::open(['id'=>'storePayments','class'=>'ajax-form','method'=>'POST']); ?>


                            <div class="form-body">
                                <?php if($isRedeem != null): ?>
                                    <input type="hidden" name="is_redeem" value="1">
                                <?php endif; ?>
                                <div class="form-group form-md-line-input">
                                    <select  class="bs-select form-control" data-live-search="true" data-size="8" name="user_id" id="user_id">
                                            <option value="">Select Client</option>
                                        <?php $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($client->customer_id); ?>" <?php if(count($clients) == 1): ?> selected <?php endif; ?>><?php echo e($client->fullName); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <label for="title">Client</label>
                                    <span class="help-block"></span>
                                </div>

                                <div class="form-group form-md-line-input" id="mem_select">
                                    <select  class="bs-select form-control" data-live-search="true" data-size="8" name="membership_id" id="membership_id">
                                        <option value="">Select Membership</option>
                                            <?php $__currentLoopData = $memberships; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($mem->id); ?>" data-price="<?php echo e($mem->price); ?>"><?php echo e($mem->title); ?> - [<?php echo e($mem->duration); ?> <?php echo e($mem->duration_type); ?>]  <?php echo e($gymSettings->currency->acronym); ?> <?php echo e($mem->price); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                    </select>
                                    <label for="title">Membership</label>
                                    <span class="help-block"></span>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group form-md-line-input form-md-floating-label">
                                            <div class="input-group left-addon right-addon">
                                                <span class="input-group-addon"><?php echo e($gymSettings->currency->acronym); ?></span>
                                                <input type="text" class="form-control" name="purchase_amount" id="purchase_amount" placeholder="Cost">
                                                <span class="help-block">Membership Cost</span>
                                                <span class="input-group-addon">.00</span>
                                                <label for="purchase_amount"></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group form-md-line-input form-md-floating-label">
                                            <div class="input-group left-addon right-addon">
                                                <span class="input-group-addon"><?php echo e($gymSettings->currency->acronym); ?></span>
                                                <input type="text" class="form-control" name="discount" id="discount" placeholder="Discount">
                                                <span class="help-block" id="msg">Discount Amount</span>
                                                <span class="input-group-addon">.00</span>
                                                <label for="discount"></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group form-md-line-input form-md-floating-label">
                                            <div class="input-group left-addon right-addon">
                                                <span class="input-group-addon"><?php echo e($gymSettings->currency->acronym); ?></span>
                                                <input type="text" class="form-control" name="amount_to_be_paid" id="amount_to_be_paid" placeholder="Amount">
                                                <span class="help-block">Amount to be Paid</span>
                                                <span class="input-group-addon">.00</span>
                                                <label for="amount_to_be_paid"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group form-md-line-input ">
                                            <div class="input-icon">
                                                <input type="text" readonly class="form-control date-picker" placeholder="Select Purchase Date" name="purchase_date" id="purchase_date" value="<?php echo e(\Carbon\Carbon::today()->format('m/d/Y')); ?>">
                                                <label for="form_control_1">Purchase Date</label>
                                                <span class="help-block">Enter Purchase Date</span>
                                                <i class="icon-calendar"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group form-md-line-input">
                                            <div class="input-icon">
                                                <input type="text" readonly class="form-control date-picker" placeholder="Select Start Date" name="start_date" id="start_date" value="<?php echo e(\Carbon\Carbon::today()->format('m/d/Y')); ?>">
                                                <label for="form_control_1">Customer is going to come from?</label>
                                                <span class="help-block">Date from when customer will be coming from.</span>
                                                <i class="icon-calendar"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group form-md-line-input form-md-floating-label">
                                            <div class="input-group left-addon right-addon">
                                                <span class="input-group-addon"><?php echo e($gymSettings->currency->acronym); ?></span>
                                                <input type="text" class="form-control" name="payment_amount" id="payment_amount">
                                                <span class="help-block">Pay Amount</span>
                                                <span class="input-group-addon">.00</span>
                                                <label for="payment_amount">How much client pay now?</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group form-md-line-input">
                                            <div class="input-icon">
                                                <input type="text" readonly class="form-control date-picker" placeholder="Select Payment Date" name="payment_date" id="payment_date" value="<?php echo e(\Carbon\Carbon::today()->format('m/d/Y')); ?>">
                                                <label for="form_control_1">Payment Date</label>
                                                <span class="help-block"></span>
                                                <i class="icon-calendar"></i>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group form-md-line-input">
                                            <div class="input-icon">
                                                <input type="text" readonly class="form-control date-picker" placeholder="Select Due Payment Date" name="next_payment_date" id="next_payment_date" value="<?php echo e(\Carbon\Carbon::today()->addDays(1)->format('m/d/Y')); ?>">
                                                <label for="form_control_1">Due Payment Date</label>
                                                <span class="help-block"></span>
                                                <i class="icon-calendar"></i>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group form-md-radios">
                                            <label class="col-md-3 control-label">Payment Source</label>
                                            <div class="col-md-9">
                                                <div class="md-radio-inline">
                                                    <?php $__currentLoopData = $paymentSources; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=> $source): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <div class="md-radio">
                                                        <input type="radio" value="<?php echo e($key); ?>" id="<?php echo e($key); ?>_radio" name="payment_source" class="md-radiobtn">
                                                        <label for="<?php echo e($key); ?>_radio">
                                                            <span></span>
                                                            <span class="check"></span>
                                                            <span class="box"></span> <i class="fa fa-<?php echo e($source['icon']); ?>"></i> <?php echo e($source['label']); ?> </label>
                                                    </div>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group form-md-line-input ">
                                            <div class="input-icon">
                                                <input type="text" class="form-control" placeholder="Remarks" name="remarks" id="remarks">
                                                <label for="form_control_1">Remark</label>
                                                <span class="help-block">Add payment remarks</span>
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
                            <?php echo Form::close(); ?>

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
<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer'); ?>
    <?php echo HTML::script('admin/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js'); ?>

    <?php echo HTML::style('admin/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css'); ?>

    <?php echo HTML::script('admin/global/plugins/ladda/spin.min.js'); ?>

    <?php echo HTML::script('admin/global/plugins/ladda/ladda.min.js'); ?>

    <?php echo HTML::script('admin/pages/scripts/ui-buttons.min.js'); ?>

    <?php echo HTML::script('admin/global/plugins/bootstrap-select/js/bootstrap-select.min.js'); ?>

    <?php echo HTML::script('admin/pages/scripts/components-bootstrap-select.min.js'); ?>

    <script>
        $(document).ready(function() {

            $("#purchase_date").datepicker({
                rtl: App.isRTL(),
                orientation: "left",
                autoclose: true,
            }).on('changeDate', function (selected) {
                var minDate = new Date(selected.date.valueOf());
                $('#start_date').datepicker('setStartDate', minDate);
            });

            $("#start_date").datepicker({
                rtl: App.isRTL(),
                orientation: "left",
                autoclose: true,
            }).on('changeDate', function (selected) {
                var maxDate = new Date(selected.date.valueOf());
                $('#purchase_date').datepicker('setEndDate', maxDate);
            });
            $("#next_payment_date").datepicker({
                rtl: App.isRTL(),
                orientation: "left",
                autoclose: true,
            })
            $("#payment_date").datepicker({
                rtl: App.isRTL(),
                orientation: "left",
                autoclose: true,
            })
        });

        $('#membership_id').on('change',function () {
            var price = $('#membership_id :selected').data('price');
            $('#purchase_amount').val(price);
            $('#discount').val(0);
            $('#amount_to_be_paid').val(price);
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
                    if (amount == 0) {
                        $('.modal-title').text('Note');
                        $('.modal-body').text('Your amount to be paid is zero. Are you sure?');
                        $('#basic').modal('show');
                    }
                } else {
                    $('#amount_to_be_paid').val(amount);
                }
            }else{
                $('#msg').html("<p class='text-danger'>Discount should not be greater than cost.</p>");
            }
        });
    </script>
    <script>
        $('#save-form').click(function(){
            $.easyAjax({
                url:"<?php echo e(route('gym-admin.client-purchase.store')); ?>",
                container:'#storePayments',
                type: "POST",
                data:$('#storePayments').serialize(),
                formReset:true,
                success:function(responce){
                    if(responce.status == 'success'){
                        $('#user_id').val('');
                        $('#user_id').selectpicker('refresh');
                        $('#payment_for').val('');
                        $('#payment_for').selectpicker('refresh');
                        $('#membership_id').val('');
                        $('#membership_id').selectpicker('refresh');
                    }
                }
            })
        });

    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.gym-merchant.gymbasic', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/stage.hamrofitness.com/public_html/resources/views/gym-admin/purchase/create.blade.php ENDPATH**/ ?>