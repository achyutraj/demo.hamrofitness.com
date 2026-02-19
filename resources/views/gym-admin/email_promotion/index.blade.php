@extends('layouts.gym-merchant.gymbasic')

@section('CSS')
    <link rel="stylesheet" href="{{ asset("admin/global/plugins/datatables/datatables.min.css") }}">
    <link rel="stylesheet" href="{{ asset("admin/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css") }}">
@stop

@section('content')
    <div class="container-fluid"      >
        <!-- BEGIN PAGE BREADCRUMBS -->
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <a href="{{route('gym-admin.dashboard.index')}}">Home</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>Email Promotion</span>
            </li>
        </ul>
        <!-- END PAGE BREADCRUMBS -->
        <!-- BEGIN PAGE CONTENT INNER -->
        <div class="page-content-inner">

            <div class="row widget-row">
                <div class="col-md-3">
                    <!-- BEGIN WIDGET THUMB -->
                    <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 ">
                        <h4 class="widget-thumb-heading">Total Emails Sent</h4>
                        <div class="widget-thumb-wrap">
                            <i class="widget-thumb-icon bg-blue icon-paper-plane"></i>
                            <div class="widget-thumb-body">
                                <span class="widget-thumb-subtitle">Count</span>
                                <span class="widget-thumb-body-stat" data-counter="counterup" data-value="{{ $emailsTotal }}">0</span>
                            </div>
                        </div>
                    </div>
                    <!-- END WIDGET THUMB -->
                </div>
                <div class="col-md-3">
                    <!-- BEGIN WIDGET THUMB -->
                    <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 ">
                        <h4 class="widget-thumb-heading">Total Sent Campaigns</h4>
                        <div class="widget-thumb-wrap">
                            <i class="widget-thumb-icon bg-green icon-paper-plane"></i>
                            <div class="widget-thumb-body">
                                <span class="widget-thumb-subtitle">Count</span>
                                <span class="widget-thumb-body-stat" data-counter="counterup" data-value="{{ $campaignsTotal }}">0</span>
                            </div>
                        </div>
                    </div>
                    <!-- END WIDGET THUMB -->
                </div>

            </div>


            <div class="row" id="targetDataTable">
                <div class="col-md-12">
                    <!-- BEGIN EXAMPLE TABLE PORTLET-->
                    <div class="portlet light ">
                        <div class="portlet-title">
                            <div class="caption font-dark">
                                <i class="icon-paper-plane font-red"></i>
                                <span class="caption-subject font-red bold uppercase"> Email Promotion</span>
                            </div>
                            <div class="actions col-sm-2 col-xs-12">

                                <a href="{{route('gym-admin.email-promotion.create')}}" class="btn dark"> new <span class="hidden-xs">campaign</span>
                                    <i class="fa fa-plus"></i>
                                </a>

                            </div>
                        </div>
                        <div class="portlet-body">
                            <table class="table table-striped table-bordered table-hover responsive table-100" id="promotion_table">
                                <thead>
                                <tr>
                                    <th class="all"> Campaign Name </th>
                                    <th class="min-tablet"> Campaign ID </th>
                                    <th class="min-tablet"> Email Title </th>
                                    <th class="min-tablet"> No of emails sent </th>
                                    <th class="min-tablet"> Status </th>
                                    <th class="min-tablet"> Sent On </th>
                                    <th class="min-tablet"> Action </th>
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
    <script src="{{ asset("admin/global/plugins/counterup/jquery.counterup.js") }}"></script>
    <script src="{{ asset("admin/global/plugins/counterup/jquery.waypoints.min.js") }}"></script>
    <script src="{{ asset("admin/global/scripts/datatable.js") }}"></script>
    <script src="{{ asset("admin/pages/scripts/table-datatables-managed.js") }}"></script>
    <script src="{{ asset("admin/global/plugins/datatables/datatables.min.js") }}"></script>
    <script src="{{ asset("admin/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js") }}"></script>

    <script>
        function load_data_table(){
            var table = $('#promotion_table');
            var url = '{{route('gym-admin.email-promotion.ajax-promotions')}}';
            table.dataTable({
                responsive: true,
                ajax: url,
                bDestroy:true,
                columns: [
                    { data: 'campaign_name', name: 'campaign_name'  },
                    { data: 'id', name: 'id'  },
                    { data: 'email_title', name: 'email_title'  },
                    { data: 'no_of_emails', name: 'no_of_emails'  },
                    { data: 'status', name: 'status'  },
                    { data: 'sent_on', name: 'sent_on'  },
                    { data: 'action', name: 'action'  },
                ],
            });
        }

    </script>
    <script>
        $(document).ready(function(){
            load_data_table();
        });
    </script>

@stop
