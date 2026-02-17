<?php $__env->startSection('CSS'); ?>
    <?php echo HTML::style('admin/global/plugins/ladda/ladda-themeless.min.css'); ?>

    <?php echo HTML::style('admin/global/plugins/datatables/datatables.min.css'); ?>

    <?php echo HTML::style('admin/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>


    <style>
        .table-scrollable .dataTable td .btn-group, .table-scrollable .dataTable th .btn-group {
            position: relative;
            margin-top: -2px;
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
                <a href="<?php echo e(route('gym-admin.client-purchase.index')); ?>">Subscription</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>Extend Subscriptions</span>
            </li>
        </ul>
        <!-- END PAGE BREADCRUMBS -->
        <!-- BEGIN PAGE CONTENT INNER -->
        <div class="page-content-inner">
            <div class="row">
                <div class="col-md-12">
                    <!-- BEGIN EXAMPLE TABLE PORTLET-->
                    <div class="portlet light ">
                        <div class="portlet-title">
                            <div class="caption font-dark">
                                <i class="fa <?php echo e($gymSettings->currency->symbol); ?> font-red"></i>
                                <span class="caption-subject font-red bold uppercase">Extend Subscriptions</span>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <table class="table table-bordered table-hover order-column table-100" id="purchase_table">
                                <thead>
                                <tr>
                                    <th class="desktop"> Client</th>
                                    <th class="desktop"> Subscription</th>
                                    <th class="desktop"> Extend From</th>
                                    <th class="desktop"> Extend To</th>
                                    <th class="desktop"> Days</th>
                                    <th class="desktop"> Extend By</th>
                                    <th class="desktop"> Reason</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <!-- END EXAMPLE TABLE PORTLET-->
                </div>
            </div>
        </div>
        <!-- END PAGE CONTENT INNER -->
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer'); ?>

    <?php echo HTML::script('admin/global/scripts/datatable.js'); ?>

    <?php echo HTML::script('admin/pages/scripts/table-datatables-managed.js'); ?>

    <?php echo HTML::script('admin/global/plugins/datatables/datatables.min.js'); ?>

    <?php echo HTML::script('admin/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js'); ?>

    <?php echo HTML::script('admin/global/plugins/ladda/spin.min.js'); ?>

    <?php echo HTML::script('admin/global/plugins/ladda/ladda.min.js'); ?>

    <?php echo HTML::script('admin/pages/scripts/ui-buttons.min.js'); ?>


    <script>
        var clientTable = $('#purchase_table');

        var table = clientTable.dataTable({
            processing: true,
            serverSide: true,
            ajax: "<?php echo e(route('gym-admin.client-purchase.ajax-extend-subscription')); ?>",
            columns: [
                {data: 'client_id', name: 'client_id'},
                {data: 'purchase_id', name: 'purchase_id'},
                {data: 'extend_from', name: 'extend_from'},
                {data: 'extend_to', name: 'extend_to'},
                {data: 'days', name: 'days'},
                {data: 'extend_by', name: 'extend_by'},
                {data: 'reasons', name: 'reasons'},
            ],
            lengthMenu: [
                [25, 50, 75 , 100, -1],
                ['25', '50','75' ,'100', 'All']
            ],
            pageLength: 25,
        });
    </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.gym-merchant.gymbasic', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/stage.hamrofitness.com/public_html/resources/views/gym-admin/purchase/extend.blade.php ENDPATH**/ ?>