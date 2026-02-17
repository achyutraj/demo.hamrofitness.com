

<?php $__env->startSection('CSS'); ?>
    <?php echo HTML::style('admin/global/plugins/ladda/ladda-themeless.min.css'); ?>

    <?php echo HTML::style('admin/global/plugins/bootstrap-datepicker/css/datepicker.css'); ?>

    <?php echo HTML::style('admin/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css'); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-fluid">

        <!-- BEGIN PAGE BREADCRUMBS -->
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <a href="<?php echo e(route('gym-admin.dashboard.index')); ?>">Home</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>Branch Setup 1 of 3</span>
            </li>
        </ul>
        <!-- END PAGE BREADCRUMBS -->
        <!-- BEGIN PAGE CONTENT INNER -->
        <div class="page-content-inner">
            <div class="row">
                <div class="col-md-12">
                    <div class="portlet light ">
                        <div class="portlet-title">
                            <div class="caption font-dark">
                                <i class="icon-layers font-red"></i>
                                <span class="caption-subject font-red bold uppercase"> Branch setup wizard</span>
                            </div>
                            <div class="actions">
                                <span class="caption-subject font-red bold uppercase"> STEP 1 of 3 </span>
                            </div>
                        </div>
                        <div class="portlet-body">

                            <div class="col-md-12">
                                <div class="progress progress-striped active">
                                    <div class="progress-bar progress-bar-success" role="progressbar"
                                         aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"
                                         style="width: <?php echo e(($completedItems*(100/$completedItemsRequired))); ?>%">
									<span class="sr-only">
									<?php echo e(($completedItems*(100/$completedItemsRequired))); ?>% Complete </span>
                                    </div>
                                </div>
                            </div>

                            <?php echo Form::open(['route'=>'gym-admin.superadmin.storeBranchPage','id'=>'branchStoreForm','class'=>'ajax-form form-horizontal','method'=>'POST','files' => true]); ?>

                            <div class="form-wizard">
                                <div class="form-body">
                                    <ul class="nav nav-pills nav-justified steps">
                                        <li class="active">
                                            <a href="javascript:;" class="step">
                                                <span class="number"> 1 </span>
                                                <span class="desc">
                                                                        <i class="fa fa-check"></i> Add Branch </span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:;" class="step">
                                                <span class="number"> 2 </span>
                                                <span class="desc">
                                                                        <i class="fa fa-check"></i> Add Manager </span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:;"
                                               class="step active">
                                                <span class="number"> 3 </span>
                                                <span class="desc">
                                                                        <i class="fa fa-check"></i> Assign Role </span>
                                            </a>
                                        </li>
                                    </ul>
                                    <?php if(isset($branchData->id)): ?>
                                        <input type="hidden" name="branch_id" value="<?php echo e($branchData->id); ?>">
                                    <?php endif; ?>
                                    <div class="form-group form-md-line-input">
                                        <label class="col-md-2 control-label" for="form_control_1">Branch Name <span class="required" aria-required="true"> * </span></label>

                                        <div class="col-md-6 input-icon right">
                                            <input type="text" class="form-control" name="title" <?php if(!is_null($branchData)): ?> value="<?php echo e($branchData->title); ?>" <?php endif; ?>>
                                            <div class="form-control-focus"></div>
                                            <span class="help-block">Enter branch name</span>
                                        </div>
                                    </div>

                                    <div class="form-group form-md-line-input">
                                        <label class="col-md-2 control-label" for="form_control_1">Address <span class="required" aria-required="true"> * </span></label>
                                        <div class="col-md-6">
                                            <textarea class="form-control" rows="3" placeholder="Enter address" name="address"><?php if(!is_null($branchData)): ?> <?php echo e($branchData->address); ?> <?php endif; ?></textarea>
                                            <div class="form-control-focus"> </div>
                                        </div>
                                    </div>

                                    <div class="form-group form-md-line-input">
                                        <label class="col-md-2 control-label" for="form_control_1">Incharge Name <span class="required" aria-required="true"> * </span></label>
                                        <div class="col-md-6 input-icon right">
                                            <input type="text" class="form-control" name="owner_incharge_name" <?php if(!is_null($branchData)): ?> value="<?php echo e($branchData->owner_incharge_name); ?>" <?php endif; ?>>
                                            <div class="form-control-focus"></div>
                                            <span class="help-block">Enter incharge name</span>
                                        </div>
                                    </div>

                                    <div class="form-group form-md-line-input">
                                        <label class="col-md-2 control-label" for="form_control_1">Mobile <span class="required" aria-required="true"> * </span></label>
                                        <div class="col-md-6 input-icon right">
                                            <input type="text" class="form-control" name="phone" <?php if(!is_null($branchData)): ?> value="<?php echo e($branchData->phone); ?>" <?php endif; ?>>
                                            <div class="form-control-focus"></div>
                                            <span class="help-block">Enter in-charge name.</span>
                                        </div>
                                    </div>

                                    <div class="form-group form-md-line-input">
                                        <label class="col-md-2 control-label" for="form_control_1">Email <span class="required" aria-required="true"> * </span></label>
                                        <div class="col-md-6 input-icon right">
                                            <input type="text" class="form-control" name="email" <?php if(!is_null($branchData)): ?> value="<?php echo e($branchData->email); ?>" <?php endif; ?>>
                                            <div class="form-control-focus"></div>
                                            <span class="help-block">Enter e-mail address.</span>
                                        </div>
                                    </div>
                                    <div class="form-group form-md-line-input">
                                        <label class="col-md-2 control-label" for="form_control_1">Join Date <span class="required" aria-required="true"> * </span></label>
                                        <div class="col-md-6 input-icon right">
                                            <input type="text" class="form-control date-picker" data-date-today-highlight="true" name="start_date">
                                            <div class="form-control-focus"></div>
                                            <span class="help-block">Enter Join Date.</span>
                                        </div>
                                    </div>
                                    <div class="form-group form-md-line-input">
                                        <label class="col-md-2 control-label" for="form_control_1">Expire Date <span class="required" aria-required="true"> * </span></label>
                                        <div class="col-md-6 input-icon right">
                                            <input type="text" class="form-control date-picker" data-date-today-highlight="true" name="end_date">
                                            <div class="form-control-focus"></div>
                                            <span class="help-block">Enter Expire Date.</span>
                                        </div>
                                    </div>
                                    <div class="form-group form-md-line-input">
                                        <label class="col-md-2 control-label" for="has_device">Has Attendance Device<span class="required" aria-required="true"> * </span></label>
                                        <div class="col-md-6 input-icon right">
                                            <select class="form-control" name="has_device">
                                                <option value="0">No</option>
                                                <option value="1">Yes</option>

                                            </select>
                                            <div class="form-control-focus"></div>
                                            <span class="help-block">Select Status</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-actions">
                                    <div class="row">
                                        <div class="col-md-offset-3 col-md-9">
                                            <a href="javascript:;" class="btn green" id="storeBranch">Submit</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php echo Form::close(); ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END PAGE CONTENT INNER -->
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer'); ?>
    <?php echo HTML::script('admin/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js'); ?>

    <?php echo HTML::script('admin/global/plugins/ladda/spin.min.js'); ?>

    <?php echo HTML::script('admin/global/plugins/ladda/ladda.min.js'); ?>

    <?php echo HTML::script('admin/pages/scripts/ui-buttons.min.js'); ?>

    <script>
        $('#storeBranch').click(function () {
            $.easyAjax({
                url: "<?php echo e(route('gym-admin.superadmin.storeBranchPage')); ?>",
                container: '#branchStoreForm',
                type: 'POST',
                data: $('#branchStoreForm').serialize()
            });
        });

        $('.date-picker').datepicker({
            rtl: App.isRTL(),
            orientation: "left",
            autoclose: true,
            format: "yyyy-mm-dd"
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.gym-merchant.gymbasic', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/stage.hamrofitness.com/public_html/resources/views/gym-admin/super-admin/create-branches/branch.blade.php ENDPATH**/ ?>