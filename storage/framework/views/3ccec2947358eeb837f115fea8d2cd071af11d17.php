<?php $__env->startSection('CSS'); ?>
    <?php echo HTML::style('admin/global/plugins/ladda/ladda-themeless.min.css'); ?>

    <?php echo HTML::style('admin/global/plugins/datatables/datatables.min.css'); ?>

    <?php echo HTML::style('admin/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>


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
                <a href="<?php echo e(route('gym-admin.products.index')); ?>">Product</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>Product Payments</span>
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
                                <span class="caption-subject font-red bold uppercase"> Product Payments</span>
                            </div>
                            <div class="pull-right">
                                <div class="btn-group">
                                    <a href="<?php echo e(route('gym-admin.product-payments.create')); ?>" id="add_payment" class="action btn dark"> add <span
                                            class="hidden-xs"></span>
                                        <i class="fa fa-plus"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <ul class="nav nav-tabs">
                                <li class="active"><a data-toggle="tab" href="#all"> Payment</a></li>
                                <li><a data-toggle="tab" href="#deleted">Deleted Payment</a></li>
                            </ul>

                            <div class="tab-content">
                                <div id="all" class="tab-pane fade in active">
                                    <table  class="table table-bordered table-hover order-column table-100" id="mem-payments">
                                        <thead>
                                        <tr>
                                            <th class="max-desktop"> Name</th>
                                            <th class="desktop"> Amount</th>
                                            <th class="desktop"> Source</th>
                                            <th class="desktop"> Payment Date</th>
                                            <th class="desktop"> Payment ID</th>
                                            <th class="desktop"> Actions</th>
                                        </tr>
                                        </thead>
                                    </table>
                                </div>
                                <div id="deleted" class="tab-pane fade">
                                    <table class="table table-bordered table-hover order-column table-100"
                                           id="mem-payments_deleted">
                                        <thead>
                                        <tr>
                                            <th class="max-desktop"> Name</th>
                                            <th class="desktop"> Amount</th>
                                            <th class="desktop"> Source</th>
                                            <th class="desktop"> Payment Date</th>
                                            <th class="desktop"> Payment ID</th>
                                            <th class="desktop"> Remarks</th>
                                            <th class="desktop"> Deleted On</th>
                                        </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
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
        jQuery(document).ready(function () {
            load_dataTable();
            loaddeleted_dataTable();
        });

        function loaddeleted_dataTable() {
            var memberPaymentDeleteTable = $('#mem-payments_deleted');

            var table = memberPaymentDeleteTable.dataTable({
                processing: true,
                serverSide: true,
                destroy: true,
                ordering: true,
                ajax: "<?php echo e(route('gym-admin.product-payments.ajax_create_deleted')); ?>",
                columns: [
                    {data: 'user_id', name: 'user_id'},
                    {data: 'payment_amount', name: 'payment_amount'},
                    {data: 'payment_source', name: 'payment_source'},
                    {data: 'payment_date', name: 'payment_date'},
                    {data: 'payment_id', name: 'payment_id'},
                    {data: 'remarks', name: 'remarks'},
                    {data: 'deleted_at', name: 'deleted_at'},
                ],
                lengthMenu: [
                    [25, 50, 75 , 100, -1],
                    ['25', '50','75' ,'100', 'All']
                ],
                pageLength: 25,
            });
        }

        function load_dataTable() {
            var productDueTable = $('#mem-payments');
            var table = productDueTable.dataTable({
                processing: true,
                serverSide: true,
                ordering: true,
                ajax: "<?php echo e(route('gym-admin.product-payments.ajax-create')); ?>",
                columns: [
                    {data: 'gym_clients.first_name', name: 'gym_clients.first_name'},
                    {data: 'payment_amount', name: 'payment_amount'},
                    {data: 'payment_source', name: 'payment_source'},
                    {data: 'payment_date', name: 'payment_date'},
                    {data: 'payment_id', name: 'payment_id'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
                lengthMenu: [
                    [25, 50, 75 , 100, -1],
                    ['25', '50','75' ,'100', 'All']
                ],
                pageLength: 25,
            });
        }
    </script>

    <script>
        $('#mem-payments').on('click', '.remove-payment', function () {
            var id = $(this).data('payment-id');
            bootbox.confirm({
                message: "Do you want to delete this product payment?",
                buttons: {
                    confirm: {
                        label: "Yes",
                        className: "btn-primary"
                    }
                },
                callback: function (result) {
                    if (result) {
                        var url = "<?php echo e(route('gym-admin.product-payments.destroy',':id')); ?>";
                        url = url.replace(':id', id);
                        $.easyAjax({
                            url: url,
                            type: "DELETE",
                            data: {id: id, _token: '<?php echo e(csrf_token()); ?>'},
                            success: function () {
                                table._fnDraw();
                            }
                        });
                    } else {
                        console.log('cancel');
                    }
                }
            })
        });
    </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.gym-merchant.gymbasic', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/stage.hamrofitness.com/public_html/resources/views/gym-admin/products/payments/index.blade.php ENDPATH**/ ?>