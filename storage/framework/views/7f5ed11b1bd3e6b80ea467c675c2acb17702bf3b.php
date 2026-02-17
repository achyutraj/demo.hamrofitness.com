<?php $__env->startSection('CSS'); ?>
    <?php echo HTML::style('admin/global/plugins/datatables/datatables.min.css'); ?>

    <?php echo HTML::style('admin/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>

    <?php echo HTML::style('admin/global/plugins/bootstrap-select/css/bootstrap-select.min.css'); ?>

    <?php echo HTML::style('admin/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css'); ?>


    <style>
        #manage-branches_wrapper {
            margin-top: 20px;
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
                <span>Products</span>
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
                                <i class="fa fa-dropbox font-red"></i>
                                <span class="caption-subject font-red bold uppercase"> Products</span>
                            </div>
                            <div class="pull-right">
                                <div class="btn-group">
                                    <a id="sample_editable_1_new" href="<?php echo e(route('gym-admin.products.create')); ?>" class="btn sbold dark"> Add New
                                        <i class="fa fa-plus"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <div class="table-toolbar">
                                <?php if(session()->has('message')): ?>
                                    <div class="alert alert-success">
                                        <?php echo e(session()->get('message')); ?>

                                    </div>
                                <?php endif; ?>
                                
                                <div id="assetList" class="tab-pane fade in active">
                                    <table class="table table-striped table-bordered table-hover order-column table-100"
                                           id="manage-branches">
                                        <thead>
                                        <tr>
                                            <th class="desktop">Name</th>
                                            <th class="desktop"> Brand Name</th>
                                            <th class="desktop"> Supplier</th>
                                            <th class="desktop"> Price</th>
                                            <th class="desktop"> Expiry Date</th>
                                            <th class="desktop"> Quantity</th>
                                            <th class="desktop"> Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td>
                                                    <?php echo e($product->name); ?>

                                                    <?php if($product->expire_date != null && $product->expire_date->lt(today())): ?>
                                                        <label class="label label-danger">Product Expired</label>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?php echo e($product->brand_name); ?></td>
                                                <td><?php echo e($product->suppliers->name ?? ''); ?></td>
                                                <td><?php echo e($gymSettings->currency->acronym); ?> <?php echo e($product->price); ?></td>
                                                <td><?php echo e(!is_null($product->expire_date) ? date('Y M d', strtotime($product->expire_date)) : 'No Date'); ?></td>
                                                <td>
                                                    <i class="fa fa-circle"
                                                       style="color: #368496;"></i> <?php echo e($product->quantity); ?> in Total<br>
                                                    <i class="fa fa-circle"
                                                       style="color: #0069D9;"></i> <?php echo e($product->quantity - ($product->quantity_expired + $product->quantity_sold)); ?>

                                                    In Stock<br>
                                                    <i class="fa fa-circle"
                                                       style="color: #68C217;"></i> <?php echo e($product->quantity_sold); ?> Sold<br>
                                                    <i class="fa fa-circle"
                                                       style="color: #DF3D35;"></i> <?php echo e($product->quantity_expired); ?>

                                                    Expired<br>
                                                </td>
                                                <td class="drop">

                                                    <div class="btn-group">
                                                        <button class="btn blue btn-xs dropdown-toggle"
                                                                onclick="addStyle()" type="button"
                                                                data-toggle="dropdown"><i class="fa fa-gears"></i> <span
                                                                    class="hidden-xs">Action</span>
                                                            <i class="fa fa-angle-down"></i>
                                                        </button>
                                                        <ul class="dropdown-menu pull-right" role="menu">
                                                                <li>
                                                                    <a href="<?php echo e(route('gym-admin.products.edit',$product->uuid)); ?>"><i
                                                                                class="fa fa-edit"></i> Edit</a>
                                                                </li>
                                                                <?php if($product->quantity_sold == 0): ?>
                                                                <li>
                                                                    <a data-toggle="modal" data-target="#deleteProduct<?php echo e($product->uuid); ?>"><i
                                                                                class="fa fa-trash"></i> Delete</a>
                                                                </li>
                                                                <?php endif; ?>
                                                                <li>
                                                                    <a href="<?php echo e(route('gym-admin.products.quantity', $product->uuid)); ?>">
                                                                        <i class="fa fa-cubes"></i> Manage Quantity
                                                                    </a>
                                                                </li>
                                                        </ul>
                                                    </div>

                                                    
                                                    <?php if($product->quantity_sold == 0): ?>
                                                    <div class="modal fade bs-modal-md in"
                                                         id="deleteProduct<?php echo e($product->uuid); ?>" role="dialog"
                                                         aria-labelledby="myModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-md" id="modal-data-application">
                                                            <?php echo Form::open(['route' => ['gym-admin.products.destroy', $product->uuid], 'class' => 'delete', 'method' => 'DELETE']); ?>

                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                                    <span class="caption-subject font-red-sunglo bold uppercase">Remove Product</span>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="row">
                                                                        <div class="col-md-12">
                                                                            <p> Are you Sure you want to Remove <?php echo e(ucwords($product->name)); ?> ?</p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="submit"  class="btn blue">Remove</button>
                                                                    <button type="button" data-dismiss="modal" class="btn">Cancel</button>
                                                                </div>
                                                            </div>
                                                            <?php echo Form::close(); ?>


                                                        </div>
                                                    </div>
                                                    <?php endif; ?>

                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('footer'); ?>
    <?php echo HTML::script('admin/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js'); ?>

    <?php echo HTML::script('admin/global/scripts/datatable.js'); ?>

    <?php echo HTML::script('admin/pages/scripts/table-datatables-managed.js'); ?>

    <?php echo HTML::script('admin/global/plugins/datatables/datatables.min.js'); ?>

    <?php echo HTML::script('admin/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js'); ?>

    <?php echo HTML::script('admin/global/plugins/bootstrap-select/js/bootstrap-select.min.js'); ?>

    <?php echo HTML::script('admin/pages/scripts/components-bootstrap-select.min.js'); ?>


    <script>
        var table = $('#manage-branches');
        $('.date-picker').datepicker({
            rtl: App.isRTL(),
            orientation: "left",
            autoclose: true,
            format: 'yyyy-mm-dd'
        });
        table.dataTable({
            responsive: true,
            lengthMenu: [
                [25, 50, 75 , 100, -1],
                ['25', '50','75' ,'100', 'All']
            ],
            pageLength: 25,
        });

        $(window).ready(function () {
            setTimeout(function () {
                $(".alert-success").remove();
            }, 3000);
        });

        function addStyle() {
            $('.drop').css('padding-bottom', '100px');
        }

        function removeStyle() {
            $('.drop').css('padding-bottom', '40px');
        }

        $(window).click(function () {
            removeStyle();
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.gym-merchant.gymbasic', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/stage.hamrofitness.com/public_html/resources/views/gym-admin/products/index.blade.php ENDPATH**/ ?>