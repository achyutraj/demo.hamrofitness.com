<div class="row">
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet light ">
            <div class="portlet-title">
                <div class="caption font-dark">
                    <i class=" fa <?php echo e($gymSettings->currency->symbol); ?> font-red"></i>
                    <span class="caption-subject font-red bold uppercase"> Membership Payments</span>
                </div>
            </div>
            <div class="portlet-body">
                <table class="table table-striped table-bordered table-hover order-column"
                       id="mem-payments" style="width: 100%">
                    <thead>
                    <tr>
                        <th class="max-desktop"> Paid</th>
                        <th class="desktop"> Payment For</th>
                        <th class="desktop"> Source</th>
                        <th class="desktop"> Payment Date</th>
                        <th class="desktop"> Payment ID</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
        <div class="portlet light ">
            <div class="portlet-title">
                <div class="caption font-dark">
                    <i class=" fa <?php echo e($gymSettings->currency->symbol); ?> font-red"></i>
                    <span class="caption-subject font-red bold uppercase"> Product Payments</span>
                </div>
            </div>
            <div class="portlet-body">
                <table class="table table-striped table-bordered table-hover order-column"
                       id="product-payments" style="width: 100%">
                    <thead>
                    <tr>
                        <th class="max-desktop"> Paid</th>
                        <th class="desktop"> Payment For</th>
                        <th class="desktop"> Quantity</th>
                        <th class="desktop"> Source</th>
                        <th class="desktop"> Payment Date</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
        <div class="portlet light ">
            <div class="portlet-title">
                <div class="caption font-dark">
                    <i class=" fa <?php echo e($gymSettings->currency->symbol); ?> font-red"></i>
                    <span class="caption-subject font-red bold uppercase"> Locker Payments</span>
                </div>
            </div>
            <div class="portlet-body">
                <table class="table table-striped table-bordered table-hover order-column"
                       id="locker-payments" style="width: 100%">
                    <thead>
                    <tr>
                        <th class="max-desktop"> Paid</th>
                        <th class="desktop"> Payment For</th>
                        <th class="desktop"> Source</th>
                        <th class="desktop"> Payment Date</th>
                        <th class="desktop"> Payment ID</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
        <!-- END EXAMPLE TABLE PORTLET-->
    </div>
</div>
<?php /**PATH /var/www/stage.hamrofitness.com/public_html/resources/views/gym-admin/gymclients/payment.blade.php ENDPATH**/ ?>