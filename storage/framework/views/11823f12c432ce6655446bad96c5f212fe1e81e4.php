<?php $__env->startSection('CSS'); ?>
    <?php echo HTML::style('admin/global/plugins/datatables/datatables.min.css'); ?>

    <?php echo HTML::style('admin/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>

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
                <span>Manage Branches</span>
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
                                <i class="fa fa-cogs font-red"></i>
                                <span class="caption-subject font-red bold uppercase"> Branches With SMS Credit Info</span>
                            </div>
                            <div class="pull-right">
                                <div class="btn-group">
                                    <a href="<?php echo e(route('gym-admin.superadmin.manage-branches')); ?>"
                                        class="btn success"> Branches
                                        <i class="fa fa-list"></i>
                                    </a>

                                    <a id="sample_editable_1_new" href="<?php echo e(route('gym-admin.superadmin.branch')); ?>"
                                        class="btn sbold dark"> Add New
                                        <i class="fa fa-plus"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <table class="table table-striped table-bordered table-hover row-border order-column nowrap"
                                id="manage-branches">
                                <thead>
                                    <tr>
                                        <th class="desktop"> Branch Name </th>
                                        <th class="desktop"> Branch Admin </th>
                                        <th class="desktop"> Phone </th>
                                        <th class="desktop"> Address </th>
                                        <th class="desktop"> Has Device </th>
                                        <th class="desktop"> SMS Status </th>
                                        <th class="desktop"> Email </th>
                                        <th class="desktop"> Start Date </th>
                                        <th class="desktop"> Expire Date </th>
                                        <th class="desktop"> Actions </th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <!-- END EXAMPLE TABLE PORTLET-->
                </div>
            </div>
        </div>
        <!-- END PAGE CONTENT INNER -->
    </div>

    
    <div class="modal fade bs-modal-md in" id="branchModal" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
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
                    <button type="button" id="deleteBranch" class="btn btn-danger">Delete</button>
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
        var table = $('#manage-branches');
        table.dataTable({
            responsive: true,
            "serverSide": true,
            "processing": true,
            "ajax": "<?php echo e(route('gym-admin.superadmin.getBranchDataWithSMSCredit')); ?>",
            "aoColumns": [{
                    'data': 'title',
                    'name': 'title',
                    'searchable': true
                },
                {
                    'data': 'owner_incharge_name',
                    'name': 'owner_incharge_name',
                    'searchable': true
                },
                {
                    'data': 'phone',
                    'name': 'phone',
                    'searchable': true
                },
                {
                    'data': 'address',
                    'name': 'address',
                    'searchable': true
                },
                {
                    'data': 'has_device',
                    'name': 'has_device'
                },
                {
                    'data': 'sms_status',
                    'name': 'sms_status'
                },
                {
                    'data': 'email',
                    'name': 'email',
                    'searchable': true
                },
                {
                    'data': 'start_date',
                    'name': 'start_date',
                    'searchable': true
                },
                {
                    'data': 'expires_on',
                    'name': 'expires_on',
                    'searchable': true
                },
                {
                    'data': 'actions',
                    'name': 'actions'
                }
            ],
            scrollX: true,
            lengthMenu: [
                [25, 50, 75 , 100, -1],
                ['25', '50','75' ,'100', 'All']
            ],
            pageLength: 25,
        });

        function deleteModal(id) {
            var url_modal = "<?php echo e(route('gym-admin.superadmin.destroy', [':id'])); ?>";
            var url = url_modal.replace(':id', id);
            $('#modelHeading').html('Remove Branch');
            var body = 'Do you want to delete branch ?<br>' +
                '<br><label class="label label-danger">NOTE:</label>  All users, payments and other data of this branch will be deleted.';
            $('.modal-body').html(body);
            $('#branchModal').modal("show");
            $('#deleteBranch').on('click', function() {
                $.easyAjax({
                    url: url,
                    type: 'DELETE',
                    data: {
                        '_token': "<?php echo e(csrf_token()); ?>"
                    },
                    success: function(response) {
                        if (response.status == "success") {
                            $('#branchModal').modal('hide');
                            table._fnDraw();
                            window.location.reload();
                        }
                    }
                })
            })
        }

        $('#manage-branches').on('click', '.branch-renew-model', function() {
            var enquiryId = $(this).data('branch-id');
            var url_modal = "<?php echo e(route('gym-admin.superadmin.renewBranchModel', [':id'])); ?>";
            var url = url_modal.replace(':id', enquiryId);
            $('#modelHeading').html('Branch Renew');
            $.ajaxModal("#branchModal", url);
        });

        $('#manage-branches').on('click', '.view-renew-history', function () {
            var enquiryId = $(this).data('branch-id');
            var url_modal = "<?php echo e(route('gym-admin.superadmin.renewHistory',[':id'])); ?>";
            var url = url_modal.replace(':id',enquiryId);
            $('#modelHeading').html('Renew History');
            $.ajaxModal("#branchModal", url);
        });

        $('#branchModal').on('click', '#add-branch-renew', function() {
            $.easyAjax({
                url: "<?php echo e(route('gym-admin.superadmin.saveBranchRenew')); ?>",
                container: '#followUpForm',
                type: "POST",
                data: $('#followUpForm').serialize(),
                success: function(response) {
                    if (response.status == 'success') {
                        $('#branchModal').modal('hide');
                        table._fnDraw();
                    }
                }
            })
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.gym-merchant.gymbasic', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/stage.hamrofitness.com/public_html/resources/views/gym-admin/super-admin/sms_index.blade.php ENDPATH**/ ?>