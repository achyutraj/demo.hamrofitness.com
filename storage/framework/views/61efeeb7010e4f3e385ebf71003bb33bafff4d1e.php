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
                <a href="<?php echo e(route('gym-admin.membership-plans.index')); ?>">Membership</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>Add Membership</span>
            </li>
        </ul>
        <!-- END PAGE BREADCRUMBS -->
        <!-- BEGIN PAGE CONTENT INNER -->
        <div class="page-content-inner">
            <div class="row">
                <div class="col-md-10 col-xs-12">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="m-heading-1 border-green m-bordered">
                                <h3>Note</h3>
                                <p>Enter '0' zero for membership duration <strong>Unlimited</strong> membership type.</p>
                            </div>
                        </div>
                    </div>
                    <div class="portlet light portlet-fit">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="icon-plus font-red"></i><span class="caption-subject font-red bold uppercase">Create Membership Plan</span></div>

                        </div>
                        <div class="portlet-body">
                            <!-- BEGIN FORM-->
                                <?php echo Form::open(['id'=>'form_sample_3','class'=>'ajax-form','method'=>'POST']); ?>

                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group form-md-line-input form-md-floating-label">
                                                <input type="text" class="form-control" name="title" id="title">
                                                <label for="title">Membership Name <span class="required" aria-required="true"> * </span></label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group form-md-line-input form-md-floating-label">
                                                <div class="input-group left-addon right-addon">
                                                    <span class="input-group-addon"> <?php echo e($gymSettings->currency->acronym); ?></span>
                                                    <input type="number" class="form-control" min="0" name="price" id="price">
                                                    <span class="help-block" id="membership_error">Enter membership valid price.</span>
                                                    <span class="input-group-addon">.00</span>
                                                    <label for="price"> Membership Price <span class="required" aria-required="true"> * </span></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group form-md-line-input form-md-floating-label">
                                                <div class="input-group left-addon right-addon">
                                                    <span class="input-group-addon"></span>
                                                    <input type="text" class="form-control" min="0" name="duration" id="duration">
                                                    <span class="help-block" id="membership_duration_error">Enter membership duration.</span>
                                                    <span class="input-group-addon">.00</span>
                                                    <label for="duration"> Membership Duration <span class="required" aria-required="true"> * </span></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group form-md-line-input form-md-floating-label">
                                                <div class="input-group left-addon right-addon">
                                                    <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                                                    <select class="form-control" id="duration_type" name="duration_type" required>
                                                        <option value="minute">Minute</option>
                                                        <option value="days">Days</option>
                                                        <option value="month">Month</option>
                                                        <option value="year">Year</option>
                                                        <option value="unlimited">Unlimited</option>
                                                    </select>
                                                    <label for="duration">Select Duration Type<span class="required" aria-required="true"> * </span></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group form-md-line-input form-md-floating-label">
                                        <textarea class="form-control" name="details" rows="3"></textarea>
                                        <label for="form_control_1">Membership Details (optional)</label>
                                    </div>

                                </div>
                                <div class="form-actions">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <button type="submit" class="btn dark mt-ladda-btn ladda-button" data-style="zoom-in" id="save-form">
                                            <span class="ladda-label">
                                                <i class="fa fa-save"></i> SAVE</span>
                                                <span class="ladda-spinner"></span>
                                                <div class="ladda-progress" style="width: 0px;"></div>
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
<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer'); ?>

    <?php echo HTML::script('admin/global/plugins/ladda/spin.min.js'); ?>

    <?php echo HTML::script('admin/global/plugins/ladda/ladda.min.js'); ?>

    <?php echo HTML::script('admin/pages/scripts/ui-buttons.min.js'); ?>


    <?php echo HTML::script('admin/global/plugins/jquery-validation/js/jquery.validate.min.js'); ?>

    <?php echo HTML::script('admin/global/plugins/jquery-validation/js/additional-methods.min.js'); ?>


    <?php echo HTML::script('admin/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js'); ?>

    <?php echo HTML::script('admin/global/plugins/bootstrap-select/js/bootstrap-select.min.js'); ?>

    <?php echo HTML::script('admin/pages/scripts/components-bootstrap-select.min.js'); ?>


    <script>
        var FormValidationMd = function() {

            var handleValidation3 = function() {
                // for more info visit the official plugin documentation:
                // http://docs.jquery.com/Plugins/Validation
                var form1 = $('#form_sample_3');
                var error1 = $('.alert-danger', form1);
                var success1 = $('.alert-success', form1);

                form1.validate({
                    errorElement: 'span', //default input error message container
                    errorClass: 'help-block help-block-error', // default input error message class
                    focusInvalid: false, // do not focus the last invalid input
                    ignore: "", // validate all fields including form hidden input
                    rules: {
                        title: {
                            required: true
                        },
                        sub_category_id: {
                            required: true
                        },
                        price: {
                            required: true,
                            number: true
                        },
                        duration: {
                            required: true
                        },
                        duration_type: {
                            required: true
                        }
                    },

                    invalidHandler: function(event, validator) { //display error alert on form submit
                        success1.hide();
                        error1.show();
                        App.scrollTo(error1, -200);
                    },

                    errorPlacement: function(error, element) {
                        if (element.is(':checkbox')) {
                            error.insertAfter(element.closest(".md-checkbox-list, .md-checkbox-inline, .checkbox-list, .checkbox-inline"));
                        } else if (element.is(':radio')) {
                            error.insertAfter(element.closest(".md-radio-list, .md-radio-inline, .radio-list,.radio-inline"));
                        } else {
                            error.insertAfter(element); // for other inputs, just perform default behavior
                        }
                    },

                    highlight: function(element) { // hightlight error inputs
                        $(element)
                                .closest('.form-group').addClass('has-error'); // set error class to the control group
                    },

                    unhighlight: function(element) { // revert the change done by hightlight
                        $(element)
                                .closest('.form-group').removeClass('has-error'); // set error class to the control group
                    },

                    success: function(label) {
                        label
                                .closest('.form-group').removeClass('has-error'); // set success class to the control group
                    },

                    submitHandler: function(form) {
                        success1.show();
                        error1.hide();
                        $.easyAjax({
                            url: "<?php echo e(route('gym-admin.membership-plans.store')); ?>",
                            container:'#form_sample_3',
                            type: "POST",
                            formReset: true,
                            data: $('#form_sample_3').serialize(),
                            success:function (res) {

                            }
                        });
                        return false;
                    }
                });
            }

            return {
                //main function to initiate the module
                init: function() {
                    handleValidation3();
                }
            };
        }();

        jQuery(document).ready(function() {
            FormValidationMd.init();
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.gym-merchant.gymbasic', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/stage.hamrofitness.com/public_html/resources/views/gym-admin/membership/create.blade.php ENDPATH**/ ?>