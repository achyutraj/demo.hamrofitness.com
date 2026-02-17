

<?php $__env->startSection('CSS'); ?>
    <style>
        .dashboard-filter {
            padding-right: 15px;
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
                    <span>Super Admin Dashboard</span>
                </li>
            </ul>
            <!-- END PAGE BREADCRUMBS -->
            <?php if($user->can('view_dashboard')): ?>
            <!-- BEGIN PAGE CONTENT INNER -->
                <div class="page-content-inner">
                    <div class="row widget-row">
                    <div class="col-md-3">
                            <!-- BEGIN WIDGET THUMB -->
                            <div class="dashboard-stat yellow">
                                <div class="visual">
                                    <i class="fa fa-users"></i>
                                </div>
                                <div class="details">
                                    <div class="number" data-counter="counterup" data-value="<?php echo e($branchCount); ?>"> 0 </div>
                                    <div class="desc"> Total Branch </div>
                                </div>
                            </div>
                            <!-- END WIDGET THUMB -->
                        </div>

                        <div class="col-md-3">
                            <!-- BEGIN WIDGET THUMB -->
                            <div class="dashboard-stat purple">
                                <div class="visual">
                                    <i class="fa fa-users"></i>
                                </div>
                                <div class="details">
                                    <div class="number" data-counter="counterup" data-value="<?php echo e($customerCount); ?>"> 0</div>
                                    <div class="desc"> Total Customer </div>
                                </div>

                            </div>
                            <!-- END WIDGET THUMB -->
                        </div>

                        <div class="col-md-3">
                            <!-- BEGIN WIDGET THUMB -->
                            <div class="dashboard-stat blue">
                                <div class="visual">
                                    <i class="fa fa-users"></i>
                                </div>
                                <div class="details">
                                    <div class="number" data-counter="counterup" data-value="<?php echo e($currentMonthEnquiries); ?>"> 0 </div>
                                    <div class="desc"> Monthly Enquiries </div>
                                </div>
                            </div>
                            <!-- END WIDGET THUMB -->
                        </div>

                        <div class="col-md-3">
                            <!-- BEGIN WIDGET THUMB -->
                            <div class="dashboard-stat green-soft">
                                <div class="visual">
                                    <i class="fa fa-users"></i>
                                </div>
                                <div class="details">
                                    <div class="number" data-counter="counterup" data-value="<?php echo e($unpaidMembers); ?>"> 0 </div>
                                    <div class="desc"> Unpaid Members </div>
                                </div>
                            </div>
                            <!-- END WIDGET THUMB -->
                        </div>
                        <div class="col-md-3">
                            <!-- BEGIN WIDGET THUMB -->
                            <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 ">
                                <h4 class="widget-thumb-heading">Total Earnings</h4>
                                <div class="widget-thumb-wrap">
                                    <i class="widget-thumb-icon bg-yellow fa <?php echo e($gymSettings->currency->symbol); ?>"></i>
                                    <div class="widget-thumb-body">
                                        <span class="widget-thumb-subtitle"><?php echo e($gymSettings->currency->acronym); ?></span>
                                        <span class="widget-thumb-body-stat" data-counter="counterup" data-value="<?php echo e($totalEarnings); ?>">0</span>
                                    </div>
                                </div>
                            </div>
                            <!-- END WIDGET THUMB -->
                        </div>
                        <div class="col-md-3">
                            <!-- BEGIN WIDGET THUMB -->
                            <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 ">
                                <h4 class="widget-thumb-heading">Monthly Income</h4>
                                <div class="widget-thumb-wrap">
                                    <i class="widget-thumb-icon bg-purple fa <?php echo e($gymSettings->currency->symbol); ?>"></i>
                                    <div class="widget-thumb-body">
                                        <span class="widget-thumb-subtitle"><?php echo e($gymSettings->currency->acronym); ?></span>
                                        <span class="widget-thumb-body-stat" data-counter="counterup" data-value="<?php echo e($currentMonthEarnings); ?>">0</span>
                                    </div>
                                </div>
                            </div>
                            <!-- END WIDGET THUMB -->
                        </div>
                        <div class="col-md-3">
                            <!-- BEGIN WIDGET THUMB -->
                            <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 ">
                                <h4 class="widget-thumb-heading">Total Due Payment</h4>
                                <div class="widget-thumb-wrap">
                                    <i class="widget-thumb-icon bg-blue fa <?php echo e($gymSettings->currency->symbol); ?>"></i>
                                    <div class="widget-thumb-body">
                                        <span class="widget-thumb-subtitle"><?php echo e($gymSettings->currency->acronym); ?></span>
                                        <span class="widget-thumb-body-stat" data-counter="counterup" data-value="<?php echo e($duePayments); ?>">0</span>
                                    </div>
                                </div>
                            </div>
                            <!-- END WIDGET THUMB -->
                        </div>
                        <div class="col-md-3">
                            <!-- BEGIN WIDGET THUMB -->
                            <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 ">
                                <h4 class="widget-thumb-heading">Monthly Expense</h4>
                                <div class="widget-thumb-wrap">
                                    <i class="widget-thumb-icon bg-green-soft fa <?php echo e($gymSettings->currency->symbol); ?>"></i>
                                    <div class="widget-thumb-body">
                                        <span class="widget-thumb-subtitle"><?php echo e($gymSettings->currency->acronym); ?></span>
                                        <span class="widget-thumb-body-stat" data-counter="counterup" data-value="<?php echo e($currentMonthExpense); ?>">0</span>
                                    </div>
                                </div>
                            </div>
                            <!-- END WIDGET THUMB -->
                        </div>


                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <div class="portlet light">
                                <div class="portlet-title">
                                    <div class="caption">
                                        <i class="icon-users font-blue"></i>
                                        <span class="caption-subject font-blue bold uppercase">Branches Expiring in next 45 days</span>
                                    </div>
                                    <div class="pull-right">
                                        <div class="btn-group">
                                            <a class="btn sbold blue"> Total <?php echo e($expiringBranches->count()); ?>

                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="portlet-body">
                                    <table class="table table-bordered table-striped table-condensed flip-content">
                                        <thead class="flip-content">
                                        <tr class="uppercase">
                                            <th> Branch Name </th>
                                            <th> Manager Name </th>
                                            <th> Phone </th>
                                            <th> Email </th>
                                            <th> Join At </th>
                                            <th> Expire On </th>
                                            <th> Remain Days </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php $__empty_1 = true; $__currentLoopData = $expiringBranches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $expireBranch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                            <tr>
                                                <td><?php echo e($expireBranch->title); ?></td>
                                                <td><?php echo e($expireBranch->owner_incharge_name); ?></td>
                                                <td><?php echo e($expireBranch->phone); ?></td>
                                                <td><?php echo e($expireBranch->email); ?></td>
                                                <td><?php echo e($expireBranch->joins_on); ?></td>
                                                <td><?php echo e($expireBranch->expires_on); ?></td>
                                                <td>
                                                    <?php
                                                    $created = new \Carbon\Carbon($expireBranch->expires_on);
                                                    $now = \Carbon\Carbon::now();
                                                    $difference = ($created->diff($now)->days < 1) ? 'today' : $created->diffInDays($now);
                                                    ?>
                                                    <span class="badge badge-danger"><?php echo e($difference); ?> Days</span></td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                            <tr>
                                                <td colspan="7">No Branch Expire.</td>
                                            </tr>
                                        <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-sm-12">
                            <!-- BEGIN PORTLET-->
                            <div class="portlet light ">
                                <div class="portlet-title tabbable-line">
                                    <div class="caption">
                                        <i class="icon-user-following font-green"></i>
                                        <span class="caption-subject font-blue bold uppercase">Recently Active</span>
                                    </div>
                                </div>
                                <div class="portlet-body">
                                    <table class="table table-bordered table-striped table-condensed flip-content">
                                        <thead class="flip-content">
                                        <tr class="uppercase">
                                            <th> BranchName </th>
                                            <th> UserName </th>
                                            <th> Last Active </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php $__empty_1 = true; $__currentLoopData = $recentlyActive; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $recent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                            <tr>
                                                <td><?php echo e($recent->title); ?></td>
                                                <td>
                                                    <?php if($recent->image != ''): ?>
                                                        <img style="width:50px;height:50px;" class="img-circle" src="<?php echo e(asset('/uploads/profile_pic/master/') . '/' . $recent->image); ?>" alt="" /><br>
                                                    <?php endif; ?>
                                                    <?php echo e(ucfirst($recent->first_name) .' '.ucfirst($recent->middle_name) .' '.ucfirst($recent->last_name)); ?></td>
                                                <td><?php echo e(\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $recent->last_activity)->diffForHumans()); ?></td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                            <tr>
                                                <td colspan="3">No Data Found.</td>
                                            </tr>
                                        <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- END PORTLET-->
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <!-- BEGIN PORTLET-->
                            <div class="portlet light ">
                                <div class="portlet-title tabbable-line">
                                    <div class="caption">
                                        <i class="icon-user-unfollow font-red"></i>
                                        <span class="caption-subject font-blue bold uppercase">NotActive Users</span>
                                    </div>
                                </div>
                                <div class="portlet-body">
                                    <table class="table table-bordered table-striped table-condensed flip-content">
                                        <thead class="flip-content">
                                        <tr class="uppercase">
                                            <th> BranchName </th>
                                            <th> UserName </th>
                                            <th> Last Active </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php $__empty_1 = true; $__currentLoopData = $notActiveUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notActive): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                            <tr>
                                                <td><?php echo e($notActive->title); ?></td>
                                                <td>
                                                    <?php if($notActive->image != ''): ?>
                                                        <img style="width:50px;height:50px;" class="img-circle" src="<?php echo e(asset('/uploads/profile_pic/master/') . '/' . $notActive->image); ?>" alt="" /><br>
                                                    <?php endif; ?>
                                                    <?php echo e(ucfirst($notActive->first_name) .' '.ucfirst($notActive->middle_name) .' '.ucfirst($notActive->last_name)); ?></td>
                                                <td>
                                                    <?php if($notActive->last_activity != null): ?>
                                                        <?php echo e(\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $notActive->last_login)->diffForHumans()); ?>

                                                    <?php else: ?>
                                                        <?php echo e('Not LogIn Till Now'); ?>

                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                            <tr>
                                                <td colspan="3">No Data Found.</td>
                                            </tr>
                                        <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- END PORTLET-->
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <div class="portlet light">
                                <div class="portlet-title">
                                    <div class="caption">
                                        <i class="icon-user-follow font-blue"></i>
                                        <span class="caption-subject font-blue bold uppercase" >Active Last Month</span>
                                    </div>
                                </div>
                                <div class="portlet-body">
                                    <table class="table table-bordered table-striped table-condensed flip-content">
                                        <thead class="flip-content">
                                        <tr class="uppercase">
                                            <th> BranchName </th>
                                            <th> UserName </th>
                                            <th> Last Active </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php $__empty_1 = true; $__currentLoopData = $userActiveInDays; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lastWeek): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                            <tr>
                                                <td><?php echo e($lastWeek->title); ?></td>
                                                <td>
                                                    <?php if($lastWeek->image != ''): ?>
                                                        <img style="width:50px;height:50px;" class="img-circle" src="<?php echo e(asset('/uploads/profile_pic/master/') . '/' . $lastWeek->image); ?>" alt="" /><br>
                                                    <?php endif; ?>
                                                    <?php echo e(ucfirst($lastWeek->first_name) .' '.ucfirst($lastWeek->middle_name) .' '.ucfirst($lastWeek->last_name)); ?></td>
                                                <td>
                                                    <?php if($lastWeek->last_activity != null): ?>
                                                        <?php echo e(\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $lastWeek->last_activity)->diffForHumans()); ?>

                                                    <?php else: ?>
                                                        <?php echo e('Not LogIn Till Now'); ?>

                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                            <tr>
                                                <td colspan="3">No Data Found.</td>
                                            </tr>
                                        <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END PAGE CONTENT INNER -->
            <?php endif; ?>

    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer'); ?>
    <?php echo HTML::script('admin/global/plugins/counterup/jquery.counterup.js'); ?>

    <?php echo HTML::script('admin/global/plugins/counterup/jquery.waypoints.min.js'); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.gym-merchant.gymbasic', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/stage.hamrofitness.com/public_html/resources/views/gym-admin/super-admin/dashboard.blade.php ENDPATH**/ ?>