<?php $__env->startSection('CSS'); ?>
    <style>
        .CSSAnimationChart, .mapChart {
            height: 339px;
        }
        .task-reminder{
            padding: 5px;
            margin-bottom: 10px;
        }
        .task-reminder p{
            line-height: 0;
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
                    <span>Dashboard</span>
                </li>
            </ul>
            <!-- END PAGE BREADCRUMBS -->

            <?php if($user->can('view_dashboard')): ?>

                <?php echo $__env->make('gym-admin.dashboard.task_reminder', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                <!-- BEGIN PAGE CONTENT INNER -->
                <div class="page-content-inner">

                    <div class="widget-row">
                        <div class="row">
                            <div class="col-md-3">
                                <!-- BEGIN WIDGET THUMB -->
                                <div class="dashboard-stat blue">
                                    <div class="visual">
                                        <i class="fa fa-users"></i>
                                    </div>
                                    <div class="details">
                                        <div class="number" data-counter="counterup" data-value="<?php echo e($totalCustomers); ?>"> 0</div>
                                        <div class="desc"> Total Customers</div>
                                    </div>
                                </div>
                                <!-- END WIDGET THUMB -->
                            </div>
                            <div class="col-md-3">
                                <!-- BEGIN WIDGET THUMB -->
                                <div class="dashboard-stat purple-soft">
                                    <div class="visual">
                                        <i class="fa fa-users"></i>
                                    </div>
                                    <div class="details">
                                        <div class="number" data-counter="counterup" data-value="<?php echo e($monthlyCustomers); ?>"> 0</div>
                                        <div class="desc"> Customers This Month</div>
                                    </div>
                                </div>
                                <!-- END WIDGET THUMB -->
                            </div>
                            <div class="col-md-3">
                                <!-- BEGIN WIDGET THUMB -->
                                <div class="dashboard-stat grey-mint">
                                    <div class="visual">
                                        <i class="fa fa-users"></i>
                                    </div>
                                    <div class="details">
                                        <div class="number" data-counter="counterup" data-value="<?php echo e($monthlyVisitors); ?>"> 0</div>
                                        <div class="desc"> Visitors This Month</div>
                                    </div>
                                </div>
                                <!-- END WIDGET THUMB -->
                            </div>
                            <div class="col-md-3">
                                <!-- BEGIN WIDGET THUMB -->
                                <div class="dashboard-stat green-soft">
                                    <div class="visual">
                                        <i class="fa fa-check"></i>
                                    </div>
                                    <div class="details">
                                        <div class="number" data-counter="counterup" data-value="<?php echo e($todayAttendance); ?>"> 0</div>
                                        <div class="desc"> Today&apos;s Check Ins</div>
                                    </div>
                                </div>
                                <!-- END WIDGET THUMB -->
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <!-- BEGIN WIDGET THUMB -->
                                <div class="dashboard-stat green">
                                    <div class="visual">
                                        <i class="fa fa-user-plus"></i>
                                    </div>
                                    <div class="details">
                                        <div class="number" data-counter="counterup" data-value="<?php echo e($totalActiveCustomers); ?>"> 0</div>
                                        <div class="desc"> Total Active Customers</div>
                                    </div>
                                </div>
                                <!-- END WIDGET THUMB -->
                            </div>
                            <div class="col-md-3">
                                <!-- BEGIN WIDGET THUMB -->
                                <div class="dashboard-stat red">
                                    <div class="visual">
                                        <i class="fa fa-user-times"></i>
                                    </div>
                                    <div class="details">
                                        <div class="number" data-counter="counterup" data-value="<?php echo e($totalInactiveCustomers); ?>"> 0</div>
                                        <div class="desc"> Total Inactive Customers</div>
                                    </div>
                                </div>
                                <!-- END WIDGET THUMB -->
                            </div>
                            <div class="col-md-3">
                                <!-- BEGIN WIDGET THUMB -->
                                <div class="dashboard-stat yellow-gold">
                                    <div class="visual">
                                        <i class="fa fa-lock"></i>
                                    </div>
                                    <div class="details">
                                        <div class="number" data-counter="counterup" data-value="<?php echo e($totalLockerAvailable); ?>"> 0</div>
                                        <div class="desc"> Total Locker Available</div>
                                    </div>
                                </div>
                                <!-- END WIDGET THUMB -->
                            </div>
                            <div class="col-md-3">
                                <!-- BEGIN WIDGET THUMB -->
                                <div class="dashboard-stat blue-dark">
                                    <div class="visual">
                                        <i class="fa fa-file-o"></i>
                                    </div>
                                    <div class="details">
                                        <div class="number" data-counter="counterup" data-value="<?php echo e($totalSMSCredit); ?>"> 0</div>
                                        <div class="desc"> Sms Credit</div>
                                    </div>
                                </div>
                                <!-- END WIDGET THUMB -->
                            </div>
                        </div>

                        <div class="row">
                            <?php if($user->can('show_total_earning')): ?>
                            <div class="col-md-3">
                                <!-- BEGIN WIDGET THUMB -->
                                <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 ">
                                    <h4 class="widget-thumb-heading">Total Earning</h4>
                                    <div class="widget-thumb-wrap">
                                        <i class="widget-thumb-icon bg-blue fa <?php echo e($gymSettings->currency->symbol); ?>"></i>
                                        <div class="widget-thumb-body">
                                            <span class="widget-thumb-subtitle"><?php echo e($gymSettings->currency->acronym); ?></span>
                                            <span class="widget-thumb-body-stat" data-counter="counterup" data-value="<?php echo e($currentBalance); ?>">0</span>
                                        </div>
                                    </div>
                                </div>
                                <!-- END WIDGET THUMB -->
                            </div>
                            <?php endif; ?>
                            <?php if($user->can('show_purchase_earning')): ?>
                            <div class="col-md-3">
                                <!-- BEGIN WIDGET THUMB -->
                                <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 ">
                                    <h4 class="widget-thumb-heading">Daily Earning</h4>
                                    <div class="widget-thumb-wrap">
                                        <i class="widget-thumb-icon bg-grey-mint fa <?php echo e($gymSettings->currency->symbol); ?>"></i>
                                        <div class="widget-thumb-body">
                                            <span class="widget-thumb-subtitle"><?php echo e($gymSettings->currency->acronym); ?></span>
                                            <span class="widget-thumb-body-stat" data-counter="counterup" data-value="<?php echo e($dailyEarn); ?>">0</span>
                                        </div>
                                    </div>
                                </div>
                                <!-- END WIDGET THUMB -->
                            </div>
                            <?php endif; ?>
                            <?php if($user->can('show_weekly_earning')): ?>
                            <div class="col-md-3">
                                <!-- BEGIN WIDGET THUMB -->
                                <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 ">
                                    <h4 class="widget-thumb-heading">Weekly Earning</h4>
                                    <div class="widget-thumb-wrap">
                                        <i class="widget-thumb-icon bg-purple-soft fa <?php echo e($gymSettings->currency->symbol); ?>"></i>
                                        <div class="widget-thumb-body">
                                            <span class="widget-thumb-subtitle"><?php echo e($gymSettings->currency->acronym); ?></span>
                                            <span class="widget-thumb-body-stat" data-counter="counterup" data-value="<?php echo e($weeklySales); ?>">0</span>
                                        </div>
                                    </div>
                                </div>
                                <!-- END WIDGET THUMB -->
                            </div>
                            <?php endif; ?>

                            <?php if($user->can('show_monthly_earning')): ?>
                            <div class="col-md-3">
                                <!-- BEGIN WIDGET THUMB -->
                                <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 ">
                                    <h4 class="widget-thumb-heading">Monthly Earning</h4>
                                    <div class="widget-thumb-wrap">
                                        <i class="widget-thumb-icon bg-green-soft fa <?php echo e($gymSettings->currency->symbol); ?>"></i>
                                        <div class="widget-thumb-body">
                                            <span class="widget-thumb-subtitle"><?php echo e($gymSettings->currency->acronym); ?></span>
                                            <span class="widget-thumb-body-stat" data-counter="counterup" data-value="<?php echo e($averageMonthly); ?>">0</span>
                                        </div>
                                    </div>
                                </div>
                                <!-- END WIDGET THUMB -->
                            </div>
                            <?php endif; ?>
                        </div>

                        <div class="row">
                            <?php if($user->can('show_total_expenses')): ?>
                            <div class="col-md-3">
                                <!-- BEGIN WIDGET THUMB -->
                                <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 ">
                                    <h4 class="widget-thumb-heading">Total Expenses</h4>
                                    <div class="widget-thumb-wrap">
                                        <i class="widget-thumb-icon bg-red fa <?php echo e($gymSettings->currency->symbol); ?>"></i>
                                        <div class="widget-thumb-body">
                                            <span class="widget-thumb-subtitle"><?php echo e($gymSettings->currency->acronym); ?></span>
                                            <span class="widget-thumb-body-stat" data-counter="counterup" data-value="<?php echo e($totalExpenses); ?>">0</span>
                                        </div>
                                    </div>
                                </div>
                                <!-- END WIDGET THUMB -->
                            </div>
                            <?php endif; ?>
                            <?php if($user->can('show_purchase_expenses')): ?>
                            <div class="col-md-3">
                                <!-- BEGIN WIDGET THUMB -->
                                <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 ">
                                    <h4 class="widget-thumb-heading">Daily Expenses</h4>
                                    <div class="widget-thumb-wrap">
                                        <i class="widget-thumb-icon bg-green fa <?php echo e($gymSettings->currency->symbol); ?>"></i>
                                        <div class="widget-thumb-body">
                                            <span class="widget-thumb-subtitle"><?php echo e($gymSettings->currency->acronym); ?></span>
                                            <span class="widget-thumb-body-stat" data-counter="counterup" data-value="<?php echo e($dailyExpenses); ?>">0</span>
                                        </div>
                                    </div>
                                </div>
                                <!-- END WIDGET THUMB -->
                            </div>
                            <?php endif; ?>
                            <?php if($user->can('show_weekly_expenses')): ?>
                            <div class="col-md-3">
                                <!-- BEGIN WIDGET THUMB -->
                                <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 ">
                                    <h4 class="widget-thumb-heading">Weekly Expenses</h4>
                                    <div class="widget-thumb-wrap">
                                        <i class="widget-thumb-icon bg-yellow fa <?php echo e($gymSettings->currency->symbol); ?>"></i>
                                        <div class="widget-thumb-body">
                                            <span class="widget-thumb-subtitle"><?php echo e($gymSettings->currency->acronym); ?></span>
                                            <span class="widget-thumb-body-stat" data-counter="counterup" data-value="<?php echo e($weeklyExpenses); ?>">0</span>
                                        </div>
                                    </div>
                                </div>
                                <!-- END WIDGET THUMB -->
                            </div>
                            <?php endif; ?>

                            <?php if($user->can('show_monthly_expenses')): ?>
                            <div class="col-md-3">
                                <!-- BEGIN WIDGET THUMB -->
                                <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 ">
                                    <h4 class="widget-thumb-heading">Monthly Expenses</h4>
                                    <div class="widget-thumb-wrap">
                                        <i class="widget-thumb-icon bg-primary fa <?php echo e($gymSettings->currency->symbol); ?>"></i>
                                        <div class="widget-thumb-body">
                                            <span class="widget-thumb-subtitle"><?php echo e($gymSettings->currency->acronym); ?></span>
                                            <span class="widget-thumb-body-stat" data-counter="counterup" data-value="<?php echo e($monthlyExpenses); ?>">0</span>
                                        </div>
                                    </div>
                                </div>
                                <!-- END WIDGET THUMB -->
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php if($user->can('dashboard_subscription_expire')): ?>
                        <!-- Subscription Expire and Due -->
                        <div class="row">
                            <div class="col-md-6 col-sm-12">
                                <div class="portlet light ">
                                    <div class="portlet-title">
                                        <div class="caption ">
                                            <span class="caption-subject font-blue bold uppercase pull-left">Subscriptions Expiring in next <?php echo e($expireSubscriptionDays); ?> days</span>
                                            <span class="caption-helper"></span>
                                        </div>
                                        <div class="pull-right">
                                            <div class="btn-group">
                                                <a href="<?php echo e(route('gym-admin.client-purchase.index')); ?>" class="btn btn-sm btn-success pull-right">View More</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="portlet-body flip-scroll" style="overflow-x: auto">
                                        <table class="table table-bordered table-striped table-condensed flip-content" style="width:auto">
                                            <thead class="flip-content">
                                            <tr class="uppercase">
                                                <th> Client Name</th>
                                                <th> Subscription</th>
                                                <th> Expiring on</th>
                                                <th> Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>

                                            <?php $__empty_1 = true; $__currentLoopData = $expiringSubscriptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$expSubs): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                <tr>
                                                    <td> <?php echo e($expSubs->client->fullName); ?> </td>
                                                    <td> <strong> <?php echo e($expSubs->membership->title); ?></strong><br>
                                                        Remain <strong><?php echo e($gymSettings->currency->acronym); ?> <?php echo e(($expSubs->amount_to_be_paid - $expSubs->paid_amount)); ?></strong>
                                                    </td>
                                                    <td><?php echo e(date('M d, Y',strtotime($expSubs->expires_on))); ?></td>
                                                    <td>
                                                        <div class="btn-group hidden-xs">
                                                            <button class="btn blue btn-xs dropdown-toggle" type="button" data-toggle="dropdown"><i
                                                                        class="fa fa-gears"></i> <span class="hidden-xs"></span>
                                                                <i class="fa fa-angle-down"></i>
                                                            </button>
                                                            <ul class="dropdown-menu pull-right" role="menu">
                                                                <li>
                                                                    <a href="javascript:;" data-id="<?php echo e($expSubs->id); ?>" class="show-subscription-reminder"><i
                                                                                class="fa fa-send"></i> Send Reminder</a>
                                                                </li>
                                                                <?php if(($expSubs->amount_to_be_paid - $expSubs->paid_amount) == 0): ?>
                                                                <li>
                                                                    <a class="renew-subscription" data-id="<?php echo e($expSubs->id); ?>" href="javascript:;"><i
                                                                                class="icon-refresh"></i> Renew Subscription</a>
                                                                </li>
                                                                <?php endif; ?>
                                                            </ul>
                                                        </div>

                                                    </td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                <tr>
                                                    <td colspan="4">No subscription expiring.</td>
                                                </tr>
                                            <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <div class="portlet light ">
                                    <div class="portlet-title">
                                        <div class="caption ">
                                            <span class="caption-subject font-blue bold uppercase">Membership Due Payments</span>
                                            <span class="caption-helper"></span>
                                        </div>
                                        <div class="pull-right">
                                            <div class="btn-group">
                                                <a href="<?php echo e(route('gym-admin.client-purchase.client-dues')); ?>" class="btn btn-sm btn-success pull-right">View More</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="portlet-body flip-scroll" style="overflow-x: auto">

                                        <table class="table table-bordered table-striped table-condensed flip-content" style="width:auto">
                                            <thead class="flip-content">
                                            <tr class="uppercase">
                                                <th> Name</th>
                                                <th> Subscription</th>
                                                <th> Due Amount</th>
                                                <th> Due Date</th>
                                                <th> Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>

                                            <?php $__empty_1 = true; $__currentLoopData = $duePayments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                <tr>
                                                    <td><?php echo e($payment->client->fullName); ?> </td>
                                                    <td><strong><?php echo e($payment->membership->title); ?> </strong></td>
                                                    <td>
                                                       <strong><?php echo e($gymSettings->currency->acronym); ?> <?php echo e($payment->amount_to_be_paid - $payment->paid_amount); ?></strong>
                                                    </td>
                                                    <td><?php if( $payment->next_payment_date): ?> <?php echo e($payment->next_payment_date->toFormattedDateString()); ?> <?php endif; ?></td>
                                                    <td>
                                                        <div class="btn-group hidden-xs">
                                                            <button class="btn blue btn-xs dropdown-toggle" type="button" data-toggle="dropdown"><i
                                                                    class="fa fa-gears"></i> <span class="hidden-xs"></span>
                                                                <i class="fa fa-angle-down"></i>
                                                            </button>
                                                            <ul class="dropdown-menu pull-right" role="menu">
                                                                <li>
                                                                    <a href="javascript:;" data-id="<?php echo e($payment->id); ?>" class="show-subscription-reminder"><i
                                                                            class="fa fa-send"></i> Send Reminder</a>
                                                                </li>
                                                                <li>
                                                                    <a class="add-payment" data-id="<?php echo e($payment->id); ?>" href="javascript:;"><i
                                                                            class="icon-plus"></i> Add Payment</a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                <tr>
                                                    <td colspan="5">No due payments.</td>
                                                </tr>
                                            <?php endif; ?>
                                            </tbody>
                                        </table>

                                    </div>
                                </div>
                            </div>
                        </div>

                         <!-- Locker Expire and Due -->
                        <div class="row">
                            <div class="col-md-6 col-sm-12">
                                <div class="portlet light ">
                                    <div class="portlet-title">
                                        <div class="caption ">
                                            <span class="caption-subject font-blue bold uppercase">Locker Expiring in next <?php echo e($expireSubscriptionDays); ?> days</span>
                                            <span class="caption-helper"></span>
                                        </div>
                                        <div class="pull-right">
                                            <div class="btn-group">
                                                <a href="<?php echo e(route('gym-admin.reservations.index')); ?>" class="btn btn-sm btn-success pull-right">View More</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="portlet-body flip-scroll" style="overflow-x: auto">

                                        <table class="table table-bordered table-striped table-condensed flip-content" style="width:auto">
                                            <thead class="flip-content">
                                            <tr class="uppercase">
                                                <th> Client Name</th>
                                                <th> Locker</th>
                                                <th> Expiring On</th>
                                                <th> Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>

                                            <?php $__empty_1 = true; $__currentLoopData = $expiringLockerSubscriptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$expire): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                <tr>
                                                    <td><?php echo e($expire->client->fullName); ?> </td>
                                                    <td><strong><?php echo e($expire->locker?->locker_num); ?> </strong>
                                                        <strong><?php echo e($gymSettings->currency->acronym); ?> <?php echo e($expire->amount_to_be_paid - $expire->paid_amount); ?> </strong>
                                                    </td>
                                                    <td><?php if( $expire->end_date): ?> <?php echo e($expire->end_date->toFormattedDateString()); ?> <?php endif; ?></td>
                                                    <td>
                                                        <div class="btn-group hidden-xs">
                                                            <button class="btn blue btn-xs dropdown-toggle" type="button" data-toggle="dropdown"><i
                                                                        class="fa fa-gears"></i> <span class="hidden-xs"></span>
                                                                <i class="fa fa-angle-down"></i>
                                                            </button>
                                                            <ul class="dropdown-menu pull-right" role="menu">
                                                                <li>
                                                                    <a href="javascript:;" data-id="<?php echo e($expire->id); ?>" class="show-locker-subscription-reminder"><i
                                                                                class="fa fa-send"></i> Send Reminder</a>
                                                                </li>
                                                                <?php if(($expire->amount_to_be_paid - $expire->paid_amount) == 0): ?>
                                                                <li>
                                                                    <a class="renew-reservation" data-id="<?php echo e($expire->uuid); ?>" href="javascript:;"><i
                                                                                class="icon-refresh"></i> Renew Reservation</a>
                                                                </li>
                                                                <?php endif; ?>
                                                            </ul>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                <tr>
                                                    <td colspan="4">No locker expiring.</td>
                                                </tr>
                                            <?php endif; ?>
                                            </tbody>
                                        </table>

                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="portlet light ">
                                    <div class="portlet-title">
                                        <div class="caption ">
                                            <span class="caption-subject font-blue bold uppercase">Locker Due Payment</span>
                                            <span class="caption-helper"></span>
                                        </div>
                                        <div class="pull-right">
                                            <div class="btn-group">
                                                <a href="<?php echo e(route('gym-admin.reservations.dues')); ?>" class="btn btn-sm btn-success pull-right">View More</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="portlet-body flip-scroll" style="overflow-x: auto">

                                        <table class="table table-bordered table-striped table-condensed flip-content" style="width:auto">
                                            <thead class="flip-content">
                                            <tr class="uppercase">
                                                <th> Client Name</th>
                                                <th> Locker</th>
                                                <th> Due Amount</th>
                                                <th> Due Date</th>
                                                <th> Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php $__empty_1 = true; $__currentLoopData = $dueLockerPayments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$due): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                <tr>
                                                    <td><?php echo e(ucwords($due->client->fullName)); ?> </td>
                                                    <td><strong><?php echo e($due->locker?->locker_num); ?> </strong></td>
                                                    <td>
                                                        <?php echo e($gymSettings->currency->acronym); ?> <?php echo e($due->amount_to_be_paid - $due->paid_amount); ?> </strong>
                                                    </td>
                                                    <td><?php if( $due->next_payment_date): ?> <?php echo e($due->next_payment_date->toFormattedDateString()); ?> <?php endif; ?></td>
                                                    <td>
                                                        <div class="btn-group hidden-xs">
                                                            <button class="btn blue btn-xs dropdown-toggle" type="button" data-toggle="dropdown"><i
                                                                    class="fa fa-gears"></i> <span class="hidden-xs"></span>
                                                                <i class="fa fa-angle-down"></i>
                                                            </button>
                                                            <ul class="dropdown-menu pull-right" role="menu">
                                                                <li>
                                                                    <a href="javascript:;" data-id="<?php echo e($due->id); ?>" class="show-locker-reminder"><i
                                                                            class="fa fa-send"></i> Send Reminder</a>
                                                                </li>
                                                                <li>
                                                                    <a class="add-locker-payment" data-id="<?php echo e($due->uuid); ?>" href="javascript:;"><i
                                                                            class="icon-plus"></i> Add Payment</a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                <tr>
                                                    <td colspan="5">No locker due.</td>
                                                </tr>
                                            <?php endif; ?>
                                            </tbody>
                                        </table>

                                    </div>
                                </div>
                            </div>
                        </div>

                         <!-- Product Expire and Due -->
                         <div class="row">
                            <div class="col-md-6 col-sm-12">
                              <div class="portlet light ">
                                  <div class="portlet-title">
                                      <div class="caption ">
                                          <span class="caption-subject font-blue bold uppercase">Product Expiring in next <?php echo e($expireProductDays); ?> days</span>
                                          <span class="caption-helper"></span>
                                      </div>
                                      <div class="pull-right">
                                          <div class="btn-group">
                                              <a href="<?php echo e(route('gym-admin.products.index')); ?>" class="btn btn-sm btn-success pull-right">View More</a>
                                          </div>
                                      </div>
                                  </div>
                                  <div class="portlet-body flip-scroll">

                                      <table class="table table-bordered table-striped table-condensed flip-content">
                                          <thead class="flip-content">
                                          <tr class="uppercase">
                                              <th> Product Name</th>
                                              <th> Expiring on</th>
                                          </tr>
                                          </thead>
                                          <tbody>

                                          <?php $__empty_1 = true; $__currentLoopData = $expiringProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$expPros): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                              <tr>
                                                  <td><?php echo e(ucwords($expPros->name ?? '')); ?> </td>
                                                  <td>
                                                      <?php echo e(date('M d, Y',strtotime($expPros->expire_date))); ?>

                                                  </td>
                                              </tr>
                                          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                              <tr>
                                                  <td colspan="2" class="text-center">No product expiring.</td>
                                              </tr>
                                          <?php endif; ?>
                                          </tbody>
                                      </table>

                                  </div>
                              </div>
                          </div>
                          <div class="col-md-6 col-sm-12">
                              <div class="portlet light ">
                                  <div class="portlet-title">
                                      <div class="caption ">
                                          <span class="caption-subject font-blue bold uppercase">Product Due Payment</span>
                                          <span class="caption-helper"></span>
                                      </div>
                                      <div class="pull-right">
                                          <div class="btn-group">
                                              <a href="<?php echo e(route('gym-admin.products.product-dues')); ?>" class="btn btn-sm btn-success pull-right">View More</a>
                                          </div>
                                      </div>
                                  </div>
                                  <div class="portlet-body flip-scroll">
                                      <table class="table table-bordered table-striped table-condensed flip-content">
                                          <thead class="flip-content">
                                          <tr class="uppercase">
                                              <th> Client Name</th>
                                              <th> Product</th>
                                              <th> Due Amount</th>
                                              <th> Due Date</th>
                                          </tr>
                                          </thead>
                                          <tbody>
                                          <?php $__empty_1 = true; $__currentLoopData = $dueProductPayments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$due): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                          <?php
                                              $data = '';
                                              $arr['product_name'] = json_decode($due->product_name,true);
                                                for($i=0; $i < count( $arr['product_name']) ;$i++){
                                                    $pro = \App\Models\Product::find($arr['product_name'][$i]);
                                                    if($pro != null){
                                                        if($i == 0){
                                                            $data = $pro->name ?? '';
                                                        }else{
                                                            $data = $data.', '.$pro->name ?? '';
                                                        }
                                                    }
                                                }

                                          ?>
                                              <tr>
                                                  <td><?php echo e($due->customer->fullName); ?> </td>
                                                  <td>
                                                      <strong><?php echo e($data); ?> </strong>
                                                  </td>
                                                  <td>
                                                      <strong><?php echo e($gymSettings->currency->acronym); ?> <?php echo e($due->total_amount - $due->paid_amount); ?> </strong>
                                                  </td>
                                                  <td><?php if( $due->next_payment_date): ?> <?php echo e($due->next_payment_date->toFormattedDateString()); ?> <?php endif; ?></td>
                                              </tr>
                                          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                              <tr>
                                                  <td colspan="4">No Product due payment.</td>
                                              </tr>
                                          <?php endif; ?>
                                          </tbody>
                                      </table>

                                  </div>
                              </div>
                          </div>

                        </div>
                    <?php endif; ?>

                    <div class="row">
                        <div class="col-md-6 col-sm-6">
                            <div class="portlet light ">
                                <div class="portlet-title">
                                    <div class="caption">
                                        <i class=" icon-users font-blue"></i>
                                        <span class="caption-subject font-blue bold uppercase">Recent Customers</span>
                                    </div>
                                    <div class="pull-right">
                                        <div class="btn-group">
                                            <a href="<?php echo e(route('gym-admin.client.index')); ?>" class="btn btn-sm btn-success pull-right">View More</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="portlet-body">
                                    <div class="mt-element-card mt-element-overlay">
                                        <div class="row">
                                            <?php $__currentLoopData = $recentClients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                                    <div class="mt-card-item mt-element-ribbon no-padding">
                                                        <div class="ribbon ribbon-clip ribbon-color-danger uppercase col-xs-8" style="font-size: 10px">
                                                            <div class="ribbon-sub ribbon-clip"></div> <?php echo e(($client->created_at) ? $client->created_at->diffForHumans(\Carbon\Carbon::now('Asia/Kathmandu')) : ''); ?>

                                                        </div>
                                                        <div class="mt-card-avatar mt-overlay-1">
                                                            <?php if($client->image != ''): ?>
                                                                <img src="<?php echo e($profileHeaderPath.$client->image); ?>"/>
                                                            <?php else: ?>
                                                                <img src="<?php echo e(asset('/fitsigma/images/').'/'.'user.svg'); ?>">
                                                            <?php endif; ?>

                                                            <div class="mt-overlay">
                                                                <ul class="mt-info">

                                                                    <li>
                                                                        <a class="btn default btn-outline"
                                                                           href="<?php echo e(route('gym-admin.client.show',$client->customer_id)); ?>">
                                                                            <i class="icon-link"></i>
                                                                        </a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <div class="mt-card-content">
                                                            <h3 class="mt-card-name"><?php echo e($client->first_name); ?>&nbsp;<?php echo e($client->middle_name); ?>&nbsp;<?php echo e($client->last_name); ?></h3>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <div class="portlet light ">
                                <div class="portlet-title">
                                    <div class="caption ">
                                        <span class="caption-subject font-blue bold uppercase"><i class="fa fa-cogs"></i> Asset Servicing</span>
                                        <span class="caption-helper"></span>
                                    </div>
                                </div>
                                <div class="portlet-body">
                                    <table class="table table-bordered table-striped table-condensed flip-content">
                                        <thead class="flip-content">
                                        <tr class="uppercase">
                                            <th> Asset Name</th>
                                            <th> Service By</th>
                                            <th> Servicing Date</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        <?php $__empty_1 = true; $__currentLoopData = $asset_services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                            <tr>
                                                <td> <?php echo e($service->assets->name); ?> </td>
                                                <td> <?php echo e($service->service_by); ?> </td>
                                                <td><?php echo e(date('M d, Y',strtotime($service->next_service_date))); ?></td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                            <tr>
                                                <td colspan="3">No Asset servicing.</td>
                                            </tr>
                                        <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php if(count($targets) > 0): ?>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="portlet light ">
                                    <div class="portlet-title">
                                        <div class="caption ">
                                            <span class="caption-subject font-blue bold uppercase"><i class="icon-target"></i> My Targets</span>
                                        </div>
                                    </div>
                                    <div class="portlet-body">
                                        <?php $__empty_1 = true; $__currentLoopData = $targets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $target): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                            <div class="caption-subject bold font-grey-gallery uppercase">
                                                <?php echo e($target['name']); ?> (<?php echo e(round($target['percent'],2)); ?>%)
                                            </div>
                                            <div class="progress progress-striped active">
                                                <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="4" aria-valuemin="0"
                                                     aria-valuemax="100" style="width: <?php echo e($target['percent']); ?>%">
                                                    <span class="sr-only"> <?php echo e($target['percent']); ?>% Complete </span>
                                                </div>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                            <h5>You don&apos;t have any target yet.</h5>
                                            <a class="btn dark" href="<?php echo e(route('gym-admin.target.create')); ?>">Create A Target <i
                                                        class="fa fa-arrow-right"></i> </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="row">
                        <?php if($user->can('show_membership_chart')): ?>
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="portlet light ">
                                    <div class="portlet-title">
                                        <div class="caption ">
                                            <span class="caption-subject font-blue bold uppercase"><i class="icon icon-pie-chart"></i> Currently Active Subscriptions</span>
                                            <span class="caption-helper"></span>
                                        </div>
                                    </div>
                                    <div class="portlet-body">
                                        <div id="activeSalesChart" class="CSSAnimationChart"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="portlet light ">
                                    <div class="portlet-title">
                                        <div class="caption ">
                                            <span class="caption-subject font-blue bold uppercase"><i class="icon icon-pie-chart"></i> Total Subscriptions</span>
                                            <span class="caption-helper"></span>
                                        </div>
                                    </div>
                                    <div class="portlet-body">
                                        <div id="salesChart" class="CSSAnimationChart"></div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if($user->can('show_finance_bar')): ?>
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="portlet light ">
                                    <div class="portlet-title">
                                        <div class="caption ">
                                            <span class="caption-subject font-blue bold uppercase"><i class="icon icon-bar-chart"></i> Subscription Finance</span>
                                            <span class="caption-helper"></span>
                                        </div>
                                    </div>
                                    <div class="portlet-body">
                                        <div id="financeChart" class="CSSAnimationChart"></div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <!-- END PAGE CONTENT INNER -->
           <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer'); ?>
    <?php echo HTML::script('admin/global/plugins/counterup/jquery.counterup.js'); ?>

    <?php echo HTML::script('admin/global/plugins/counterup/jquery.waypoints.min.js'); ?>

    <?php echo HTML::script('admin/global/plugins/morris/morris.min.js'); ?>

    <?php echo HTML::script('admin/global/plugins/amcharts/amcharts/amcharts.js'); ?>

    <?php echo HTML::script('admin/global/plugins/amcharts/amcharts/serial.js'); ?>

    <?php echo HTML::script('admin/global/plugins/amcharts/amcharts/pie.js'); ?>

    <?php echo HTML::script('admin/global/plugins/amcharts/amcharts/themes/light.js'); ?>

    <?php echo HTML::script('admin/pages/scripts/dashboard.js'); ?>

    <script>
        var months = new Array();
        months['1'] = 'Jan';
        months['2'] = 'Feb';
        months['3'] = 'Mar';
        months['4'] = 'Apr';
        months['5'] = 'May';
        months['6'] = 'Jun';
        months['7'] = 'Jul';
        months['8'] = 'Aug';
        months['9'] = 'Sep';
        months['10'] = 'Oct';
        months['11'] = 'Nov';
        months['12'] = 'Dec';
        var chart = AmCharts.makeChart("financeChart", {
            "type": "serial",
            "theme": "light",
            "marginLeft": 45,
            "marginRight": 8,
            "marginTop": 10,
            "marginBottom": 26,
            "dataProvider": [
                <?php $__currentLoopData = $financeCharts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chart): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                {
                    "Month": months['<?php echo e($chart->M); ?>'],
                    "income": <?php echo e($chart->S); ?>

                },
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            ],
            "valueAxes": [{
                "axisAlpha": 0,
                "position": "left"
            }],
            "startDuration": 1,
            "graphs": [{
                "alphaField": "alpha",
                "balloonText": "<span style='font-size:12px;'>[[title]] in [[category]]:<br><span style='font-size:20px;'>[[value]]</span> [[additional]]</span>",
                "fillAlphas": 1,
                "title": "Income",
                "type": "column",
                "valueField": "income",
                "dashLengthField": "dashLengthColumn"
            }],
            "categoryField": "Month",
            "categoryAxis": {
                "gridPosition": "start",
                "axisAlpha": 0,
                "tickLength": 0
            },
        });

        var pie = AmCharts.makeChart("salesChart", {
            "type": "pie",
            "theme": "light",
            "path": "../assets/global/plugins/amcharts/ammap/images/",
            "dataProvider": [
                    <?php $__currentLoopData = $membershipsStats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                {
                    "country": "<?php echo e($mem['title']); ?>",
                    "value": "<?php echo e($mem['total']); ?>"
                },
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            ],
            "valueField": "value",
            "titleField": "country",
            "outlineAlpha": 0.4,
            "balloonText": "[[title]]<br><span style='font-size:14px'><b>[[value]]</b> ([[percents]]%)</span>",
            "export": {
                "enabled": true
            }
        });

        var activePieChart = AmCharts.makeChart("activeSalesChart", {
            "type": "pie",
            "theme": "light",
            "path": "../assets/global/plugins/amcharts/ammap/images/",
            "dataProvider": [
                    <?php $__currentLoopData = $activeMembershipsStats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                {
                    "country": "<?php echo e($mem['title']); ?>",
                    "value": "<?php echo e($mem['total']); ?>"
                },
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            ],
            "valueField": "value",
            "titleField": "country",
            "outlineAlpha": 0.4,
            "balloonText": "[[title]]<br><span style='font-size:14px'><b>[[value]]</b> ([[percents]]%)</span>",
            "export": {
                "enabled": true
            }
        });

        // send subscription reminder
        $('.show-subscription-reminder').click(function () {
            var id = $(this).data('id');
            var show_url = "<?php echo e(route('gym-admin.client-purchase.show-subscription-reminder-modal',['#id'])); ?>";
            var url = show_url.replace('#id', id);
            $('#modelHeading').html('Select');
            $.ajaxModal("#reminderModal", url);
        });

        //send locker reminder
        $('.show-locker-reminder').click(function () {
            var id = $(this).data('id');
            var show_url = "<?php echo e(route('gym-admin.reservations.show-locker-reminder-modal',['#id'])); ?>";
            var url = show_url.replace('#id', id);
            $('#modelHeading').html('Select');
            $.ajaxModal("#reminderModal", url);
        });

        //renew subscription
        $('.renew-subscription').click(function () {
            var id = $(this).data('id');
            var show_url = "<?php echo e(route('gym-admin.client-purchase.renew-subscription-modal',['#id'])); ?>";
            var url = show_url.replace('#id', id);
            $('#modelHeading').html('Renew Subscription');
            $.ajaxModal("#reminderModal", url);
        });

        // add payment
        $('.add-payment').click(function () {
            var id = $(this).data('id');
            var show_url = "<?php echo e(route('gym-admin.membership-payments.add-payment-modal',['#id'])); ?>";
            var url = show_url.replace('#id', id);
            $('#modelHeading').html('Add Payment');
            $.ajaxModal("#reminderModal", url);
        });

         //add locker payment
         $('.add-locker-payment').click(function () {
            var id = $(this).data('id');
            var show_url = "<?php echo e(route('gym-admin.reservation-payments.add-payment-model',['#id'])); ?>";
            var url = show_url.replace('#id', id);
            $('#modelHeading').html('Add Locker Payment');
            $.ajaxModal("#reminderModal", url);
        });

        //renew reservation
        $('.renew-reservation').click(function () {
            var id = $(this).data('id');
            var show_url = "<?php echo e(route('gym-admin.reservations.renew-reservation-modal',['#id'])); ?>";
            var url = show_url.replace('#id', id);
            $('#modelHeading').html('Renew Reservation');
            $.ajaxModal("#reminderModal", url);
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.gym-merchant.gymbasic', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/stage.hamrofitness.com/public_html/resources/views/gym-admin/dashboard/index.blade.php ENDPATH**/ ?>