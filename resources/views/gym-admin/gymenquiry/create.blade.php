@extends('layouts.gym-merchant.gymbasic')

@section('CSS')

    {!! HTML::style('admin/global/plugins/ladda/ladda-themeless.min.css') !!}
    {!! HTML::style('admin/global/plugins/bootstrap-datepicker/css/datepicker.css') !!}
    {!! HTML::style('admin/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') !!}

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
                <a href="{{ route('gym-admin.enquiry.index') }}">Leads</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>Add Lead</span>
            </li>
        </ul>
        <!-- END PAGE BREADCRUMBS -->
        <!-- BEGIN PAGE CONTENT INNER -->
        <div class="page-content-inner">
            <div class="row">
                <div class="col-md-12">
                    <div class="portlet light">
                        <div class="portlet-title">
                            <div class="caption font-red">
                                <i class="bold uppercase font-red icon-plus"></i> Add Enquiry
                            </div>
                        </div>

                        <div class="portlet-body form">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="list" style="list-style-type: none">
                                        @foreach ($errors->all() as $error)
                                            <li class="item">{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <form action="{{route('gym-admin.enquiry.store')}}" method="post"
                                      enctype="multipart/form-data">
                                @csrf
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group form-md-line-input ">
                                            <input type="text" readonly data-provide="datepicker"
                                                   data-date-end-date="0d" data-date-today-highlight="true"
                                                   class="form-control date-picker"
                                                   value="{{ \Carbon\Carbon::today()->format('m/d/Y') }}"
                                                   name="enquiry_date" id="enquiry_date">
                                            <label for="form_control_1">Enquiry Date</label>
                                            <div class="form-control-focus"></div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group form-md-line-input ">
                                            <input type="text" class="form-control" name="customer_name" value="{{ old('customer_name') }}"
                                                   id="customer_name">
                                            <label for="form_control_1">Customer First Name <span class="required"
                                                                                            aria-required="true"> * </span></label>
                                            <div class="form-control-focus"></div>
                                        </div>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group form-md-line-input ">
                                            <input type="text" class="form-control" name="customer_mname" value="{{ old('customer_mname') }}"
                                                   id="customer_mname">
                                            <label for="form_control_1">Customer Middle Name</label>
                                            <div class="form-control-focus"></div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group form-md-line-input ">
                                            <input type="text" class="form-control" name="customer_lname" value="{{ old('customer_lname') }}"
                                                   id="customer_lname">
                                            <label for="form_control_1">Customer Last Name <span class="required"
                                                                                            aria-required="true"> * </span></label>
                                            <div class="form-control-focus"></div>
                                        </div>
                                    </div>

                                </div>

                                <div class="row">

                                    <div class="col-md-6">
                                        <div class="form-group form-md-line-input">
                                            <input type="number" class="form-control" name="mobile" id="mobile" value="{{ old('mobile') }}">
                                            <label for="form_control_1">Mobile <span class="required"
                                                                                     aria-required="true"> * </span></label>
                                            <div class="form-control-focus"></div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group form-md-line-input">
                                            <input type="email" class="form-control" name="email" value="{{ old('email') }}">
                                            <label for="form_control_1">Customer Email <span class="required"
                                                aria-required="true"> * </span></label>
                                            <div class="form-control-focus"></div>
                                        </div>
                                    </div>

                                </div>

                                <div class="form-group form-md-line-input ">
                                    <textarea name="address" class="form-control" id="address" cols="30"
                                              rows="3">{{ old('address') }}</textarea>
                                    <label for="form_control_1">Address <span class="required"
                                        aria-required="true"> * </span></label>
                                    <div class="form-control-focus"></div>
                                </div>


                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group form-md-line-input ">
                                            <input type="text" readonly class="form-control date-picker"
                                                   data-provide="datepicker" data-date-end-date="0d"
                                                   data-date-start-view="decades" data-date-autoclose="true" name="dob"
                                                   id="dob">
                                            <label for="form_control_1">Date of Birth</label>
                                            <div class="form-control-focus"></div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group form-md-line-input">
                                            <input type="number" min="0" class="form-control" name="age" id="age">
                                            <label for="form_control_1">Age</label>
                                            <div class="form-control-focus"></div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group form-md-line-input">
                                            <div class="form-group form-md-radios">
                                                <div class="md-radio-inline">
                                                    <div class="md-radio">
                                                        <input type="radio" id="sex-1" checked name="sex" value="Male"
                                                               class="md-radiobtn">
                                                        <label for="sex-1">
                                                            <span></span>
                                                            <span class="check"></span>
                                                            <span class="box"></span> Male </label>
                                                    </div>
                                                    <div class="md-radio">
                                                        <input type="radio" id="sex-2" name="sex" class="md-radiobtn"
                                                               value="Female">
                                                        <label for="sex-2">
                                                            <span></span>
                                                            <span class="check"></span>
                                                            <span class="box"></span> Female</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group form-md-line-input">
                                            <input type="number" min="0" class="form-control" value="0"
                                                   name="height_feet" id="height_feet">
                                            <label for="form_control_1">Height Feet</label>
                                            <div class="form-control-focus"></div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group form-md-line-input">
                                            <input type="number" min="0" class="form-control" value="0"
                                                   name="height_inches" id="height_inches">
                                            <label for="form_control_1">Height Inches</label>
                                            <div class="form-control-focus"></div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group form-md-line-input">
                                            <input type="number" min="0" class="form-control" value="0" name="weight"
                                                   id="weight">
                                            <label for="form_control_1">Weight</label>
                                            <div class="form-control-focus"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group form-md-line-input">
                                    <label class="control-label">Occupation</label>
                                    <div class="form-group form-md-radios">
                                        <div class="md-radio-inline">
                                            <div class="md-radio">
                                                <input type="radio" id="professional" name="occupation"
                                                       value="Professional" class="md-radiobtn">
                                                <label for="professional">
                                                    <span></span>
                                                    <span class="check"></span>
                                                    <span class="box"></span> Professional </label>
                                            </div>
                                            <div class="md-radio">
                                                <input type="radio" id="business" name="occupation" class="md-radiobtn"
                                                       value="Business">
                                                <label for="business">
                                                    <span></span>
                                                    <span class="check"></span>
                                                    <span class="box"></span> Business</label>
                                            </div>
                                            <div class="md-radio">
                                                <input type="radio" id="service" name="occupation" class="md-radiobtn"
                                                       value="Service">
                                                <label for="service">
                                                    <span></span>
                                                    <span class="check"></span>
                                                    <span class="box"></span> Service</label>
                                            </div>
                                            <div class="md-radio">
                                                <input type="radio" id="homemaker" name="occupation" class="md-radiobtn"
                                                       value="Homemaker">
                                                <label for="homemaker">
                                                    <span></span>
                                                    <span class="check"></span>
                                                    <span class="box"></span> Homemaker</label>
                                            </div>
                                            <div class="md-radio">
                                                <input type="radio" id="student" name="occupation" class="md-radiobtn"
                                                       value="Student">
                                                <label for="student">
                                                    <span></span>
                                                    <span class="check"></span>
                                                    <span class="box"></span> Student</label>
                                            </div>
                                            <div class="md-radio">
                                                <input type="radio" id="other" name="occupation" checked
                                                       class="md-radiobtn" value="Other">
                                                <label for="other">
                                                    <span></span>
                                                    <span class="check"></span>
                                                    <span class="box"></span> Other</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group form-md-line-input ">
                                    <textarea name="occupation_details" class="form-control" id="occupation_details"
                                              cols="30" rows="3"></textarea>
                                    <label for="form_control_1">Occupation Details</label>
                                    <div class="form-control-focus"></div>
                                </div>

                                <div class="form-group form-md-line-input">
                                    <label class="control-label">How did you get to know about us?</label>
                                    <div class="form-group form-md-radios">
                                        <div class="md-radio-inline">
                                            <div class="md-radio">
                                                <input type="radio" id="website" name="come_to_know" value="Website"
                                                       class="md-radiobtn">
                                                <label for="website">
                                                    <span></span>
                                                    <span class="check"></span>
                                                    <span class="box"></span> Website </label>
                                            </div>
                                            <div class="md-radio">
                                                <input type="radio" id="news-paper" name="come_to_know"
                                                       value="News paper" class="md-radiobtn">
                                                <label for="news-paper">
                                                    <span></span>
                                                    <span class="check"></span>
                                                    <span class="box"></span> News paper </label>
                                            </div>
                                            <div class="md-radio">
                                                <input type="radio" id="hoarding" name="come_to_know" value="Hoarding"
                                                       class="md-radiobtn">
                                                <label for="hoarding">
                                                    <span></span>
                                                    <span class="check"></span>
                                                    <span class="box"></span> Hoarding</label>
                                            </div>
                                            <div class="md-radio">
                                                <input type="radio" id="existing-member" name="come_to_know"
                                                       value="Existing Member" class="md-radiobtn">
                                                <label for="existing-member">
                                                    <span></span>
                                                    <span class="check"></span>
                                                    <span class="box"></span> Existing Member</label>
                                            </div>
                                            <div class="md-radio">
                                                <input type="radio" id="family" name="come_to_know" value="Family"
                                                       class="md-radiobtn">
                                                <label for="family">
                                                    <span></span>
                                                    <span class="check"></span>
                                                    <span class="box"></span> Family </label>
                                            </div>
                                            <div class="md-radio">
                                                <input type="radio" id="friends" name="come_to_know" value="Friends"
                                                       class="md-radiobtn">
                                                <label for="friends">
                                                    <span></span>
                                                    <span class="check"></span>
                                                    <span class="box"></span> Friends </label>
                                            </div>
                                            <div class="md-radio">
                                                <input type="radio" id="doctor" name="come_to_know" value="Doctor"
                                                       class="md-radiobtn">
                                                <label for="doctor">
                                                    <span></span>
                                                    <span class="check"></span>
                                                    <span class="box"></span> Doctor </label>
                                            </div>
                                            <div class="md-radio">
                                                <input type="radio" id="old-member" name="come_to_know"
                                                       value="Old Member" class="md-radiobtn">
                                                <label for="old-member">
                                                    <span></span>
                                                    <span class="check"></span>
                                                    <span class="box"></span> Old Member </label>
                                            </div>
                                            <div class="md-radio">
                                                <input type="radio" id="sms" name="come_to_know" value="SMS"
                                                       class="md-radiobtn">
                                                <label for="sms">
                                                    <span></span>
                                                    <span class="check"></span>
                                                    <span class="box"></span> SMS </label>
                                            </div>
                                            <div class="md-radio">
                                                <input type="radio" id="email" name="come_to_know" value="Email"
                                                       class="md-radiobtn">
                                                <label for="email">
                                                    <span></span>
                                                    <span class="check"></span>
                                                    <span class="box"></span> Email </label>
                                            </div>
                                            <div class="md-radio">
                                                <input type="radio" id="others" name="come_to_know" checked
                                                       value="Others" class="md-radiobtn">
                                                <label for="others">
                                                    <span></span>
                                                    <span class="check"></span>
                                                    <span class="box"></span> Others </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <hr>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label for="">Goal</label>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group form-md-radios">
                                            <div class="md-radio-inline">
                                                <div class="md-radio">
                                                    <input type="radio" id="weight-loss" name="customer_goal"
                                                           value="Weight Loss" checked class="md-radiobtn">
                                                    <label for="weight-loss">
                                                        <span></span>
                                                        <span class="check"></span>
                                                        <span class="box"></span> Weight Loss </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group form-md-line-input">
                                            <label for="form_control_1" class="control-label">If weight loss how
                                                much?</label>
                                            <input type="number" min="0" class="form-control" value="0"
                                                   name="weight_loss_amount" id="weight_loss_amount">
                                            <span class="help-block">In Kg</span>
                                            <div class="form-control-focus"></div>

                                        </div>
                                    </div>

                                    <div class="col-md-2 col-md-offset-1">
                                        <div class="form-group form-md-radios">
                                            <div class="md-radio-inline">
                                                <div class="md-radio">
                                                    <input type="radio" id="weight-gain" name="customer_goal"
                                                           value="Weight Gain" class="md-radiobtn">
                                                    <label for="weight-gain">
                                                        <span></span>
                                                        <span class="check"></span>
                                                        <span class="box"></span> Weight Gain </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group form-md-line-input">
                                            <label for="form_control_1" class="control-label">If weight gain how
                                                much?</label>
                                            <input type="number" min="0" class="form-control" value="0"
                                                   name="weight_gain_amount" id="weight_gain_amount">
                                            <span class="help-block">In Kg</span>
                                            <div class="form-control-focus"></div>

                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group form-md-radios">
                                            <div class="md-radio-inline">
                                                <div class="md-radio">
                                                    <input type="radio" id="flexibility" name="customer_goal"
                                                           value="Flexibility" class="md-radiobtn">
                                                    <label for="flexibility">
                                                        <span></span>
                                                        <span class="check"></span>
                                                        <span class="box"></span> Flexibility </label>
                                                </div>
                                                <div class="md-radio">
                                                    <input type="radio" id="injury-rehabilitation" name="customer_goal"
                                                           class="md-radiobtn" value="Injury Rehabilitation">
                                                    <label for="injury-rehabilitation">
                                                        <span></span>
                                                        <span class="check"></span>
                                                        <span class="box"></span> Injury Rehabilitation</label>
                                                </div>
                                                <div class="md-radio">
                                                    <input type="radio" id="yoga" name="customer_goal"
                                                           class="md-radiobtn" value="Yoga">
                                                    <label for="yoga">
                                                        <span></span>
                                                        <span class="check"></span>
                                                        <span class="box"></span> Yoga</label>
                                                </div>
                                                <div class="md-radio">
                                                    <input type="radio" id="toning" name="customer_goal"
                                                           class="md-radiobtn" value="Toning">
                                                    <label for="toning">
                                                        <span></span>
                                                        <span class="check"></span>
                                                        <span class="box"></span> Toning</label>
                                                </div>
                                                <div class="md-radio">
                                                    <input type="radio" id="stress-management" name="customer_goal"
                                                           class="md-radiobtn" value="Stress Management">
                                                    <label for="stress-management">
                                                        <span></span>
                                                        <span class="check"></span>
                                                        <span class="box"></span> Stress Management</label>
                                                </div>
                                                <div class="md-radio">
                                                    <input type="radio" id="cardio-vascular-endurance"
                                                           name="customer_goal" class="md-radiobtn"
                                                           value="Cardio Vascular Endurance">
                                                    <label for="cardio-vascular-endurance">
                                                        <span></span>
                                                        <span class="check"></span>
                                                        <span class="box"></span> Cardio Vascular Endurance</label>
                                                </div>
                                                <div class="md-radio">
                                                    <input type="radio" id="strength-endurance" name="customer_goal"
                                                           class="md-radiobtn" value="Strength Endurance">
                                                    <label for="strength-endurance">
                                                        <span></span>
                                                        <span class="check"></span>
                                                        <span class="box"></span> Strength Endurance</label>
                                                </div>
                                                <div class="md-radio">
                                                    <input type="radio" id="others" name="customer_goal"
                                                           class="md-radiobtn" checked value="Others">
                                                    <label for="other">
                                                        <span></span>
                                                        <span class="check"></span>
                                                        <span class="box"></span> Other</label>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                                <hr>

                                <div class="form-group form-md-line-input">
                                    <label class="control-label">Do you exercise regularly?</label>
                                    <div class="form-group form-md-radios">
                                        <div class="md-radio-inline">
                                            <div class="md-radio">
                                                <input type="radio" id="exercise-yes" name="exercise_regularly"
                                                       value="Yes" class="md-radiobtn">
                                                <label for="exercise-yes">
                                                    <span></span>
                                                    <span class="check"></span>
                                                    <span class="box"></span> Yes </label>
                                            </div>
                                            <div class="md-radio">
                                                <input type="radio" id="exercise-no" checked name="exercise_regularly"
                                                       class="md-radiobtn" value="No">
                                                <label for="exercise-no">
                                                    <span></span>
                                                    <span class="check"></span>
                                                    <span class="box"></span> No</label>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <div class="form-group form-md-line-input exercise-type">
                                    <label class="control-label">If yes what type?</label>
                                    <div class="form-group form-md-radios">
                                        <div class="md-radio-inline">
                                            <div class="md-radio">
                                                <input type="radio" id="exercise-type-gym" checked name="exercise_type"
                                                       value="Gym" class="md-radiobtn">
                                                <label for="exercise-type-gym">
                                                    <span></span>
                                                    <span class="check"></span>
                                                    <span class="box"></span> Gym </label>
                                            </div>
                                            <div class="md-radio">
                                                <input type="radio" id="exercise-type-aerobics" name="exercise_type"
                                                       value="Aerobics" class="md-radiobtn">
                                                <label for="exercise-type-aerobics">
                                                    <span></span>
                                                    <span class="check"></span>
                                                    <span class="box"></span> Aerobics </label>
                                            </div>
                                            <div class="md-radio">
                                                <input type="radio" id="exercise-type-yoga" name="exercise_type"
                                                       value="Yoga" class="md-radiobtn">
                                                <label for="exercise-type-yoga">
                                                    <span></span>
                                                    <span class="check"></span>
                                                    <span class="box"></span> Yoga </label>
                                            </div>
                                            <div class="md-radio">
                                                <input type="radio" id="exercise-type-walking" name="exercise_type"
                                                       value="Walking" class="md-radiobtn">
                                                <label for="exercise-type-walking">
                                                    <span></span>
                                                    <span class="check"></span>
                                                    <span class="box"></span> Walking </label>
                                            </div>
                                            <div class="md-radio">
                                                <input type="radio" id="exercise-type-jogging" name="exercise_type"
                                                       value="Jogging" class="md-radiobtn">
                                                <label for="exercise-type-jogging">
                                                    <span></span>
                                                    <span class="check"></span>
                                                    <span class="box"></span> Jogging </label>
                                            </div>
                                            <div class="md-radio">
                                                <input type="radio" id="exercise-type-spinning" name="exercise_type"
                                                       value="Spinning" class="md-radiobtn">
                                                <label for="exercise-type-spinning">
                                                    <span></span>
                                                    <span class="check"></span>
                                                    <span class="box"></span> Spinning </label>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group form-md-line-input">
                                            <div class="row">
                                                <label class="col-md-4 control-label">If gyming where?</label>
                                                <div class="col-md-8">
                                                    <input type="text" class="form-control" name="gyming_where"
                                                           id="gyming_where">
                                                    <div class="form-control-focus"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group form-md-line-input">
                                            <div class="row">
                                                <label class="control-label col-md-5">How much time?</label>
                                                <div class="col-md-7">
                                                    <input type="text" class="form-control" name="gyming_since"
                                                           id="gyming_since">
                                                    <div class="form-control-focus"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12 border-grey-salt" style="border: 2px dashed">
                                        <div class="form-group form-md-line-input">
                                            <label class="control-label">Package offered</label>
                                            <div class="form-group form-md-radios">
                                                <div class="md-radio-inline">
                                                    <div class="md-radio">
                                                        <input type="radio" id="package-monthly" checked
                                                               name="packages_offered" value="Monthly"
                                                               class="md-radiobtn">
                                                        <label for="package-monthly">
                                                            <span></span>
                                                            <span class="check"></span>
                                                            <span class="box"></span> Monthly </label>
                                                    </div>
                                                    <div class="md-radio">
                                                        <input type="radio" id="package-quarterly"
                                                               name="packages_offered" class="md-radiobtn"
                                                               value="Quarterly">
                                                        <label for="package-quarterly">
                                                            <span></span>
                                                            <span class="check"></span>
                                                            <span class="box"></span> Quarterly</label>
                                                    </div>
                                                    <div class="md-radio">
                                                        <input type="radio" id="package-half-yearly"
                                                               name="packages_offered" class="md-radiobtn"
                                                               value="Half Yearly">
                                                        <label for="package-half-yearly">
                                                            <span></span>
                                                            <span class="check"></span>
                                                            <span class="box"></span> Half Yearly</label>
                                                    </div>
                                                    <div class="md-radio">
                                                        <input type="radio" id="package-yearly" name="packages_offered"
                                                               class="md-radiobtn" value="Yearly">
                                                        <label for="package-yearly">
                                                            <span></span>
                                                            <span class="check"></span>
                                                            <span class="box"></span> Yearly</label>
                                                    </div>
                                                    <div class="md-radio">
                                                        <input type="radio" id="package-personal-training"
                                                               name="packages_offered" class="md-radiobtn"
                                                               value="Personal Training">
                                                        <label for="package-personal-training">
                                                            <span></span>
                                                            <span class="check"></span>
                                                            <span class="box"></span> Personal Training</label>
                                                    </div>
                                                    <div class="md-radio">
                                                        <input type="radio" id="package-other" name="packages_offered"
                                                               class="md-radiobtn" value="Any Other">
                                                        <label for="package-other">
                                                            <span></span>
                                                            <span class="check"></span>
                                                            <span class="box"></span> Any Other</label>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>


                                        <div class="form-group form-md-line-input ">
                                            <textarea name="remark" class="form-control" id="remark" cols="30"
                                                      rows="3"></textarea>
                                            <label for="form_control_1">Remark<span aria-required="true"></span></label>
                                            <div class="form-control-focus"></div>
                                            <span class="help-block"></span>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group form-md-line-input ">
                                                    <input type="number" min="0" class="form-control"
                                                           id="package_amount" name="package_amount"/>
                                                    <label for="form_control_1">Package amount</label>
                                                    <div class="form-control-focus"></div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group form-md-line-input ">
                                                    <input type="text" readonly data-provide="datepicker"
                                                           data-date-today-highlight="true"
                                                           class="form-control date-picker"
                                                           value="{{ \Carbon\Carbon::today()->format('m/d/Y') }}"
                                                           name="next_follow_up_on" id="next_follow_up_on">
                                                    <label for="form_control_1">Next follow up on?</label>
                                                    <div class="form-control-focus"></div>
                                                </div>

                                            </div>


                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="form-actions noborder">
                                <button type="submit" class="btn dark mt-ladda-btn ladda-button" data-style="zoom-in"
                                        id="save-form">
                                            <span class="ladda-label">
                                                <i class="fa fa-save"></i> SAVE</span>
                                    <span class="ladda-spinner"></span>
                                    <div class="ladda-progress" style="width: 0px;"></div>
                                </button>
                                <button type="reset" class="btn default">Reset</button>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END PAGE CONTENT INNER -->
        </div>
    </div>
@stop

@section('footer')

    {!! HTML::script('admin/global/plugins/ladda/spin.min.js') !!}
    {!! HTML::script('admin/global/plugins/ladda/ladda.min.js') !!}
    {!! HTML::script('admin/pages/scripts/ui-buttons.min.js') !!}

    {!! HTML::script('admin/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js') !!}

    {!! HTML::script('admin/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') !!}
    {!! HTML::style('admin/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') !!}
    {!! HTML::style('admin/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js') !!}


    {!! HTML::script('admin/global/plugins/jquery-validation/js/jquery.validate.min.js') !!}
    {!! HTML::script('admin/global/plugins/jquery-validation/js/additional-methods.min.js') !!}


    <script>

        $('#dob').change(function () {
            var lre = /^\s*/;

            var inputDate = document.getElementById('dob').value;
            inputDate = inputDate.replace(lre, "");

            age = get_age(new Date(inputDate));

            $('#age').val(age);

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

        $('.exercise-type').css('display', 'none');
        $(function () {
            $("input[name='exercise_regularly']").change(function () {
                if ($(this).val() === 'Yes') {
                    $('.exercise-type').css('display', 'block');
                } else {
                    $('.exercise-type').css('display', 'none');
                }
            });
        });
    </script>
@stop
