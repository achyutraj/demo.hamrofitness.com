

<?php $__env->startSection('CSS'); ?>
    <?php echo HTML::style('admin/global/plugins/ladda/ladda-themeless.min.css'); ?>

    <?php echo HTML::style('admin/global/plugins/bootstrap-select/css/bootstrap-select.min.css'); ?>

    <?php echo HTML::style('admin/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css'); ?>

    <style>
        .bill-color {
            color: #888;
        }

        .file-size {
            line-height: 0;
            color: #a2a2a2;
            font-size: 13px;
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
                <a href="<?php echo e(route('gym-admin.products.index')); ?>">Product</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span><?php if(isset($product->uuid)): ?> Edit <?php else: ?> Add <?php endif; ?> Product</span>
            </li>
        </ul>
        <!-- END PAGE BREADCRUMBS -->
        <!-- BEGIN PAGE CONTENT INNER -->
        <div class="page-content-inner">
            <div class="row">
                <div class="col-md-10 col-xs-12">
                    <div class="portlet light portlet-fit">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="icon-plus font-red"></i><span class="caption-subject font-red bold uppercase"><?php if(isset($product->uuid)): ?>
                                        Edit <?php else: ?> Add <?php endif; ?> Product</span></div>


                        </div>
                        <div class="portlet-body">
                            <!-- BEGIN FORM-->
                            <?php echo Form::open(['id'=>'create-edit-product','class'=>'ajax-form']); ?>

                                <div class="form-body">
                                    <?php if(isset($product->uuid)): ?>
                                        <input type="hidden" name="_method" value="PUT">
                                    <?php endif; ?>
                                
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group form-md-line-input ">
                                                <div class="input-icon">
                                                    <select class="form-control select2" name="supplier_id"
                                                            id="supplier_id">
                                                        <option selected disabled>Please Select</option>
                                                        <?php $__currentLoopData = $suppliers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $supplier): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($supplier->id); ?>" <?php echo e(isset($product) && ($product->supplier_id == $supplier->id) ? 'selected' : ''); ?>><?php echo e($supplier->name); ?></option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                    <label for="supplier_id">Purchase From <span class="required"
                                                        aria-required="true"> * </span></label>
                                                    <span class="help-block">Add Supplier Name</span>
                                                    <i class="fa fa-shopping-cart"></i>
                                                </div>
                                                <?php if( !isset($product->uuid)): ?> 
                                                    <a class="btn btn-xs btn-success" href="<?php echo e(route('gym-admin.suppliers.create')); ?>" title="Add Supplier">Add</a>
                                                <?php endif; ?>

                                                <?php $__errorArgs = ['supplier_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                    <div style="color: red;"><?php echo e($message); ?></div>
                                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                            </div>
                                        </div>
                                    
                                        <div class="col-md-6">
                                            <div class="form-group form-md-line-input ">
                                                <div class="input-icon">
                                                    <input type="text" class="form-control" placeholder="Product Name"
                                                        name="name" value="<?php echo e($product->name ?? ''); ?>">
                                                    <label for="name">Product Name<span class="required"
                                                                                            aria-required="true"> * </span></label>
                                                    <span class="help-block">Add Product Name</span>
                                                    <i class="fa fa-sticky-note-o"></i>
                                                </div>
                                                <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                    <div style="color: red;"><?php echo e($message); ?></div>
                                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group form-md-line-input ">
                                                <div class="input-icon">
                                                    <input type="text" class="form-control" placeholder="Product Tag"
                                                        name="tag" value="<?php echo e($product->tag ?? ''); ?>">
                                                    <label for="tag">Product Tag</label>
                                                    <span class="help-block">Add Product Tag</span>
                                                    <i class="fa fa-sticky-note-o"></i>
                                                </div>
                                                <?php $__errorArgs = ['tag'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                    <div style="color: red;"><?php echo e($message); ?></div>
                                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                            </div>
                                        </div>
                                    
                                        <div class="col-md-6">
                                            <div class="form-group form-md-line-input ">
                                                <div class="input-icon">
                                                    <input type="text" class="form-control" placeholder="Product Brand"
                                                        name="brand_name" value="<?php echo e($product->brand_name ?? ''); ?>">
                                                    <label for="brand_name">Product Brand</label>
                                                    <span class="help-block">Add Product Brand</span>
                                                    <i class="fa fa-sticky-note-o"></i>
                                                </div>
                                                <?php $__errorArgs = ['brand_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                    <div style="color: red;"><?php echo e($message); ?></div>
                                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group form-md-line-input ">
                                                <div class="input-icon">
                                                    <input type="number" class="form-control" placeholder="Product Quantity"
                                                        name="quantity" value="<?php echo e($product->quantity ?? ''); ?>">
                                                    <label for="quantity">Product Quantity</label>
                                                    <span class="help-block">Add Product Quantity</span>
                                                    <i class="fa fa-sticky-note-o"></i>
                                                </div>
                                                <?php $__errorArgs = ['quantity'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                    <div style="color: red;"><?php echo e($message); ?></div>
                                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                            </div>
                                        </div>
                                    
                                        <div class="col-md-6">
                                            <div class="form-group form-md-line-input ">
                                                <div class="input-icon">
                                                    <input type="number" class="form-control" placeholder="Price" name="price" style="padding-left: 50px"
                                                        value="<?php echo e($product->price ?? ''); ?>">
                                                    <label for="price">Product Price Per Piece<span class="required"
                                                                                        aria-required="true"> * </span></label>
                                                    <span class="help-block">Add Price of Item</span>
                                                    <i class="fa"><?php echo e($gymSettings->currency->acronym); ?></i>
                                                </div>
                                                <?php $__errorArgs = ['price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                    <div style="color: red;"><?php echo e($message); ?></div>
                                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group form-md-line-input ">
                                        <div class="input-group left-addon right-addon">
                                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                            <input type="text" class="form-control date-picker" readonly
                                                name="purchase_date"
                                                value="<?php if(isset($product->uuid)): ?> <?php echo e(\Carbon\Carbon::parse($product->purchase_date)->format('m/d/Y')); ?> <?php else: ?> <?php echo e(\Carbon\Carbon::now('Asia/Kathmandu')->format('m/d/Y')); ?> <?php endif; ?>">
                                            <label for="purchase_date">Purchase Date<span class="required"
                                                                                        aria-required="true"> * </span></label>
                                        </div>
                                        <?php $__errorArgs = ['purchase_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <div style="color: red;"><?php echo e($message); ?></div>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>

                                    <div class="form-group form-md-line-input ">
                                        <div class="input-group left-addon right-addon">
                                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                            <input type="text" class="form-control date-picker" readonly
                                                name="expiry_date"
                                                value="<?php if(isset($product->uuid) && !is_null($product->expiry_date)): ?> <?php echo e(\Carbon\Carbon::parse($product->expiry_date)->format('m/d/Y')); ?> <?php endif; ?>">
                                            <label for="expiry_date">Expiry Date </label>
                                        </div>
                                        <?php $__errorArgs = ['expiry_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <div style="color: red;"><?php echo e($message); ?></div>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>

                                
                                </div>
                                <div class="form-actions">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <?php if(isset($product) && $product->uuid): ?>
                                                <button type="button" class="btn dark mt-ladda-btn ladda-button"
                                                        data-style="zoom-in" onclick="addUpdate('<?php echo e($product->uuid); ?>')">
                                                    <span class="ladda-label"><i class="fa fa-save"></i> Update</span>
                                                </button>
                                            <?php else: ?>
                                                <button type="button" class="btn dark mt-ladda-btn ladda-button"
                                                        data-style="zoom-in" onclick="addUpdate()">
                                                    <span class="ladda-label"><i class="fa fa-save"></i> Save</span>
                                                </button>
                                            <?php endif; ?>
                                            <a type="button" class="btn default"
                                            href="<?php echo e(route('gym-admin.products.index')); ?>">Cancel</a>
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

    <?php echo HTML::script('admin/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js'); ?>

    <?php echo HTML::style('admin/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css'); ?>

    <?php echo HTML::script('admin/global/plugins/ladda/spin.min.js'); ?>

    <?php echo HTML::script('admin/global/plugins/ladda/ladda.min.js'); ?>

    <?php echo HTML::script('admin/pages/scripts/ui-buttons.min.js'); ?>

    <?php echo HTML::script('admin/global/plugins/bootstrap-select/js/bootstrap-select.min.js'); ?>

    <?php echo HTML::script('admin/pages/scripts/components-bootstrap-select.min.js'); ?>

    <?php echo HTML::script('admin/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js'); ?>

    <script>
        $('.date-picker').datepicker({
            rtl: App.isRTL(),
            orientation: "left",
            autoclose: true
        });

        function addUpdate(id) {

            var url;
            if (typeof id != 'undefined') {
                url = "<?php echo e(route('gym-admin.products.update',':id')); ?>";
                url = url.replace(':id', id);
            } else {
                url = "<?php echo e(route('gym-admin.products.store')); ?>";
            }

            $.easyAjax({
                type: "POST",
                url: url,
                container: '#create-edit-product',
                data: $('#create-edit-product').serialize(),
            });
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.gym-merchant.gymbasic', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/stage.hamrofitness.com/public_html/resources/views/gym-admin/products/create-edit.blade.php ENDPATH**/ ?>