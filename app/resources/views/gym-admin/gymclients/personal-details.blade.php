<div class="portlet light">
     <div class="portlet-title">
        <div class="caption font-green">
            <span class="caption-subject bold uppercase">Customer Details</span>
        </div>
    </div>
    <div class="portlet-body form">
        <div class="form-body">
            <!-- Row 1 -->
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>First Name <span class="required">*</span></label>
                        <input type="text" class="form-control" name="first_name" value="{{ old('first_name') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Middle Name</label>
                        <input type="text" class="form-control" name="middle_name" value="{{ old('middle_name') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Last Name <span class="required">*</span></label>
                        <input type="text" class="form-control" name="last_name" value="{{ old('last_name') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Gender <span class="required">*</span></label>
                        <select class="form-control" name="gender">
                            <option value="">Select</option>
                            <option value="male" {{ old('gender')=='male'?'selected':'' }}>Male</option>
                            <option value="female" {{ old('gender')=='female'?'selected':'' }}>Female</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Row 2 -->
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Date of Birth</label>
                        <input value="{{old('dob')}}" class="form-control form-control-inline input-small date-picker" placeholder="Date of Birth" size="16" type="text" readonly value="" id="dob" name="dob" />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Age</label>
                        <input type="text" class="form-control" id="age" readonly>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>Marital Status</label>
                        <div class="md-radio-inline">
                            <div class="md-radio">
                                <input type="radio" value="yes" id="yes_radio" name="marital_status" class="md-radiobtn">
                                <label for="yes_radio">
                                    <span></span>
                                    <span class="check"></span>
                                    <span class="box"></span> Married </label>
                            </div>
                            <div class="md-radio ">
                                <input type="radio" value="no" id="no_radio" checked name="marital_status" class="md-radiobtn" >
                                <label for="no_radio">
                                    <span></span>
                                    <span class="check"></span>
                                    <span class="box"></span> Unmarried </label>
                            </div>
                        </div>
                    </div>
                </div>
                 <div class="col-md-3" id="anniversaryDiv" style="display: none;">
                    <div class="form-group">
                        <label for="anniversary">Anniversary Date</label>
                        <input class="form-control form-control-inline input-small date-picker" placeholder="Anniversary" size="16" type="text" value="" id="anniversary" readonly name="anniversary" />
                        <span class="help-block"> </span>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>Occupation</label>
                        <input type="text" class="form-control" name="occupation" value="{{ old('occupation') }}">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="portlet-body form">
        <div class="form-body">
            <!-- Row 1 -->
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Email <span class="required">*</span></label>
                        <input type="email" class="form-control" name="email" value="{{ old('email') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Contact No/ Mobile <span class="required">*</span></label>
                        <input type="text" class="form-control" name="mobile" value="{{ old('mobile') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Emergency Contact No</label>
                        <input type="text" class="form-control" name="emergency_contact" value="{{ old('emergency_contact') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Address <span class="required">*</span></label>
                        <input type="text" class="form-control" name="address" value="{{ old('address') }}">
                    </div>
                </div>
            </div>

            <!-- Row 2 -->
            <div class="row">
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Height (Feet)</label>
                        <input type="number" class="form-control" name="height_feet" value="{{ old('height_feet') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Height (Inches)</label>
                        <input type="number" class="form-control" name="height_inches" value="{{ old('height_inches') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Weight (kg)</label>
                        <input type="number" class="form-control" name="weight" value="{{ old('weight') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Body Fat (%)</label>
                        <input type="number" step="0.1" class="form-control" name="fat" value="{{ old('fat') }}">
                    </div>
                </div>
                 <div class="col-md-2">
                    <div class="form-group">
                        <label>Chest (cm)</label>
                        <input type="number" class="form-control" name="chest" value="{{ old('chest') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Waist (cm)</label>
                        <input type="number" class="form-control" name="waist" value="{{ old('waist') }}">
                    </div>
                </div>
            </div>

            <!-- Row 3 -->
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Arms (cm)</label>
                        <input type="number" class="form-control" name="arms" value="{{ old('arms') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Experienced in Gym</label>
                        <select class="form-control" name="experienced_in_gym">
                            <option value="">Select</option>
                            <option value="yes" {{ old('experienced_in_gym')=='yes'?'selected':'' }}>Yes</option>
                            <option value="no" {{ old('experienced_in_gym')=='no'?'selected':'' }}>No</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>Blood Group</label>
                        <select class="form-control" name="blood_group">
                            <option value="">Select</option>
                            <option value="A+" {{ old('blood_group')=='A+'?'selected':'' }}>A+</option>
                            <option value="A-" {{ old('blood_group')=='A-'?'selected':'' }}>A-</option>
                            <option value="B+" {{ old('blood_group')=='B+'?'selected':'' }}>B+</option>
                            <option value="B-" {{ old('blood_group')=='B-'?'selected':'' }}>B-</option>
                            <option value="O+" {{ old('blood_group')=='O+'?'selected':'' }}>O+</option>
                            <option value="O-" {{ old('blood_group')=='O-'?'selected':'' }}>O-</option>
                            <option value="AB+" {{ old('blood_group')=='AB+'?'selected':'' }}>AB+</option>
                            <option value="AB-" {{ old('blood_group')=='AB-'?'selected':'' }}>AB-</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Referred By</label>
                        <select class="form-control" name="referred_by">
                            <option value="" >Select Referred By</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->customer_id}}" @if(old('referred_by')== $client->customer_id) selected @endif >{{ $client->fullName }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
