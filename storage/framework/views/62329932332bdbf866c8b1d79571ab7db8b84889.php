<!DOCTYPE html>
<html lang="en">
<!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
	<meta charset="utf-8"/>
	<title>HamroFitness | Error</title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8">
	<meta content="" name="description"/>
	<meta content="" name="author"/>

	<!-- BEGIN GLOBAL MANDATORY STYLES -->
<?php echo HTML::style("//fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all"); ?>

<?php echo HTML::style("admin/global/plugins/font-awesome/css/font-awesome.min.css"); ?>

<?php echo HTML::style("admin/global/plugins/simple-line-icons/simple-line-icons.min.css"); ?>

<?php echo HTML::style("admin/global/plugins/bootstrap/css/bootstrap.min.css"); ?>

<?php echo HTML::style("admin/global/plugins/uniform/css/uniform.default.css"); ?>

<?php echo HTML::style("admin/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css"); ?>

<!-- END GLOBAL MANDATORY STYLES -->

<?php echo HTML::style("admin/admin/pages/css/error.css"); ?>


<!-- BEGIN THEME STYLES -->
<?php echo HTML::style("admin/global/css/components-rounded.css"); ?>

<?php echo HTML::style("admin/global/css/plugins.css"); ?>

<?php echo HTML::style("admin/admin/layout4/css/layout.css"); ?>

<?php echo HTML::style("admin/admin/layout4/css/themes/default.css"); ?>

<?php echo HTML::style("admin/admin/layout4/css/custom.css"); ?>

<!-- END THEME STYLES -->

	<link rel="apple-touch-icon" sizes="57x57" href="<?php echo e(URL::asset('front/icons/apple-icon-57x57.png')); ?>">
	<link rel="apple-touch-icon" sizes="57x57" href="<?php echo e(URL::asset('front/icons/apple-icon-60x60.png')); ?>">
	<link rel="apple-touch-icon" sizes="57x57" href="<?php echo e(URL::asset('front/icons/apple-icon-72x72.png')); ?>">
	<link rel="apple-touch-icon" sizes="57x57" href="<?php echo e(URL::asset('front/icons/apple-icon-76x76.png')); ?>">
	<link rel="apple-touch-icon" sizes="57x57" href="<?php echo e(URL::asset('front/icons/apple-icon-114x114.png')); ?>">
	<link rel="apple-touch-icon" sizes="57x57" href="<?php echo e(URL::asset('front/icons/apple-icon-120x120.png')); ?>">
	<link rel="apple-touch-icon" sizes="57x57" href="<?php echo e(URL::asset('front/icons/apple-icon-144x144.png')); ?>">
	<link rel="apple-touch-icon" sizes="57x57" href="<?php echo e(URL::asset('front/icons/apple-icon-152x152.png')); ?>">
	<link rel="apple-touch-icon" sizes="57x57" href="<?php echo e(URL::asset('front/icons/apple-icon-180x180.png')); ?>">
	<link rel="apple-touch-icon" sizes="57x57" href="<?php echo e(URL::asset('front/icons/android-icon-192x192.png')); ?>">
	<link rel="apple-touch-icon" sizes="57x57" href="<?php echo e(URL::asset('front/icons/favicon-32x32.png')); ?>">
	<link rel="apple-touch-icon" sizes="57x57" href="<?php echo e(URL::asset('front/icons/favicon-96x96.png')); ?>">
	<link rel="apple-touch-icon" sizes="57x57" href="<?php echo e(URL::asset('front/icons/favicon-16x16.png')); ?>">
	<link rel="icon" type="image/png" href="<?php echo e(URL::asset('front/icons/favicon-16x16.png')); ?>">
	<link rel="manifest" href="<?php echo e(URL::asset('front/icons/manifest.json')); ?>">
	<meta name="msapplication-TileColor" content="#ffffff">
	<meta name="msapplication-TileImage" content="<?php echo e(URL::asset('front/icons/ms-icon-144x144.png')); ?>">
	<meta name="theme-color" content="#ffffff">
</head>
<body class="page-404-full-page">
<div class="row">
	<div class="col-md-12 page-404">
		<div class="number">
			404
		</div>
		<div class="details">
			<h3>Page Not Found.</h3>
			<p>
				Looks like the page you're trying to visit doesn't exist. Please check the URL and try your luck again.<br/>
				<a class="btn blue" href="<?php echo e(URL::to('/')); ?>">
					<i class="fa fa-arrow-left"></i> Return Home </a>
			</p>

		</div>
	</div>
</div>
<!--[if lt IE 9]>
<?php echo HTML::script("admin/global/plugins/respond.min.js"); ?>

<?php echo HTML::script("admin/global/plugins/excanvas.min.js"); ?>

<![endif]-->
<?php echo HTML::script("admin/global/plugins/jquery.min.js"); ?>

<?php echo HTML::script("admin/global/plugins/jquery-migrate.min.js"); ?>

<?php echo HTML::script("admin/global/plugins/jquery-ui/jquery-ui.min.js"); ?>

<?php echo HTML::script("admin/global/plugins/bootstrap/js/bootstrap.min.js"); ?>

<?php echo HTML::script("admin/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js"); ?>

<?php echo HTML::script("admin/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js"); ?>

<?php echo HTML::script("admin/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js"); ?>

<?php echo HTML::script("admin/global/plugins/jquery.blockui.min.js"); ?>

<?php echo HTML::script("admin/global/plugins/jquery.cokie.min.js"); ?>

<?php echo HTML::script("admin/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js"); ?>

<!-- END CORE PLUGINS -->
<?php echo HTML::script("admin/global/scripts/metronic.js"); ?>

<?php echo HTML::script("admin/admin/layout4/scripts/layout.js"); ?>

<?php echo HTML::script("admin/admin/layout4/scripts/demo.js"); ?>

<script>
    jQuery(document).ready(function() {
        Metronic.init(); // init metronic core components
        Layout.init(); // init current layout
        Demo.init(); // init demo features
    });
</script>
<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>
<?php /**PATH /var/www/stage.hamrofitness.com/public_html/resources/views/errors/404.blade.php ENDPATH**/ ?>