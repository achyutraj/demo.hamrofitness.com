@extends('layouts.gym-merchant.gymbasic')

@section('CSS')
    <link rel="stylesheet" href="{{ asset("admin/global/plugins/ladda/ladda-themeless.min.css") }}">
    <link rel="stylesheet" href="{{ asset("admin/global/plugins/datatables/datatables.min.css") }}">
    <link rel="stylesheet" href="{{ asset("admin/global/plugins/bootstrap-select/css/bootstrap-select.min.css") }}">
    <link rel="stylesheet" href="{{ asset("admin/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css") }}">
@stop

@section('content')
    <div class="container-fluid">
        <!-- BEGIN PAGE BREADCRUMBS -->
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <a href="{{route('gym-admin.dashboard.index')}}">Home</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>Target Report</span>
            </li>
        </ul>
        <!-- END PAGE BREADCRUMBS -->
        <!-- BEGIN PAGE CONTENT INNER -->
        <div class="page-content-inner">
            <div class="row">
                <div class="col-md-12">
                    <div class="m-heading-1 border-green m-bordered">
                        <h3>Target Report</h3>
                        <p>Well you had made some targets and we help you to track them. </p>
                        <p>This section provides the reports on the target that you have made.</p>
                        <ul>
                            <li>Memberships</li>
                            <li>Sale</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="portlet light portlet-fit">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="icon-target font-red"></i><span class="caption-subject font-red bold uppercase">Select Target</span>
                    </div>


                </div>
                <div class="portlet-body">
                    <div class="row">
                        <div class="col-md-5">
                            {{ html()->form->open(['id'=>'createTargetReport','class'=>'ajax-form']) !!}
                            <div class="form-body">
                                <div class="form-group form-md-line-input ">
                                    <select class="bs-select form-control targetData" data-live-search="true"
                                            data-size="8" name="target" id="target">
                                        @if(count($targets)>0)
                                            @foreach($targets as $target)
                                                <option value="{{$target->id}}">{{$target->title}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <label for="title">Select Target</label>
                                    <span class="help-block"></span>
                                </div>
                                <div class="form-actions" style="margin-top: 70px">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <button type="button" class="btn dark mt-ladda-btn ladda-button"
                                                    data-style="zoom-in" id="save-form">
                                                <span class="ladda-label"><i class="icon-arrow-up"></i> Submit</span>
                                            </button>
                                            <button type="reset" class="btn default">Reset</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{ html()->form->close() !!}
                        </div>
                        <div class="col-md-4" id="easyStats" style="display: none">
                            <div class="easy-pie-chart">
                                <div class="number transactions" id="users_percent" data-percent="0">
                                    <span id="spanData"></span>%
                                </div>
                                <a class="title" href="javascript:;" id="graphTitle">

                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row" id="targetDataTable">
                <div class="col-md-12">
                    <!-- BEGIN EXAMPLE TABLE PORTLET-->
                    <div class="portlet light ">
                        <div class="portlet-title">
                            <div class="row">
                                <div class="col-md-6 col-sm-12" style="padding:10px;">
                                    <div class="caption font-dark">
                                        <i class="icon-target font-red"></i>
                                        <span class="caption-subject font-red bold uppercase"> Membership Target Details</span>
                                    </div>
                                </div>

                                <div class="col-md-6 col-sm-12" style="text-align:right;">
                                    <div class="caption font-dark">
                                        <a class="btn btn-success downloadTarget">
                                            <span class="caption-subject font-white bold uppercase"> PDF</span>
                                            <i class="fa fa-download"></i>
                                        </a>
                                        <a class="btn btn-info downloadExcelTarget">
                                            <span class="caption-subject font-white bold uppercase"> Excel</span>
                                            <i class="fa fa-download"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <table class="table-100 table table-striped table-bordered table-hover order-column responsive"
                                   id="targets_table">
                                <thead>
                                <tr>
                                    <th class="all"> Name</th>
                                    <th class="min-tablet"> Membership</th>
                                    <th class="min-tablet"> Payment Amount</th>
                                    <th class="min-tablet"> Date</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <!-- END EXAMPLE TABLE PORTLET-->
                </div>
            </div>
            <div class="row" id="targetDataTable1">
                <div class="col-md-12">
                    <!-- BEGIN EXAMPLE TABLE PORTLET-->
                    <div class="portlet light ">
                        <div class="portlet-title">
                            <div class="row">
                                <div class="col-md-6 col-sm-12" style="padding:10px;">
                                    <div class="caption font-dark">
                                        <i class="icon-target font-red"></i>
                                        <span class="caption-subject font-red bold uppercase"> Product Target Details</span>
                                    </div>
                                </div>

                                <div class="col-md-6 col-sm-12" style="text-align:right;">
                                    <div class="caption font-dark">
                                        <a class="btn btn-success downloadTarget1">
                                            <span class="caption-subject font-white bold uppercase"> PDF</span>
                                            <i class="fa fa-download"></i>
                                        </a>
                                        <a class="btn btn-info downloadExcelTarget1">
                                            <span class="caption-subject font-white bold uppercase"> Excel</span>
                                            <i class="fa fa-download"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <table class="table-100 table table-striped table-bordered table-hover order-column responsive"
                                   id="targets_table1">
                                <thead>
                                <tr>
                                    <th class="all"> Name</th>
                                    <th class="min-tablet"> Product</th>
                                    <th class="min-tablet"> Payment Amount</th>
                                    <th class="min-tablet"> Date</th>
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
@stop

@section('footer')
    <script src="{{ asset("admin/global/plugins/jquery-easypiechart/jquery.easypiechart.min.js") }}"></script>
    <script src="{{ asset("admin/global/plugins/bootstrap-select/js/bootstrap-select.min.js") }}"></script>
    <script src="{{ asset("admin/pages/scripts/components-bootstrap-select.min.js") }}"></script>

    <script src="{{ asset("admin/global/scripts/datatable.js") }}"></script>
    <script src="{{ asset("admin/pages/scripts/table-datatables-managed.js") }}"></script>
    <script src="{{ asset("admin/global/plugins/datatables/datatables.min.js") }}"></script>
    <script src="{{ asset("admin/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js") }}"></script>

    <script>
        $('#save-form').click(function () {
            var type = $('#target').val();
            if (type == '') {
                $.showToastr('Please Select a Type', 'error');
            } else {
                $.easyAjax({
                    url: "{{route('gym-admin.target-report.store')}}",
                    container: '#createTargetReport',
                    type: "POST",
                    data: $('#createTargetReport').serialize(),
                    success: function (res) {
                        if (res.status == 'success') {
                            $('#users_percent').attr('data-percent', res.data.percent);
                            $('#spanData').html(res.data.percent);
                            $('#graphTitle').html(' ' + res.data.report + ' &nbsp;<i class="icon-arrow-right"></i>');
                            $('#easyStats').css('display', 'block');
                            $('#targetDataTable').css('display', 'block');
                            $('#targetDataTable1').css('display', 'block');
                            load_data_table(res.data.target_id);
                            load_data_table1(res.data.target_id);
                            load_data_target(res.data.target_id);

                            $('.easy-pie-chart .number.transactions').easyPieChart({
                                animate: 3000,
                                size: 150,
                                lineWidth: 10,
                                barColor: '#e43a45'
                            });
                        }
                    }
                });
            }

        });
    </script>
    <script>
        function load_data_target(id) {
            $('.downloadTarget').on('click', function () {
                window.location = 'target-report/download/' + id + '/membership';
            });
            $('.downloadExcelTarget').on('click', function () {
                window.location = 'target-report/download/excel/' + id + '/membership';
            });
            $('.downloadTarget1').on('click', function () {
                window.location = 'target-report/download/' + id + '/product';
            });
            $('.downloadExcelTarget1').on('click', function () {
                window.location = 'target-report/download/excel/' + id + '/product';
            });
        }


        function load_data_table(id) {
            var table = $('#targets_table');
            var url = "{{route('gym-admin.target-report.ajax-create',['#id','membership'])}}";
            url = url.replace('#id', id);

            // begin first table
            table.DataTable({
                "sAjaxSource": url,
                bDestroy:true,
                "aoColumns": [
                    {data: 'gym_clients.first_name', name: 'gym_clients.first_name'},
                    {data: 'gym_memberships.title', name: 'gym_memberships.title'},
                    {data: 'payment_amount', name: 'payment_amount'},
                    {data: 'payment_date', name: 'payment_date'},
                ]
            });
        }

        function load_data_table1(id) {
            var table = $('#targets_table1');
            var url = "{{route('gym-admin.target-report.ajax-create',['#id','product'])}}";
            url = url.replace('#id', id);
            // begin first table
            table.DataTable({
                "sAjaxSource": url,
                bDestroy:true,
                "aoColumns": [
                    {data: 'gym_clients.first_name', name: 'gym_clients.first_name'},
                    {data: 'product_name', name: 'product_name'},
                    {data: 'payment_amount', name: 'payment_amount'},
                    {data: 'payment_date', name: 'payment_date'},
                ],
            });
        }

    </script>
@stop
