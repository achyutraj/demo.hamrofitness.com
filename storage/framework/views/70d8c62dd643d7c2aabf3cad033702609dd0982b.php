<?php $__env->startSection('CSS'); ?>
    <?php echo HTML::style('css/cropper.css'); ?>

    <?php echo HTML::style('admin/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css'); ?>

    <?php echo HTML::style('admin/global/plugins/ladda/ladda-themeless.min.css'); ?>

    <?php echo HTML::style('admin/pages/css/profile.min.css'); ?>

    <style>
        .error-msg {
            color: red;
            display: none;
        }
        .table-scrollable {
            width: 100%;
            overflow-x: hidden;
            overflow-y: hidden;
            border: 1px solid #e7ecf1;
            margin: 10px 0!important;
        }

    </style>
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
                <span>Device</span>
            </li>
        </ul>
        <!-- END PAGE BREADCRUMBS -->
        <!-- BEGIN PAGE CONTENT INNER -->
        <div class="page-content-inner">
            <div class="row">
                <div class="col-md-12">
                    <!-- BEGIN PROFILE SIDEBAR -->
                    <?php $__currentLoopData = $devices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $device): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="profile-sidebar">
                        <!-- PORTLET MAIN -->
                        <div class="portlet light profile-sidebar-portlet ">
                            <div class="profile-usertitle">
                                <div class="profile-usertitle-name"> <?php echo e($device['Name']); ?> - <?php echo e($device['BranchCode']); ?></div>
                            </div>

                        </div>
                        <!-- END PORTLET MAIN -->
                        <!-- PORTLET MAIN -->
                        <div class="portlet light ">
                            <!-- STAT -->
                            <div class="row list-separated profile-stat">
                                <div class="col-xs-12">
                                    <div class="col-md-6 col-sm-12">
                                        <div class="uppercase profile-stat-title"> <?php echo e($device['UserCount']); ?> </div>
                                        <div class="uppercase profile-stat-text"> Total Users</div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="uppercase profile-stat-title"> <?php echo e($device['FPCount']); ?> </div>
                                        <div class="uppercase profile-stat-text"> Total FingerPrint</div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="uppercase profile-stat-title"> <?php echo e($device['TransCount']); ?> </div>
                                        <div class="uppercase profile-stat-text"> Total Transaction</div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="uppercase profile-stat-title"> <?php echo e($device['ClientCode']); ?> </div>
                                        <div class="uppercase profile-stat-text"> Client Code</div>
                                    </div>
                                </div>
                            </div>

                            <!-- END STAT -->
                            <div>
                                <div class="margin-top-20 profile-desc-link">
                                    <i class="fa fa-clock-o"></i>
                                    <a href="javascript:;">Last Active: <?php echo e($device['LastActivity']); ?></a>
                                </div>
                                <div class="margin-top-20 profile-desc-link">
                                    <i class="fa fa-check-circle"></i>
                                    <a href="javascript:;">Status: <?php echo e($device['DeviceStatus']); ?></a>
                                </div>
                                <div class="margin-top-20 profile-desc-link">
                                    <i class="fa fa-info-circle"></i>
                                    <a href="javascript:;">Ip: <?php echo e($device['IP']); ?></a>
                                </div>
                                <div class="margin-top-20 profile-desc-link">
                                    <i class="fa fa-envelope"></i>
                                    <a href="javascript:;">SN: <?php echo e($device['SN']); ?></a>
                                </div>
                                <div class="margin-top-20 profile-desc-link">
                                    <i class="fa fa-fax"></i>
                                    <a href="javascript:;">Model: <?php echo e($device['DeviceModel']); ?></a>
                                </div>
                                <div class="margin-top-20 profile-desc-link">
                                    <i class="fa fa-gear"></i>
                                    <a href="javascript:;">Device Function: <?php echo e($device['DevFuns']); ?></a>
                                </div>
                            </div>
                        </div>
                        <!-- END PORTLET MAIN -->
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <!-- END BEGIN PROFILE SIDEBAR -->

                </div>
            </div>
        </div>
        <!-- END PAGE CONTENT INNER -->
    </div>


<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer'); ?>
    <?php echo HTML::script('admin/global/plugins/moment.min.js'); ?>

    <?php echo HTML::script('admin/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js'); ?>

    <?php echo HTML::script('admin/global/plugins/ladda/spin.min.js'); ?>

    <?php echo HTML::script('admin/global/plugins/ladda/ladda.min.js'); ?>

    <?php echo HTML::script('admin/pages/scripts/ui-buttons.min.js'); ?>

    <?php echo HTML::script('admin/pages/scripts/profile.min.js'); ?>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.gym-merchant.gymbasic', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/stage.hamrofitness.com/public_html/resources/views/devices/adms/index.blade.php ENDPATH**/ ?>