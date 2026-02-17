<div class="actions">
    <a class="btn sbold dark" data-toggle="modal" data-target="#addNewDepartment">Add New
        <i class="fa fa-plus"></i></a>
</div>
<table class="table table-striped">
    <thead>
    <tr>
        <th>Name</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
    <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $department): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
            <td><?php echo e($department->name); ?></td>
            <td>
                <a class="btn btn-sm btn-primary" data-toggle="modal" data-target="#departmentEditModel<?php echo e($department->id); ?>"
                    style="font-size: 12px;">Edit <i class="fa fa-edit"></i></a>

                <?php echo $__env->make('devices.departments.edit', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                <a class="btn btn-sm btn-danger department-delete" data-department-url="<?php echo e(route('device.departments.delete', $department->id)); ?>" 
                     href="javascript:;">
                    Delete <i class="fa fa-trash"></i> 
                </a>
                
            </td>
        </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
</table>

<?php echo $__env->make('devices.departments.create', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php /**PATH /var/www/stage.hamrofitness.com/public_html/resources/views/devices/departments/table.blade.php ENDPATH**/ ?>