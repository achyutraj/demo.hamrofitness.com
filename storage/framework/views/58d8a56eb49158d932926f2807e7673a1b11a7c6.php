<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title>HamroFitness</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport"/>
    <meta content="" name="description"/>
    <meta content="" name="author"/>

    <link href="<?php echo e(asset('fonts/stylesheet.css')); ?>" rel="stylesheet">

    <?php echo HTML::style('admin/global/plugins/font-awesome/css/font-awesome.min.css'); ?>


    <?php echo HTML::style('admin/global/plugins/bootstrap/css/bootstrap.min.css'); ?>


    <?php echo HTML::style('admin/global/css/components-md.min.css'); ?>


    <?php echo HTML::style('admin/pages/css/auth.css'); ?>



    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo e(asset('favicon/favicon-16x16.png')); ?>">

    <style>
        .alert {
            margin-top: 10px;
            margin-bottom: 10px;
        }

        .user-login-5 .alert {
            margin-top: 0;
        }
        .view {
            float: right;
            margin-top: -34px;
        }
    </style>
    <?php echo $__env->yieldContent('css'); ?>
</head>
<!-- END HEAD -->

<body class="hold-transition login-page">
<!-- BEGIN : LOGIN PAGE 5-2 -->
<?php echo $__env->yieldContent('content'); ?>
<!-- BEGIN CORE PLUGINS -->
<?php echo HTML::script('admin/global/plugins/jquery.min.js'); ?>

<?php echo HTML::script('admin/global/plugins/bootstrap/js/bootstrap.min.js'); ?>

<?php echo HTML::script('admin/global/plugins/js.cookie.min.js'); ?>

<?php echo HTML::script('admin/global/plugins/jquery.blockui.min.js'); ?>

<?php echo HTML::script('admin/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js'); ?>

<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
<?php echo HTML::script('admin/global/plugins/jquery-validation/js/jquery.validate.min.js'); ?>

<?php echo HTML::script('admin/global/plugins/jquery-validation/js/additional-methods.min.js'); ?>

<?php echo HTML::script('admin/global/plugins/backstretch/jquery.backstretch.min.js'); ?>

<!-- END PAGE LEVEL PLUGINS -->

<?php echo $__env->yieldContent('js'); ?>
</body>

</html>
<?php /**PATH /var/www/stage.hamrofitness.com/public_html/resources/views/layouts/merchant/login.blade.php ENDPATH**/ ?>