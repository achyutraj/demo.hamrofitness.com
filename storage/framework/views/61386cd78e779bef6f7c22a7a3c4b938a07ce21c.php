<head>
    <meta charset="utf-8"/>
    <title>Business Admin | <?php echo e($title); ?></title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport"/>
    <meta content="Gym Management System" name="description"/>
    <meta content="INITIATIVE" name="author"/>
    
    <link rel="apple-touch-icon" sizes="57x57" href="<?php echo e(asset('favicon/apple-icon-57x57.png')); ?>">
    <link rel="apple-touch-icon" sizes="60x60" href="<?php echo e(asset('favicon/apple-icon-60x60.png')); ?>">
    <link rel="apple-touch-icon" sizes="72x72" href="<?php echo e(asset('favicon/apple-icon-72x72.png')); ?>">
    <link rel="apple-touch-icon" sizes="76x76" href="<?php echo e(asset('favicon/apple-icon-76x76.png')); ?>">
    <link rel="apple-touch-icon" sizes="114x114" href="<?php echo e(asset('favicon/apple-icon-114x114.png')); ?>">
    <link rel="apple-touch-icon" sizes="120x120" href="<?php echo e(asset('favicon/apple-icon-120x120.png')); ?>">
    <link rel="apple-touch-icon" sizes="144x144" href="<?php echo e(asset('favicon/apple-icon-144x144.png')); ?>">
    <link rel="apple-touch-icon" sizes="152x152" href="<?php echo e(asset('favicon/apple-icon-152x152.png')); ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo e(asset('favicon/apple-icon-180x180.png')); ?>">
    <link rel="icon" type="image/png" sizes="192x192"  href="<?php echo e(asset('favicon/android-icon-192x192.png')); ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo e(asset('favicon/favicon-32x32.png')); ?>">
    <link rel="icon" type="image/png" sizes="96x96" href="<?php echo e(asset('favicon/favicon-96x96.png')); ?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo e(asset('favicon/favicon-16x16.png')); ?>">
    <link rel="manifest" href="<?php echo e(asset('favicon//manifest.json')); ?>">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="<?php echo e(asset('favicon/ms-icon-144x144.png')); ?>">
    <meta name="theme-color" content="#ffffff">
    
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>"/>

    <!-- BEGIN GLOBAL MANDATORY STYLES -->
<?php echo HTML::style("admin/global/plugins/font-awesome/css/font-awesome.min.css"); ?>

<?php echo HTML::style("admin/global/css/font-awesome-animation.min.css"); ?>


<?php echo HTML::style("admin/global/plugins/simple-line-icons/simple-line-icons.min.css"); ?>

<?php echo HTML::style("admin/global/plugins/bootstrap/css/bootstrap.css"); ?>

<?php echo HTML::style("admin/global/plugins/uniform/css/uniform.default.css"); ?>

<?php echo HTML::style("admin/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css"); ?>

<!-- END GLOBAL MANDATORY STYLES -->
    <!-- BEGIN PAGE LEVEL PLUGINS -->
<?php echo HTML::style('admin/global/plugins/bootstrap-daterangepicker/daterangepicker-bs3.css'); ?>

<!-- END PAGE LEVEL PLUGINS -->
    <!-- BEGIN THEME GLOBAL STYLES -->
<?php echo HTML::style('admin/global/css/components-md.min.css',array('id'=>'style_components')); ?>

<?php echo HTML::style('admin/global/css/plugins-md.min.css'); ?>

<?php echo HTML::style('admin/global/css/md-loader.css'); ?>

<?php echo HTML::style('admin/global/plugins/select2/select2.min.css'); ?>

<!-- END THEME GLOBAL STYLES -->
    <!-- BEGIN THEME LAYOUT STYLES -->
<?php echo HTML::style('admin/admin/layout3/css/layout.css'); ?>

<?php echo HTML::style('admin/global/plugins/froiden-helper/helper.css'); ?>

<?php echo HTML::style('admin/global/plugins/bootstrap-toastr/toastr.min.css'); ?>


<?php echo HTML::style('fitsigma_customer/bower_components/sidebar-nav/dist/sidebar-nav.min.css'); ?>

<!-- animation CSS -->
<?php echo HTML::style('fitsigma_customer/css/animate.css'); ?>

<!-- Custom CSS -->
<?php echo HTML::style('fitsigma_customer/css/style.css'); ?>

<!-- color CSS -->
<?php echo HTML::style('fitsigma_customer/css/colors/megna.css'); ?>

<!--helper CSS-->
    <?php echo HTML::style('admin/global/plugins/froiden-helper/helper.css'); ?>

    <?php echo HTML::style('fitsigma_customer/css/custom.css'); ?>

    <style>
        .sidebar #side-menu .user-pro {
            background: url(<?php echo e(asset('fitsigma_customer/images/profile-menu.png')); ?>) center center/cover no-repeat;
        }

        .modal-backdrop {
            z-index: 99;
        }

        .modal-dialog {
            z-index: 100;
        }

        body.modal-open .modal .modal-dialog {
            margin-top: 5%;
        }

        .sidebar {
            z-index: 97;
        }

        .navbar {
            z-index: 98;
        }
    </style>

    <?php echo $__env->yieldContent('CSS'); ?>
    <?php echo HTML::style('admin/admin/layout3/css/custom.css?v=1.6'); ?>

    <!-- END THEME LAYOUT STYLES -->
</head>

<!-- END HEAD -->
<?php /**PATH /var/www/stage.hamrofitness.com/public_html/resources/views/layouts/gym-merchant/headmaterial.blade.php ENDPATH**/ ?>