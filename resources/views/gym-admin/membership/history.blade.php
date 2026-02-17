@extends('layouts.gym-merchant.gymbasic')

@section('CSS')
    {!! HTML::style('admin/global/plugins/datatables/datatables.min.css') !!}
    {!! HTML::style('admin/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css') !!}
    <style>
        .info-box {
            display: block;
            min-height: 80px;
            background: #fff;
            width: 100%;
            box-shadow: 0 1px 1px rgba(0,0,0,0.1);
            border-radius: 2px;
            margin-bottom: 15px;
        }
        .info-box-icon {
            border-radius: 2px 0 0 2px;
            display: block;
            float: left;
            height: 80px;
            width: 80px;
            text-align: center;
            font-size: 40px;
            line-height: 80px;
            background: rgba(0,0,0,0.2);
        }
        .info-box-content {
            padding: 5px 10px;
            margin-left: 80px;
        }
        .info-box-text {
            display: block;
            font-size: 14px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .info-box-number {
            display: block;
            font-weight: bold;
            font-size: 18px;
        }
        .bg-blue { background-color: #3c8dbc !important; }
        .bg-green { background-color: #00a65a !important; }
        .bg-red { background-color: #dd4b39 !important; }
        .bg-yellow { background-color: #f39c12 !important; }
        .text-warning { color: #f39c12; }
        .old-value { color: #dd4b39; font-weight: bold; }
        .new-value { color: #00a65a; font-weight: bold; }
    </style>
@stop

@section('content')
    <div class="container-fluid">
        <!-- BEGIN PAGE BREADCRUMBS -->
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <a href="{{ route('gym-admin.dashboard.index') }}">Home</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <a href="{{ route('gym-admin.membership-plans.index') }}">Membership Plans</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>History</span>
            </li>
        </ul>
        <!-- END PAGE BREADCRUMBS -->

        <!-- BEGIN PAGE CONTENT INNER -->
        <div class="page-content-inner">
            <div class="row">
                <div class="col-md-12">
                    <div class="portlet light bordered">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="fa fa-history"></i>
                                <span class="caption-subject font-red bold uppercase">Membership History - {{ $membership->title }}</span>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <!-- Current Membership Details -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="portlet light bordered">
                                        <div class="portlet-title">
                                            <div class="caption">
                                                <i class="fa fa-info-circle"></i>
                                                Current Details
                                            </div>
                                        </div>
                                        <div class="portlet-body">
                                            <table class="table table-striped">
                                                <tr>
                                                    <td><strong>Title:</strong></td>
                                                    <td>{{ $membership->title }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Price:</strong></td>
                                                    <td>{{ $membership->price }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Duration:</strong></td>
                                                    <td>{{ $membership->duration }} {{ $membership->duration_type }}(s)</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Details:</strong></td>
                                                    <td>{{ $membership->details ?: '---' }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Created:</strong></td>
                                                    <td>{{ $membership->created_at->format('M d, Y H:i') }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Last Updated:</strong></td>
                                                    <td>{{ $membership->updated_at->format('M d, Y H:i') }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="portlet light bordered">
                                        <div class="portlet-title">
                                            <div class="caption">
                                                <i class="fa fa-exclamation-triangle"></i>
                                                Impact Summary
                                            </div>
                                        </div>
                                        <div class="portlet-body">
                                             @php
                                                 $highImpactChanges = $membership->membershipHistories()
                                                     ->where('action_type', 'updated')
                                                     ->where(function($query) {
                                                         $query->where('field_name', 'duration_and_type')
                                                               ->orWhere('field_name', 'multiple_fields');
                                                     })
                                                     ->get();
                                             @endphp

                                             @if($highImpactChanges->count() > 0)
                                                 <div class="alert alert-warning">
                                                     <strong>⚠️ Important Changes Detected!</strong><br>
                                                     {{ $highImpactChanges->count() }} significant change(s) detected. These changes may affect existing subscriptions.
                                                 </div>
                                             @endif

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="info-box">
                                                        <span class="info-box-icon bg-blue"><i class="fa fa-users"></i></span>
                                                        <div class="info-box-content">
                                                            <span class="info-box-text">Active Subscriptions</span>
                                                            <span class="info-box-number">{{ $membership->subscriptions()->where('status', 'active')->count() }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="info-box">
                                                        <span class="info-box-icon bg-green"><i class="fa fa-history"></i></span>
                                                        <div class="info-box-content">
                                                            <span class="info-box-text">Total Changes</span>
                                                            <span class="info-box-number">{{ $membership->membershipHistories()->count() }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Change History Table -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="portlet light bordered">
                                        <div class="portlet-title">
                                            <div class="caption">
                                                <i class="fa fa-list"></i>
                                                Change History
                                            </div>
                                        </div>
                                        <div class="portlet-body">
                                            <table class="table table-striped table-bordered" id="history-table">
                                                <thead>
                                                    <tr>
                                                        <th>Date & Time</th>
                                                        <th>Action</th>
                                                        <th>Changed By</th>
                                                        <th>Changes</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($membership->membershipHistories()->orderBy('created_at', 'desc')->get() as $history)
                                                        <tr class="@if($history->action_type == 'deleted') danger @elseif($history->field_name == 'multiple_fields' || $history->field_name == 'duration_and_type') warning @endif">
                                                            <td>{{ $history->created_at->format('M d, Y H:i:s') }}</td>
                                                            <td>
                                                                @if($history->action_type == 'created')
                                                                    <span class="label label-success">Created</span>
                                                                @elseif($history->action_type == 'updated')
                                                                    <span class="label label-info">Updated</span>
                                                                @elseif($history->action_type == 'deleted')
                                                                    <span class="label label-danger">Deleted</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if($history->changedByUser)
                                                                    {{ $history->changedByUser->username ?: 'Unknown' }}
                                                                @else
                                                                    System
                                                                @endif
                                                            </td>
                                                            <td>{{ $history->change_reason ?: '-' }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END PAGE CONTENT INNER -->
    </div>
@endsection

@section('footer')
    {!! HTML::script('admin/global/scripts/datatable.js') !!}
    {!! HTML::script('admin/pages/scripts/table-datatables-managed.js') !!}
    {!! HTML::script('admin/global/plugins/datatables/datatables.min.js') !!}
    {!! HTML::script('admin/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js') !!}

    <script>
        $(document).ready(function() {
            $('#history-table').DataTable({
                "order": [[0, "desc"]],
                "pageLength": 25,
                "responsive": true
            });
        });
    </script>
@endsection
