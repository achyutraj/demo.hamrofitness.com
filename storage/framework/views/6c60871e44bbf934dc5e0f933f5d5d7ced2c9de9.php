
<?php $__env->startSection('CSS'); ?>
    <style>
        h4, h5 {
            font-weight: 600;
        }

        .danger {
            color: red;
        }
        #department.bs-select{
            width: 500px;
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
                <span>Device Management</span>
            </li>
        </ul>
        <!-- END PAGE BREADCRUMBS -->
        <!-- BEGIN PAGE CONTENT INNER -->
        <div class="page-content-inner">
            <div class="row">
                <div class="col-md-12">
                    <!-- BEGIN EXAMPLE TABLE PORTLET-->
                    <div class="portlet light ">
                        <div class="portlet-title">
                            <div class="caption font-dark">
                                <i class="fa fa-fax font-red"></i>
                                <span class="caption-subject font-red bold uppercase"> Device Management</span>
                            </div>
                        </div>

                        <div class="portlet-body">
                            <div class="table-toolbar">
                                <?php if(session()->has('message')): ?>
                                    <div class="alert alert-success">
                                        <?php echo e(session()->get('message')); ?>

                                    </div>
                                <?php endif; ?>
                                <?php if(session()->has('danger')): ?>
                                    <div class="alert alert-danger">
                                        <?php echo e(session()->get('danger')); ?>

                                    </div>
                                <?php endif; ?>
                                <div class="asset-tab">
                                    <ul class="nav nav-tabs">
                                        <li class="active">
                                            <a data-toggle="tab" href="#branchList">Shift</a>
                                        </li>
                                        <li>
                                            <a data-toggle="tab" href="#departmentList">Department</a>
                                        </li>
                                        <li>
                                            <a data-toggle="tab" href="#deviceList">Device</a>
                                        </li>
                                    </ul>

                                    <div class="tab-content">
                                        <div id="branchList" class="tab-pane fade in active">
                                            <?php echo $__env->make('devices.shifts.table', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                        </div>
                                        <div id="departmentList" class="tab-pane fade">
                                            <?php echo $__env->make('devices.departments.table', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                        </div>
                                        <div id="deviceList" class="tab-pane fade">
                                            <?php echo $__env->make('devices.device_info.table', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('footer'); ?>
<script href="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap.min.js"></script>
<?php echo HTML::script('admin/global/plugins/bootbox/bootbox.min.js'); ?>

<?php echo HTML::script('fitsigma_customer/bower_components/datatables/jquery.dataTables.min.js'); ?>

<script>
    $(document).ready(function () {
        $('#paymentTable').DataTable();
    });

    $('.bs-select').select2();

    $(function () {
        setTimeout(function () {
            $('.alert-message').slideUp();
        }, 3000);
    });
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var UIBootbox = function () {
        var branchData = function () {
            $(".branch-delete").click(function () {
                var branchUrl = $(this).data('branch-url');
                bootbox.confirm({
                    message: "Do you want to delete this shift?",
                    buttons: {
                        confirm: {
                            label: "Yes",
                            className: "btn-primary"
                        }
                    },
                    callback: function(result){
                        if(result){
                            $.easyAjax({
                                url: branchUrl,
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
        var departmentData = function () {
            $(".department-delete").click(function () {
                var departmentUrl = $(this).data('department-url');
                bootbox.confirm({
                    message: "Do you want to delete this department?",
                    buttons: {
                        confirm: {
                            label: "Yes",
                            className: "btn-primary"
                        }
                    },
                    callback: function(result){
                        if(result){
                            $.easyAjax({
                                url: departmentUrl,
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
        var deviceData = function () {
            $(".device-delete").click(function () {
                var deviceUrl = $(this).data('device-url');
                bootbox.confirm({
                    message: "Do you want to delete this device?",
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
        return {
            init: function () {
                branchData()
                departmentData()
                deviceData()
            }
        }
    }();
    jQuery(document).ready(function () {
        UIBootbox.init()
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.gym-merchant.gymbasic', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/stage.hamrofitness.com/public_html/resources/views/devices/index.blade.php ENDPATH**/ ?>