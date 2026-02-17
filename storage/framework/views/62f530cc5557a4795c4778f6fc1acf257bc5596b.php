<div class="modal" tabindex="-1" id="branchEditModel<?php echo e($shift->id); ?>" role="dialog">
   <div class="modal-dialog" role="document">
       <div class="modal-content">
           <div class="modal-header">
               <h4 style="font-weight: 600;" class="modal-title">Edit Device Shift</h4>
               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                   <span aria-hidden="true">&times;</span>
               </button>
           </div>
           <form action="<?php echo e(route('device.shifts.update', $shift->id)); ?>" method="POST">
               <?php echo e(csrf_field()); ?>

               <div class="modal-body">
                   <div class="form-group col-md-4">
                       <label for="name"><h5>Name *</h5></label>
                       <input type="text" value="<?php echo e($shift->name); ?>" class="form-control" name="name"
                              placeholder=" Name"  required>
                   </div>
                   <div class="form-group col-md-4">
                        <label for="from_time"><h5>From Time *</h5></label>
                        <input type="time" class="form-control" name="from_time" placeholder="Enter From Time" required
                            value="<?php echo e($shift->from_time); ?>" required>
                        <?php if($errors->has('from_time')): ?>
                            <span class="invalid-feedback danger" role="alert">
                                <strong><?php echo e($errors->first('from_time')); ?></strong>
                            </span>
                        <?php endif; ?>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="to_time"><h5>To Time *</h5></label>
                        <input type="time" class="form-control" name="to_time" placeholder="Enter To Time" required
                            value="<?php echo e($shift->to_time); ?>" required>
                        <?php if($errors->has('to_time')): ?>
                            <span class="invalid-feedback danger" role="alert">
                                <strong><?php echo e($errors->first('to_time')); ?></strong>
                            </span>
                        <?php endif; ?>
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
</div><?php /**PATH /var/www/stage.hamrofitness.com/public_html/resources/views/devices/shifts/edit.blade.php ENDPATH**/ ?>