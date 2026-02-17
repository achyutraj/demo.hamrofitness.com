@extends('layouts.gym-merchant.gymbasic')

@section('CSS')
    {!! HTML::style('admin/global/plugins/select2/select2.css') !!}
    {!! HTML::style('admin/global/plugins/select2/select2-bootstrap.css') !!}
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
                <span>Target</span>
            </li>
        </ul>
        <!-- END PAGE BREADCRUMBS -->
        <!-- BEGIN PAGE CONTENT INNER -->
        <div class="page-content-inner">
            <div class="row widget-row">
                <div class="col-md-3">
                    <!-- BEGIN WIDGET THUMB -->
                    <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 ">
                        <h4 class="widget-thumb-heading">Total Targets</h4>
                        <div class="widget-thumb-wrap">
                            <i class="widget-thumb-icon bg-blue icon-grid"></i>
                            <div class="widget-thumb-body">
                                <span class="widget-thumb-subtitle">Count</span>
                                <span class="widget-thumb-body-stat" data-counter="counterup" data-value="{{$allCount}}">0</span>
                            </div>
                        </div>
                    </div>
                    <!-- END WIDGET THUMB -->
                </div>

                <div class="col-md-3">
                    <!-- BEGIN WIDGET THUMB -->
                    <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 ">
                        <h4 class="widget-thumb-heading">Completed Targets</h4>
                        <div class="widget-thumb-wrap">
                            <i class="widget-thumb-icon bg-green icon-badge"></i>
                            <div class="widget-thumb-body">
                                <span class="widget-thumb-subtitle">Count</span>
                                <span class="widget-thumb-body-stat" data-counter="counterup" data-value="{{$allCompleted}}">0</span>
                            </div>
                        </div>
                    </div>
                    <!-- END WIDGET THUMB -->
                </div>

            </div>
            @if(count($targetsProgress) > 0)
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light ">
                            <div class="portlet-title">
                                <div class="caption ">
                                    <span class="caption-subject font-dark bold uppercase">Target Status</span>
                                </div>
                            </div>
                            <div class="portlet-body">
                                @forelse($targetsProgress as $target)
                                    <div class="caption-subject bold font-grey-gallery uppercase">
                                        {{$target['name']}} ({{ round($target['percent'],2) }}%)</div>
                                    <div class="progress progress-striped active">
                                        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="4" aria-valuemin="0" aria-valuemax="100" style="width: {{$target['percent']}}%">
                                            <span class="sr-only"> {{$target['percent']}}% Complete </span>
                                        </div>
                                    </div>
                                @empty
                                    <h5>You don't have any target yet.</h5>
                                    <a class="btn dark" href="{{route('gym-admin.target.create')}}">Create A Target <i class="fa fa-arrow-right"></i> </a>
                                @endforelse
                            </div>
                        </div>
                    </div>

                </div>
            @endif

            <div class="row">
                <div class="col-md-12">
                    <!-- BEGIN EXAMPLE TABLE PORTLET-->
                    <div class="portlet light ">
                        <div class="portlet-title">
                            <div class="caption font-dark">
                                <i class="icon-target font-red"></i>
                                <span class="caption-subject font-red bold uppercase"> Targets</span>
                            </div>
                            <div class="pull-right">
                                <div class="btn-group">
                                    <a id="addTarget" href="{{route('gym-admin.target.create')}}" class="btn sbold dark"> Add New
                                        <i class="fa fa-plus"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <table class="table table-striped table-bordered table-hover table-100" id="targets_table">
                                <thead>
                                <tr>
                                    <th class="all"> Title </th>
                                    <th class="min-tablet"> Type </th>
                                    <th class="min-tablet"> Value </th>
                                    <th class="min-tablet"> End Date </th>
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
    {!! HTML::script('admin/global/plugins/counterup/jquery.counterup.js') !!}
    {!! HTML::script('admin/global/plugins/counterup/jquery.waypoints.min.js') !!}
    {!! HTML::script('admin/global/scripts/datatable.js') !!}
    {!! HTML::script('admin/pages/scripts/table-datatables-managed.js') !!}
    {!! HTML::script('admin/global/plugins/datatables/datatables.min.js') !!}
    {!! HTML::script('admin/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js') !!}
    {!! HTML::script('admin/global/plugins/ladda/spin.min.js') !!}
    {!! HTML::script('admin/global/plugins/ladda/ladda.min.js') !!}
    {!! HTML::script('admin/pages/scripts/ui-buttons.min.js') !!}


    <script>
    function load_dataTable() {
        var targetTable = $('#targets_table');

        var table = targetTable.dataTable({
            processing: true,
            serverSide: true,
            ajax: "{{route('gym-admin.target.ajax-create')}}",
            columns: [
                {data: 'title', name: 'title'},
                {data: 'targetType', name: 'targetType'},
                {data: 'value', name: 'value'},
                {data: 'date', name: 'date'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });
    }
    </script>

    <script>
        $('#targets_table').on('click','.remove-target',function(){
            var id = $(this).data('id');
            bootbox.confirm({
                message: "Do you want to delete this target?",
                buttons: {
                    confirm: {
                        label: "Yes",
                        className: "btn-primary"
                    }
                },
                callback: function(result){
                    if(result){

                        var url = "{{route('gym-admin.target.destroy',':id')}}";
                        url = url.replace(':id',id);

                        $.easyAjax({
                            url: url,
                            type: "DELETE",
                            data: {id: id,_token: '{{ csrf_token() }}'},
                            success: function(){
                                load_dataTable();
                            }
                        });
                    }
                    else {
                        console.log('cancel');
                    }
                }
            })
        });
    </script>
    <script>
        $(document).ready(function(){
            load_dataTable();
        });
    </script>
@stop
