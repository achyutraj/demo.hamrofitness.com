

<?php $__env->startSection('CSS'); ?>
    <?php echo HTML::style('admin/global/plugins/bootstrap-daterangepicker/daterangepicker.css'); ?>

    <?php echo HTML::style('admin/global/plugins/bootstrap-select/css/bootstrap-select.min.css'); ?>

    <?php echo HTML::style('admin/global/plugins/ladda/ladda-themeless.min.css'); ?>

    <?php echo HTML::style('admin/global/plugins/datatables/datatables.min.css'); ?>

    <?php echo HTML::style('admin/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
        <!-- BEGIN PAGE BREADCRUMBS -->
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <a href="<?php echo e(route('gym-admin.dashboard.index')); ?>">Home</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>Finance Report</span>
            </li>
        </ul>
        <!-- END PAGE BREADCRUMBS -->
        <!-- BEGIN PAGE CONTENT INNER -->
        <div class="page-content-inner">

            <div class="row">
                <div class="col-md-12">
                    <div class="m-heading-1 border-green m-bordered">
                        <h3>Finance Report</h3>
                        <p>This report section provides you the report on your financial statements : </p>
                        <ul>
                            <li>Received payments.</li>
                            <li>Due payments.</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="portlet light portlet-fit">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa <?php echo e($gymSettings->currency->symbol); ?> font-red"></i><span class="caption-subject font-red bold uppercase">Finance Report</span></div>
                </div>
                <div class="portlet-body">
                    <div class="row">
                        <div class="col-md-offset-2 col-md-5">
                            <?php echo Form::open(['id'=>'createFinanceReport','class'=>'ajax-form']); ?>

                            <div class="form-body">
                                <div class="form-group form-md-line-input ">
                                    <select  class="bs-select form-control" data-live-search="true" data-size="8" name="type" id="type">
                                        <option value="all">Received Membership Payments</option>
                                        <option value="debtors">Due Membership Payments</option>
                                        <option value="allProduct">Received Product Payments</option>
                                        <option value="dueProducts">Due Product Payments</option>
                                        <option value="lockerPayments">Received Locker Payments</option>
                                        <option value="lockerDues">Due Locker Payments</option>
                                    </select>
                                    <label for="title">Select Type</label>
                                    <span class="help-block"></span>
                                </div>
                                <div class="form-group form-md-line-input" id="payment" >
                                    <select class="bs-select form-control" data-live-search="true" data-size="8" name="payment_source" id="payment_source">
                                        <option value="all">All</option>
                                        <?php $__currentLoopData = $paymentSources; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $source): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($key); ?>"><?php echo e($source['label']); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <label for="title">Select Payment Source</label>
                                    <span class="help-block"></span>
                                </div>
                                <div class="form-group">
                                    <div id="reportrange" class="btn default">
                                        <i class="fa fa-calendar"></i> &nbsp;
                                        <span id="date"><?php echo e(\Carbon\Carbon::now('Asia/Kathmandu')->toFormattedDateString()); ?> - <?php echo e(\Carbon\Carbon::now('Asia/Kathmandu')->toFormattedDateString()); ?> </span>
                                        <b class="fa fa-angle-down"></b>
                                    </div>
                                </div>
                                <div class="form-actions" style="margin-top: 70px">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <button type="button" class="btn dark mt-ladda-btn ladda-button" data-style="zoom-in" id="save-form">
                                                <span class="ladda-label"><i class="icon-arrow-up"></i> Submit</span>
                                            </button>
                                            <button type="reset" class="btn default">Reset</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php echo Form::close(); ?>

                        </div>
                        <div class="col-md-4" id="easyStats" style="display: none;margin-top: 50px;margin-left: 20px">
                            <div class="widget-thumb widget-bg-color-dark-light text-uppercase margin-bottom-20 ">
                                <h4 class="widget-thumb-heading font-white" id="heading"></h4>
                                <div class="widget-thumb-wrap">
                                    <i class="widget-thumb-icon bg-white-opacity fa <?php echo e($gymSettings->currency->symbol); ?>"></i>
                                    <div class="widget-thumb-body">
                                        <span class="widget-thumb-subtitle font-white">Total</span>
                                        <span class="widget-thumb-body-stat counter" id="count" data-counter="counterup" data-value="">0</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row" id="financeAllDataTable" style="display: none" >
                <div class="col-md-12">
                    <!-- BEGIN EXAMPLE TABLE PORTLET-->
                    <div class="portlet light ">
                        <div class="portlet-title">
                            <div class="row">
                                <div class="col-md-6 col-sm-12" style="padding:10px;">
                                    <div class="caption font-dark">
                                        <i class="icon-target font-red"></i>
                                        <span class="caption-subject font-red bold uppercase">Membership Payment Report</span>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12" style="text-align:right;">
                                    <div class="caption font-dark">
                                        <a class="btn btn-success downloadFinance">
                                            <span class="caption-subject font-white bold uppercase"> PDF</span>
                                            <i class="fa fa-download"></i>
                                        </a>
                                        <a class="btn btn-info downloadExcelFinance">
                                            <span class="caption-subject font-white bold uppercase"> Excel</span>
                                            <i class="fa fa-download"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <table class="table table-striped table-bordered table-hover order-column responsive" id="all">
                                <thead>
                                <tr>
                                    <th class="all"> Payer Name </th>
                                    <th class="min-tablet"> Amount </th>
                                    <th class="min-tablet"> Payment Method </th>
                                    <th class="min-tablet"> Date </th>
                                    <th class="min-tablet"> Remarks </th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <!-- END EXAMPLE TABLE PORTLET-->
                </div>
            </div>
            <div class="row" id="financeDebtorsDataTable" style="display: none" >
                <div class="col-md-12">
                    <!-- BEGIN EXAMPLE TABLE PORTLET-->
                    <div class="portlet light ">
                        <div class="portlet-title">
                            <div class="row">
                                <div class="col-md-6 col-sm-12" style="padding:10px;">
                                    <div class="caption font-dark">
                                        <i class="icon-target font-red"></i>
                                        <span class="caption-subject font-red bold uppercase"> Membership Due Report</span>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12" style="text-align:right;">
                                    <div class="caption font-dark">
                                        <a class="btn btn-success downloadFinance">
                                            <span class="caption-subject font-white bold uppercase"> PDF</span>
                                            <i class="fa fa-download"></i>
                                        </a>
                                        <a class="btn btn-info downloadExcelFinance">
                                            <span class="caption-subject font-white bold uppercase"> Excel</span>
                                            <i class="fa fa-download"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <table class="table-100 table table-striped table-bordered table-hover order-column responsive" id="debtors">
                                <thead>
                                <tr>
                                    <th class="all"> Payer Name </th>
                                    <th class="min-tablet"> Paid Amount </th>
                                    <th class="min-tablet"> Remaining </th>
                                    <th class="min-tablet"> Last Payment Date </th>
                                    <th class="min-tablet"> Remarks </th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <!-- END EXAMPLE TABLE PORTLET-->
                </div>
            </div>
            <div class="row" id="financeAllProductDataTable" style="display: none" >
                <div class="col-md-12">
                    <!-- BEGIN EXAMPLE TABLE PORTLET-->
                    <div class="portlet light ">
                        <div class="portlet-title">
                            <div class="row">
                                <div class="col-md-6 col-sm-12" style="padding:10px;">
                                    <div class="caption font-dark">
                                        <i class="icon-target font-red"></i>
                                        <span class="caption-subject font-red bold uppercase"> Product Payment Report</span>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12" style="text-align:right;">
                                    <div class="caption font-dark">
                                        <a class="btn btn-success downloadFinance">
                                            <span class="caption-subject font-white bold uppercase"> PDF</span>
                                            <i class="fa fa-download"></i>
                                        </a>
                                        <a class="btn btn-info downloadExcelFinance">
                                            <span class="caption-subject font-white bold uppercase"> Excel</span>
                                            <i class="fa fa-download"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <table class="table-100 table table-striped table-bordered table-hover order-column responsive" id="allProduct">
                                <thead>
                                <tr>
                                    <th class="all"> Payee </th>
                                    <th class="min-tablet"> Amount </th>
                                    <th class="min-tablet"> Payment Method </th>
                                    <th class="min-tablet"> Date </th>
                                    <th class="min-tablet"> Remarks </th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <!-- END EXAMPLE TABLE PORTLET-->
                </div>
            </div>
            <div class="row" id="financeDebtorsProductDataTable" style="display: none" >
                <div class="col-md-12">
                    <!-- BEGIN EXAMPLE TABLE PORTLET-->
                    <div class="portlet light ">
                        <div class="portlet-title">
                            <div class="row">
                                <div class="col-md-6 col-sm-12" style="padding:10px;">
                                    <div class="caption font-dark">
                                        <i class="icon-target font-red"></i>
                                        <span class="caption-subject font-red bold uppercase"> Product Due Report</span>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12" style="text-align:right;">
                                    <div class="caption font-dark">
                                        <a class="btn btn-success downloadFinance">
                                            <span class="caption-subject font-white bold uppercase"> PDF</span>
                                            <i class="fa fa-download"></i>
                                        </a>
                                        <a class="btn btn-info downloadExcelFinance">
                                            <span class="caption-subject font-white bold uppercase"> Excel</span>
                                            <i class="fa fa-download"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <table class="table-100 table table-striped table-bordered table-hover order-column responsive" id="dueProducts">
                                <thead>
                                <tr>
                                    <th class="all"> Payee </th>
                                    <th class="min-tablet"> Paid Amount </th>
                                    <th class="min-tablet"> Remaining </th>
                                    <th class="min-tablet"> Last Payment Date </th>
                                    <th class="min-tablet"> Customer Type </th>

                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <!-- END EXAMPLE TABLE PORTLET-->
                </div>
            </div>
            <div class="row" id="financeLockerPaymentDataTable" style="display: none" >
                <div class="col-md-12">
                    <!-- BEGIN EXAMPLE TABLE PORTLET-->
                    <div class="portlet light ">
                        <div class="portlet-title">
                            <div class="row">
                                <div class="col-md-6 col-sm-12" style="padding:10px;">
                                    <div class="caption font-dark">
                                        <i class="icon-target font-red"></i>
                                        <span class="caption-subject font-red bold uppercase"> Locker Payment Report</span>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12" style="text-align:right;">
                                    <div class="caption font-dark">
                                        <a class="btn btn-success downloadFinance">
                                            <span class="caption-subject font-white bold uppercase"> PDF</span>
                                            <i class="fa fa-download"></i>
                                        </a>
                                        <a class="btn btn-info downloadExcelFinance">
                                            <span class="caption-subject font-white bold uppercase"> Excel</span>
                                            <i class="fa fa-download"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <table class="table-100 table table-striped table-bordered table-hover order-column responsive" id="lockerPayments">
                                <thead>
                                <tr>
                                    <th class="all"> Payee </th>
                                    <th class="min-tablet"> Amount </th>
                                    <th class="min-tablet"> Payment Method </th>
                                    <th class="min-tablet"> Date </th>
                                    <th class="min-tablet"> Remarks </th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <!-- END EXAMPLE TABLE PORTLET-->
                </div>
            </div>
            <div class="row" id="financeLockerDueDataTable" style="display: none" >
                <div class="col-md-12">
                    <!-- BEGIN EXAMPLE TABLE PORTLET-->
                    <div class="portlet light ">
                        <div class="portlet-title">
                            <div class="row">
                                <div class="col-md-6 col-sm-12" style="padding:10px;">
                                    <div class="caption font-dark">
                                        <i class="icon-target font-red"></i>
                                        <span class="caption-subject font-red bold uppercase"> Locker Due Report</span>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12" style="text-align:right;">
                                    <div class="caption font-dark">
                                        <a class="btn btn-success downloadFinance">
                                            <span class="caption-subject font-white bold uppercase"> PDF</span>
                                            <i class="fa fa-download"></i>
                                        </a>
                                        <a class="btn btn-info downloadExcelFinance">
                                            <span class="caption-subject font-white bold uppercase"> Excel</span>
                                            <i class="fa fa-download"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <table class="table-100 table table-striped table-bordered table-hover order-column responsive" id="lockerDues">
                                <thead>
                                <tr>
                                    <th class="all"> Payee </th>
                                    <th class="min-tablet"> Paid Amount </th>
                                    <th class="min-tablet"> Remaining </th>
                                    <th class="min-tablet"> Last Payment Date </th>
                                    <th class="min-tablet"> Remarks </th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <!-- END EXAMPLE TABLE PORTLET-->
                </div>
            </div>

        </div>
        <!-- END PAGE CONTENT INNER -->
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer'); ?>
    <?php echo HTML::script('admin/global/plugins/bootstrap-daterangepicker/moment.min.js'); ?>

    <?php echo HTML::script('admin/global/plugins/jquery-easypiechart/jquery.easypiechart.min.js'); ?>

    <?php echo HTML::script('admin/global/plugins/bootstrap-daterangepicker/daterangepicker.js'); ?>

    <?php echo HTML::script('admin/global/plugins/counterup/jquery.counterup.js'); ?>

    <?php echo HTML::script('admin/global/plugins/counterup/jquery.waypoints.min.js'); ?>

    <?php echo HTML::script('admin/global/plugins/bootstrap-select/js/bootstrap-select.min.js'); ?>

    <?php echo HTML::script('admin/pages/scripts/components-bootstrap-select.min.js'); ?>

    <?php echo HTML::script('admin/global/scripts/datatable.js'); ?>

    <?php echo HTML::script('admin/pages/scripts/table-datatables-managed.js'); ?>

    <?php echo HTML::script('admin/global/plugins/datatables/datatables.min.js'); ?>

    <?php echo HTML::script('admin/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js'); ?>


    <script>
        $('#type').on('change',function(){
           let type = $(this).val();
            if(type == 'all' || type == 'allProduct' || type == 'lockerPayments' ){
                $('#payment').show();
            }else{
                $('#payment').hide();
            }
        });

        $('#reportrange').daterangepicker({
                    opens: (App.isRTL() ? 'left' : 'right'),
                    startDate: moment(),
                    endDate: moment(),
                    dateLimit: {
                        days: 1000
                    },
                    showDropdowns: true,
                    showWeekNumbers: true,
                    timePicker: false,
                    timePickerIncrement: 1,
                    timePicker12Hour: true,
                    ranges: {
                        'Today': [moment(), moment()],
                        'Yesterday': [moment().subtract('days', 1), moment().subtract('days', 1)],
                        'Last 7 Days': [moment().subtract('days', 6), moment()],
                        'Last 30 Days': [moment().subtract('days', 29), moment()],
                        'This Month': [moment().startOf('month'), moment().endOf('month')],
                        'Last Month': [moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')]
                    },
                    buttonClasses: ['btn'],
                    applyClass: 'green',
                    cancelClass: 'default',
                    format: 'MM/DD/YYYY',
                    separator: ' to ',
                    locale: {
                        applyLabel: 'Apply',
                        fromLabel: 'From',
                        toLabel: 'To',
                        customRangeLabel: 'Custom Range',
                        daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
                        monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                        firstDay: 1
                    }
                },
                function (start, end) {
                    $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
                }
        );
    </script>

    <script>
        $('#save-form').click(function(){
            var dateRange = $('#reportrange span').html();
            var type = $('#type').val();
            var paymentType = $('#payment_source').val();
            if(type == ''){
                $.showToastr('Please Select a Type','error');
            }else {
                $.easyAjax({
                    url: "<?php echo e(route('gym-admin.finance-report.store')); ?>",
                    container:'#createBookingReport',
                    type:"POST",
                    data:{date_range:dateRange,type:type,payment_source:paymentType,_token:'<?php echo e(csrf_token()); ?>'},
                    success:function(res){
                        if(res.status = 'success'){
                            $('#count').attr('data-value',res.total);
                            $('#heading').html(res.report);
                            $('#easyStats').css('display','block');
                            $('#count').attr('data-value',res.total);
                            $('#heading').html(res.report);
                            $('#easyStats').css('display','block');
                            if(res.type == 'all'){
                                $('#financeDebtorsDataTable').css('display','none');
                                $('#financeAllDataTable').css('display','block');
                                $('#financeAllProductDataTable').css('display','none');
                                $('#financeDebtorsProductDataTable').css('display','none');
                                $('#financeLockerPaymentDataTable').css('display','none');
                                $('#financeLockerDueDataTable').css('display','none');
                            }else if(res.type == 'allProduct'){
                                $('#financeDebtorsDataTable').css('display','none');
                                $('#financeAllDataTable').css('display','none');
                                $('#financeAllProductDataTable').css('display','block');
                                $('#financeDebtorsProductDataTable').css('display','none');
                                $('#financeLockerPaymentDataTable').css('display','none');
                                $('#financeLockerDueDataTable').css('display','none');
                            }else if(res.type == 'dueProducts'){
                                $('#financeDebtorsDataTable').css('display','none');
                                $('#financeAllDataTable').css('display','none');
                                $('#financeAllProductDataTable').css('display','none');
                                $('#financeDebtorsProductDataTable').css('display','block');
                                $('#financeLockerPaymentDataTable').css('display','none');
                                $('#financeLockerDueDataTable').css('display','none');
                            }else if(res.type == 'debtors') {
                                $('#financeAllDataTable').css('display','none');
                                $('#financeAllProductDataTable').css('display','none');
                                $('#financeDebtorsProductDataTable').css('display','none');
                                $('#financeDebtorsDataTable').css('display','block');
                                $('#financeLockerPaymentDataTable').css('display','none');
                                $('#financeLockerDueDataTable').css('display','none');
                            }else if(res.type == 'lockerPayments'){
                                $('#financeAllDataTable').css('display','none');
                                $('#financeAllProductDataTable').css('display','none');
                                $('#financeDebtorsProductDataTable').css('display','none');
                                $('#financeDebtorsDataTable').css('display','none');
                                $('#financeLockerPaymentDataTable').css('display','block');
                                $('#financeLockerDueDataTable').css('display','none');
                            }else if(res.type == 'lockerDues'){
                                $('#financeAllDataTable').css('display','none');
                                $('#financeAllProductDataTable').css('display','none');
                                $('#financeDebtorsProductDataTable').css('display','none');
                                $('#financeDebtorsDataTable').css('display','none');
                                $('#financeLockerPaymentDataTable').css('display','none');
                                $('#financeLockerDueDataTable').css('display','block');
                            }
                            load_data_table(res.type,res.start_date,res.end_date,res.paymentType);
                            load_download_data(res.type,res.start_date,res.end_date,res.paymentType);
                            $('.counter').counterUp();
                        }
                    }
                });
            }
        });

    </script>
    <script>
        function load_download_data(id,s,e,paymentType){
            $('.downloadFinance').on('click',function(){
                window.location = 'finance-report/download/'+ id + '/' + s + '/' + e +'/'+paymentType;
            });
            $('.downloadExcelFinance').on('click',function(){
                window.location = 'finance-report/download/excel/'+ id + '/' + s + '/' + e +'/'+paymentType;
            });
        }
    </script>
    <script>
        function load_data_table(id,s,e,paymentType){
            var table = $('#'+id);
            var url = "<?php echo e(route('gym-admin.finance-report.ajax-create',['#id','#s','#e','#paymentType'])); ?>";
            url = url.replace('#id',id);
            url = url.replace('#s',s);
            url = url.replace('#e',e);
            url = url.replace('#paymentType',paymentType);
            // begin first table
            table.dataTable({
                "sAjaxSource": url,
                bDestroy:true,
                "aoColumns": [
                    {data: 'gym_clients.first_name', name: 'gym_clients.first_name'},
                    {data: 'payment_amount', name: 'payment_amount'},
                    {data: 'payment_source', name: 'payment_source'},
                    {data: 'payment_date', name: 'payment_date'},
                    {data: 'remarks', name: 'remarks'},
                ]
            });
        }

    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.gym-merchant.gymbasic', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/stage.hamrofitness.com/public_html/resources/views/gym-admin/reports/finance/index.blade.php ENDPATH**/ ?>