
<?php $__env->startSection('sms'); ?>
    <div class="inbox-header">
        <h2 class="pull-left">SMS</h2>
        <h4 class="pull-right">SMS Credit: <?php echo e($credit_balance); ?></h4>
    </div>
    <div class="inbox-content">
        <?php if($user->is_admin == 1): ?>
        <a href="javascript:;" data-title="View" class="btn info view-btn btn-block view-admin-smses">
            View All SMS sent to Branch Manager </a>
        <?php endif; ?>
        <a href="javascript:;" data-title="View" data-url="customer" class="btn red view-btn btn-block view-customer-smses">
            View All SMS sent to Customers </a>
        <a href="javascript:;" data-title="View" class="btn green view-btn btn-block view-employee-smses">
            View All SMS sent to Employees </a>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('detail-scripts'); ?>
    <script>
        $('a.view-customer-smses').on('click', function () {
            window.location = "<?php echo e(url('gym-admin/sms/customers')); ?>";
        });
        $('a.view-admin-smses').on('click', function () {
            window.location = "<?php echo e(url('gym-admin/sms/admins')); ?>";
        });
        $('a.view-employee-smses').on('click', function () {
            window.location = "<?php echo e(url('gym-admin/sms/employees')); ?>";
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('gym-admin.sms.index', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/stage.hamrofitness.com/public_html/resources/views/gym-admin/sms/detail.blade.php ENDPATH**/ ?>