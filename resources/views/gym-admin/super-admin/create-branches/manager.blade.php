@extends('layouts.gym-merchant.gymbasic')

@section('CSS')
    <link rel="stylesheet" href="{{ asset("css/cropper.css") }}">
    <link rel="stylesheet" href="{{ asset("admin/global/plugins/bootstrap-datepicker/css/datepicker.css") }}">
    <link rel="stylesheet" href="{{ asset("admin/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css") }}">
    <link rel="stylesheet" href="{{ asset("admin/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css") }}">
    <link rel="stylesheet" href="{{ asset("admin/global/plugins/ladda/ladda-themeless.min.css") }}">
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
                <span>Branch Setup 2 of 4</span>
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
                                <span class="caption-subject font-red bold uppercase"> Branch setup wizard</span>
                            </div>
                            <div class="actions">
                                <span class="caption-subject font-red bold uppercase"> STEP 2 of 3 </span>
                            </div>
                        </div>
                        <div class="portlet-body">

                            <div class="col-md-12">
                                <div class="progress progress-striped active">
                                    <div class="progress-bar progress-bar-success" role="progressbar"
                                         aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"
                                         style="width: {{ ($completedItems*(100/$completedItemsRequired)) }}%">
									<span class="sr-only">
									{{ ($completedItems*(100/$completedItemsRequired)) }}% Complete </span>
                                    </div>
                                </div>
                            </div>

                            {{ html()->form()->open(['route'=>'gym-admin.superadmin.store','id'=>'managerStoreForm','class'=>'ajax-form form-horizontal','method'=>'POST','files' => true]) }}
                            <div class="form-wizard">
                                <div class="form-body">
                                    <ul class="nav nav-pills nav-justified steps">
                                        @if(isset($branch_id))
                                            <li>
                                                <a href="{{ route('gym-admin.superadmin.branch', [$branch_id]) }}" class="step">
                                                    <span class="number"> 1 </span>
                                                    <span class="desc">
                                                                            <i class="fa fa-check"></i> Add Branch </span>
                                                </a>
                                            </li>
                                        @endif
                                        <li class="active">
                                            <a href="javascript:" class="step">
                                                <span class="number"> 2 </span>
                                                <span class="desc">
                                                                        <i class="fa fa-check"></i> Add Manager </span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:"
                                               class="step active">
                                                <span class="number"> 3 </span>
                                                <span class="desc">
                                                                        <i class="fa fa-check"></i> Assign Role </span>
                                            </a>
                                        </li>
                                    </ul>
                                    @if(!is_null($managerData))
                                        <input type="hidden" name="manager_id" value="{{ $managerData->id }}">
                                    @endif
                                    @if(isset($branch_id))
                                        <input type="hidden" name="branch_id" value="{{ $branch_id }}">
                                    @endif
                                    <div class="form-group form-md-line-input">
                                        <label class="col-md-2 control-label" for="form_control_1">First Name <span class="required" aria-required="true"> * </span></label>

                                        <div class="col-md-6 input-icon right">
                                            <input type="text" class="form-control" name="first_name"
                                                   @if(!is_null($managerData)) value="{{ $managerData->first_name }}" @endif>

                                            <div class="form-control-focus"></div>
                                            <span class="help-block">Enter your first name</span>
                                            <i class="icon-user"></i>
                                        </div>
                                    </div>

                                    <div class="form-group form-md-line-input">
                                        <label class="col-md-2 control-label" for="form_control_1">Middle Name</label>

                                        <div class="col-md-6 input-icon right">
                                            <input type="text" class="form-control" name="middle_name"
                                                   @if(!is_null($managerData)) value="{{ $managerData->middle_name }}" @endif>

                                            <div class="form-control-focus"></div>
                                            <span class="help-block">Enter your middle name</span>
                                            <i class="icon-user"></i>
                                        </div>
                                    </div>

                                    <div class="form-group form-md-line-input">
                                        <label class="col-md-2 control-label" for="form_control_1">Last Name <span class="required"
                                                                                                                   aria-required="true"> * </span></label>

                                        <div class="col-md-6 input-icon right">
                                            <input type="text" class="form-control" name="last_name" id="last_name"
                                                   @if(!is_null($managerData)) value="{{ $managerData->last_name }}" @endif>

                                            <div class="form-control-focus"></div>
                                            <span class="help-block">Enter your last name</span>
                                            <i class="icon-user"></i>
                                        </div>
                                    </div>

                                    <div class="form-group form-md-line-input">
                                        <label class="col-md-2 control-label" for="form_control_1">Email <span class="required"
                                                                                                               aria-required="true"> * </span></label>

                                        <div class="col-md-6 input-icon right">
                                            <input type="text" class="form-control" name="email" id="email"
                                                   @if(!is_null($managerData)) value="{{ $managerData->email }}" @endif>

                                            <div class="form-control-focus"></div>
                                            <span class="help-block">Enter your email address</span>
                                            <i class="icon-user"></i>
                                        </div>
                                    </div>

                                    <div class="form-group form-md-radios">
                                        <label class="col-md-2 control-label" for="form_control_1">Gender</label>

                                        <div class="col-md-6">
                                            <div class="md-radio-inline">
                                                <div class="md-radio">
                                                    <input type="radio" id="male" name="gender" value="male"
                                                           class="md-radiobtn"
                                                           @if(!is_null($managerData) && ($managerData->gender == 'male')) checked @endif>
                                                    <label for="male">
                                                        <span></span>
                                                        <span class="check"></span>
                                                        <span class="box"></span> Male </label>
                                                </div>
                                                <div class="md-radio">
                                                    <input type="radio" id="female" name="gender" value="female"
                                                           class="md-radiobtn"
                                                           @if(!is_null($managerData) && ($managerData->gender == 'female')) checked @endif>
                                                    <label for="female">
                                                        <span></span>
                                                        <span class="check"></span>
                                                        <span class="box"></span> Female </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group form-md-line-input mobile">
                                        <label class="col-md-2 control-label" for="form_control_1">Mobile <span class="required"
                                                                                                                aria-required="true"> * </span></label>

                                        <div class="col-md-6 input-icon right">
                                            <input type="tel" class="form-control" id="mobile" name="mobile"
                                                   @if(!is_null($managerData) && isset($managerData->mobile)) value="{{ $managerData->mobile }}" @endif>

                                            <div class="form-control-focus"></div>
                                            <span class="help-block error-message">Your mobile number</span>
                                            <i class="fa fa-mobile"></i>
                                        </div>
                                    </div>
                                    <div class="form-group form-md-line-input">
                                        <label class="col-md-2 control-label" for="form_control_1">Date of Birth</label>

                                        <div class="col-md-6 input-icon right">
                                            <input type="text" class="form-control date-picker" placeholder="Birth Date"
                                                   data-provide="datepicker" data-date-autoclose="true" data-date-today-highlight="true"
                                                   name="date_of_birth" id="date_of_birth" @if(!is_null($managerData) && isset($managerData->date_of_birth)) value="{{ $managerData->date_of_birth }}" @endif>
                                            <div class="form-control-focus"></div>
                                            <span class="help-block">Enter your date of birth</span>
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                    </div>
                                    <div class="form-group form-md-line-input ">
                                        <label class="col-md-2 control-label" for="form_control_1"> Username</label>

                                        <div class="col-md-6 input-icon right">
                                            <input type="text" class="form-control" name="username"
                                                   @if(!is_null($managerData) && isset($managerData->username)) value="{{ $managerData->username }}" @endif>

                                            <span class="help-block">Enter Username</span>
                                            <i class="fa fa-users"></i>
                                        </div>
                                    </div>

                                    <div class="form-group form-md-line-input has-success input-icon right ">

                                    </div>
                                    <div class="form-group form-md-line-input ">
                                        <label class="col-md-2 control-label" for="form_control_1">Password</label>
                                        <div class="col-md-6 input-icon right">
                                            <input type="password" class="form-control" id="password" name="password">
                                            <span class="help-block">Enter password</span>
                                            <i class="fa fa-key"></i>
                                        </div>
                                    </div>
                                    <div class="form-group form-md-line-input ">
                                        <label class="col-md-2 control-label" for="form_control_1">Confirm Password</label>
                                        <div class="col-md-6 input-icon right">
                                            <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                                            <span class="help-block">Enter confirm password</span>
                                            <i class="fa fa-key"></i>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-actions">
                                    <div class="row">
                                        <div class="col-md-offset-3 col-md-9">
                                            <a href="javascript:" class="btn green" id="storeManager">Submit</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{ html()->form()->close() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END PAGE CONTENT INNER -->
    </div>
@stop

@section('footer')
    <script src="{{ asset("admin/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js") }}"></script>
    <script src="{{ asset("admin/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js") }}"></script>
    <script src="{{ asset("admin/global/plugins/ladda/spin.min.js") }}"></script>
    <script src="{{ asset("admin/global/plugins/ladda/ladda.min.js") }}"></script>
    <script src="{{ asset("admin/pages/scripts/ui-buttons.min.js") }}"></script>
    <script src="{{ asset("js/cropper.js") }}"></script>
    <script>
        $('#storeManager').click(function () {
            $.easyAjax({
                url: "{{ route('gym-admin.superadmin.storeManagerPage') }}",
                container: '#managerStoreForm',
                type: 'POST',
                data: $('#managerStoreForm').serialize(),
                file: true
            });
        });

        $('.date-picker').datepicker({
            rtl: App.isRTL(),
            orientation: "left",
            autoclose: true,
            format: "yyyy-mm-dd"
        });
    </script>
@stop
