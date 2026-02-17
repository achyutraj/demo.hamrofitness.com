

<?php $__env->startSection('CSS'); ?>
    <?php echo HTML::style('admin/apps/css/inbox.min.css'); ?>

    <?php echo HTML::style('admin/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.css'); ?>

    <?php echo HTML::style('admin/global/plugins/bootstrap-select/css/bootstrap-select.min.css'); ?>

    <?php echo $__env->yieldPushContent('show-styles'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="page-container">
        <!-- BEGIN CONTENT -->
        <div class="page-content-wrapper">
            <!-- BEGIN CONTENT BODY -->
            <!-- BEGIN PAGE HEAD-->
            <!-- END PAGE HEAD-->
            <!-- BEGIN PAGE CONTENT BODY -->
            <div class="page-content">
                <div class="container-fluid">
                    <!-- BEGIN PAGE BREADCRUMBS -->
                    <ul class="page-breadcrumb breadcrumb">
                        <li>
                            <a href="<?php echo e(route('gym-admin.dashboard.index')); ?>">Home</a>
                            <i class="fa fa-circle"></i>
                        </li>
                        <li>
                            <a href="javascript:;">SMS</a>
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
                                            <?php if($smsSetting[0]->sms_status == 'disabled'): ?>
                                                <p>Goto settings/sms and enable sms status option to send sms</p>
                                            <?php else: ?>
                                                <a href="<?php echo e(route('gym-admin.sms.create')); ?>" class="btn red compose-btn btn-block">
                                                <i class="fa fa-edit"></i> Compose </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="portlet-body">
                                        <div class="inbox-body">
                                            <?php echo $__env->yieldContent('sms'); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="pull-right">

                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END PAGE CONTENT INNER -->
                </div>
            </div>
            <!-- END PAGE CONTENT BODY -->
            <!-- END CONTENT BODY -->
        </div>
        <!-- END CONTENT -->
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer'); ?>
    <?php echo HTML::script('admin/global/plugins/bootstrap-select/js/bootstrap-select.min.js'); ?>

    <?php echo HTML::script('admin/pages/scripts/components-bootstrap-select.min.js'); ?>

    <?php echo $__env->yieldPushContent('detail-scripts'); ?>
    <?php echo $__env->yieldPushContent('show-scripts'); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.gym-merchant.gymbasic', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/stage.hamrofitness.com/public_html/resources/views/gym-admin/sms/index.blade.php ENDPATH**/ ?>