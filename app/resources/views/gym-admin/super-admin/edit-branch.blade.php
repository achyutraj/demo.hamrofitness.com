@extends('layouts.gym-merchant.gymbasic')

@section('CSS')
    {!! HTML::style('admin/global/plugins/ladda/ladda-themeless.min.css') !!}
    {!! HTML::style('admin/global/plugins/bootstrap-select/css/bootstrap-select.min.css') !!}
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
                <span>Edit Branch</span>
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
                                <i class="icon-layers font-red"></i>
                                <span class="caption-subject font-red bold uppercase"> Edit Branch</span>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <ul class="nav nav-tabs">
                                <li class="active">
                                    <a href="#branchTab" tabindex="-1" data-toggle="tab"> Branch </a>
                                </li>
                                <li>
                                    <a href="#permissionTab" tabindex="-1" data-toggle="tab"> Permission and Roles </a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane fade active in" id="branchTab">
                                    {!! Form::open(['id'=>'branchStoreForm','class'=>'ajax-form form-horizontal']) !!}
                                    <div class="form-wizard">
                                        <div class="form-body">
                                            <div class="form-group form-md-line-input">
                                                <label class="col-md-2 control-label" for="form_control_1">Branch Name <span class="required" aria-required="true"> * </span></label>

                                                <div class="col-md-6 input-icon right">
                                                    <input type="text" class="form-control" name="title" @if(!is_null($branchData)) value="{{ $branchData->title }}" @endif>
                                                    <div class="form-control-focus"></div>
                                                    <span class="help-block">Enter branch name</span>
                                                </div>
                                            </div>

                                            <div class="form-group form-md-line-input">
                                                <label class="col-md-2 control-label" for="form_control_1">Address <span class="required" aria-required="true"> * </span></label>
                                                <div class="col-md-6">
                                                    <textarea class="form-control" rows="3" placeholder="Enter address" name="address">@if(!is_null($branchData)) {{ $branchData->address }} @endif</textarea>
                                                    <div class="form-control-focus"> </div>
                                                </div>
                                            </div>

                                            <div class="form-group form-md-line-input">
                                                <label class="col-md-2 control-label" for="form_control_1">Incharge Name <span class="required" aria-required="true"> * </span></label>
                                                <div class="col-md-6 input-icon right">
                                                    <input type="text" class="form-control" name="owner_incharge_name" @if(!is_null($branchData)) value="{{ $branchData->owner_incharge_name }}" @endif>
                                                    <div class="form-control-focus"></div>
                                                    <span class="help-block">Enter incharge name</span>
                                                </div>
                                            </div>

                                            <div class="form-group form-md-line-input">
                                                <label class="col-md-2 control-label" for="form_control_1">Mobile <span class="required" aria-required="true"> * </span></label>
                                                <div class="col-md-6 input-icon right">
                                                    <input type="text" class="form-control" name="phone" @if(!is_null($branchData)) value="{{ $branchData->phone }}" @endif>
                                                    <div class="form-control-focus"></div>
                                                    <span class="help-block">Enter incharge name.</span>
                                                </div>
                                            </div>

                                            <div class="form-group form-md-line-input">
                                                <label class="col-md-2 control-label" for="form_control_1">Email <span class="required" aria-required="true"> * </span></label>
                                                <div class="col-md-6 input-icon right">
                                                    <input type="text" class="form-control" name="email" @if(!is_null($branchData)) value="{{ $branchData->email }}" @endif>
                                                    <div class="form-control-focus"></div>
                                                    <span class="help-block">Enter e-mail address.</span>
                                                </div>
                                            </div>

                                            <div class="form-group form-md-line-input">
                                                <label class="col-md-2 control-label" for="form_control_1">Join Date <span class="required" aria-required="true"> * </span></label>
                                                <div class="col-md-6 input-icon right">
                                                    <input type="text" class="form-control date-picker" data-date-today-highlight="true" name="start_date" @if(!is_null($branchData)) value="{{ $branchData->start_date }}" @endif>
                                                    <div class="form-control-focus"></div>
                                                    <span class="help-block">Enter Join Date.</span>
                                                </div>
                                            </div>

                                            <div class="form-group form-md-line-input">
                                                <label class="col-md-2 control-label" for="form_control_1">Expire Date <span class="required" aria-required="true"> * </span></label>
                                                <div class="col-md-6 input-icon right">
                                                    <input type="text" class="form-control date-picker" data-date-today-highlight="true" name="end_date" @if(!is_null($branchData)) value="{{ $branchData->end_date }}" @endif>
                                                    <div class="form-control-focus"></div>
                                                    <span class="help-block">Enter Expire Date.</span>
                                                </div>
                                            </div>

                                            <div class="form-group form-md-line-input">
                                                <label class="col-md-2 control-label" for="form_control_1">Username <span class="required" aria-required="true">  </span></label>
                                                <div class="col-md-6 input-icon right">
                                                    <input type="text" class="form-control" name="username" value="{{$managerData->username}}" placeholder="Username">
                                                    <div class="form-control-focus"></div>
                                                    <span class="help-block">Enter username.</span>
                                                </div>
                                            </div>
                                            <div class="form-group form-md-line-input">
                                                <label class="col-md-2 control-label" for="form_control_1">Password <span class="required" aria-required="true">  </span></label>
                                                <div class="col-md-6 input-icon right">
                                                    <input type="password" class="form-control" name="password" value="" placeholder="Leave it blank to keep current password.">
                                                    <div class="form-control-focus"></div>
                                                    <span class="help-block">Enter password.</span>
                                                </div>
                                            </div>
                                            @if($branchData->has_device == 1)
                                            <div class="form-group form-md-line-input">
                                                <label class="col-md-2 control-label" for="form_control_1">Auth Key <span class="required" aria-required="true">  </span></label>
                                                <div class="col-md-6 input-icon right">
                                                    <input type="text" class="form-control" name="auth_key" value="{{ $branchData->auth_key}}" readonly>
                                                    <div class="form-control-focus"></div>
                                                    <span class="help-block">Enter auth key.</span>
                                                </div>
                                            </div>
                                            @endif
                                            <div class="form-group form-md-line-input">
                                                <label class="col-md-2 control-label" for="has_device">Has Attendance Device<span class="required" aria-required="true"> * </span></label>
                                                <div class="col-md-6 input-icon right">
                                                    <select class="form-control" name="has_device">
                                                        <option value="0" @if($branchData->has_device == 0)  selected @endif >No</option>
                                                        <option value="1"  @if($branchData->has_device == 1)  selected @endif >Yes</option>
                                                    </select>
                                                    <div class="form-control-focus"></div>
                                                    <span class="help-block">Select Status</span>
                                                </div>
                                            </div>
                                            <input type="hidden" name="merchant_id" value="{{$managerData->id}}">
                                            <hr>
                                        </div>

                                        <div class="form-actions">
                                            <div class="row">
                                                <div class="col-md-offset-3 col-md-9">
                                                    <a href="javascript:;" class="btn green" id="storeBranch">Submit</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {!! Form::close() !!}
                                </div>
                                <div class="tab-pane fade" id="permissionTab">
                                    {!! Form::open(['id'=>'updateRolesAndPermissionForm','class'=>'ajax-form form-horizontal']) !!}
                                    <div class="form-wizard">
                                        <div class="form-body">
                                            <div class="form-group form-md-line-input">
                                                <label class="col-md-2 control-label">Branch Admin</label>
                                                <div class="col-md-6">
                                                    <select class="bs-select form-control" data-live-search="true" data-size="8" name="manager_id" id="manager_id">
                                                        @foreach($managers as $manager)
                                                            <option @if($user->id == $manager->id) selected @endif value="{{ $manager->id }}">{{ ucfirst($manager->first_name).' '.ucfirst($manager->last_name) }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group form-md-line-input">
                                                <label class="col-md-2 control-label">Role</label>
                                                <div class="col-md-6">
                                                    <select class="bs-select form-control" data-live-search="true" data-size="8" name="role_id" id="role_id">
                                                        <option selected disabled>Select Role</option>
                                                        @foreach($roles as $role)
                                                            <option @if(!empty($managerData->roles->first()) && $managerData->roles[0]->id == $role->id) selected @endif value="{{ $role->id }}">{{ ucfirst($role->name) }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <hr>
                                        </div>

                                        <div class="form-actions">
                                            <div class="row">
                                                <div class="col-md-offset-3 col-md-9">
                                                    <a href="javascript:;" class="btn green" id="updateRole">Submit</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {!! Form::close() !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END PAGE CONTENT INNER -->
    </div>
@stop

@section('footer')
    {!! HTML::script('admin/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') !!}
    {!! HTML::style('admin/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') !!}
    {!! HTML::script('admin/global/plugins/ladda/spin.min.js') !!}
    {!! HTML::script('admin/global/plugins/ladda/ladda.min.js') !!}
    {!! HTML::script('admin/pages/scripts/ui-buttons.min.js') !!}
    {!! HTML::script('admin/global/plugins/bootstrap-select/js/bootstrap-select.min.js') !!}
    {!! HTML::script('admin/pages/scripts/components-bootstrap-select.min.js') !!}
    <script>
        $('.date-picker').datepicker({
            rtl: App.isRTL(),
            orientation: "left",
            autoclose: true,
            format: "yyyy-mm-dd"
        });
        $('#storeBranch').click(function () {
            $.easyAjax({
                url: "{{ route('gym-admin.superadmin.update', [$branchData->id]) }}",
                container: '#branchStoreForm',
                type: 'PUT',
                data: $('#branchStoreForm').serialize()
            });
        });

        $('#updateRole').click(function () {
            $.easyAjax({
                url: "{{ route('gym-admin.superadmin.updateRolesAndPermission') }}",
                container: '#updateRolesAndPermissionForm',
                type: 'POST',
                data: $('#updateRolesAndPermissionForm').serialize()
            });
        });
    </script>
@stop
