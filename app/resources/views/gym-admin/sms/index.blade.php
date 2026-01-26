@extends('layouts.gym-merchant.gymbasic')

@section('CSS')
    {!! HTML::style('admin/apps/css/inbox.min.css') !!}
    {!! HTML::style('admin/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.css') !!}
    {!! HTML::style('admin/global/plugins/bootstrap-select/css/bootstrap-select.min.css') !!}
    @stack('show-styles')
@stop

@section('content')
    <div class="page-container">
        <!-- BEGIN CONTENT -->
        <div class="page-content-wrapper">
            <!-- BEGIN CONTENT BODY -->
            <!-- BEGIN PAGE HEAD-->
            <!-- END PAGE HEAD-->
            <!-- BEGIN PAGE CONTENT BODY -->
            <div class="page-content">
                <div class="container-fluid">
                    <!-- BEGIN PAGE BREADCRUMBS -->
                    <ul class="page-breadcrumb breadcrumb">
                        <li>
                            <a href="{{ route('gym-admin.dashboard.index') }}">Home</a>
                            <i class="fa fa-circle"></i>
                        </li>
                        <li>
                            <a href="javascript:;">SMS</a>
                        </li>
                    </ul>
                    <!-- END PAGE BREADCRUMBS -->
                    <!-- BEGIN PAGE CONTENT INNER -->
                    <div class="page-content-inner">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="portlet light ">
                                    <div class="portlet-title">
                                        <div class="caption font-dark">
                                            @if ($smsSetting[0]->sms_status == 'disabled')
                                                <p>Goto settings/sms and enable sms status option to send sms</p>
                                            @else
                                                <a href="{{route('gym-admin.sms.create')}}" class="btn red compose-btn btn-block">
                                                <i class="fa fa-edit"></i> Compose </a>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="portlet-body">
                                        <div class="inbox-body">
                                            @yield('sms')
                                        </div>
                                    </div>
                                </div>
                                <div class="pull-right">

                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END PAGE CONTENT INNER -->
                </div>
            </div>
            <!-- END PAGE CONTENT BODY -->
            <!-- END CONTENT BODY -->
        </div>
        <!-- END CONTENT -->
    </div>
@stop

@section('footer')
    {!! HTML::script('admin/global/plugins/bootstrap-select/js/bootstrap-select.min.js') !!}
    {!! HTML::script('admin/pages/scripts/components-bootstrap-select.min.js') !!}
    @stack('detail-scripts')
    @stack('show-scripts')

@stop
