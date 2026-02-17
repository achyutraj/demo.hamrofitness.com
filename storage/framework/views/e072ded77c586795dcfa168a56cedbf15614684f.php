<?php $__env->startSection('CSS'); ?>
    <?php echo HTML::style('admin/global/plugins/ladda/ladda-themeless.min.css'); ?>

    <?php echo HTML::style('admin/global/plugins/datatables/datatables.min.css'); ?>

    <?php echo HTML::style('admin/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>

    <style>
        .add-pending-btn-gap {
            margin-right: 10px;
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
                <span>Product Due</span>
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
                                <span class="caption-subject font-red bold uppercase"> Product Due</span>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <div class="table-toolbar">
                            </div>
                            <table class="table table-100 table-striped table-bordered table-hover responsive"
                                   id="purchase_table">
                                <thead>
                                <tr>
                                    <th class="desktop">Consumer Name</th>
                                    <th class="desktop">Product Name</th>
                                    <th class="desktop">Purchased At</th>
                                    <th class="desktop">Total Price</th>
                                    <th class="desktop">Paid </th>
                                    <th class="desktop">Remaining </th>
                                    <th class="desktop">Next Pay Date</th>
                                    <th class="desktop">Action</th>
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

    

    <div class="modal fade bs-modal-md in" id="reminderModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" id="modal-data-application">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <span class="caption-subject font-red-sunglo bold uppercase" id="modelHeading"></span>
                </div>
                <div class="modal-body">
                    Loading...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn default" data-dismiss="modal">Close</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
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
        function load_dataTable() {
            // var productDueTable = $('#purchase_table');

        }
    </script>
    <script>
        $(document).ready(function () {
            // load_dataTable();
            $('#purchase_table').dataTable({
                processing: true,
                serverSide: true,
                ajax: "<?php echo e(route('gym-admin.product-dues.ajax-create')); ?>",
                columns: [
                    {data: 'customer_name', name: 'customer_name'},
                    {data: 'product_name', name: 'product_name'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'product_amount', name: 'product_amount'},
                    {data: 'paid_amount', name: 'paid_amount'},
                    {data: 'total_amount', name: 'total_amount'},
                    {data: 'next_payment_date', name: 'next_payment_date'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
                lengthMenu: [
                    [25, 50, 75 , 100, -1],
                    ['25', '50','75' ,'100', 'All']
                ],
                pageLength: 25,
            });

        });

        $('#purchase_table').on('click', '.add-payment', function () {
            var id = $(this).data('id');
            var show_url = "<?php echo e(route('gym-admin.product-payments.add-payment-modal',['#id'])); ?>";
            var url = show_url.replace('#id', id);
            $('#modelHeading').html('Add Payment');
            $.ajaxModal("#reminderModal", url);
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.gym-merchant.gymbasic', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/stage.hamrofitness.com/public_html/resources/views/gym-admin/products/dues.blade.php ENDPATH**/ ?>