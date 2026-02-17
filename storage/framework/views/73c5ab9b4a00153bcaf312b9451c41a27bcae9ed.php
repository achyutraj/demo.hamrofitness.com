

<?php $__env->startSection('CSS'); ?>
    <?php echo HTML::style('admin/global/plugins/ladda/ladda-themeless.min.css'); ?>

    <?php echo HTML::style('admin/global/plugins/bootstrap-select/css/bootstrap-select.min.css'); ?>

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
                <span>Branch Setup 3 of 3</span>
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
                                <span class="caption-subject font-red bold uppercase"> STEP 3 of 3 </span>
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

                            <?php echo Form::open(['route'=>'gym-admin.superadmin.storeRolePage','id'=>'roleStoreForm','class'=>'ajax-form form-horizontal','method'=>'POST','files' => true]); ?>

                            <div class="form-wizard">
                                <div class="form-body">
                                    <ul class="nav nav-pills nav-justified steps">
                                        <?php if(!is_null($branchData) > 0): ?>
                                            <li>
                                                <a href="<?php echo e(route('gym-admin.superadmin.branch', [$branchData->id])); ?>" class="step">
                                                    <span class="number"> 1 </span>
                                                    <span class="desc">
                                                                            <i class="fa fa-check"></i> Add Branch </span>
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                        <?php if(isset($manager_id)): ?>
                                            <li>
                                                <a href="<?php echo e(route('gym-admin.superadmin.manager', [$manager_id])); ?>" class="step">
                                                    <span class="number"> 2 </span>
                                                    <span class="desc">
                                                                            <i class="fa fa-check"></i> Add Manager </span>
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                        <li class="active">
                                            <a href="javascript:;"
                                               class="step active">
                                                <span class="number"> 3 </span>
                                                <span class="desc">
                                                                        <i class="fa fa-check"></i> Assign Role </span>
                                            </a>
                                        </li>
                                    </ul>
                                    <input type="hidden" name="role_id" value="<?php echo e($role->id); ?>">
                                    <div class="form-group form-md-line-input">
                                        <label class="col-md-2 control-label">Branch Name</label>
                                        <div class="col-md-6">
                                            <select class="bs-select form-control" data-live-search="true" data-size="8" name="branch_id" id="branch_id">
                                                    <option selected value="<?php echo e($branchData->id); ?>"><?php echo e(ucwords($branchData->title)); ?></option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group form-md-line-input">
                                        <label class="col-md-2 control-label" for="form_control_1">Role Name <span class="required" aria-required="true"> * </span></label>
                                        <div class="col-md-6 input-icon right">
                                            <select class="form-control todo-taskbody-tags" name="role">
                                                <option>Select Role</option>
                                                <option class="todo-username pull-left" value="<?php echo e($role->name); ?>"><?php echo e($role->name); ?></option>
                                            </select>
                                            <div class="form-control-focus"></div>
                                            <span class="help-block">Select Role</span>
                                        </div>
                                    </div>

                                    <hr>
                                </div>

                                <div class="form-actions">
                                    <div class="row">
                                        <div class="col-md-offset-3 col-md-9">
                                            <a href="javascript:;" class="btn green" id="storeRole">Submit</a>
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
    <?php echo HTML::script('admin/global/plugins/ladda/spin.min.js'); ?>

    <?php echo HTML::script('admin/global/plugins/ladda/ladda.min.js'); ?>

    <?php echo HTML::script('admin/pages/scripts/ui-buttons.min.js'); ?>

    <?php echo HTML::script('admin/global/plugins/bootstrap-select/js/bootstrap-select.min.js'); ?>

    <?php echo HTML::script('admin/pages/scripts/components-bootstrap-select.min.js'); ?>

    <script>
        $('#storeRole').click(function () {
            $.easyAjax({
                url: "<?php echo e(route('gym-admin.superadmin.storeRolePage')); ?>",
                container: '#roleStoreForm',
                type: 'POST',
                data: $('#roleStoreForm').serialize()
            });
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.gym-merchant.gymbasic', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/stage.hamrofitness.com/public_html/resources/views/gym-admin/super-admin/create-branches/role.blade.php ENDPATH**/ ?>