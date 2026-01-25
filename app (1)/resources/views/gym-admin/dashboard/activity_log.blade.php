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
                <span>Activity Log</span>
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
                                <i class="icon-list font-red"></i>
                                <span class="caption-subject font-red bold uppercase"> Activity Log</span>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <table class="table table-striped table-bordered table-hover order-column" style="width: 100%"
                                   id="gym_clients">
                                <thead>
                                <tr>
                                    <th class="max-desktop"> Date</th>
                                    <th class="max-desktop"> Logged Name</th>
                                    <th class="desktop"> Event</th>
                                    <th class="desktop"> Performed By</th>
                                    <th class="desktop"> Description</th>

                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($logs as $log)
                                        <tr>
                                            <td>{{ date('M d,Y',strtotime($log->created_at)) }}</td>
                                            <td>{{ $log->log_name }}</td>
                                            <td>{{ $log->event }}</td>
                                            <td>{{ $log->causer?->username }}</td>
                                            <td>{{ $log->description }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
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
        var clientTable = $('#gym_clients').dataTable({
            pageLength: 25,
            lengthMenu: [
                [25, 50, 75 , 100, -1],
                ['25', '50','75' ,'100', 'All']
            ],
        });
    </script>
@stop
