

<?php $__env->startSection('content'); ?>
    <div class="page-lock">
        <div class="panel">
            <div class="panel-body">
                <div class="page-logo">
                    <a class="brand" href="javascript:;">
                        <?php if(is_null($gymSettings)): ?>
                            <?php echo HTML::image(asset('/fitsigma/images/').'/'.'fitness-plus.png', 'Logo',array("class" => "img-responsive")); ?>

                        <?php else: ?>
                            <?php if($gymSettings->front_image != ''): ?>
                                <?php echo HTML::image(asset('/uploads/gym_setting/master/').'/'.$gymSettings->front_image, 'Logo',array("class" => "img-responsive")); ?>

                            <?php else: ?>
                                <?php echo HTML::image(asset('/fitsigma/images').'/'.'fitness-plus.png', 'Logo',array("class" => "img-responsive")); ?>

                            <?php endif; ?>
                        <?php endif; ?>
                    </a>
                </div>
                <div class="page-body">
                    <?php if($userValue->image == ''): ?>
                        <img class="page-lock-img" src="<?php echo e(asset('/fitsigma/images/').'/'.'user.svg'); ?>" alt="">
                    <?php else: ?>
                        <img class="page-lock-img" src="<?php echo e($profileHeaderPath.$userValue->image); ?>" alt="">
                    <?php endif; ?>
                    <div class="page-lock-info">
                        <h1><?php echo e($userValue->first_name); ?></h1>
                        <small> <?php echo e($userValue->email); ?> </small><br/>
                        <span class="locked"> Locked </span>
                        <?php echo Form::open(array('route' => ['merchant.lockLogin'], 'method' => 'POST', "id" => "login-form", "class" => 'form-inline')); ?>

                        <div id="error-message"></div>
                        <div class="input-group input-medium">
                            <input type="password" class="form-control" placeholder="Password" name="password" id="password">
                            <span class="input-group-btn">
                                <a class="btn blue icn-only view">
                                    <i class="fa fa-eye size-icon"></i>
                                </a>
                            </span>
                            <span class="input-group-btn">
                                <button type="submit" class="btn green icn-only">
                                    <i class="fa fa-arrow-circle-o-right size-icon"></i>
                                </button>
                            </span>
                        </div>
                        <!-- /input-group -->
                        <div class="relogin">
                            <a href="<?php echo e(route('merchant.logout')); ?>"> Not <?php echo e($userValue->first_name); ?> ? </a>
                        </div>
                        <?php echo Form::close(); ?>

                    </div>
                </div>
                <small> <?php echo e(\Carbon\Carbon::now('Asia/Kathmandu')->year); ?> &copy; HamroFitness </small>
            </div>
        </div>
    </div>
    <style>
        body {
            color: #111;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.merchant.locked', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/stage.hamrofitness.com/public_html/resources/views/gym-admin/lockscreen.blade.php ENDPATH**/ ?>