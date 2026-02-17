<div class="modal" tabindex="-1" id="deviceEditModel<?php echo e($device->id); ?>" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 style="font-weight: 600;" class="modal-title">Edit Device</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?php echo e(route('device.info.update', $device->id)); ?>" method="POST">
                <?php echo e(csrf_field()); ?>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group form-md-line-input col-md-12">
                                <label for="department">Department <span class="required" aria-required="true"> * </span></label>
                                <select class="bs-select form-control" data-live-search="true" data-size="8" id="department"
                                        name="department[]" multiple required>
                                    <option>Select Department</option>
                                    <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $department): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option class="todo-username pull-left"
                                            <?php if($device->departments->contains($department->id)): ?> selected <?php endif; ?>
                                                value="<?php echo e($department->id); ?>"><?php echo e($department->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group form-md-line-input col-md-6">
                            <label>Device Code <span class="required" aria-required="true"> * </span></label>
                            <input type="text" class="form-control" placeholder="Enter Device Code"
                                    name="code" value="<?php echo e($device->code); ?>" required>
                            <div class="form-control-focus"></div>
                            <span class="help-block">Enter Device Code</span>
                        </div>
                        <div class="form-group form-md-line-input col-md-6">
                            <label>Device Brand Name <span class="required" aria-required="true"> * </span></label>
                            <input type="text" class="form-control" placeholder="Enter Device Name"
                                    name="name" value="<?php echo e($device->name); ?>" required>
                            <div class="form-control-focus"></div>
                            <span class="help-block">Enter Device Brand Name</span>
                        </div>
                        <div class="form-group form-md-line-input col-md-6">
                            <label>Ip Address <span class="required" aria-required="true"> * </span></label>
                            <input type="text" class="form-control" placeholder="Enter Ip Address"
                                    name="ip_address" value="<?php echo e($device->ip_address); ?>" required>
                            <div class="form-control-focus"></div>
                            <span class="help-block">Enter Ip Address</span>
                        </div>
                        <div class="form-group form-md-line-input col-md-6">
                            <label>Serial No. <span class="required" aria-required="true"> * </span></label>
                            <input type="text" class="form-control" placeholder="Enter Serial No."
                                    name="serial_num" value="<?php echo e($device->serial_num); ?>" required>
                            <div class="form-control-focus"></div>
                            <span class="help-block">Enter Serial No.</span>
                        </div>
                        <div class="form-group form-md-line-input col-md-6">
                            <label>Port No. <span class="required" aria-required="true"> * </span></label>
                            <input type="text" class="form-control" placeholder="Enter Port No."
                                    name="port_num" value="<?php echo e($device->port_num); ?>" required>
                            <div class="form-control-focus"></div>
                            <span class="help-block">Enter Port No.</span>
                        </div>
                        <div class="form-group form-md-line-input col-md-6">
                            <label>Device Type</label>
                            <input type="text" class="form-control" placeholder="Enter Device Type"
                                    name="device_type" value="<?php echo e($device->device_type); ?>">
                            <div class="form-control-focus"></div>
                            <span class="help-block">Enter Device Type</span>
                        </div>
                        <div class="form-group form-md-line-input col-md-6">
                            <label>Device Model</label>
                            <input type="text" class="form-control" placeholder="Enter Device Model"
                                    name="device_model" value="<?php echo e($device->device_model); ?>">
                            <div class="form-control-focus"></div>
                            <span class="help-block">Enter Device Model</span>
                        </div>
                        <div class="form-group form-md-line-input col-md-6">
                            <label>Vendor Name <span class="required" aria-required="true"> * </span></label>
                            <input type="text" class="form-control" placeholder="Enter Vendor Name"
                                    name="vendor_name" value="<?php echo e($device->vendor_name); ?>" required>
                            <div class="form-control-focus"></div>
                            <span class="help-block">Enter Vendor Name</span>
                        </div>
                        <div class="form-group form-md-line-input col-md-6">
                            <label>Device Status</label>
                            <select class="form-control todo-taskbody-tags" id="device_status" name="device_status">
                                <option class="todo-username pull-left"
                                    value="1" <?php if($device->device_status == 1): ?> selected <?php endif; ?>>Active</option>
                                <option class="todo-username pull-left"
                                    value="0" <?php if($device->device_status == 0): ?> selected <?php endif; ?>>InActive</option>
                            </select>
                        </div>
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
 </div><?php /**PATH /var/www/stage.hamrofitness.com/public_html/resources/views/devices/device_info/edit.blade.php ENDPATH**/ ?>