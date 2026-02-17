<?php $__env->startSection('CSS'); ?>
    <?php echo HTML::style('admin/global/plugins/ladda/ladda-themeless.min.css'); ?>

    <?php echo HTML::style('admin/global/plugins/datatables/datatables.min.css'); ?>

    <?php echo HTML::style('admin/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>

    <style>
        .table-scrollable .dataTable td .btn-group,
        .table-scrollable .dataTable th .btn-group {
            margin-top: -10px;
        }

        /* Child row toggle cell */
        td.details-control {
            text-align: center;
            cursor: pointer;
            width: 24px;
        }

        td.details-control i {
            font-size: 16px;
            color: #666;
        }

        tr.shown td.details-control i {
            color: #e7505a;
        }

        /* Subscription column styling for multi-line display */
        .subscription-cell {
            white-space: normal !important;
            word-wrap: break-word;
            word-break: break-word;
            max-width: 200px;
            line-height: 1.4;
            padding: 8px !important;
        }

        /* Ensure table cells can wrap content */
        .table td {
            vertical-align: top;
        }

        /* DataTable responsive column handling */
        .dataTables_wrapper .dataTables_scrollBody {
            overflow-x: auto;
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
                <span>Client Subscriptions</span>
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
                                <i class="fa <?php echo e($gymSettings->currency->symbol); ?> font-red"></i>
                                <span class="caption-subject font-red bold uppercase"><?php echo e($title); ?>

                                    Subscription</span>
                            </div>
                            <div class="pull-right">
                                <div class="btn-group">
                                    <?php if($title === 'active'): ?>
                                        <a href="<?php echo e(route('gym-admin.client-purchase.inactive')); ?>" class="btn btn-danger">
                                            Inactive
                                        </a>
                                    <?php else: ?>
                                        <a href="<?php echo e(route('gym-admin.client-purchase.active')); ?>" class="btn btn-success">
                                            Active
                                        </a>
                                    <?php endif; ?>
                                    <a href="<?php echo e(route('gym-admin.client-purchase.index')); ?>" class="btn btn-info">
                                        All Subscriptions
                                    </a>
                                    <a id="addTarget" href="<?php echo e(route('gym-admin.client-purchase.create')); ?>"
                                        class="btn sbold dark"> Add New
                                        <i class="fa fa-plus"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <table class="table table-striped table-bordered table-hover row-border order-column nowrap"
                                style="width: 100%" id="purchase_table">
                                <thead>
                                    <tr>
                                        <th class="desktop" style="width: 24px;"></th>
                                        <th class="desktop"> Client</th>
                                        <th class="desktop"> Subscription</th>
                                        <th class="desktop"> Remain Amt</th>
                                        <th class="desktop"> Next payment</th>
                                        <th class="desktop"> Expires On</th>
                                        <th class="desktop"> Action</th>
                                        <th class="desktop" style="display:none;"> Purchase Amt</th>
                                        <th class="desktop" style="display:none;"> Start Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $purchases; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $purchase): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td class="details-control"><i class="fa fa-plus-square-o"></i></td>
                                            <td class="subscription-cell"><a href="<?php echo e(route('gym-admin.client.show', $purchase['client_id'])); ?>"><?php echo e($purchase['client']['first_name']); ?>

                                                    <?php echo e($purchase['client']['middle_name']); ?>

                                                    <?php echo e($purchase['client']['last_name']); ?></a></td>
                                            <td class="subscription-cell"><?php echo e($purchase['membership']['title']); ?></td>
                                            <td>NPR <?php echo e($purchase['amount_to_be_paid'] - $purchase['paid_amount']); ?></td>
                                            <td>
                                                <?php if($purchase['amount_to_be_paid'] - $purchase['paid_amount'] > 0): ?>
                                                    <?php if(isset($purchase['next_payment_date'])): ?>
                                                        <?php echo e(date('M d, Y', strtotime($purchase['next_payment_date']))); ?>

                                                        <label class="label label-danger">Due</label>
                                                    <?php else: ?>
                                                        <label class="label label-warning">No Next Pay Date</label>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <label class="label label-success">Payment Complete</label>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if($purchase['status'] == 'extend'): ?>
                                                    <span class="label label-info">Extend</span>
                                                <?php endif; ?>
                                                <?php echo e(date('M d, Y', strtotime($purchase['expires_on']))); ?>

                                                <?php if(!is_null($purchase['expires_on']) && $purchase['expires_on'] > \Carbon\Carbon::today()): ?>
                                                    <label class="label label-success">
                                                        <?php echo e(\Carbon\Carbon::parse($purchase['expires_on'])->diffForHumans()); ?></label>
                                                <?php else: ?>
                                                    <label class="label label-danger">
                                                        <?php echo e(\Carbon\Carbon::parse($purchase['expires_on'])->diffForHumans()); ?></label>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if($purchase['status'] == 'freeze'): ?>
                                                    <div class="btn-group"><button class="btn red btn-xs" type="button">
                                                            <i class="fa fa-pause"></i>Freeze
                                                        </button></div>
                                                <?php else: ?>
                                                    <div class="btn-group"><button class="btn blue btn-xs dropdown-toggle"
                                                            type="button" data-toggle="dropdown"><i
                                                                class="fa fa-gears"></i> <span
                                                                class="hidden-xs hidden-medium">Actions</span>
                                                            <i class="fa fa-angle-down"></i></button>
                                                        <ul class="dropdown-menu pull-right" role="menu">

                                                            <?php if($purchase['amount_to_be_paid'] - $purchase['paid_amount'] > 0): ?>
                                                                <li><a
                                                                        href="<?php echo e(route('gym-admin.client-purchase.show', $purchase['id'])); ?>">
                                                                        <i class="fa fa-edit"></i>Edit</a>
                                                                </li>
                                                                <li><a href="javascript:;" data-id="<?php echo e($purchase['id']); ?>"
                                                                        class="remove-purchase"> <i
                                                                            class="fa fa-trash"></i>Remove </a>
                                                                </li>
                                                                <li> <a class="add-payment" data-id="<?php echo e($purchase['id']); ?>"
                                                                        href="javascript:;"><i class="fa fa-plus"></i> Add
                                                                        Payment </a>
                                                                </li>
                                                            <?php endif; ?>
                                                            <?php if(
                                                                $purchase['amount_to_be_paid'] - $purchase['paid_amount'] == 0 &&
                                                                    $purchase['membership']['duration_type'] != 'unlimited' &&
                                                                    $purchase['client']['status'] == 1): ?>
                                                                <li><a class="renew-subscription"
                                                                        data-id="<?php echo e($purchase['id']); ?>"
                                                                        href="javascript:;"><i class="icon-refresh"></i>
                                                                        Renew Subscription</a>
                                                                </li>
                                                            <?php endif; ?>
                                                            <?php if($purchase['membership']['duration_type'] != 'unlimited'): ?>
                                                                <li><a class="extend-subscription"
                                                                        data-id="<?php echo e($purchase['id']); ?>"
                                                                        href="javascript:;"><i class="fa fa-expand"></i>
                                                                        Extend Subscription</a>
                                                                </li>
                                                                <?php if($purchase['expires_on'] > now()): ?>
                                                                    <?php if($purchase['status'] == 'freeze_pending'): ?>
                                                                        <li>
                                                                            <a href=""><i
                                                                                    class="fa fa-pause"></i>Freeze On
                                                                                <?php echo e(isset($purchase['freeze_date']) && $purchase['freeze_date'] != null ? date('M d,Y', strtotime($purchase['freeze_date'])) : ''); ?></a>
                                                                        </li>
                                                                    <?php else: ?>
                                                                        <li><a class="freeze-subscription"
                                                                                data-id="<?php echo e($purchase['id']); ?>"
                                                                                href="javascript:;"><i
                                                                                    class="fa fa-pause"></i> Freeze
                                                                                Subscription</a>
                                                                        </li>
                                                                    <?php endif; ?>
                                                                <?php endif; ?>
                                                            <?php endif; ?>
                                                            <li><a class="show-subscription-reminder"
                                                                    data-id="<?php echo e($purchase['id']); ?>" href="javascript:;"><i
                                                                        class="fa fa-send"></i> Send Renew Reminder</a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                            <td style="display:none;">NPR <?php echo e($purchase['amount_to_be_paid']); ?></td>
                                            <td style="display:none;">
                                                <?php if($purchase['is_renew']): ?>
                                                    <span class="label label-info">Renew</span>
                                                <?php endif; ?>
                                                <?php echo e(date('M d, Y', strtotime($purchase['start_date']))); ?>

                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- END EXAMPLE TABLE PORTLET-->
                </div>
            </div>
        </div>
        <!-- END PAGE CONTENT INNER -->
    </div>

    

    <div class="modal fade bs-modal-md in" id="reminderModal" role="dialog" aria-labelledby="myModalLabel"
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

    <?php echo HTML::script('admin/global/plugins/ladda/spin.min.js'); ?>

    <?php echo HTML::script('admin/global/plugins/ladda/ladda.min.js'); ?>

    <?php echo HTML::script('admin/pages/scripts/ui-buttons.min.js'); ?>


    <script>
        var table = $('#purchase_table').DataTable({
            scrollX: false,
            columnDefs: [{
                    targets: [0, 6],
                    orderable: false,
                    searchable: false
                },
                {
                    width: "25%",
                    targets: 2
                }, // Subscription column width
                {
                    width: "15%",
                    targets: 1
                }, // Client column width
                {
                    width: "12%",
                    targets: [3, 4]
                }, // Amount columns width
                {
                    width: "15%",
                    targets: 5
                }, // Expires On column width
                {
                    width: "10%",
                    targets: 6
                }, // Action column width
            ],
            pageLength: 25,
            lengthMenu: [
                [25, 50, 75, 100, -1],
                ['25', '50', '75', '100', 'All']
            ],
            autoWidth: false
        });

        function format(row) {
            var data = row.data();
            var subscription = data[2] || '-';
            var remainingAmt = data[3] || '-';
            var nextPayment = data[4] || '-';
            var expiryDate = data[5] || '-';
            var purchaseAmt = data[7] || '-';
            var startDate = data[8] || '-';

            return '<div class="row">' +
                '<div class="col-md-12">' +
                '<table class="table table-condensed">' +
                '<tr><td><strong>Subscription</strong></td><td>' + subscription + '</td></tr>' +
                '<tr><td><strong>Purchase Amt</strong></td><td>' + purchaseAmt + '</td></tr>' +
                '<tr><td><strong>Remaining Amt</strong></td><td>' + remainingAmt + '</td></tr>' +
                '<tr><td><strong>Start Date</strong></td><td>' + startDate + '</td></tr>' +
                '<tr><td><strong>Next Payment</strong></td><td>' + nextPayment + '</td></tr>' +
                '<tr><td><strong>Expires On</strong></td><td>' + expiryDate + '</td></tr>' +
                '</table>' +
                '</div>' +
                '</div>';
        }

        $('#purchase_table tbody').on('click', 'td.details-control', function() {
            var tr = $(this).closest('tr');
            var row = table.row(tr);
            var icon = $(this).find('i');

            if (row.child.isShown()) {
                row.child.hide();
                tr.removeClass('shown');
                icon.removeClass('fa-minus-square-o').addClass('fa-plus-square-o');
            } else {
                row.child(format(row)).show();
                tr.addClass('shown');
                icon.removeClass('fa-plus-square-o').addClass('fa-minus-square-o');
            }
        });

        $('#purchase_table').on('click', '.remove-purchase', function() {
            var id = $(this).data('id');
            bootbox.confirm({
                message: "Do you want to delete this purchase?",
                buttons: {
                    confirm: {
                        label: "Yes",
                        className: "btn-primary"
                    }
                },
                callback: function(result) {
                    if (result) {

                        var url = "<?php echo e(route('gym-admin.client-purchase.destroy', ':id')); ?>";
                        url = url.replace(':id', id);

                        $.easyAjax({
                            url: url,
                            type: "DELETE",
                            data: {
                                id: id,
                                _token: '<?php echo e(csrf_token()); ?>'
                            },
                            success: function() {
                                location.reload();
                            }
                        });
                    } else {
                        console.log('cancel');
                    }
                }
            })
        });

        $('#purchase_table').on('click', '.add-payment', function() {
            var id = $(this).data('id');
            var show_url = "<?php echo e(route('gym-admin.membership-payments.add-payment-modal', ['#id'])); ?>";
            var url = show_url.replace('#id', id);
            $('#modelHeading').html('Add Payment');
            $.ajaxModal("#reminderModal", url);
        });

        $('#purchase_table').on('click', '.renew-subscription', function() {
            var id = $(this).data('id');
            var show_url = "<?php echo e(route('gym-admin.client-purchase.renew-subscription-modal', ['#id'])); ?>";
            var url = show_url.replace('#id', id);
            $('#modelHeading').html('Renew Subscription');
            $.ajaxModal("#reminderModal", url);
        });

        //send subscription reminder
        $('#purchase_table').on('click', '.show-subscription-reminder', function() {
            var id = $(this).data('id');
            var show_url = "<?php echo e(route('gym-admin.client-purchase.show-subscription-reminder-modal', ['#id'])); ?>";
            var url = show_url.replace('#id', id);
            $('#modelHeading').html('Send Renew Reminder');
            $.ajaxModal("#reminderModal", url);
        });

        $('#purchase_table').on('click', '.extend-subscription', function() {
            var id = $(this).data('id');
            var show_url = "<?php echo e(route('gym-admin.client-purchase.extend-subscription-modal', ['#id'])); ?>";
            var url = show_url.replace('#id', id);
            $('#modelHeading').html('Extend Subscription');
            $.ajaxModal("#reminderModal", url);
        });

        $('#purchase_table').on('click', '.freeze-subscription', function() {
            var id = $(this).data('id');
            var show_url = "<?php echo e(route('gym-admin.client-purchase.freeze-subscription-modal', ['#id'])); ?>";
            var url = show_url.replace('#id', id);
            $('#modelHeading').html('Freeze Subscription');
            $.ajaxModal("#reminderModal", url);
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.gym-merchant.gymbasic', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/stage.hamrofitness.com/public_html/resources/views/gym-admin/purchase/inactive.blade.php ENDPATH**/ ?>