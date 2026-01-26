@extends('layouts.gym-merchant.gymbasic')

@section('CSS')
    {!! HTML::style('admin/global/plugins/ladda/ladda-themeless.min.css') !!}
    {!! HTML::style('admin/global/plugins/bootstrap-select/css/bootstrap-select.min.css') !!}
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
                <a href="{{ route('gym-admin.users.showEmployee') }}">Employees</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>Add Employee</span>
            </li>
        </ul>
        <!-- END PAGE BREADCRUMBS -->
        <!-- BEGIN PAGE CONTENT INNER -->
        <div class="page-content-inner">
            <div class="row">
                <div class="col-md-10 col-xs-12">

                    <div class="portlet light ">
                        <div class="portlet-title">
                            <div class="caption font-dark">
                                <i class="icon-plus font-red"></i>
                                <span class="caption-subject font-red bold uppercase"> Add Employee</span>
                            </div>
                        </div>
                        <div class="portlet-body p-0">
                            <form action="{{route('gym-admin.users.storeEmployee')}}" class="form" method="post">
                                {{ csrf_field() }}
                                <div class="form-body col-md-12 p-0">
                                    <div class="form-group col-md-4 form-md-line-input">
                                        <label class="control-label" for="first_name">First Name <span class="required" aria-required="true"> * </span></label>
                                        <div class="input-icon right">
                                            <input type="text" value="{{old('first_name')}}" class="form-control" placeholder="First Name" name="first_name" id="fisrt_name" required>
                                            <div class="form-control-focus"> </div>
                                            @if(!$errors->isEmpty())
                                                <span class="alert-danger" style="color:red;">{{$errors->first('first_name')}}</span>
                                            @else
                                                <span class="help-block">Enter first name</span>
                                            @endif
                                            <i class="icon-user"></i>
                                        </div>
                                    </div>

                                    <div class="form-group col-md-4 form-md-line-input">
                                        <label class="control-label" for="middle_name">Middle Name</label>

                                        <div class="input-icon right">
                                            <input type="text" value="{{old('middle_name')}}" class="form-control" placeholder="Middle Name" name="middle_name" id="middle_name">
                                            <div class="form-control-focus"> </div>
                                            @if($errors->isEmpty())
                                                <span class="help-block">Enter middle name</span>
                                            @else
                                                <span class="alert-danger" style="color:red;">{{$errors->first('middle_name')}}</span>
                                            @endif
                                            <i class="icon-user"></i>
                                        </div>
                                    </div>

                                    <div class="form-group col-md-4 form-md-line-input">
                                        <label class="control-label" for="last_name">Last Name <span class="required" aria-required="true"> * </span></label>

                                        <div class="input-icon right">
                                            <input type="text" value="{{old('last_name')}}" class="form-control" placeholder="Last Name" name="last_name" id="last_name" required>
                                            <div class="form-control-focus"> </div>
                                        @if($errors->isEmpty())
                                                <span class="help-block">Enter last name</span>
                                            @else
                                                <span class="alert-danger" style="color:red;">{{$errors->first('last_name')}}</span>
                                            @endif
                                            <i class="icon-user"></i>
                                        </div>

                                    </div>

                                    <div class="form-group col-md-4 form-md-line-input">
                                        <label class="control-label" for="mobile">Mobile <span class="required" aria-required="true"> * </span></label>

                                        <div class="input-icon right">
                                            <input type="number" value="{{old('mobile')}}" class="form-control" placeholder="Mobile number" id="mobile" name="mobile" required>
                                            <div class="form-control-focus"> </div>
                                            @if($errors->isEmpty())
                                                <span class="help-block">Mobile number</span>
                                            @else
                                                <span class="alert-danger" style="color:red;">{{$errors->first('mobile')}}</span>
                                            @endif
                                            <i class="fa fa-mobile"></i>
                                        </div>
                                    </div>

                                    <div class="form-group col-md-4 form-md-line-input">
                                        <label class="control-label" for="email">Email <span class="required" aria-required="true"> * </span></label>

                                        <div class="input-icon right">
                                            <input type="email" class="form-control" placeholder="Email" id="email" value="{{old('email')}}" name="email" required>
                                            <div class="form-control-focus"> </div>
                                            @if($errors->isEmpty())
                                                <span class="help-block">Email address</span>
                                            @else
                                                <span class="alert-danger" style="color:red;">{{$errors->first('email')}}</span>
                                            @endif
                                            <i class="fa fa-envelope"></i>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4 form-md-line-input">
                                        <label class="control-label" for="date_of_birth">Date of Birth</label>
                                        <div class="input-icon right">
                                            <input readonly value="{{old('date_of_birth')}}" name="date_of_birth" id="date_of_birth" type="text"  class="form-control  date-picker" data-date-format="yyyy-mm-dd"  placeholder="Date of birth">
                                            <div class="form-control-focus"> </div>
                                            @if($errors->isEmpty())
                                                <span class="help-block">Enter date of birth</span>
                                            @else
                                                <span class="alert-danger" style="color:red;">{{$errors->first('date_of_birth')}}</span>
                                            @endif
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6 form-md-radios">
                                        <label class="control-label" for="gender">Gender</label>
                                        <div class="md-radio-inline">
                                            <div class="md-radio">
                                                <input type="radio" id="male" name="gender" value="male" class="md-radiobtn" checked @if(old('gender') == 'male') checked @endif >
                                                <label for="male">
                                                    <span></span>
                                                    <span class="check"></span>
                                                    <span class="box"></span> Male </label>
                                            </div>
                                            <div class="md-radio">
                                                <input type="radio" id="female" name="gender" value="female" class="md-radiobtn" @if(old('gender') == 'female') checked @endif >
                                                <label for="female">
                                                    <span></span>
                                                    <span class="check"></span>
                                                    <span class="box"></span> Female </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6 form-md-line-input">
                                        <label class="control-label" for="position">Position <span class="required" aria-required="true"> * </span></label>

                                        <div class="input-icon right">
                                            <input type="text" value="{{old('position')}}" name="position" id="position" type="text"  class="form-control" placeholder="Position" required>
                                            <div class="form-control-focus"> </div>
                                            @if($errors->isEmpty())
                                                <span class="help-block">Enter Position</span>
                                            @else
                                                <span class="alert-danger" style="color:red;">{{$errors->first('position')}}</span>
                                            @endif
                                            <i class="fa fa-user-plus"></i>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6 form-md-line-input">
                                        <label class="control-label" for="username">Username <span class="required" aria-required="true"> * </span></label>

                                        <div class="input-icon right">
                                            <input type="text" value="{{old('username')}}"  class="form-control" placeholder="Username" name="username" required>
                                            @if($errors->isEmpty())
                                                <span class="help-block"></span>
                                            @else
                                                <div class="form-control-focus"> </div>
                                                <span class="alert-danger" style="color:red;">{{$errors->first('username')}}</span>
                                            @endif
                                            <i class="fa fa-users"></i>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6 form-md-line-input">
                                        <label class="control-label" for="password">Password <span class="required" aria-required="true"> * </span></label>

                                        <div class="input-icon right">
                                            <input type="password" class="form-control" placeholder="New password" id="password" name="password" required>
                                            @if($errors->isEmpty())
                                                <span class="help-block">Enter password </span>
                                                <div class="form-control-focus"> </div>
                                            @else
                                                <span class="alert-danger" style="color:red;">{{$errors->first('password')}}</span>
                                            @endif
                                            <i class="fa fa-key"></i>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-12 form-md-line-input">
                                        <label for="title">Role <span class="required" aria-required="true"> * </span></label>
                                        <select  class="bs-select form-control" data-live-search="true" data-size="8" name="role[]" required>
                                            <option value="">Select Role</option>
                                            @foreach($role as $roles)
                                                <option value="{{$roles->id}}" >{{$roles->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <button class="btn btn-success" type="submit" id="updateProfile">Submit</button>
                                            <a class="btn btn-danger">Cancel</a>
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
    {!! HTML::script('admin/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') !!}
    {!! HTML::style('admin/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') !!}
    {!! HTML::script('admin/global/plugins/ladda/spin.min.js') !!}
    {!! HTML::script('admin/global/plugins/ladda/ladda.min.js') !!}
    {!! HTML::script('admin/pages/scripts/ui-buttons.min.js') !!}
    {!! HTML::script('admin/global/plugins/bootstrap-select/js/bootstrap-select.min.js') !!}
    {!! HTML::script('admin/pages/scripts/components-bootstrap-select.min.js') !!}
    <script>

        $('#date_of_birth').datepicker({
            rtl: App.isRTL(),
            orientation: "left",
            autoclose: true,
            endDate: '+0d',
            startView: 'decades'
        });

    </script>
    <script>
        $(function () {
            $('#myTab li:first-child a').tab('show')
        })
    </script>

@stop
