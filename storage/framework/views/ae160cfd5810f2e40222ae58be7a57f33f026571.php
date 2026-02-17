<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <span class="caption-subject font-red-sunglo bold uppercase"><i class="fa fa-list"></i> Renew History</span>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label class="control-label sbold">Branch Name:</label>
                <p class="form-control-static"> <?php echo e(ucwords($branch->title)); ?> </p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label class="control-label sbold">Branch Admin:</label>
                <p class="form-control-static"> <?php echo e(ucwords($branch->owner_incharge_name)); ?> </p>
            </div>
        </div>
        <!--/span-->
        <div class="col-md-4">
            <div class="form-group">
                <label class="control-label sbold">Contact Number:</label>
                <p class="form-control-static"> <?php echo e($branch->phone); ?> </p>
            </div>
        </div>
        <!--/span-->
    </div>

    <div class="row">
        <div class="col-md-12 ">
            <table class="table table-striped table-bordered table-hover row-border">
                <thead>
                    <tr>
                        <th>Created Date</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Package Offered</th>
                        <th>Package Amount</th>
                        <th>Remark</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $branch->histories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $follow): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($follow->created_at->toFormattedDateString()); ?></td>
                        <td><?php echo e($follow->renew_start_date->toFormattedDateString()); ?></td>
                        <td><?php echo e($follow->renew_end_date->toFormattedDateString()); ?></td>
                        <td><?php echo e($follow->package_offered); ?> Month</td>
                        <td><?php echo e($follow->package_amount); ?></td>
                        <td><?php echo e($follow->remark); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="modal-footer">
        <a href="javascript:;" class="btn blue"  data-dismiss="modal" aria-hidden="true" >OK</a>
    </div>
</div>
<?php /**PATH /var/www/stage.hamrofitness.com/public_html/resources/views/gym-admin/super-admin/view-renew-history.blade.php ENDPATH**/ ?>