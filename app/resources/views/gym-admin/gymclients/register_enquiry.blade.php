@extends('layouts.gym-merchant.gymbasic')
<meta name="csrf-token" content="{{ csrf_token() }}">
@section('CSS')
    {!! HTML::style('front/js/cropper/cropper.min.css?v=1.0')!!}
    {!! HTML::style('admin/global/plugins/bootstrap-datepicker/css/datepicker.css') !!}
    {!! HTML::style('admin/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') !!}
    {!! HTML::style('admin/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css') !!}
    {!! HTML::style('admin/global/plugins/ladda/ladda-themeless.min.css') !!}
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
                <a href="{{route('gym-admin.client.index')}}">Customer</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>Add</span>
            </li>
        </ul>
        <!-- END PAGE BREADCRUMBS -->
        <!-- BEGIN PAGE CONTENT INNER -->
        <div class="page-content-inner">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="list" style="list-style-type: none">
                        @foreach ($errors->all() as $error)
                            <li class="item">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{route('gym-admin.client.store')}}" method="post" enctype="multipart/form-data">
                <div class="row">
                    {{csrf_field()}}
                    <div class="col-md-6">
                        <!-- BEGIN SAMPLE FORM PORTLET-->
                        <div class="portlet light ">
                            <div class="portlet-title">
                                <div class="caption font-green">
                                    <i class="icon-pin font-green"></i>
                                    <span class="caption-subject bold uppercase"> Personal Details </span>
                                </div>
                            </div>
                            <div class="portlet-body form">
                                <input type="hidden" name="id" value="{{$enquiry->id}}">
                                <div class="form-body">
                                    <div class="form-group form-md-line-input form-md-floating-label">
                                        <input type="text" class="form-control" value="{{ ucwords($enquiry->customer_name) }}"
                                               id="first_name" name="first_name">
                                        <label for="form_control_1">First Name <span class="required" aria-required="true"> * </span></label>
                                        @if(!$errors->isEmpty())
                                            <span class="help-block"
                                                  style="color:red;">{{$errors->first('first_name')}}</span>
                                        @else
                                            <span class="help-block">Please enter clients first name.</span>
                                        @endif
                                    </div>
                                    <div class="form-group form-md-line-input form-md-floating-label">
                                        <input type="text"
                                               value="{{ ucwords($enquiry->customer_mname) }}"
                                               class="form-control" id="middle_name" name="middle_name">
                                        <label for="form_control_1">Middle Name</label>
                                        <span class="help-block">Please enter clients middle name.</span>
                                    </div>
                                    <div class="form-group form-md-line-input form-md-floating-label">
                                        <input type="text"
                                               value="{{ ucwords($enquiry->customer_lname) }}"
                                               class="form-control" id="last_name" name="last_name">
                                        <label for="form_control_1">Last Name <span class="required" aria-required="true"> * </span></label>
                                        @if(!$errors->isEmpty())
                                            <span class="help-block"
                                                  style="color:red;">{{$errors->first('last_name')}}</span>
                                        @else
                                            <span class="help-block">Please enter clients last name.</span>
                                        @endif                                </div>

                                    <div class="form-group form-md-line-input form-md-floating-label">
                                        <select class="form-control edited" id="gender" name="gender">
                                            <option value=""></option>
                                            <option
                                                    @if($enquiry->sex == "male")
                                                    selected
                                                    @endif
                                                    value="male">Male
                                            </option>
                                            <option
                                                    @if($enquiry->sex == "female")
                                                    selected
                                                    @endif
                                                    value="female">Female
                                            </option>
                                        </select>
                                        <label for="form_control_1">Gender</label>
                                    </div>
                                    <div class="form-group form-md-line-input">
                                        <div class="form-md-radios">
                                            <label>Marital Status</label>
                                            <div class="md-radio-inline">
                                                <div class="md-radio">
                                                    <input type="radio" value="yes" id="yes_radio" name="marital_status"
                                                           class="md-radiobtn">
                                                    <label for="yes_radio">
                                                        <span></span>
                                                        <span class="check"></span>
                                                        <span class="box"></span> Married </label>
                                                </div>
                                                <div class="md-radio ">
                                                    <input type="radio" value="no" id="no_radio" name="marital_status"
                                                           class="md-radiobtn" checked>
                                                    <label for="no_radio">
                                                        <span></span>
                                                        <span class="check"></span>
                                                        <span class="box"></span> Unmarried </label>
                                                </div>
                                            </div>
                                        </div>
                                        <span class="help-block"></span>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <input class="form-control form-control-inline input-small date-picker"
                                                       placeholder="Date of Birth" size="16" type="text" readonly
                                                       value="{{ !empty($enquiry->dob) ? $enquiry->dob->format('m/d/Y') : '' }}" id="dob"
                                                       name="dob"/>
                                                <span class="help-block"> </span>
                                            </div>
                                        </div>
                                        <div class="col-md-6" id="anniversaryDiv">
                                            <div class="form-group">
                                                <input class="form-control form-control-inline input-small date-picker"
                                                       placeholder="Anniversary" size="16" type="text" value=""
                                                       id="anniversary" readonly name="anniversary"/>
                                                <span class="help-block"> </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group form-md-line-input ">
                                        <input type="number" class="form-control" value="{{ $enquiry->age }}" id="age"
                                               name="age">
                                        <label for="form_control_1">Age</label>
                                        <span class="help-block">Please enter clients age.</span>
                                    </div>

                                    <div class="form-group form-md-line-input ">
                                        <input type="text" class="form-control" name="occupation" id="occupation"
                                               value="{{$enquiry->occupation}}">
                                        <label for="form_control_1">Occupation</label>
                                        <div class="form-control-focus"></div>
                                    </div>

                                    <div class="form-group form-md-line-input ">
                                        <textarea name="occupation_details" class="form-control" id="occupation_details"
                                                  cols="30" rows="3">{{$enquiry->occupation_details}}</textarea>
                                        <label for="form_control_1">Occupation Details</label>
                                        <div class="form-control-focus"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- END SAMPLE FORM PORTLET-->
                    </div>
                    <div class="col-md-6">
                        <!-- BEGIN SAMPLE FORM PORTLET-->
                        <div class="portlet light ">
                            <div class="portlet-title">
                                <div class="caption font-green">
                                    <i class="icon-pin font-green"></i>
                                    <span class="caption-subject bold uppercase"> Contact Details</span>
                                </div>
                            </div>
                            <div class="portlet-body form">
                                <div class="form-body">

                                    <div class="form-group form-md-line-input form-md-floating-label">
                                        <input type="text" value="{{ $enquiry->email }}" class="form-control" id="email"
                                               name="email">
                                        <label for="form_control_1">Email <span class="required" aria-required="true"> * </span></label>
                                        @if(!$errors->isEmpty())
                                            <span class="help-block"
                                                  style="color:red;">{{$errors->first('email')}}</span>
                                        @else
                                            <span class="help-block">Please enter clients email.</span>
                                        @endif                                    </div>

                                    <div class="form-group form-md-line-input form-md-floating-label">
                                        <input type="number" value="{{ $enquiry->mobile }}" class="form-control"
                                               id="mobile" name="mobile">
                                        <label for="form_control_1">Phone <span class="required" aria-required="true"> * </span></label>
                                        @if(!$errors->isEmpty())
                                            <span class="help-block"
                                                  style="color:red;">{{$errors->first('mobile')}}</span>
                                        @else
                                            <span class="help-block">Please enter clients phone number.</span>
                                        @endif                                    </div>

                                    <div class="form-group form-md-line-input form-md-floating-label">
                                        <input type="number" value="{{ $enquiry->mobile }}" class="form-control"
                                               id="emergency_contact" name="emergency_contact">
                                        <label for="emergency_contact">Emergency Contanct NO. <span class="required" aria-required="true"> * </span></label>
                                        @if(!$errors->isEmpty())
                                            <span class="help-block"
                                                  style="color:red;">{{$errors->first('emergency_contact')}}</span>
                                        @else
                                            <span class="help-block">Please enter clients emergency contact number.</span>
                                        @endif                                    </div>

                                    <div class="form-group form-md-line-input form-md-floating-label">
                                        <textarea class="form-control" rows="3" name="address"
                                                  id="address">{{ $enquiry->address }}</textarea>
                                        <label for="form_control_1">Address</label>
                                    </div>

                                    <div class="row">
                                        <div class="col-xs-12">
                                            <label>Height</label>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group form-md-line-input ">
                                                <input type="number" value="{{ $enquiry->height_feet }}"
                                                       class="form-control" id="height_feet" name="height_feet"
                                                       placeholder="feet">
                                                <span class="help-block">Enter feet.</span>
                                            </div>

                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group form-md-line-input ">
                                                <input type="number" class="form-control"
                                                       value="{{ $enquiry->height_inches }}" id="height_inches"
                                                       name="height_inches" placeholder="inches">
                                                <span class="help-block">Enter inches.</span>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group form-md-line-input form-md-floating-label">
                                                <input type="number" class="form-control" value="{{ $enquiry->weight }}"
                                                       id="weight" name="weight">
                                                <label for="form_control_1">Weight</label>
                                                <span class="help-block">Please enter client's weight.</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group form-md-line-input form-md-floating-label">
                                                <input type="number" min="0" class="form-control" id="fat" name="fat">
                                                <label for="form_control_1">Fats</label>
                                                <span class="help-block">Please enter client's Fat in %</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group form-md-line-input form-md-floating-label">
                                                <input type="number" min="0" class="form-control" id="chest"
                                                       name="chest">
                                                <label for="form_control_1">Chest</label>
                                                <span class="help-block">Please enter client's Chest in inch.</span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group form-md-line-input form-md-floating-label">
                                                <input type="number" min="0" class="form-control" id="waist"
                                                       name="waist">
                                                <label for="form_control_1">Waist</label>
                                                <span class="help-block">Please enter client's waist in inch.</span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group form-md-line-input form-md-floating-label">
                                                <input type="number" min="0" class="form-control" id="arms" name="arms">
                                                <label for="form_control_1">Arms</label>
                                                <span class="help-block">Please enter client's arms in inch.</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group form-md-line-input form-md-floating-label">
                                        <select class="form-control edited" id="source" name="source">
                                            <option value="">Select Source</option>
                                            <option value="Website"
                                                    @if($enquiry->come_to_know == "Website")selected @endif>Website
                                            </option>
                                            <option value="SMS" @if($enquiry->come_to_know == "SMS")selected @endif>
                                                SMS
                                            </option>
                                            <option value="Newspaper"
                                                    @if($enquiry->come_to_know == "Newspaper")selected @endif>Newspaper
                                            </option>
                                            <option value="Hoarding"
                                                    @if($enquiry->come_to_know == "Hoarding")selected @endif>Hoarding
                                            </option>
                                            <option value="Existing Member"
                                                    @if($enquiry->come_to_know == "Existing Member")selected @endif>
                                                Existing Member
                                            </option>
                                            <option value="Family"
                                                    @if($enquiry->come_to_know == "Family")selected @endif>Family
                                            </option>
                                            <option value="Friends"
                                                    @if($enquiry->come_to_know == "Friends")selected @endif>Friends
                                            </option>
                                            <option value="Doctor"
                                                    @if($enquiry->come_to_know == "Doctor")selected @endif>Doctor
                                            </option>
                                            <option value="Old Member"
                                                    @if($enquiry->come_to_know == "Old Member")selected @endif>Newspaper
                                            </option>
                                            <option value="Huntplex"
                                                    @if($enquiry->come_to_know == "Huntplex")selected @endif>Huntplex
                                            </option>
                                            <option value="Email" @if($enquiry->come_to_know == "Email")selected @endif>
                                                Email
                                            </option>
                                            <option value="Others"
                                                    @if($enquiry->come_to_know == "Others")selected @endif>Others
                                            </option>
                                        </select>
                                        <label for="form_control_1">Select Source</label>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group form-md-line-input form-md-floating-label">
                                                <select class="form-control edited" id="blood_group" name="blood_group">
                                                    <option value="" >Select Blood Group</option>
                                                    <option value="a+" @if(old('blood_group')=="a+") selected @endif >A+</option>
                                                    <option value="a-"@if(old('blood_group')=="a-") selected @endif>A-</option>
                                                    <option value="b+" @if(old('blood_group')=="b+") selected @endif >B+</option>
                                                    <option value="b-"@if(old('blood_group')=="b-") selected @endif>B-</option>
                                                    <option value="ab+" @if(old('blood_group')=="ab+") selected @endif >AB+</option>
                                                    <option value="ab-"@if(old('blood_group')=="ab-") selected @endif>AB-</option>
                                                    <option value="o+" @if(old('blood_group')=="o+") selected @endif >O+</option>
                                                    <option value="o-"@if(old('blood_group')=="o-") selected @endif>O-</option>
                                                </select>
                                                <label for="blood_group">Blood Group <span class="required" aria-required="true">  </span></label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="form-md-radios">
                                                    <label>Experience in Gym</label>
                                                    <div class="md-radio-inline">
                                                        <div class="md-radio">
                                                            <input type="radio" value="1" id="yes" name="is_gym_experience" class="md-radiobtn">
                                                            <label for="yes">
                                                                <span></span>
                                                                <span class="check"></span>
                                                                <span class="box"></span> Yes </label>
                                                        </div>
                                                        <div class="md-radio ">
                                                            <input type="radio" value="0" id="no" checked name="is_gym_experience" class="md-radiobtn" >
                                                            <label for="no">
                                                                <span></span>
                                                                <span class="check"></span>
                                                                <span class="box"></span> No </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <span class="help-block"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <!-- END SAMPLE FORM PORTLET-->
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light ">
                            <div class="row">
                                <div class="col-md-offset-5 col-md-1">
                                    <button type="submit" class="btn blue mt-ladda-btn ladda-button">
                                        <span class="ladda-label"><i class="icon-arrow-up"></i>  Save</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@stop

