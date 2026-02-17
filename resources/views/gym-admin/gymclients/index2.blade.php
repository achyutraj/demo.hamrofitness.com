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
                <span>Clients</span>
            </li>
        </ul>
        <!-- END PAGE BREADCRUMBS -->
        <!-- BEGIN PAGE CONTENT INNER -->
        <div class="page-content-inner">
            <div class="row">
                <div class="col-md-12">
                    @if(session()->has('message'))
                        <div class="alert alert-message alert-success">
                            {{ session()->get('message') }}
                        </div>
                    @endif
                    @if(session()->has('danger'))
                            <div class="alert alert-danger alert-success">
                                {{ session()->get('danger') }}
                            </div>
                    @endif
                <!-- BEGIN EXAMPLE TABLE PORTLET-->
                    <div class="portlet light ">
                        <div class="portlet-title">
                            <div class="caption font-dark">
                                <i class="icon-users font-red"></i>
                                <span class="caption-subject font-red bold uppercase"> Customers</span>
                            </div>
                            <div class="pull-right">
                                <div class="btn-group">
                                    <a id="sample_editable_1_new" href="{{route('gym-admin.client.create')}}" class="btn sbold dark"> Add New
                                        <i class="fa fa-plus"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <table class="table table-striped table-bordered table-hover order-column" style="width: 100%"
                                   id="gym_clients">
                                <thead>
                                <tr>
                                    <th class="max-desktop"> ID</th>
                                    <th class="max-desktop"> Name</th>
                                    <th class="desktop"> Subscription</th>
                                    <th class="desktop"> Subscription Status</th>
                                    <th class="desktop"> Is Deleted</th>
                                    <th class="desktop"> Is Expired</th>
                                    <th class="desktop"> Is Denied</th>
                                    <th class="desktop"> Add to Device</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($clients as $client)
                                        <tr>
                                            <td>{{$client->customer_id}}</td>
                                            <td>{{$client->fullName}}</td>
                                            <td>{{$client->activeMembership?->membership->title}}</td>
                                            <td>
                                                @if($client->activeMembership?->expires_on !== null)
                                                    @if($client->activeMembership->expires_on > today())
                                                        @if($client->status == 1)
                                                           <label class="label label-success"> Active </label>
                                                        @else
                                                           <label class="label label-danger"> Inactive User</label>
                                                        @endif
                                                    @else
                                                        @if($client->status == 1)
                                                           <label class="label label-danger"> Expired </label>
                                                        @else
                                                           <label class="label label-danger">Expired & Inactive User</label>
                                                        @endif
                                                    @endif
                                                    -{{ date('Y-m-d',strtotime($client->activeMembership->expires_on)) }}
                                                @endif
                                            </td>
                                            <td>{{ $client->is_device_deleted ? 'Yes': 'No'}}</td>
                                            <td>{{ $client->is_expired ? 'Yes': 'No'}}</td>
                                            <td>{{ $client->is_denied ? 'Yes': 'No'}}</td>
                                            <td>{{ $client->latestDeviceClients()->name ?? ''}}</td>
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
        var clientTable = $('#gym_clients');
        clientTable.dataTable();
    </script>
@stop
