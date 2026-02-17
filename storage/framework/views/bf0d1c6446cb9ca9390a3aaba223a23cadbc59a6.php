<?php $__env->startSection('title', 'Product Quantity Management'); ?>
<?php $__env->startSection('content'); ?>
    <div class="page-content">
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-success alert-dismissable" style="display: none;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                    <strong>Success!</strong> <span class="success-message"></span>
                </div>

                <div class="alert alert-danger alert-dismissable" style="display: none;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                    <strong>Error!</strong> <span class="error-message"></span>
                </div>

                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-shopping-cart"></i>
                            Product Quantity Management
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="portlet light bordered">
                                    <div class="portlet-title">
                                        <div class="caption">
                                            <i class="fa fa-plus"></i>
                                            Update Quantity
                                        </div>
                                    </div>
                                    <div class="portlet-body">
                                        <?php echo Form::open(['id'=>'form_sample_3','class'=>'ajax-form','method'=>'POST']); ?>

                                            <div class="form-group">
                                                <label>Product Name</label>
                                                <input type="text" class="form-control" value="<?php echo e($product->name); ?>" readonly>
                                            </div>
                                            <div class="form-group">
                                                <label>Current Quantity</label>
                                                <input type="number" class="form-control" value="<?php echo e($product->quantity); ?>" readonly>
                                            </div>
                                            <div class="form-group">
                                                <label>Action <span class="required" aria-required="true"> * </span></label>
                                                <select name="action" class="form-control" required>
                                                    <option value="add">Add Quantity</option>
                                                    <option value="remove">Remove Quantity</option>
                                                    <option value="update">Update Total Quantity</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>Quantity <span class="required" aria-required="true"> * </span></label>
                                                <input type="number" name="quantity" class="form-control" required min="1">
                                            </div>
                                            <div class="form-group">
                                                <label>Notes <span class="required" aria-required="true"> * </span></label>
                                                <textarea name="notes" class="form-control" rows="3" required></textarea>
                                            </div>
                                            <button type="submit" class="btn dark mt-ladda-btn ladda-button" data-style="zoom-in" id="save-form">
                                                <span class="ladda-label">
                                                    <i class="fa fa-save"></i> Update Quantity
                                                </span>
                                                <span class="ladda-spinner"></span>
                                                <div class="ladda-progress" style="width: 0px;"></div>
                                            </button>
                                        <?php echo Form::close(); ?>

                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="portlet light bordered">
                                    <div class="portlet-title">
                                        <div class="caption">
                                            <i class="fa fa-history"></i>
                                            Quantity History
                                        </div>
                                    </div>
                                    <div class="portlet-body">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Date</th>
                                                        <th>Type</th>
                                                        <th>Previous Qty</th>
                                                        <th>Change</th>
                                                        <th>New Qty</th>
                                                        <th>Notes</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $__currentLoopData = $product->quantityHistories()->orderBy('created_at', 'desc')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $history): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <tr>
                                                            <td><?php echo e($history->created_at->format('M d,Y')); ?></td>
                                                            <td>
                                                                <?php if($history->type == 'add'): ?>
                                                                    <span class="label label-success">Add</span>
                                                                <?php elseif($history->type == 'remove'): ?>
                                                                    <span class="label label-danger">Remove</span>
                                                                <?php else: ?>
                                                                    <span class="label label-info">Update</span>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td><?php echo e($history->previous_quantity); ?></td>
                                                            <td><?php echo e($history->quantity); ?></td>
                                                            <td><?php echo e($history->new_quantity); ?></td>
                                                            <td><?php echo e($history->notes); ?></td>
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
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer'); ?>
    <?php echo HTML::script('admin/global/plugins/ladda/spin.min.js'); ?>

    <?php echo HTML::script('admin/global/plugins/ladda/ladda.min.js'); ?>

    <?php echo HTML::script('admin/pages/scripts/ui-buttons.min.js'); ?>

    <?php echo HTML::script('admin/global/plugins/jquery-validation/js/jquery.validate.min.js'); ?>

    <?php echo HTML::script('admin/global/plugins/jquery-validation/js/additional-methods.min.js'); ?>


    <script>
        var FormValidationMd = function() {
            var handleValidation3 = function() {
                var form1 = $('#form_sample_3');
                var error1 = $('.alert-danger', form1);
                var success1 = $('.alert-success', form1);

                form1.validate({
                    errorElement: 'span',
                    errorClass: 'help-block help-block-error',
                    focusInvalid: false,
                    ignore: "",
                    rules: {
                        action: {
                            required: true
                        },
                        quantity: {
                            required: true,
                            min: 1
                        },
                        notes: {
                            required: true
                        }
                    },

                    invalidHandler: function(event, validator) {
                        success1.hide();
                        error1.show();
                        App.scrollTo(error1, -200);
                    },

                    errorPlacement: function(error, element) {
                        if (element.is(':checkbox')) {
                            error.insertAfter(element.closest(".md-checkbox-list, .md-checkbox-inline, .checkbox-list, .checkbox-inline"));
                        } else if (element.is(':radio')) {
                            error.insertAfter(element.closest(".md-radio-list, .md-radio-inline, .radio-list,.radio-inline"));
                        } else {
                            error.insertAfter(element);
                        }
                    },

                    highlight: function(element) {
                        $(element)
                            .closest('.form-group').addClass('has-error');
                    },

                    unhighlight: function(element) {
                        $(element)
                            .closest('.form-group').removeClass('has-error');
                    },

                    success: function(label) {
                        label
                            .closest('.form-group').removeClass('has-error');
                    },

                    submitHandler: function(form) {
                        success1.hide();
                        error1.hide();
                        $.easyAjax({
                            url: "<?php echo e(route('gym-admin.products.update-quantity', $product->uuid)); ?>",
                            container: '#form_sample_3',
                            type: "POST",
                            data: $('#form_sample_3').serialize(),
                            success: function(response) {
                                if(response.status == 'success') {
                                    $('.success-message').html(response.message);
                                    $('.alert-success').show();
                                    // Reload the page after 1 second to show updated data
                                    setTimeout(function() {
                                        window.location.reload();
                                    }, 1000);
                                }
                            },
                            error: function(response) {
                                $('.error-message').html(response.responseJSON.message);
                                $('.alert-danger').show();
                            }
                        });
                        return false;
                    }
                });
            }

            return {
                init: function() {
                    handleValidation3();
                }
            };
        }();

        jQuery(document).ready(function() {
            FormValidationMd.init();
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.gym-merchant.gymbasic', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/stage.hamrofitness.com/public_html/resources/views/gym-admin/products/quantity.blade.php ENDPATH**/ ?>