@section('footer')
    {!! HTML::script('admin/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') !!}
    {!! HTML::style('admin/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') !!}
    {!! HTML::script('admin/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js') !!}
    {!! HTML::script('admin/global/plugins/ladda/spin.min.js') !!}
    {!! HTML::script('admin/global/plugins/ladda/ladda.min.js') !!}
    {!! HTML::script('admin/pages/scripts/ui-buttons.min.js') !!}
    {!! HTML::script("front/js/cropper/crop-avatar.js?v=1.0")!!}
    {!! HTML::script("front/js/cropper/cropper.min.js?v=1.0")!!}
    <script>
        $('#dob').datepicker({
            rtl: App.isRTL(),
            orientation: "left",
            autoclose: true,
            endDate: '+0d',
            startView: 'decades'
        });
        $('#anniversary').datepicker({
            rtl: App.isRTL(),
            orientation: "left",
            autoclose: true
        });
        $('#joining_date').datepicker({
            rtl: App.isRTL(),
            orientation: "left",
            autoclose: true,
            endDate: '+0d'
        });
    </script>
    <script>
        $('#upload_clients').click(function () {
            $.easyAjax({
                url: "{{route('gym-admin.client.store')}}",
                container: '#clients_details',
                type: "POST",
                file: true,
                formReset: true
            })
        });
    </script>
    <script>
        $(function () {
            var value = $('input[name=marital_status]:checked').val();
            if (value == 'no') {
                $('#anniversaryDiv').css('display', 'none');
            } else {
                $('#anniversaryDiv').css('display', 'block');
            }
        });
        $('#dob').change(function () {
            var lre = /^\s*/;
            var inputDate = document.getElementById('dob').value;
            inputDate = inputDate.replace(lre, "");
            age = get_age(new Date(inputDate));
            $('#age').val(age);
        });
        $('input[name=marital_status]').on('change', function () {
            var value = $('input[name=marital_status]:checked').val();
            if (value == 'no') {
                $('#anniversaryDiv').css('display', 'none');
            } else {
                $('#anniversaryDiv').css('display', 'block');
            }
        });
        function get_age(birth) {
            var today = new Date();
            var nowyear = today.getFullYear();
            var nowmonth = today.getMonth();
            var nowday = today.getDate();
            var birthyear = birth.getFullYear();
            var birthmonth = birth.getMonth();
            var birthday = birth.getDate();
            var age = nowyear - birthyear;
            var age_month = nowmonth - birthmonth;
            var age_day = nowday - birthday;
            if (age_month < 0 || (age_month == 0 && age_day < 0)) {
                age = parseInt(age) - 1;
            }
            return age;
        }
    </script>


@stop