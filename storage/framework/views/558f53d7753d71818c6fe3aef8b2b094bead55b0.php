

<?php $__env->startSection('CSS'); ?>
    <?php echo HTML::style('admin/global/plugins/ladda/ladda-themeless.min.css'); ?>

    <?php echo HTML::style('admin/global/plugins/bootstrap-select/css/bootstrap-select.min.css'); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-fluid"  >
        <!-- BEGIN PAGE BREADCRUMBS -->
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <a href="<?php echo e(route('gym-admin.dashboard.index')); ?>">Home</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <a href="<?php echo e(route('device.biometrics.index')); ?>">Customer Biometric</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>Add</span>
            </li>
        </ul>
        <!-- END PAGE BREADCRUMBS -->
        <!-- BEGIN PAGE CONTENT INNER -->
        <div class="page-content-inner">
            <div class="row">
                <div class="col-md-12 col-xs-12">
                    <div class="portlet light portlet-fit">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="icon-plus font-red"></i><span class="caption-subject font-red bold uppercase">Create</span>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <?php if(is_array($errors) && count($errors) > 0): ?>
                                <div class="alert alert-danger">
                                    <ul class="list" style="list-style-type: none">
                                        <?php $__currentLoopData = $errors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li class="item"><?php echo e($error); ?></li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ul>
                                </div>
                            <?php elseif($errors instanceof \Illuminate\Support\MessageBag && $errors->any()): ?>
                                <div class="alert alert-danger">
                                    <ul class="list" style="list-style-type: none">
                                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li class="item"><?php echo e($error); ?></li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ul>
                                </div>
                            <?php endif; ?>

                            <!-- BEGIN FORM-->
                            <form action="<?php echo e(route('device.biometrics.store')); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <div class="form-body">
                                    <div class="text-center"><h4>Select Client and their shifts </h4>
                                    <p class="text-danger">Note: Only Active Client are lists here</p></div>
                                    <table class="table table-striped table-responsive">
                                        <thead>
                                            <tr>
                                                <th>Client</th>
                                                <?php $__currentLoopData = $shifts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shift): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <th>
                                                   <div class="md-checkbox">
                                                        <input type="checkbox" id="check_all-<?php echo e($shift->id); ?>" value="<?php echo e($shift->id); ?>"
                                                            class="md-check">

                                                        <label for="check_all-<?php echo e($shift->id); ?>">
                                                            <span></span>
                                                            <span class="check"></span>
                                                            <span class="box"></span> Check All <?php echo e($shift->name); ?></label>
                                                    </div>
                                                </th>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <?php $__currentLoopData = $devices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $device): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <th>
                                                    <div class="md-checkbox">
                                                            <input type="checkbox" id="device_check_all-<?php echo e($device->id); ?>" value="<?php echo e($device->id); ?>"
                                                                class="md-check">

                                                            <label for="device_check_all-<?php echo e($device->id); ?>">
                                                                <span></span>
                                                                <span class="check"></span>
                                                                <span class="box"></span> Check All <?php echo e($device->name); ?></label>
                                                        </div>
                                                    </th>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php
                                                    $clientShifts = $client->shifts()->pluck('shift_id')->toArray();
                                                    $device_clients = $client->devices()->pluck('device_id')->toArray();
                                                ?>
                                                <tr>
                                                    <td><?php echo e($client->fullName); ?></td>
                                                    <?php $__currentLoopData = $shifts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shift): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <td>
                                                            <div class="md-checkbox data-shift" data-shift="<?php echo e($shift->id); ?>">
                                                                <input type="checkbox" name="shifts[<?php echo e($client->customer_id); ?>][<?php echo e($shift->id); ?>]" class="md-check"
                                                                id="s-<?php echo e($client->customer_id); ?>-<?php echo e($shift->id); ?>" value="<?php echo e($shift->id); ?>"
                                                                <?php if(in_array($shift->id,$clientShifts)): ?> checked <?php endif; ?>>
                                                                <label for="s-<?php echo e($client->customer_id); ?>-<?php echo e($shift->id); ?>">
                                                                    <span></span>
                                                                    <span class="check"></span>
                                                                    <span class="box"></span> <?php echo e($shift->name); ?> </label>
                                                            </div>
                                                        </td>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    <?php $__currentLoopData = $devices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $device): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <td>
                                                            <div class="md-checkbox data-device" data-device="<?php echo e($device->id); ?>">
                                                                <input type="checkbox" name="devices[<?php echo e($client->customer_id); ?>][<?php echo e($device->id); ?>]" class="md-check"
                                                                id="d-<?php echo e($client->customer_id); ?>-<?php echo e($device->id); ?>" value="<?php echo e($device->id); ?>"
                                                                <?php if(in_array($device->id,$device_clients)): ?> checked <?php endif; ?> >
                                                                <label for="d-<?php echo e($client->customer_id); ?>-<?php echo e($device->id); ?>">
                                                                    <span></span>
                                                                    <span class="check"></span>
                                                                    <span class="box"></span> <?php echo e($device->name); ?> </label>
                                                            </div>
                                                        </td>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="form-actions">
                                    <div class="row">
                                        <div class="col-md-offset-3 col-md-9">
                                            <button type="submit" class="btn btn-primary">Save</button>
                                            <a href="<?php echo e(route('device.biometrics.index')); ?>" class="btn default">Cancel</a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <!-- END FORM-->
                          </div>
                    </div>

                </div>
            </div>
        </div>
        <!-- END PAGE CONTENT INNER -->
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('footer'); ?>
    <script>
        $(document).ready(function () {
            //for shifts check all
            <?php $__currentLoopData = $shifts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shift): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            $('#check_all-'+<?php echo e($shift->id); ?>).change(function () {
                var shiftId = $(this).val();
                var check = $(this).prop('checked');
                var elementsToSelect = $('.data-shift[data-shift="' + shiftId + '"]');
                if(check) {
                    elementsToSelect.each(function () {
                        var $collection = $(this);
                        $collection.find('input[type="checkbox"]').prop('checked', true);
                        $collection.closest('span').addClass('checked');
                    });
                }else{
                    elementsToSelect.each(function () {
                        var $collection = $(this);
                        $collection.find('input[type="checkbox"]').prop('checked', false);
                        $collection.closest('span').removeClass('checked');
                    });
                }
            });
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            //for device check all
            <?php $__currentLoopData = $devices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $device): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            $('#device_check_all-'+<?php echo e($device->id); ?>).change(function () {
                var deviceId = $(this).val();
                var check = $(this).prop('checked');
                var elementsToSelect = $('.data-device[data-device="' + deviceId + '"]');
                if(check) {
                    elementsToSelect.each(function () {
                        var $collection = $(this);
                        $collection.find('input[type="checkbox"]').prop('checked', true);
                        $collection.closest('span').addClass('checked');
                    });
                }else{
                    elementsToSelect.each(function () {
                        var $collection = $(this);
                        $collection.find('input[type="checkbox"]').prop('checked', false);
                        $collection.closest('span').removeClass('checked');
                    });
                }
            });
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        });

    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.gym-merchant.gymbasic', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/stage.hamrofitness.com/public_html/resources/views/devices/biometrics/create.blade.php ENDPATH**/ ?>