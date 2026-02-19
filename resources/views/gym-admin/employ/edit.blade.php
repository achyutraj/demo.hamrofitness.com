@extends('layouts.gym-merchant.gymbasic')

@section('CSS')
    <link rel="stylesheet" href="{{ asset("admin/global/plugins/ladda/ladda-themeless.min.css") }}">
    <link rel="stylesheet" href="{{ asset("admin/global/plugins/bootstrap-select/css/bootstrap-select.min.css") }}">
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
                <a href="{{ route('gym-admin.users.showEmployee') }}">Employees</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>Edit Employee</span>
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
                                <span class="caption-subject font-red bold uppercase"> Edit Employee</span>
                            </div>
                        </div>
                        <div class="portlet-body p-0">
                            <form action="{{ route('gym-admin.users.updateEmployee', $employ->id) }}" method="post">
                                {{ csrf_field() }}
                                <div class="form-body col-md-12 p-0">
                                    <div class="form-group col-md-4 form-md-line-input">
                                        <label class="control-label" for="first_name">First Name</label>
                                        <div class="input-icon right">
                                            <input type="text" value="{{ $employ->first_name }}" class="form-control"
                                                placeholder="First Name" name="first_name" id="fisrt_name">
                                            <div class="form-control-focus"></div>
                                            <span class="help-block">Enter first name</span>
                                            <i class="icon-user"></i>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4 form-md-line-input">
                                        <label class="control-label" for="middle_name">Middle Name</label>
                                        <div class="input-icon right">
                                            <input type="text" value="{{ $employ->middle_name }}" class="form-control"
                                                placeholder="Middle Name" name="middle_name" id="middle_name">
                                            <div class="form-control-focus"></div>
                                            <span class="help-block">Enter middle name</span>
                                            <i class="icon-user"></i>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4 form-md-line-input">
                                        <label class="control-label" for="last_name">Last Name</label>
                                        <div class="input-icon right">
                                            <input type="text" value="{{ $employ->last_name }}" class="form-control"
                                                placeholder="Last Name" name="last_name" id="last_name">
                                            <div class="form-control-focus"></div>
                                            <span class="help-block">Enter last name</span>
                                            <i class="icon-user"></i>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4 form-md-line-input">
                                        <label class="control-label" for="mobile">Mobile</label>
                                        <div class="input-icon right">
                                            <input type="number" class="form-control" value="{{ $employ->mobile }}"
                                                placeholder="Mobile number" id="mobile" name="mobile">
                                            <div class="form-control-focus"></div>
                                            <span class="help-block">Mobile number</span>
                                            <i class="fa fa-mobile"></i>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4 form-md-line-input">
                                        <label class="control-label" for="email">Email</label>

                                        <div class="input-icon right">
                                            <input type="email" value="{{ $employ->email }}" class="form-control"
                                                placeholder="Email" id="email" name="email">
                                            <div class="form-control-focus"></div>
                                            <span class="help-block">Email address</span>
                                            <i class="fa fa-envelope"></i>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4 form-md-line-input">
                                        <label class="control-label" for="date_of_birth">Date of Birth</label>

                                        <div class="input-icon right">
                                            <input readonly name="date_of_birth" value="{{ $employ->date_of_birth }}"
                                                id="date_of_birth" type="text" class="form-control  date-picker"
                                                data-date-format="yyyy-mm-dd" placeholder="Date of birth">
                                            <div class="form-control-focus"></div>
                                            <span class="help-block">Enter date of birth</span>
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                    </div>

                                    <div class="form-group col-md-6 form-md-radios">
                                        <label class="control-label" for="gender">Gender</label>
                                        <div class="md-radio-inline">
                                            <div class="md-radio">
                                                <input type="radio" value="male" id="male_radio" name="gender"
                                                    class="md-radiobtn" @if ($employ->gender == 'male') checked @endif>
                                                <label for="male_radio">
                                                    <span></span>
                                                    <span class="check"></span>
                                                    <span class="box"></span> Male </label>
                                            </div>
                                            <div class="md-radio ">
                                                <input type="radio" value="female" id="female_radio" name="gender"
                                                    class="md-radiobtn" @if ($employ->gender == 'female') checked @endif>
                                                <label for="female_radio">
                                                    <span></span>
                                                    <span class="check"></span>
                                                    <span class="box"></span> Female </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group col-md-6 form-md-line-input">
                                        <label class="control-label" for="position">Position</label>
                                        <div class="input-icon right">
                                            <input type="text" value="{{ $employ->position }}" name="position"
                                                id="position" class="form-control" placeholder="Position">
                                            <div class="form-control-focus"></div>
                                            <span class="help-block">Enter Position</span>
                                            <i class="fa fa-user-plus"></i>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6 form-md-line-input ">
                                        <label class="control-label" for="username">Username</label>
                                        <div class="input-icon right">
                                            <input type="text" readonly value="{{ $employ->username }}"
                                                class="form-control" placeholder="Username" name="username">

                                            <span class="help-block">This cannot be changed later</span>
                                            <div class="form-control-focus"></div>
                                            <i class="fa fa-users"></i>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6 form-md-line-input ">
                                        <label class="control-label" for="password">Password</label>
                                        <div class="input-icon right">
                                            <input type="password" value="" class="form-control"
                                                placeholder="New password" id="password" name="password">

                                            <span class="help-block">Enter password </span>
                                            <div class="form-control-focus"></div>
                                            <i class="fa fa-key"></i>
                                        </div>
                                    </div>
                                    <?php
                                    $employRole = $employ->merchant->roles->first();
                                    ?>
                                    <div class="form-group col-md-12 form-md-line-input">
                                        <label for="title">Role</label>
                                        <select class="bs-select form-control" data-live-search="true" data-size="8"
                                            name="role[]" required>
                                            <option value="">Select Role</option>
                                            @foreach ($role as $roles)
                                                <option value="{{ $roles->id }}"
                                                    @if (!is_null($employRole) && $employRole->id == $roles->id) selected @endif>
                                                    {{ $roles->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                </div>
                                <div class="form-actions">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <button class="btn btn-success" type="submit">Update</button>
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
    <script src="{{ asset("admin/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js") }}"></script>
    <link rel="stylesheet" href="{{ asset("admin/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css") }}">
    <script src="{{ asset("admin/global/plugins/ladda/spin.min.js") }}"></script>
    <script src="{{ asset("admin/global/plugins/ladda/ladda.min.js") }}"></script>
    <script src="{{ asset("admin/pages/scripts/ui-buttons.min.js") }}"></script>
    <script src="{{ asset("admin/global/plugins/bootstrap-select/js/bootstrap-select.min.js") }}"></script>
    <script src="{{ asset("admin/pages/scripts/components-bootstrap-select.min.js") }}"></script>
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
        $(function() {
            $('#myTab li:first-child a').tab('show')
        })
    </script>

@stop
