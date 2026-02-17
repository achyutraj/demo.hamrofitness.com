<div class="actions">
    <?php if($user->is_admin == 1): ?>
    <a class="btn sbold dark" data-toggle="modal" data-target="#addNewDevice">Add New
        <i class="fa fa-plus"></i></a>
        <?php endif; ?>
        
</div>
<table class="table table-striped">
    <thead>
    <tr>
        <th>Department</th>
        <th>Device Name</th>
        <th>IpAddress</th>
        <th>Serial No</th>
        <th>Status</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
    <?php $__currentLoopData = $devices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $device): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
            <td>
                <?php $__currentLoopData = $device->departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $depart): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php echo e($depart->name); ?> , 
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </td>
            <td><?php echo e($device->name); ?></td>
            <td><?php echo e($device->ip_address); ?></td>
            <td><?php echo e($device->serial_num); ?></td>
            <td><?php echo e($device->device_status == 1 ? 'Active' : 'Inactive'); ?></td>
            <td>
                <?php if($user->is_admin == 1): ?>
                <a class="btn btn-sm btn-primary" data-toggle="modal" data-target="#deviceEditModel<?php echo e($device->id); ?>"
                    style="font-size: 12px;">Edit <i class="fa fa-edit"></i></a>

                <?php echo $__env->make('devices.device_info.edit', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                <a class="btn btn-sm btn-danger device-delete" data-device-url="<?php echo e(route('device.info.delete', $device->id)); ?>" 
                    data-device-id="<?php echo e($device->id); ?>" href="javascript:;">
                    Delete <i class="fa fa-trash"></i> 
                <?php endif; ?>
                <a class="btn btn-sm btn-info"
                        href="<?php echo e(route('device.info.show', $device->id)); ?>"
                        style="font-size: 12px;">Check Status <i class="fa fa-check-circle"></i></a>
                </a>
            </td>
        </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
</table>

<?php if($user->is_admin == 1): ?>
<?php echo $__env->make('devices.device_info.create', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php endif; ?><?php /**PATH /var/www/stage.hamrofitness.com/public_html/resources/views/devices/device_info/table.blade.php ENDPATH**/ ?>