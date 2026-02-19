@extends('layouts.gym-merchant.gymbasic')

@section('CSS')
    <link rel="stylesheet" href="{{ asset("admin/global/plugins/ladda/ladda-themeless.min.css") }}">
@stop

@section('content')
    <div class="container-fluid"  >
        <!-- BEGIN PAGE BREADCRUMBS -->
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <a href="{{ route('gym-admin.dashboard.index') }}">Home</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <a href="{{ route('gym-admin.gymmerchantroles.index') }}">Roles</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>Edit Role</span>
            </li>
        </ul>
        <!-- END PAGE BREADCRUMBS -->
        <!-- BEGIN PAGE CONTENT INNER -->
        <div class="page-content-inner">
            <div class="row">
                <div class="col-md-7 col-xs-12">

                    <div class="portlet light ">
                        <div class="portlet-title">
                            <div class="caption font-dark">
                                <i class="icon-pencil font-red"></i>
                                <span class="caption-subject font-red bold uppercase"> Edit Role</span>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <!-- {{ html()->form()->open(['id'=>'profileUpdateForm','class'=>'ajax-form form-horizontal','method'=>'PUT','files' => true]) }} -->
                            <form action="{{route('gym-admin.gymmerchantroles.updateData', $role->id)}}" method="post">
                            {{csrf_field()}}
                            <div class="form-body">
                                <div class="form-group form-md-line-input">
                                    <label class="col-md-2 control-label" for="form_control_1">Role Name</label>
                                    <div class="col-md-6">
                                        <div class="input-icon right">
                                            <input type="text" class="form-control" placeholder="Role Name" name="name" id="name" value="{{ $role->name }}">
                                            <div class="form-control-focus"> </div>
                                            <span class="help-block">Enter role name</span>
                                            <i class="icon-user"></i>
                                        </div>
                                    </div>
                                </div>

                                <hr>
                            </div>

                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-offset-3 col-md-9">
                                        <!-- <a href="javascript:;" class="btn green" id="updateProfile">Submit</a> -->
                                        <button class="btn btn-success" type="submit">Update</button>
                                        <a href="{{ route('gym-admin.users.index') }}" class="btn default">Cancel</a>
                                    </div>
                                </div>
                            </div>
                            </form>
                        </div>
                    </div>


                </div>
            </div>
        </div>
        <!-- END PAGE CONTENT INNER -->
    </div>
@stop

@section('footer')

    <script src="{{ asset("admin/global/plugins/ladda/spin.min.js") }}"></script>
    <script src="{{ asset("admin/global/plugins/ladda/ladda.min.js") }}"></script>

@stop