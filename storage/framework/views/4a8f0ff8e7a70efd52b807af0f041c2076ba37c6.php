<nav class="navbar navbar-default navbar-fixed-top m-b-0">
    <div class="navbar-header"><a class="navbar-toggle hidden-sm hidden-md hidden-lg " href="javascript:void(0)" data-toggle="collapse"
                                  data-target=".navbar-collapse"><i class="ti-menu"></i></a>
        <div class="top-left-part">
            <a class="logo" href="<?php echo e(route('gym-admin.dashboard.index')); ?>">
                <span class="hidden-xs">
                    <?php if($gymSettings->front_image != ''): ?>
                        <?php echo HTML::image(asset('/uploads/gym_setting/master/').'/'.$gymSettings->front_image, 'Logo',array("class" => "logo-style")); ?>

                    <?php else: ?>
                        <?php echo HTML::image(asset('/fitsigma/images').'/'.'fitness-plus.png', 'Logo',array("class" => "logo-style")); ?>

                    <?php endif; ?>
                </span>
            </a>
        </div>
        <ul class="nav navbar-top-links navbar-left hidden-xs">
            <li><a href="javascript:void(0)" class="open-close hidden-xs waves-effect waves-light"><i class="icon-arrow-left-circle ti-menu"></i></a></li>
        </ul>
        <ul class="nav navbar-top-links navbar-right pull-right">

            <?php if($user->is_admin == 1): ?>
                <li class="p-r-10">
                    <div class="btn-group margin-top-10">
                        <a class="btn green dropdown-toggle" data-toggle="dropdown" href="javascript:;" aria-expanded="true"> <?php echo e($common_details->title); ?>

                            <i class="fa fa-angle-down"></i>
                        </a>
                        <ul class="dropdown-menu scale-up">
                            <?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li>
                                    <a href="javascript:;" onclick="changeBranch(<?php echo e($branch->id); ?>);return false;"><?php echo e($branch->title); ?>

                                        <?php if(isset($merchantBusiness->detail_id) && isset($branch->id) && $merchantBusiness->detail_id == $branch->id): ?>
                                            <i class="fa fa-check"></i>
                                        <?php endif; ?>
                                    </a>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                </li>
            <?php endif; ?>

            <li class="p-r-10">
                <div class="btn-group margin-top-10 hidden-sm hidden-xs">
                    <a class="btn btn-success" href="<?php echo e(route('gym-admin.fitnessCalculation')); ?>">
                        <i class="fa fa-calculator"></i> Calculator
                    </a>
                </div>
            </li>

            <?php if(count($todayBirthDay) > 0 || count($todayAnniversary) > 0): ?>
            <li class="p-r-10">
                <div class="btn-group margin-top-10 hidden-sm hidden-xs">
                    <a class="btn btn-info" type="button" data-toggle="modal" data-target="#wishModal">
                        <i class="fa fa-gift"></i> Wish Me
                    </a>
                </div>
            </li>
            <?php endif; ?>

            <?php if(count($todayExpireSubscription) > 0): ?>
            <li class="p-r-10">
                <div class="btn-group margin-top-10 hidden-sm hidden-xs">
                    <a class="btn btn-info" type="button" data-toggle="modal" data-target="#expireSubscriptionModal">
                        <i class="fa fa-bell-o"></i> Today Expire
                    </a>
                </div>
            </li>
            <?php endif; ?>

            <li class="p-r-5" style="padding-left: 5px">
                <div class="btn-group margin-top-10 hidden-sm hidden-xs">
                    <?php if($user->is_admin !== 1 && $user->common->end_date!=''): ?>
                        <?php if(\Carbon\Carbon::parse($user->common->end_date)->diffInDays() > 45): ?>
                            <a href="#" class="btn btn-success uppercase"> <?php echo e(\Carbon\Carbon::parse($user->common->end_date)->diffInDays()); ?>

                                Days <span class="hidden-xs hidden-sm">Remaining</span>
                                <i class="icon-clock hidden-xs hidden-sm"></i>
                            </a>
                        <?php elseif(\Carbon\Carbon::parse($user->common->end_date)->diffInDays() <= 45): ?>
                            <a href="#" class="btn yellow-lemon uppercase"> <?php echo e(\Carbon\Carbon::parse($user->common->end_date)->diffInDays()); ?>

                                Days <span class="hidden-xs hidden-sm">Remaining</span>
                                <i class="icon-clock hidden-xs hidden-sm"></i>
                            </a>
                        <?php elseif(\Carbon\Carbon::parse($user->common->end_date)->diffInDays() == 0): ?>
                            <a href="#" class="btn red-mint uppercase"> Ends Tomorrow
                                <i class="icon-clock hidden-xs hidden-sm"></i>
                            </a>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </li>

            <li class="dropdown">
                <a class="dropdown-toggle waves-effect waves-light" data-toggle="dropdown" href="#"><i class="fa fa-info-circle"></i>
                </a>
                <ul class="dropdown-menu mailbox scale-up">
                    <?php if($user->can("view_software_updates")): ?>
                        <li class=" merchant-notif-count-title">
                            <div class="drop-title">
                                <a href="<?php echo e(route('gym-admin.upcoming.index')); ?>">
                                   S/W Updates
                                </a>
                            </div>

                        </li>
                    <?php endif; ?>
                    <li class=" merchant-notif-count-title">
                        <div class="drop-title">
                            <a href="<?php echo e(route('gym-admin.faqs.index')); ?>">
                                FAQs
                            </a>
                        </div>

                    </li>
                    <li class=" merchant-notif-count-title">
                        <div class="drop-title">For Any Support</div>
                    </li>
                    <li>
                        <div class="message-center merchant-notifications">
                            <div class="mail-contnet">
                                <a target="_blank" href="https://encodenepal.com/">
                                    <h5 class="text-success">Encode Nepal</h5>
                                    <span class="mail-desc"> 9851096919 </span>
                                </a>
                            </div>
                            <div class="mail-contnet">
                                <a target="_blank" href="https://apps.google.com/meet/">
                                    <h5 class="text-success">Chat on Google Meet</h5>
                                    <span class="mail-desc"></span>
                                </a>
                            </div>
                        </div>
                    </li>
                </ul>
                <!-- /.dropdown-messages -->
            </li>

            
            <li class="dropdown"><a class="dropdown-toggle waves-effect waves-light" data-toggle="dropdown" href="#"><i class="icon-list"></i>
                    <div class="notify merchant-notif-count"><?php if($taskReminder > 0): ?><span class="heartbit"></span><span class="point"></span><?php endif; ?>
                    </div>
                </a>
                <ul class="dropdown-menu mailbox scale-up">
                    <li class=" merchant-notif-count-title">
                        <div class="drop-title">You have <?php echo e($taskReminder); ?> tasks.</div>
                    </li>
                    <li>
                        <div class="message-center merchant-notifications">
                            <?php $__currentLoopData = $tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <a href="javascript:;">

                                    <div class="mail-contnet">
                                        <?php if($task->priority === "high"): ?>
                                            <h5 class="text-danger"><?php echo e($task->heading); ?></h5>
                                        <?php else: ?>
                                            <h5 class="text-warning"><?php echo e($task->heading); ?></h5>
                                        <?php endif; ?>
                                        <span class="mail-desc"><?php echo e(substr($task->description,0,25)); ?>...</span>
                                    </div>
                                </a>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </li>
                    <li>
                        <a class="text-center" href="<?php echo e(route('gym-admin.task.index')); ?>"> <strong>View</strong> <i class="fa fa-angle-right"></i> </a>
                    </li>
                </ul>
                <!-- /.dropdown-messages -->
            </li>

            
            <li class="dropdown"><a class="dropdown-toggle waves-effect waves-light" data-toggle="dropdown" href="#"><i class="icon-bell"></i>
                    <div class="notify merchant-notif-count"><?php if($unreadNotifications > 0): ?><span class="heartbit"></span><span class="point"></span><?php endif; ?>
                    </div>
                </a>
                <ul class="dropdown-menu mailbox scale-up">
                    <li class=" merchant-notif-count-title">
                        <div class="drop-title">You have <?php echo e($unreadNotifications); ?> new notifications</div>
                    </li>
                    <li>
                        <div class="message-center merchant-notifications">
                            <?php $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <a href="javascript:;">
                                    <div class="user-img"><img src="<?php echo e(asset('/fitsigma/images/').'/'.'user.svg'); ?>" alt="user" class="img-circle"> <span
                                                class="profile-status online pull-right"></span></div>
                                    <div class="mail-contnet">
                                        <h5><?php echo $notification['notification_type']; ?></h5>
                                        <span class="mail-desc"><?php echo e($notification['title']); ?></span></div>
                                </a>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </li>
                    <li>
                        <a class="text-center mark-read"> <strong>Mark as Read</strong> <i class="fa fa-angle-right"></i> </a>
                    </li>
                </ul>
                <!-- /.dropdown-messages -->
            </li>

            <li class="dropdown">
                <a class="dropdown-toggle profile-pic waves-effect waves-light" data-toggle="dropdown" href="#">
                    <?php if($user->image ==''): ?>
                        <img src="<?php echo e(asset('/fitsigma/images/').'/'.'user.svg'); ?>" width="36" class="img-circle img-change"/>
                    <?php else: ?>
                        <?php if($gymSettings->local_storage == 0): ?>
                            <img class="img-circle img-change" width="36" src="<?php echo e($profilePath.$user->image); ?>"/>
                        <?php else: ?>
                            <img class="img-circle img-change" width="36" src="<?php echo e(asset('/uploads/profile_pic/thumb/').'/'.$user->image); ?>"/>
                        <?php endif; ?>
                    <?php endif; ?><b class="hidden-xs"><?php echo e(ucwords($user->username)); ?></b>
                </a>
                <ul class="dropdown-menu dropdown-user scale-up">
                    <?php if($user->can("update_profile")): ?>
                        <li>
                            <a href="<?php echo e(route('gym-admin.profile.index')); ?>">
                                <i class="icon-user"></i> My Profile </a>
                        </li>
                    <?php endif; ?>
                    <?php if($user->can("view_settings")): ?>
                        <li>
                            <a href="<?php echo e(route('gym-admin.setting.index')); ?>">
                                <i class="fa fa-gear faa-spin animated"></i> Gym Settings </a>
                        </li>
                    <?php endif; ?>
                    <?php if($user->can("generate_i_cards")): ?>
                        <li>
                            <a href="<?php echo e(route('gym-admin.i-card.index')); ?>">
                                <i class="fa fa-qrcode"></i> Generate I-Cards </a>
                        </li>
                        <li>
                            <a href="<?php echo e(route('gym-admin.barcode.index')); ?>">
                                <i class="fa fa-barcode"></i> Generate Barcode </a>
                        </li>
                    <?php endif; ?>
                    <?php if($user->can('manage_permissions')): ?>
                        <li>
                            <a href="<?php echo e(route('gym-admin.users.index')); ?>">
                                <i class="fa fa-lock"></i> User Permissions </a>
                        </li>
                    <?php endif; ?>
                    <li>
                        <a href="<?php echo e(url('/cache-clear')); ?>">
                            <i class="fa fa-spinner"></i> Cache Clear </a>
                    </li>
                    <li>
                        <a href="<?php echo e(url('/view-clear')); ?>">
                            <i class="fa fa-tachometer"></i> View Clear </a>
                    </li>
                    <?php if($user->can('download_backup')): ?>
                        <li>
                            <a href="<?php echo e(route('gym-admin.backup.index')); ?>">
                                <i class="fa fa-cloud-download"></i> Take Backup </a>
                        </li>
                        <li class="divider"></li>
                    <?php endif; ?>
                    <li>
                        <a href="<?php echo e(route('merchant.lockscreen')); ?>">
                            <i class="icon-lock"></i> Lock Screen </a>
                    </li>
                    <li>
                        <a href="<?php echo e(route('gym-admin.logout.index')); ?>">
                            <i class="icon-key"></i> Log Out </a>
                    </li>
                </ul>
                <!-- /.dropdown-user -->
            </li>
        </ul>
    </div>
    <!-- /.navbar-header -->
    <!-- /.navbar-top-links -->
    <!-- /.navbar-static-side -->
</nav>
<?php /**PATH /var/www/stage.hamrofitness.com/public_html/resources/views/layouts/gym-merchant/navbar.blade.php ENDPATH**/ ?>