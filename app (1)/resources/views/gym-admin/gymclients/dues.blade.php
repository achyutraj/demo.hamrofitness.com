<div class="row">
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet light ">
            <div class="portlet-title">
                <div class="caption font-dark">
                    <i class=" fa {{ $gymSettings->currency->symbol }} font-red"></i>
                    <span class="caption-subject font-red bold uppercase"> Membership Dues</span>
                </div>
            </div>
            <div class="portlet-body">
                <table class="table table-striped table-bordered table-hover order-column table-100"
                       id="mem-dues" style="width: 100%">
                    <thead>
                    <tr>
                        <th class="max-desktop"> Remaining</th>
                        <th class="desktop"> Payment To</th>
                        <th class="desktop"> Purchased At</th>
                        <th class="desktop"> Due Payment Date</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
        <div class="portlet light ">
            <div class="portlet-title">
                <div class="caption font-dark">
                    <i class=" fa {{ $gymSettings->currency->symbol }} font-red"></i>
                    <span class="caption-subject font-red bold uppercase"> Product Dues</span>
                </div>
            </div>
            <div class="portlet-body">
                <table class="table table-striped table-bordered table-hover order-column table-100"
                       id="product-dues" style="width: 100%">
                    <thead>
                    <tr>
                        <th class="max-desktop"> Remaining</th>
                        <th class="desktop"> Payment To</th>
                        <th class="desktop"> Purchased At</th>
                        <th class="desktop"> Due Payment Date</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
        <div class="portlet light ">
            <div class="portlet-title">
                <div class="caption font-dark">
                    <i class=" fa {{ $gymSettings->currency->symbol }} font-red"></i>
                    <span class="caption-subject font-red bold uppercase"> Locker Dues</span>
                </div>
            </div>
            <div class="portlet-body">
                <table class="table table-striped table-bordered table-hover order-column table-100"
                       id="locker-dues" style="width: 100%">
                    <thead>
                    <tr>
                        <th class="max-desktop"> Remaining</th>
                        <th class="desktop"> Payment To</th>
                        <th class="desktop"> Purchased At</th>
                        <th class="desktop"> Due Payment Date</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
        <!-- END EXAMPLE TABLE PORTLET-->
    </div>
</div>
