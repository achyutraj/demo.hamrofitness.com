<?php $__env->startSection('CSS'); ?>
    <?php echo HTML::style('admin/global/plugins/datatables/datatables.min.css'); ?>

    <?php echo HTML::style('admin/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>

    <?php echo HTML::style('admin/global/plugins/ladda/ladda-themeless.min.css'); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-fluid"  >
        <!-- BEGIN PAGE BREADCRUMBS -->
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <a href="<?php echo e(route('gym-admin.dashboard.index')); ?>">Home</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>Membership</span>
            </li>
        </ul>
        <!-- END PAGE BREADCRUMBS -->
        <!-- BEGIN PAGE CONTENT INNER -->
        <div class="page-content-inner">
            <div class="row">
            <?php if(session()->has('message')): ?>
                <div class="alert alert-message alert-success">
                    <?php echo e(session()->get('message')); ?>

                </div>
            <?php endif; ?>
                <div class="col-md-12">

                    <div class="portlet light portlet-fit">
                        <div class="portlet-title col-xs-12">
                            <div class="caption col-sm-9 col-xs-12">
                                <i class="icon-badge font-red"></i><span class="caption-subject font-red bold uppercase">Membership Plans</span>
                            </div>

                            <div class="actions col-sm-3 col-xs-12">

                                    <a href="<?php echo e(route('gym-admin.membership-plans.create')); ?>" class="btn dark"> add <span class="hidden-xs">membership</span>
                                        <i class="fa fa-plus"></i>
                                    </a>

                            </div>

                        </div>
                        <div class="portlet-body">

                            <div class="row">
                            <div class="col-md-12 col-xs-12">

                                <div class="portlet-body">
                                    <div class="mt-element-list">
                                        <div class="mt-list-head list-news ext-1 font-white bg-grey-gallery">
                                            <div class="list-head-title-container">
                                                <h3 class="list-title">Memberships</h3>
                                            </div>
                                            <div class="list-count pull-right bg-red plans"><?php echo e(count($memberships)); ?></div>
                                        </div>
                                        <div class="mt-list-container list-simple col-md-12">
                                            <ul class="col-xs-12">
                                                <?php $__currentLoopData = $memberships; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <li class="mt-list-item col-md-12" id="mem-<?php echo e($mem->id); ?>">
                                                    <div class="row">
                                                        <div class="col-sm-5 col-md-4 list-item-content">
                                                            <?php echo e(ucwords($mem->title)); ?><br>
                                                            Duration: <?php echo e($mem->duration); ?> <?php echo e($mem->duration_type); ?>

                                                        </div>
                                                        <div class="col-sm-4 col-md-2 col-sm-2">
                                                            <?php echo e($gymSettings->currency->acronym); ?> <?php echo e($mem->price); ?>

                                                        </div>
                                                        <div class="col-sm-2 col-md-1 hidden-xs">
                                                            <?php if($mem->status == "active"): ?>
                                                                <span class="label label-success uppercase"> <?php echo e($mem->status); ?> </span>
                                                            <?php else: ?>
                                                                <span class="label label-danger uppercase"> <?php echo e($mem->status); ?> </span>
                                                            <?php endif; ?>
                                                        </div>
                                                        <div class="col-sm-2 col-md-3 hidden-xs">Total Subscription
                                                            <span class="label label-info uppercase"> <?php echo e($mem->subscriptions_count ?? 0); ?></span>
                                                        </div>
                                                        <div class="col-sm-3 col-md-2">
                                                            <div class="btn-group">
                                                                <button class="btn btn-xs blue dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="true"><i class="fa fa-gears"></i><span class="hidden-xs">ACTION</span>
                                                                    <i class="fa fa-angle-down"></i>
                                                                </button>
                                                                <ul class="dropdown-menu  pull-right" role="menu">
                                                                    <li>
                                                                        <a href="<?php echo e(route('gym-admin.membership-plans.edit',[$mem->id])); ?>"><i class="fa fa-edit"></i> Edit </a>
                                                                    </li>
                                                                    <li>
                                                                        <a href="<?php echo e(route('gym-admin.membership-plans.history',[$mem->id])); ?>"><i class="fa fa-history"></i> View History </a>
                                                                    </li>
                                                                    <li>
                                                                        <a class="delete-button" data-pk="<?php echo e($mem->business_category_id); ?>" data-membership-id="<?php echo e($mem->id); ?>" href="javascript:;"><i class="fa fa-trash"></i> Delete </a>
                                                                    </li>

                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            </div>

                        </div>
                    </div>


                </div>
            </div>
        </div>
        <!-- END PAGE CONTENT INNER -->
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer'); ?>
<?php echo HTML::script('admin/global/plugins/bootbox/bootbox.min.js'); ?>

<script>
    $(function(){
            setTimeout(function() {
                $('.alert-message').slideUp();
            }, 3000);
        });
</script>
<script>
    var UIBootbox = function () {
        var o = function () {
            $(".delete-button").click(function () {
                var memID = $(this).data('membership-id');
                var categoryId = $(this).data('pk');

                bootbox.confirm({
                    message: "Do you want to delete this membership?",
                    buttons: {
                        confirm: {
                            label: "Yes",
                            className: "btn-primary"
                        }
                    },
                    callback: function(result){
                        if(result){

                            var url = "<?php echo e(route('gym-admin.membership-plans.destroy',':id')); ?>";
                            url = url.replace(':id',memID);

                            $.easyAjax({
                                url: url,
                                type: "DELETE",
                                data: {
                                    memID: memID,
                                    _token: '<?php echo e(csrf_token()); ?>'
                                },
                                success: function(){
                                    $('#mem-'+memID).fadeOut();
                                    var val = $('.plans-'+categoryId).html();
                                    var plans = val - 1;
                                    $('.plans-'+categoryId).html(plans);
                                }
                            });
                        }
                        else {
                            console.log('cancel');
                        }
                    }
                })

            })
        };
        return {
            init: function () {
                o()
            }
        }
    }();
    jQuery(document).ready(function () {
        UIBootbox.init()
    });
</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.gym-merchant.gymbasic', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/stage.hamrofitness.com/public_html/resources/views/gym-admin/membership/index.blade.php ENDPATH**/ ?>