

<?php $__env->startSection('CSS'); ?>
    <?php echo HTML::style('admin/global/plugins/ladda/ladda-themeless.min.css'); ?>

    <?php echo HTML::style('admin/global/plugins/bootstrap-select/css/bootstrap-select.min.css'); ?>

    <?php echo HTML::style('admin/global/plugins/datatables/datatables.min.css'); ?>

    <?php echo HTML::style('admin/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>

    <style>
        .table-scrollable .dataTable td .btn-group, .table-scrollable .dataTable th .btn-group {
            position: relative;
            margin-top: -2px;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
        <!-- BEGIN PAGE BREADCRUMBS -->
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <a href="<?php echo e(route('gym-admin.dashboard.index')); ?>">Home</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>Clients Biometric</span>
            </li>
        </ul>
        <div class="page-content-inner">
            <div class="row">
                <?php if(session()->has('message')): ?>
                    <div class="alert alert-message alert-success">
                        <?php echo e(session()->get('message')); ?>

                    </div>
                <?php endif; ?>
                <?php if(session()->has('danger')): ?>
                        <div class="alert alert-danger alert-success">
                            <?php echo e(session()->get('danger')); ?>

                        </div>
                <?php endif; ?>
                <?php if(session('errors')): ?>
                    <div class="alert alert-danger">
                        <ul>
                            <?php $__currentLoopData = session('errors'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                <?php endif; ?>
                <div class="col-md-12">
                    <!-- BEGIN EXAMPLE TABLE PORTLET-->
                    <div class="portlet light ">
                        <div class="portlet-title">
                            <div class="caption font-dark">
                                <i class="icon-users font-red"></i>
                                <span class="caption-subject font-red bold uppercase"> Customers Biometric</span>
                            </div>
                            <div class="pull-right">
                                <div class="btn-group">
                                    <a href="<?php echo e(route('device.biometrics.create')); ?>" class="btn sbold dark"> Add New
                                        <i class="fa fa-plus"></i>
                                    </a>

                                    <a href="<?php echo e(route('device.biometrics.addCardForm')); ?>" class="btn sbold btn-info"> Add Card
                                        <i class="fa fa-plus"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <table class="table table-striped table-bordered table-100"
                                   id="gym_clients">
                                <thead>
                                <tr>
                                    <th class="desktop">Client</th>

                                    <th class="desktop">Device Details</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                    <tr>
                                        <td style="text-align: left" width="20%">
                                            Name:<strong> <?php echo e($client->fullName); ?></strong>
                                            <br>UserPin: <strong><?php echo e($client->customer_id); ?> </strong>
                                            <br>CardID: <strong><?php echo e($client->card); ?> </strong>

                                        </td>

                                        <td>
                                            <table class="table table-bordered order-column table-100 nowrap" style="width: 100%"
                                                   id="gym_clients" >
                                                <thead>
                                                <tr>
                                                    <th class="desktop"> Department</th>
                                                    <th class="desktop"> Shift</th>
                                                    <th class="desktop"> Device</th>
                                                    <th class="desktop"> Door Access</th>
                                                    <th class="desktop"> Actions</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $__currentLoopData = $client->devices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $device): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <tr class="">
                                                            <td>
                                                                <?php $__currentLoopData = $device->departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $depart): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <p><?php echo e($depart->name); ?> , </p>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                            </td>
                                                            <td>
                                                                <?php $__currentLoopData = $client->shifts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shift): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <p><?php echo e($shift->name); ?> , </p>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                            </td>
                                                            <td><?php echo e($device->name); ?></td>
                                                            <td>
                                                                <?php if($device->pivot->is_device_deleted == 1 || $device->pivot->is_denied == 1): ?>
                                                                    <span class="label label-danger">Denied</span>
                                                                <?php else: ?>
                                                                    <span class="label label-success">Allowed</span>
                                                                <?php endif; ?>
                                                                <?php echo e($client->clientDeviceSync = null ? 'Not Sync' : ''); ?>

                                                            </td>
                                                            <td>
                                                                <div class="btn-group">
                                                                    <button class="btn blue btn-xs dropdown-toggle" type="button" data-toggle="dropdown"><i class="fa fa-gears"></i> <span class="hidden-xs hidden-medium">Actions</span>
                                                                        <i class="fa fa-angle-down"></i>
                                                                    </button>
                                                                    <ul class="dropdown-menu pull-right" role="menu">
                                                                        <li>
                                                                            <a href="<?php echo e(route('gym-admin.client.show', $client->customer_id)); ?>"> <i class="fa fa-edit"></i>Show Profile</a>
                                                                        </li>
                                                                        <li>
                                                                            <a class="remove-user" data-device_name = "<?php echo e($device->name); ?>"
                                                                            data-url="<?php echo e(route('device.biometrics.clientRemoveFromDevice',['clientId'=>$client->customer_id,'deviceId'=>$device->id])); ?>"> <i class="fa fa-trash"></i>Remove</a>
                                                                        </li>
                                                                    <?php if($client->clientDeviceSync = null): ?>
                                                                    <li>
                                                                        <a href="<?php echo e(route('device.biometrics.syncUser',['clientId'=>$client->customer_id])); ?>"> <i class="fa fa-plus"></i>Sync</a>
                                                                    </li>
                                                                    <?php endif; ?>
                                                                    <?php if($device->pivot->is_device_deleted == 1): ?>
                                                                    <li>
                                                                        <a class="renew-user" data-device_name = "<?php echo e($device->name); ?>" data-client_id="<?php echo e($client->id); ?>" data-device_id="<?php echo e($device->id); ?>"
                                                                        data-url="<?php echo e(route('device.biometrics.renewUserStore')); ?>"> <i class="fa fa-recycle"></i>Renew</a>
                                                                    </li>
                                                                    <?php else: ?>
                                                                    <li>
                                                                        <a class="denied-user" data-device_name = "<?php echo e($device->name); ?>"
                                                                        data-url="<?php echo e(route('device.biometrics.clientRemoveFromDeviceOnly',['clientId'=>$client->customer_id,'deviceId'=>$device->id])); ?>"> <i class="fa fa-stop-circle"></i>Denied</a>
                                                                    </li>
                                                                    <?php endif; ?>
                                                                </ul>
                                                            </div>
                                                                
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </tbody>
                                            </table>
                                        </td>

                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('footer'); ?>
    <?php echo HTML::script('admin/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js'); ?>

    <?php echo HTML::style('admin/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css'); ?>

    <?php echo HTML::script('admin/global/plugins/ladda/spin.min.js'); ?>

    <?php echo HTML::script('admin/global/plugins/ladda/ladda.min.js'); ?>

    <?php echo HTML::script('admin/pages/scripts/ui-buttons.min.js'); ?>

    <?php echo HTML::script('admin/global/plugins/bootstrap-select/js/bootstrap-select.min.js'); ?>

    <?php echo HTML::script('admin/pages/scripts/components-bootstrap-select.min.js'); ?>

    <?php echo HTML::script('admin/global/scripts/datatable.js'); ?>

    <?php echo HTML::script('admin/pages/scripts/table-datatables-managed.js'); ?>

    <?php echo HTML::script('admin/global/plugins/datatables/datatables.min.js'); ?>

    <?php echo HTML::script('admin/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js'); ?>

    <script>
        var table = $('#gym_clients');
        table.dataTable({
            responsive: true,
        });
        $(function () {
            setTimeout(function () {
                $('.alert-message').slideUp();
            }, 3000);
        });
    </script>
    <script>
       var UIBootbox = function () {
       var deviceData = function () {
            $('#gym_clients').on('click', '.remove-user', function () {
                var deviceUrl = $(this).data('url');
                var deviceName = $(this).data('device_name');

                bootbox.confirm({
                    message: "Do you want to delete this client from "+ deviceName + "?",
                    buttons: {
                        confirm: {
                            label: "Yes",
                            className: "btn-primary"
                        }
                    },
                    callback: function(result){
                        if(result){
                            $.easyAjax({
                                url: deviceUrl,
                                type: 'POST',
                                data: {
                                    '_method': 'delete' , '_token': '<?php echo e(csrf_token()); ?>'
                                },
                                success: function(){
                                    location.reload();
                                }
                            });
                        }
                        else {
                            console.log('cancel');
                        }
                    }
                })

            })
        };

        var deniedUserData = function () {
            $('#gym_clients').on('click', '.denied-user', function () {
                var deviceUrl = $(this).data('url');
                var deviceName = $(this).data('device_name');
                bootbox.confirm({
                    message: "Do you want to denied this client from "+ deviceName + "?",
                    buttons: {
                        confirm: {
                            label: "Yes",
                            className: "btn-primary"
                        }
                    },
                    callback: function(result){
                        if(result){
                            $.easyAjax({
                                url: deviceUrl,
                                type: 'POST',
                                data: {
                                    '_method': 'delete' , '_token': '<?php echo e(csrf_token()); ?>'
                                },
                                success: function(){
                                    location.reload();
                                }
                            });
                        }
                        else {
                            console.log('cancel');
                        }
                    }
                })

            })
        };
        
        var renewUserData = function () {
            $('#gym_clients').on('click', '.renew-user', function () {
                var deviceUrl = $(this).data('url');
                var deviceId = $(this).data('device_id');
                var clientId = $(this).data('client_id');
                var deviceName = $(this).data('device_name');
                bootbox.confirm({
                    message: "Do you want to renew this client on "+ deviceName + "?",
                    buttons: {
                        confirm: {
                            label: "Yes",
                            className: "btn-primary"
                        }
                    },
                    callback: function(result){
                        if(result){
                            var dataToSend = {
                                '_token': '<?php echo e(csrf_token()); ?>',
                                'devices': {}
                            };

                            // Construct the data structure for devices
                            dataToSend.devices[clientId] = {};  // Initialize the object for clientId
                            dataToSend.devices[clientId][deviceId] = deviceId.toString(); // Add the deviceId

                            $.easyAjax({
                                url: deviceUrl,
                                type: 'POST',
                                data: dataToSend,
                                success: function(){
                                    location.reload();
                                }
                            });
                        }
                        else {
                            console.log('cancel');
                        }
                    }
                })

            })
        };
        
        return {
            init: function () {
                deviceData(),
                deniedUserData(),
                renewUserData()
            }
        }
    }();
    jQuery(document).ready(function () {
        UIBootbox.init()
    });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.gym-merchant.gymbasic', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/stage.hamrofitness.com/public_html/resources/views/devices/biometrics/multi-device-index.blade.php ENDPATH**/ ?>