<div class="actions">
    <a class="btn sbold dark" data-toggle="modal" data-target="#addNewBranch">Add New
        <i class="fa fa-plus"></i></a>
</div>
<table class="table table-striped">
    <thead>
    <tr>
        <th>Name</th>
        <th>From Time </th>
        <th>To Time </th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
    <?php $__currentLoopData = $shifts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shift): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
            <td><?php echo e($shift->name); ?></td>
            <td><?php echo e($shift->from_time); ?></td>
            <td><?php echo e($shift->to_time); ?></td>

            <td>
                <a class="btn btn-sm btn-primary" data-toggle="modal" data-target="#branchEditModel<?php echo e($shift->id); ?>"
                    style="font-size: 12px;">Edit <i class="fa fa-edit"></i></a>

                <?php echo $__env->make('devices.shifts.edit', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                <a class="btn btn-sm btn-danger branch-delete" data-branch-url="<?php echo e(route('device.shifts.delete', $shift->id)); ?>" 
                    data-branch-id="<?php echo e($shift->id); ?>" href="javascript:;">
                    Delete <i class="fa fa-trash"></i> 
                </a>
               
            </td>
        </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
</table>

<?php echo $__env->make('devices.shifts.create', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php /**PATH /var/www/stage.hamrofitness.com/public_html/resources/views/devices/shifts/table.blade.php ENDPATH**/ ?>