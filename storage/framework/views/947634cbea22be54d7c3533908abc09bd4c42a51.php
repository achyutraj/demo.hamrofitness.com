<?php if(count($todayTasks) > 0): ?>
<div class="page-content-inner">
    <div class="row card">
        <h3>Today Task</h3>
        <div class="card-body">
            <?php $__currentLoopData = $todayTasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                    $status = 'info';
                    if($task->priority == 'high'){
                        $status = 'danger';
                    }elseif($task->priority == 'medium'){
                        $status = 'warning';
                    }
                    ?>
                    <div class="col-md-4 col-lg-4 col-xs-12">
                        <div class="task-reminder alert-<?php echo e($status); ?>">
                            <a href="<?php echo e(route('gym-admin.task.index')); ?>" class="text-white">
                                <h4><?php echo e(ucfirst($task->heading)); ?> </h4>
                                <p>Description: <?php echo e($task->description); ?></p>
                                <p>   Status: <?php echo e($task->status); ?></p>
                                 <p>   Deadline: <?php echo e($task->deadline->toFormattedDateString()); ?></p>
                            </a>
                        </div>
                    </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</div>
<?php endif; ?>
<?php /**PATH /var/www/stage.hamrofitness.com/public_html/resources/views/gym-admin/dashboard/task_reminder.blade.php ENDPATH**/ ?>