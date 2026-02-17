<?php $__env->startSection('CSS'); ?>
    <?php echo HTML::style('admin/global/plugins/ladda/ladda-themeless.min.css'); ?>

    <?php echo HTML::style('admin/global/plugins/datatables/datatables.min.css'); ?>

    <?php echo HTML::style('admin/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>

    <style>
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
                                <span class="caption-subject font-red bold uppercase"> Subscriptions</span>
                            </div>
                            <div class="pull-right">
                                <div class="btn-group">
                                    <a href="<?php echo e(route('gym-admin.client-purchase.active')); ?>" class="btn btn-success">
                                        Active
                                    </a>
                                    <a href="<?php echo e(route('gym-admin.client-purchase.inactive')); ?>" class="btn btn-info">
                                        Inactive
                                    </a>
                                    <a class="btn btn-warning add-pending-btn-gap" href="<?php echo e(route('gym-admin.client-purchase.pending-subscription')); ?>">
                                        Pending (<?php echo e($pendingCount); ?>)</a>
                                    <a class="btn btn-danger add-pending-btn-gap" href="<?php echo e(route('gym-admin.client-purchase.deleted-subscription')); ?>">
                                        Deleted (<?php echo e($deletedCount); ?>)</a>
                                    <a id="addTarget" href="<?php echo e(route('gym-admin.client-purchase.create')); ?>" class="btn sbold dark"> Add New
                                        <i class="fa fa-plus"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <table class="table table-striped table-bordered table-hover row-border order-column nowrap" style="width: 100%"
                                   id="purchase_table">
                                <thead>
                                <tr>
                                    <th class="desktop" style="width: 24px;"></th>
                                    <th class="desktop"> Client</th>
                                    <th class="desktop"> Subscription</th>
                                    <th class="desktop"> Remain Amt</th>
                                    <th class="desktop"> Next Payment</th>
                                    <th class="desktop"> Expires On</th>
                                    <th class="desktop"> Action</th>
                                    <th class="desktop" style="display:none;"> Username</th>
                                    <th class="desktop" style="display:none;"> Purchase Amt</th>
                                    <th class="desktop" style="display:none;"> Start Date</th>
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

    <?php echo HTML::script('admin/global/plugins/ladda/spin.min.js'); ?>

    <?php echo HTML::script('admin/global/plugins/ladda/ladda.min.js'); ?>

    <?php echo HTML::script('admin/pages/scripts/ui-buttons.min.js'); ?>


    <script>
        var clientTable = $('#purchase_table');
        var table = clientTable.dataTable({
            processing: true,
            serverSide: true,
            ajax: "<?php echo e(route('gym-admin.client-purchase.ajax-create',['type'=>'all'])); ?>",
            columns: [
                {className: 'details-control', orderable: false, data: null, defaultContent: '<i class="fa fa-plus-square-o"></i>', searchable: false},
                {
                    data: 'gym_clients.first_name', name: 'gym_clients.first_name',
                    render: function(data, type, row) {
                        if (type === 'display' && data) {
                            return '<span class="subscription-cell">' + data + '</span>';
                        }
                        return data;
                    }
                },
                {
                    data: 'membership', name: 'membership',
                    render: function(data, type, row) {
                        if (type === 'display' && data) {
                            return '<span class="subscription-cell">' + data + '</span>';
                        }
                        return data;
                    }
                },
                {data: 'paid_amount', name: 'paid_amount'},
                {data: 'next_payment_date', name: 'next_payment_date'},
                {data: 'expires_on', name: 'expires_on'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
                {data: 'gym_clients.username', name: 'gym_clients.username', visible: false},
                {data: 'amount_to_be_paid', name: 'amount_to_be_paid', visible: false},
                {data: 'start_date', name: 'start_date', visible: false},
            ],
            order: [[5, 'desc']],
            scrollX: false,
            columnDefs: [
                { width: "25%", targets: 2 }, // Subscription column width
                { width: "15%", targets: 1 }, // Client column width
                { width: "12%", targets: [3, 4] }, // Amount columns width
                { width: "15%", targets: 5 }, // Expires On column width
                { width: "10%", targets: 6 }, // Action column width
            ],
            pageLength: 25,
            lengthMenu: [
                [25, 50, 75 , 100, -1],
                ['25', '50','75' ,'100', 'All']
            ],
            autoWidth: false
        });

        // Format child row content
        function format(row) {
            var data = row.data();
            var username = data['gym_clients.username'] || '';
            var membership = data.membership || '';
            var purchaseAmt = data.amount_to_be_paid || 0;
            var remainingAmt = data.paid_amount || 0;
            var startDate = data.start_date || '-';
            var nextPayment = data.next_payment_date || '-';
            var expiryDate = data.expires_on || '-';

            return '<div class="row">'
                + '<div class="col-md-12">'
                + '<table class="table table-condensed">'
                + '<tr><td><strong>Subscription</strong></td><td>' + membership + '</td></tr>'
                + '<tr><td><strong>Purchase Amt</strong></td><td>' + purchaseAmt + '</td></tr>'
                + '<tr><td><strong>Remaining Amt</strong></td><td>' + remainingAmt + '</td></tr>'
                + '<tr><td><strong>Start Date</strong></td><td>' + startDate + '</td></tr>'
                + '<tr><td><strong>Next Payment</strong></td><td>' + nextPayment + '</td></tr>'
                + '<tr><td><strong>Expires On</strong></td><td>' + expiryDate + '</td></tr>'
                + '</table>'
                + '</div>'
                + '</div>';
        }

        // Toggle child details on cell click
        $('#purchase_table tbody').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = table.api().row(tr);
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

        $('#purchase_table').on('click', '.remove-purchase', function () {
            var id = $(this).data('id');
            bootbox.confirm({
                message: "Do you want to delete this purchase?",
                buttons: {
                    confirm: {
                        label: "Yes",
                        className: "btn-primary"
                    }
                },
                callback: function (result) {
                    if (result) {

                        var url = "<?php echo e(route('gym-admin.client-purchase.destroy',':id')); ?>";
                        url = url.replace(':id', id);

                        $.easyAjax({
                            url: url,
                            type: "DELETE",
                            data: {id: id, _token: '<?php echo e(csrf_token()); ?>'},
                            success: function () {
                                location.reload();
                            }
                        });
                    }
                    else {
                        console.log('cancel');
                    }
                }
            })
        });

        $('#purchase_table').on('click', '.add-payment', function () {
            var id = $(this).data('id');
            var show_url = "<?php echo e(route('gym-admin.membership-payments.add-payment-modal',['#id'])); ?>";
            var url = show_url.replace('#id', id);
            $('#modelHeading').html('Add Payment');
            $.ajaxModal("#reminderModal", url);
        });

        $('#purchase_table').on('click', '.renew-subscription', function () {
            var id = $(this).data('id');
            var show_url = "<?php echo e(route('gym-admin.client-purchase.renew-subscription-modal',['#id'])); ?>";
            var url = show_url.replace('#id', id);
            $('#modelHeading').html('Renew Subscription');
            $.ajaxModal("#reminderModal", url);
        });

        //send subscription reminder
        $('#purchase_table').on('click', '.show-subscription-reminder', function () {
            var id = $(this).data('id');
            var show_url = "<?php echo e(route('gym-admin.client-purchase.show-subscription-reminder-modal',['#id'])); ?>";
            var url = show_url.replace('#id', id);
            $('#modelHeading').html('Send Renew Reminder');
            $.ajaxModal("#reminderModal", url);
        });

        $('#purchase_table').on('click', '.extend-subscription', function () {
            var id = $(this).data('id');
            var show_url = "<?php echo e(route('gym-admin.client-purchase.extend-subscription-modal',['#id'])); ?>";
            var url = show_url.replace('#id', id);
            $('#modelHeading').html('Extend Subscription');
            $.ajaxModal("#reminderModal", url);
        });

        $('#purchase_table').on('click', '.freeze-subscription', function () {
            var id = $(this).data('id');
            var show_url = "<?php echo e(route('gym-admin.client-purchase.freeze-subscription-modal',['#id'])); ?>";
            var url = show_url.replace('#id', id);
            $('#modelHeading').html('Freeze Subscription');
            $.ajaxModal("#reminderModal", url);
        });

    </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.gym-merchant.gymbasic', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/stage.hamrofitness.com/public_html/resources/views/gym-admin/purchase/index.blade.php ENDPATH**/ ?>