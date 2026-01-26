@extends('layouts.gym-merchant.gymbasic')

@section('CSS')
    {!! HTML::style('admin/global/plugins/ladda/ladda-themeless.min.css') !!}
    {!! HTML::style('admin/global/plugins/datatables/datatables.min.css') !!}
    {!! HTML::style('admin/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css') !!}
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
                <span>SMS Templates</span>
            </li>
        </ul>
        <!-- END PAGE BREADCRUMBS -->
        <!-- BEGIN PAGE CONTENT INNER -->
        <div class="page-content-inner">
            <div class="row">
                <div class="col-md-12">
                    <!-- BEGIN EXAMPLE TABLE PORTLET-->
                    <div class="portlet light ">
                        <div class="portlet-title">
                            <div class="caption font-dark">
                                <i class="fa fa-file font-red"></i>
                                <span class="caption-subject font-red bold uppercase"> Templates</span>
                            </div>
                            <div class="pull-right">
                                <div class="btn-group">
                                    <a id="addTarget" href="{{route('gym-admin.templates.create')}}" class="btn sbold dark"> Add New
                                        <i class="fa fa-plus"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <table class="table table-bordered table-hover order-column table-100" id="template_table">
                                <thead>
                                <tr>
                                    <th class="desktop"> Name</th>
                                    <th class="desktop"> Message</th>
                                    <th class="desktop"> Status</th>
                                    <th class="desktop"> Action</th>
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

    {!! HTML::script('admin/global/scripts/datatable.js') !!}
    {!! HTML::script('admin/pages/scripts/table-datatables-managed.js') !!}
    {!! HTML::script('admin/global/plugins/datatables/datatables.min.js') !!}
    {!! HTML::script('admin/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js') !!}
    {!! HTML::script('admin/global/plugins/ladda/spin.min.js') !!}
    {!! HTML::script('admin/global/plugins/ladda/ladda.min.js') !!}
    {!! HTML::script('admin/pages/scripts/ui-buttons.min.js') !!}

    <script>
        var clientTable = $('#template_table');

        var table = clientTable.dataTable({
            processing: true,
            serverSide: true,
            ajax: "{{route('gym-admin.templates.ajax-create')}}",
            columns: [
                {data: 'name', name: 'name'},
                {data: 'message', name: 'message'},
                {data: 'status', name: 'status'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ],
            pageLength: 25,
            lengthMenu: [
                [25, 50, 75 , 100, -1],
                ['25', '50','75' ,'100', 'All']
            ],
        });
    </script>

@stop
