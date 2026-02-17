

<?php $__env->startSection('CSS'); ?>
    <?php echo HTML::style('admin/global/plugins/datatables/datatables.min.css'); ?>

    <?php echo HTML::style('admin/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>

    <?php echo HTML::style('admin/global/plugins/datatables/Responsive-2.0.2/css/responsive.bootstrap.css'); ?>

    <?php echo HTML::style('admin/global/plugins/datatables/Responsive-2.0.2/css/responsive.dataTables.css'); ?>

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
                <span>Users</span>
            </li>
        </ul>
        <!-- END PAGE BREADCRUMBS -->
        <!-- BEGIN PAGE CONTENT INNER -->
        <div class="page-content-inner">
            <?php if(session()->has('message')): ?>
                <div class="alert alert-message alert-success">
                    <?php echo e(session()->get('message')); ?>

                </div>
            <?php endif; ?>
            <div class="row">
                <div class="col-md-12">
                    <!-- Begin: life time stats -->
                    <div class="portlet light">
                        <div class="portlet-title">
                            <div class="caption col-sm-9 col-xs-12">
                                <i class="icon-user font-red"></i><span class="caption-subject font-red bold uppercase">Users</span>
                            </div>

                            <div class="actions col-sm-3 col-xs-12">
                                <a href="<?php echo e(route('gym-admin.gymmerchantroles.create')); ?>" class="btn dark"> Add Roles
                                    <i class="fa fa-plus"></i>
                                </a>
                            </div>

                        </div>
                        <div class="portlet-body">
                            <div class="row">
                                <div class="col-md-6 col-sm-12">
                                    <table class="table table-striped table-bordered table-hover order-column table-100" id="merchants">
                                        <thead>
                                        <tr role="row" class="heading">
                                            <th class="max-desktop">
                                                Username
                                            </th>
                                            <th class="desktop">
                                                Role
                                            </th>
                                            <th class="desktop">
                                                Actions
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-md-6 col-sm-12" style="border-left: 2px solid #4FBCD5;">
                                    <table class="table table-striped table-bordered table-hover order-column table-100" id="merchants1">
                                        <thead>
                                            <tr role="row" class="heading">
                                                <th class="max-desktop">
                                                    Role
                                                </th>
                                                <th class="desktop">
                                                    Actions
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End: life time stats -->
                </div>
            </div>
        </div>
    </div>

    



    <div class="modal fade bs-modal-md in" id="reminderModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" id="modal-data-application">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <span class="caption-subject font-red-sunglo bold uppercase" id="modelHeading"></span>
                </div>
                <div class="modal-body">
                    Loading...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn default" data-dismiss="modal">Close</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    
<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer'); ?>
    <?php echo HTML::script('admin/global/scripts/datatable.js'); ?>

    <?php echo HTML::script('admin/pages/scripts/table-datatables-managed.js'); ?>

    <?php echo HTML::script('admin/global/plugins/datatables/datatables.min.js'); ?>

    <?php echo HTML::script('admin/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js'); ?>


    <script>

        var merchantTable = $('#merchants');

        var table = merchantTable.dataTable({
            processing: true,
            serverSide: true,
            ajax: "<?php echo e(route('gym-admin.users.ajax-create')); ?>",
            columns: [
                {data: 'username', name: 'username'},
                {data: 'role', name: 'role'},
                {data: 'edit', name: 'edit', orderable: false, searchable: false},
            ]
        });

        $('#merchants').on('click','.assign-role', function () {
            var id = $(this).data('user-id');
            var show_url = "<?php echo e(route('gym-admin.users.assign-role-modal',['#id'])); ?>";
            var url = show_url.replace('#id', id);
            $('#modelHeading').html('Assign Role');
            $.ajaxModal("#reminderModal", url);
        });


        $('#merchants').on('click','.remove-user',function(){
            var id = $(this).data('user-id');
            bootbox.confirm({
                message: "Do you want to delete this user?",
                buttons: {
                    confirm: {
                        label: "Yes",
                        className: "btn-primary <?php if($userCount == 1): ?> disabled <?php endif; ?>",
                        disabled: "true"
                    }
                },
                callback: function(result) {
                    var userCount = "<?php echo e($userCount); ?>";
                    if(result && userCount > 1) {
                        var url = "<?php echo e(route('gym-admin.users.destroy',':id')); ?>";
                        url = url.replace(':id',id);

                        $.easyAjax({
                            url: url,
                            type: "DELETE",
                            data: {id: id,_token: '<?php echo e(csrf_token()); ?>'},
                            success: function(){
                                load_dataTable();
                            }
                        });
                    } else {
                        console.log('cancel');
                    }
                }
            })
        });
        $(function(){
            setTimeout(function() {
                $('.alert-message').slideUp();
            }, 3000);
        });

        var merchant1Table = $('#merchants1');

        var table1 = merchant1Table.dataTable({
            processing: true,
            serverSide: true,
            ajax: "<?php echo e(route('gym-admin.gymmerchantroles.ajax-create')); ?>",
            columns: [
                {data: 'name', name: 'name'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });

        $('#merchants1').on('click','.remove-role',function(){
            var id = $(this).data('role-id');
            bootbox.confirm({
                message: "Do you want to delete this role?",
                buttons: {
                    confirm: {
                        label: "Yes",
                        className: "btn-primary"
                    }
                },
                callback: function(result){
                    if(result){
                        var url_role = "<?php echo e(route('gym-admin.gymmerchantroles.destroy',':id')); ?>";
                        url_role_new = url_role.replace(':id',id);
                        $.easyAjax({
                            url: url_role_new,
                            type: "DELETE",
                            data: {id: id,_token: '<?php echo e(csrf_token()); ?>'},
                            success: function(){
                                load_dataTable();
                            }
                        });
                    } else {
                        console.log('cancel');
                    }
                }
            })
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.gym-merchant.gymbasic', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/stage.hamrofitness.com/public_html/resources/views/gym-admin/users/index.blade.php ENDPATH**/ ?>