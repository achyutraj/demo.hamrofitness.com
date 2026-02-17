<div class="modal" tabindex="-1" id="departmentEditModel<?php echo e($department->id); ?>" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 style="font-weight: 600;" class="modal-title">Edit Device department</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?php echo e(route('device.departments.update', $department->id)); ?>" method="POST">
                <?php echo e(csrf_field()); ?>

                <div class="modal-body">
                    <div class="form-group">
                        <label for="name"><h5>Department Name *</h5></label>
                        <input type="text" value="<?php echo e($department->name); ?>" class="form-control" name="name"
                               placeholder="Department Name"  required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                         Close
                    </button>
                    <button type="submit" class="btn btn-primary">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
 </div><?php /**PATH /var/www/stage.hamrofitness.com/public_html/resources/views/devices/departments/edit.blade.php ENDPATH**/ ?>