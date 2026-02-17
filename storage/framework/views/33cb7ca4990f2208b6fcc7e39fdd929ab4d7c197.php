<div class="modal" tabindex="-1" id="addNewDepartment" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 style="font-weight: 600;" class="modal-title">Add New Device Department</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?php echo e(route('device.departments.store')); ?>" method="POST">
                <?php echo e(csrf_field()); ?>

                <div class="modal-body">
                    <div class="form-group">
                        <label for="name"><h5>Department Name *</h5></label>
                        <input type="text" class="form-control" name="name" placeholder="Department Name"
                               value="<?php echo e(old('name')); ?>" required>
                        <?php if($errors->has('name')): ?>
                            <span class="invalid-feedback danger" role="alert">
                                <strong><?php echo e($errors->first('name')); ?></strong>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Close
                    </button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div><?php /**PATH /var/www/stage.hamrofitness.com/public_html/resources/views/devices/departments/create.blade.php ENDPATH**/ ?>