<!DOCTYPE html>
<html lang="en">

<?php echo $__env->make("layouts.gym-merchant.headmaterial", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<body class="page-container-bg-solid page-md wysihtml5-supported" style="margin-bottom: 30px;">
<!-- Preloader -->
<div id="wrapper">
    <!-- Navigation -->
<?php echo $__env->make('layouts.gym-merchant.navbar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<!-- Left navbar-header -->
<?php echo $__env->make('gym-admin.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<!-- Left navbar-header end -->

    <!-- Page Content -->
    <div id="page-wrapper">
        <div class="page-content">
            <?php echo $__env->yieldContent('content'); ?>
        </div>
        <!-- /.container-fluid -->
        <footer class="footer text-center"> <?php echo e(\Carbon\Carbon::now()->format('Y')); ?> &copy; HamroFitness</footer>
    </div>
    <!-- /#page-wrapper -->
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

<div class="modal fade" id="wishModal" tabindex="-1" role="dialog" aria-labelledby="wishModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="wishModal">Today Birthdays & Anniversaries</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <ul class="feeds">
                    <?php $__currentLoopData = $todayBirthDay; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $today): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li>
                            <div class="col1">
                                <div class="cont">
                                    <div class="cont-col1">
                                        <div class="label label-sm label-success">
                                            <i class="fa fa-birthday-cake"></i>
                                        </div>
                                    </div>
                                    <div class="cont-col2">
                                        <div class="desc"> <?php echo e($today->fullName); ?>'s birthday </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php $__currentLoopData = $todayAnniversary; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $today): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li>
                            <div class="col1">
                                <div class="cont">
                                    <div class="cont-col1">
                                        <div class="label label-sm label-danger">
                                            <i class="fa fa-heart-o"></i>
                                        </div>
                                    </div>
                                    <div class="cont-col2">
                                        <div class="desc"> <?php echo e($today->fullName); ?>'s anniversary </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="expireSubscriptionModal" tabindex="-1" role="dialog" aria-labelledby="expireSubscriptionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="expireSubscriptionModal">Today Subscription Expire </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <ul class="feeds">
                    <?php $__currentLoopData = $todayExpireSubscription; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $today): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li>
                            <div class="col1">
                                <div class="cont">
                                    <div class="cont-col1">
                                        <div class="label label-sm label-success">
                                            <i class="fa fa-user"></i>
                                        </div>
                                    </div>
                                    <div class="cont-col2">
                                        <div class="desc"> <?php echo e($today->client->fullName); ?>'s <?php echo e($today->membership->title); ?> has been expired. </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<?php echo $__env->yieldContent('modal'); ?>
<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
<!-- BEGIN CORE PLUGINS -->
<!--[if lt IE 9]>
<?php echo HTML::script("admin/global/plugins/respond.min.js"); ?>

<?php echo HTML::script("admin/global/plugins/excanvas.min.js"); ?>

<![endif]-->
<?php echo HTML::script("admin/global/plugins/jquery.min.js"); ?>

<?php echo HTML::script("admin/global/plugins/jquery-migrate.min.js"); ?>

<!-- IMPORTANT! Load jquery-ui-1.10.3.custom.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
<?php echo HTML::script("admin/global/plugins/jquery-ui/jquery-ui-1.10.3.custom.min.js"); ?>

<?php echo HTML::script("admin/global/plugins/bootstrap/js/bootstrap.min.js"); ?>

<?php echo HTML::script("admin/global/plugins/js.cookie.min.js"); ?>




<?php echo HTML::script("admin/global/plugins/jquery.blockui.min.js"); ?>

<?php echo HTML::script("admin/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js"); ?>



<?php echo HTML::script("admin/global/plugins/jquery-idle-timeout/jquery.idletimeout.js"); ?>

<?php echo HTML::script("admin/global/plugins/jquery-idle-timeout/jquery.idletimer.js"); ?>



<?php echo HTML::script("admin/global/plugins/jquery.cokie.min.js"); ?>

<?php echo HTML::script("admin/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js"); ?>

<?php echo HTML::script("admin/global/plugins/select2/select2.min.js"); ?>

<!-- END CORE PLUGINS -->
<?php echo HTML::script("admin/global/scripts/app.js"); ?>

<?php echo HTML::script('admin/global/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js'); ?>

<?php echo HTML::script('admin/pages/scripts/components-bootstrap-maxlength.min.js'); ?>


<?php echo HTML::script("admin/global/scripts/metronic.js"); ?>

<?php echo HTML::script("admin/admin/layout3/scripts/layout.min.js"); ?>

<?php echo HTML::script("admin/admin/layout3/scripts/demo.js"); ?>

<?php echo HTML::script("admin/global/plugins/froiden-helper/helper.js?v=1.2"); ?>




<!-- Menu Plugin JavaScript -->
<?php echo HTML::script('fitsigma_customer/bower_components/sidebar-nav/dist/sidebar-nav.min.js'); ?>

<!--slimscroll JavaScript -->
<?php echo HTML::script('fitsigma_customer/js/jquery.slimscroll.js'); ?>

<!--Wave Effects -->
<?php echo HTML::script('fitsigma_customer/js/waves.js'); ?>

<!--Counter js -->
<?php echo HTML::script('fitsigma_customer/bower_components/waypoints/lib/jquery.waypoints.js'); ?>

<?php echo HTML::script('fitsigma_customer/bower_components/counterup/jquery.counterup.min.js'); ?>


<!-- Custom Theme JavaScript -->
<?php echo HTML::script('fitsigma_customer/js/custom.min.js'); ?>


<?php echo $__env->yieldContent("footer"); ?>


<script>
    var sameOrigin;
    try {
        sameOrigin = window.parent.location.host == window.location.host;
    }
    catch (e) {
        sameOrigin = false;
    }


    var UIIdleTimeout = function () {

        return {

            //main function to initiate the module
            init: function () {

                // cache a reference to the countdown element so we don't have to query the DOM for it on each ping.
                var $countdown;

                $('body').append('<div class="modal fade" id="idle-timeout-dialog" data-backdrop="static"><div class="modal-dialog modal-small"><div class="modal-content"><div class="modal-header"><h4 class="modal-title">Your session is about to expire.</h4></div><div class="modal-body"><p><i class="fa fa-warning"></i> You session will be locked in <span id="idle-timeout-counter"></span> seconds.</p><p>Do you want to continue your session?</p></div><div class="modal-footer"><button id="idle-timeout-dialog-logout" type="button" class="btn btn-default">No, Logout</button><button id="idle-timeout-dialog-keepalive" type="button" class="btn btn-primary" data-dismiss="modal">Yes, Keep Working</button></div></div></div></div>');

                // start the idle timer plugin
                $.idleTimeout('#idle-timeout-dialog', '.modal-content button:last', {
                    idleAfter: '<?php echo e((!is_null($gymSettings->idle_time))? $gymSettings->idle_time : 600); ?>', // 10 minutes
                    timeout: 60000, //60 seconds to timeout
                    pollingInterval: 5, // 5 seconds
                    keepAliveURL: '<?php echo e(route('merchant.keep-alive')); ?>',
                    serverResponseEquals: 'OK',
                    AJAXTimeout: 10000,
                    onTimeout: function () {
                        window.location = "<?php echo e(url('lock-screen')); ?>";
                    },
                    onIdle: function () {
                        $('#idle-timeout-dialog').modal('show');
                        $countdown = $('#idle-timeout-counter');

                        $('#idle-timeout-dialog-keepalive').on('click', function () {
                            $('#idle-timeout-dialog').modal('hide');
                        });

                        $('#idle-timeout-dialog-logout').on('click', function () {
                            $('#idle-timeout-dialog').modal('hide');
                            $.idleTimeout.options.onTimeout.call(this);
                        });
                    },
                    onCountdown: function (counter) {
                        $countdown.html(counter); // update the counter
                    }
                });

            }

        };

    }();
</script>


<script>
    jQuery(document).ready(function () {

        Metronic.init(); // init metronic core components
//        Layout.init(); // init current layout
        Demo.init(); // init demo features

        <?php if($isDesktop): ?>
        UIIdleTimeout.init();
        <?php endif; ?>

    });
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>
<script>
    function clear_form_elements(id_name) {
        jQuery("#" + id_name).find(':input').each(function () {
            switch (this.type) {
                case 'password':
                case 'text':
                case 'textarea':
                case 'file':
                case 'select-one':
                case 'select-multiple':
                    jQuery(this).val('');
                    break;
                case 'checkbox':
                case 'radio':
                    this.checked = false;
            }
        });
    }
</script>

<script>
    // $('.user-pro ul').removeClass('in');

    $('.mark-read').click(function () {
        var url = '<?php echo e(URL::route("gym-admin.dashboard.markRead")); ?>';

        $.easyAjax({
            url: url,
            type: 'POST',
            data: {_token: "<?php echo e(csrf_token()); ?>"},
            success: function (response) {
                $('.merchant-notif-count').remove();
                $('.merchant-notifications').empty();
                        <?php echo e($unreadNotifications = 0); ?>

                $('.merchant-notif-count-title').html( "<div class=\"drop-title\">You have <?php echo e($unreadNotifications); ?> new notifications</div>" );
            }
        })

    });

    $('#quick-menu-link').click(function () {
        $('#quick-menu-modal').modal('show');
    });

</script>

<?php echo $__env->yieldPushContent('after-scripts'); ?>
<script>
    function changeBranch(id) {
        $.easyAjax({
            type: 'POST',
            url: '<?php echo e(route('gym-admin.superadmin.setBusinessId')); ?>',
            data: {'businessId': id},
            success: function (response) {
                if (response.success == true) {
                    window.location.reload();
                }
            }
        });
    }
</script>
</body>
</html>
<?php /**PATH /var/www/stage.hamrofitness.com/public_html/resources/views/layouts/gym-merchant/gymbasic.blade.php ENDPATH**/ ?>