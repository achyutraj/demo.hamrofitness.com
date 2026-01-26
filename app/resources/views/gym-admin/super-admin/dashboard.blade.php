@extends('layouts.gym-merchant.gymbasic')

@section('CSS')
    <style>
        .dashboard-filter {
            padding-right: 15px;
        }
    </style>
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
                    <span>Super Admin Dashboard</span>
                </li>
            </ul>
            <!-- END PAGE BREADCRUMBS -->
            @if($user->can('view_dashboard'))
            <!-- BEGIN PAGE CONTENT INNER -->
                <div class="page-content-inner">
                    <div class="row widget-row">
                    <div class="col-md-3">
                            <!-- BEGIN WIDGET THUMB -->
                            <div class="dashboard-stat yellow">
                                <div class="visual">
                                    <i class="fa fa-users"></i>
                                </div>
                                <div class="details">
                                    <div class="number" data-counter="counterup" data-value="{{ $branchCount }}"> 0 </div>
                                    <div class="desc"> Total Branch </div>
                                </div>
                            </div>
                            <!-- END WIDGET THUMB -->
                        </div>

                        <div class="col-md-3">
                            <!-- BEGIN WIDGET THUMB -->
                            <div class="dashboard-stat purple">
                                <div class="visual">
                                    <i class="fa fa-users"></i>
                                </div>
                                <div class="details">
                                    <div class="number" data-counter="counterup" data-value="{{ $customerCount }}"> 0</div>
                                    <div class="desc"> Total Customer </div>
                                </div>

                            </div>
                            <!-- END WIDGET THUMB -->
                        </div>

                        <div class="col-md-3">
                            <!-- BEGIN WIDGET THUMB -->
                            <div class="dashboard-stat blue">
                                <div class="visual">
                                    <i class="fa fa-users"></i>
                                </div>
                                <div class="details">
                                    <div class="number" data-counter="counterup" data-value="{{ $currentMonthEnquiries }}"> 0 </div>
                                    <div class="desc"> Monthly Enquiries </div>
                                </div>
                            </div>
                            <!-- END WIDGET THUMB -->
                        </div>

                        <div class="col-md-3">
                            <!-- BEGIN WIDGET THUMB -->
                            <div class="dashboard-stat green-soft">
                                <div class="visual">
                                    <i class="fa fa-users"></i>
                                </div>
                                <div class="details">
                                    <div class="number" data-counter="counterup" data-value="{{ $unpaidMembers }}"> 0 </div>
                                    <div class="desc"> Unpaid Members </div>
                                </div>
                            </div>
                            <!-- END WIDGET THUMB -->
                        </div>
                        <div class="col-md-3">
                            <!-- BEGIN WIDGET THUMB -->
                            <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 ">
                                <h4 class="widget-thumb-heading">Total Earnings</h4>
                                <div class="widget-thumb-wrap">
                                    <i class="widget-thumb-icon bg-yellow fa {{$gymSettings->currency->symbol}}"></i>
                                    <div class="widget-thumb-body">
                                        <span class="widget-thumb-subtitle">{{$gymSettings->currency->acronym}}</span>
                                        <span class="widget-thumb-body-stat" data-counter="counterup" data-value="{{ $totalEarnings }}">0</span>
                                    </div>
                                </div>
                            </div>
                            <!-- END WIDGET THUMB -->
                        </div>
                        <div class="col-md-3">
                            <!-- BEGIN WIDGET THUMB -->
                            <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 ">
                                <h4 class="widget-thumb-heading">Monthly Income</h4>
                                <div class="widget-thumb-wrap">
                                    <i class="widget-thumb-icon bg-purple fa {{$gymSettings->currency->symbol}}"></i>
                                    <div class="widget-thumb-body">
                                        <span class="widget-thumb-subtitle">{{$gymSettings->currency->acronym}}</span>
                                        <span class="widget-thumb-body-stat" data-counter="counterup" data-value="{{ $currentMonthEarnings }}">0</span>
                                    </div>
                                </div>
                            </div>
                            <!-- END WIDGET THUMB -->
                        </div>
                        <div class="col-md-3">
                            <!-- BEGIN WIDGET THUMB -->
                            <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 ">
                                <h4 class="widget-thumb-heading">Total Due Payment</h4>
                                <div class="widget-thumb-wrap">
                                    <i class="widget-thumb-icon bg-blue fa {{$gymSettings->currency->symbol}}"></i>
                                    <div class="widget-thumb-body">
                                        <span class="widget-thumb-subtitle">{{$gymSettings->currency->acronym}}</span>
                                        <span class="widget-thumb-body-stat" data-counter="counterup" data-value="{{ $duePayments }}">0</span>
                                    </div>
                                </div>
                            </div>
                            <!-- END WIDGET THUMB -->
                        </div>
                        <div class="col-md-3">
                            <!-- BEGIN WIDGET THUMB -->
                            <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 ">
                                <h4 class="widget-thumb-heading">Monthly Expense</h4>
                                <div class="widget-thumb-wrap">
                                    <i class="widget-thumb-icon bg-green-soft fa {{$gymSettings->currency->symbol}}"></i>
                                    <div class="widget-thumb-body">
                                        <span class="widget-thumb-subtitle">{{$gymSettings->currency->acronym}}</span>
                                        <span class="widget-thumb-body-stat" data-counter="counterup" data-value="{{ $currentMonthExpense }}">0</span>
                                    </div>
                                </div>
                            </div>
                            <!-- END WIDGET THUMB -->
                        </div>


                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <div class="portlet light">
                                <div class="portlet-title">
                                    <div class="caption">
                                        <i class="icon-users font-blue"></i>
                                        <span class="caption-subject font-blue bold uppercase">Branches Expiring in next 45 days</span>
                                    </div>
                                    <div class="pull-right">
                                        <div class="btn-group">
                                            <a class="btn sbold blue"> Total {{ $expiringBranches->count()}}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="portlet-body">
                                    <table class="table table-bordered table-striped table-condensed flip-content">
                                        <thead class="flip-content">
                                        <tr class="uppercase">
                                            <th> Branch Name </th>
                                            <th> Manager Name </th>
                                            <th> Phone </th>
                                            <th> Email </th>
                                            <th> Join At </th>
                                            <th> Expire On </th>
                                            <th> Remain Days </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($expiringBranches as $expireBranch)
                                            <tr>
                                                <td>{{ $expireBranch->title }}</td>
                                                <td>{{ $expireBranch->owner_incharge_name }}</td>
                                                <td>{{ $expireBranch->phone }}</td>
                                                <td>{{ $expireBranch->email }}</td>
                                                <td>{{ $expireBranch->joins_on }}</td>
                                                <td>{{ $expireBranch->expires_on }}</td>
                                                <td>
                                                    <?php
                                                    $created = new \Carbon\Carbon($expireBranch->expires_on);
                                                    $now = \Carbon\Carbon::now();
                                                    $difference = ($created->diff($now)->days < 1) ? 'today' : $created->diffInDays($now);
                                                    ?>
                                                    <span class="badge badge-danger">{{ $difference }} Days</span></td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7">No Branch Expire.</td>
                                            </tr>
                                        @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-sm-12">
                            <!-- BEGIN PORTLET-->
                            <div class="portlet light ">
                                <div class="portlet-title tabbable-line">
                                    <div class="caption">
                                        <i class="icon-user-following font-green"></i>
                                        <span class="caption-subject font-blue bold uppercase">Recently Active</span>
                                    </div>
                                </div>
                                <div class="portlet-body">
                                    <table class="table table-bordered table-striped table-condensed flip-content">
                                        <thead class="flip-content">
                                        <tr class="uppercase">
                                            <th> BranchName </th>
                                            <th> UserName </th>
                                            <th> Last Active </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($recentlyActive as $recent)
                                            <tr>
                                                <td>{{ $recent->title }}</td>
                                                <td>
                                                    @if($recent->image != '')
                                                        <img style="width:50px;height:50px;" class="img-circle" src="{{ asset('/uploads/profile_pic/master/') . '/' . $recent->image }}" alt="" /><br>
                                                    @endif
                                                    {{ ucfirst($recent->first_name) .' '.ucfirst($recent->middle_name) .' '.ucfirst($recent->last_name) }}</td>
                                                <td>{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $recent->last_activity)->diffForHumans() }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3">No Data Found.</td>
                                            </tr>
                                        @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- END PORTLET-->
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <!-- BEGIN PORTLET-->
                            <div class="portlet light ">
                                <div class="portlet-title tabbable-line">
                                    <div class="caption">
                                        <i class="icon-user-unfollow font-red"></i>
                                        <span class="caption-subject font-blue bold uppercase">NotActive Users</span>
                                    </div>
                                </div>
                                <div class="portlet-body">
                                    <table class="table table-bordered table-striped table-condensed flip-content">
                                        <thead class="flip-content">
                                        <tr class="uppercase">
                                            <th> BranchName </th>
                                            <th> UserName </th>
                                            <th> Last Active </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($notActiveUsers as $notActive)
                                            <tr>
                                                <td>{{ $notActive->title }}</td>
                                                <td>
                                                    @if($notActive->image != '')
                                                        <img style="width:50px;height:50px;" class="img-circle" src="{{ asset('/uploads/profile_pic/master/') . '/' . $notActive->image }}" alt="" /><br>
                                                    @endif
                                                    {{ ucfirst($notActive->first_name) .' '.ucfirst($notActive->middle_name) .' '.ucfirst($notActive->last_name) }}</td>
                                                <td>
                                                    @if($notActive->last_activity != null)
                                                        {{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $notActive->last_login)->diffForHumans()}}
                                                    @else
                                                        {{ 'Not LogIn Till Now' }}
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3">No Data Found.</td>
                                            </tr>
                                        @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- END PORTLET-->
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <div class="portlet light">
                                <div class="portlet-title">
                                    <div class="caption">
                                        <i class="icon-user-follow font-blue"></i>
                                        <span class="caption-subject font-blue bold uppercase" >Active Last Month</span>
                                    </div>
                                </div>
                                <div class="portlet-body">
                                    <table class="table table-bordered table-striped table-condensed flip-content">
                                        <thead class="flip-content">
                                        <tr class="uppercase">
                                            <th> BranchName </th>
                                            <th> UserName </th>
                                            <th> Last Active </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($userActiveInDays as $lastWeek)
                                            <tr>
                                                <td>{{ $lastWeek->title }}</td>
                                                <td>
                                                    @if($lastWeek->image != '')
                                                        <img style="width:50px;height:50px;" class="img-circle" src="{{ asset('/uploads/profile_pic/master/') . '/' . $lastWeek->image }}" alt="" /><br>
                                                    @endif
                                                    {{ ucfirst($lastWeek->first_name) .' '.ucfirst($lastWeek->middle_name) .' '.ucfirst($lastWeek->last_name) }}</td>
                                                <td>
                                                    @if($lastWeek->last_activity != null)
                                                        {{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $lastWeek->last_activity)->diffForHumans()}}
                                                    @else
                                                        {{ 'Not LogIn Till Now' }}
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3">No Data Found.</td>
                                            </tr>
                                        @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END PAGE CONTENT INNER -->
            @endif

    </div>
@stop

@section('footer')
    {!! HTML::script('admin/global/plugins/counterup/jquery.counterup.js') !!}
    {!! HTML::script('admin/global/plugins/counterup/jquery.waypoints.min.js') !!}
@stop
