<div class="row">
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet light ">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-badge font-red"></i>
                    <span class="caption-subject font-red sbold uppercase">Memberships</span>
                </div>
                <div class="actions">
                    <div class="btn-group">
                        <a href="#"
                           class="btn sbold">Total: <?php echo e($total_purchases); ?>

                        </a>
                        <a id="sample_editable_1_new"
                           href="<?php echo e(route('gym-admin.client-purchase.user-create', $client->id)); ?>"
                           class="btn sbold dark"> Add New
                            <i class="fa fa-plus"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="portlet-body">
                <table class="table table-striped table-bordered table-hover order-column"
                       id="memberships" style="width: 100%">
                    <thead>
                    <tr>
                        <th class="max-desktop"> Membership</th>
                        <th class="desktop"> Amount</th>
                        <th class="desktop"> Status</th>
                        <th class="desktop"> Start Date</th>
                        <th class="desktop"> Expire Date</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
        <div class="portlet light ">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-key font-red"></i>
                    <span class="caption-subject font-red sbold uppercase">Reservations</span>
                </div>
                <div class="actions">
                    <div class="btn-group">
                        <a href="#"
                           class="btn sbold">Total: <?php echo e($total_reservations); ?>

                        </a>
                        <a id="sample_editable_1_new"
                           href="<?php echo e(route('gym-admin.reservations.user-create', $client->id)); ?>"
                           class="btn sbold dark"> Add New
                            <i class="fa fa-plus"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="portlet-body">
                <table class="table table-striped table-bordered table-hover order-column"
                       id="reservations" style="width: 100%">
                    <thead>
                    <tr>
                        <th class="max-desktop"> LockerNO. </th>
                        <th class="desktop"> Amount</th>
                        <th class="desktop"> Status</th>
                        <th class="desktop"> Start Date</th>
                        <th class="desktop"> Expire Date</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
        <!-- END EXAMPLE TABLE PORTLET-->
    </div>
</div><?php /**PATH /var/www/stage.hamrofitness.com/public_html/resources/views/gym-admin/gymclients/membership.blade.php ENDPATH**/ ?>