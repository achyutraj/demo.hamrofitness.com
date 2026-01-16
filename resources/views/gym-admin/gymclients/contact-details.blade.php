<div class="portlet light">
    <div class="portlet-title">
        <div class="caption font-green">
            <i class="icon-pin font-green"></i>
            <span class="caption-subject bold uppercase">Contact & Body Details</span>
        </div>
    </div>
    <div class="portlet-body form">
        <div class="form-body">
            <!-- Row 1 -->
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" class="form-control" name="email" value="{{ old('email') }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Contact No/ Mobile</label>
                        <input type="text" class="form-control" name="mobile" value="{{ old('mobile') }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Emergency Contact No</label>
                        <input type="text" class="form-control" name="emergency_contact" value="{{ old('emergency_contact') }}">
                    </div>
                </div>
            </div>

            <!-- Row 2 -->
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Height (Feet)</label>
                        <input type="number" class="form-control" name="height_feet" value="{{ old('height_feet') }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Height (Inches)</label>
                        <input type="number" class="form-control" name="height_inches" value="{{ old('height_inches') }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Weight (kg)</label>
                        <input type="number" class="form-control" name="weight" value="{{ old('weight') }}">
                    </div>
                </div>
            </div>

            <!-- Row 3 -->
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Body Fat (%)</label>
                        <input type="number" step="0.1" class="form-control" name="fat" value="{{ old('fat') }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Chest (cm)</label>
                        <input type="number" class="form-control" name="chest" value="{{ old('chest') }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Waist (cm)</label>
                        <input type="number" class="form-control" name="waist" value="{{ old('waist') }}">
                    </div>
                </div>
            </div>

            <!-- Row 4 -->
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Arms (cm)</label>
                        <input type="number" class="form-control" name="arms" value="{{ old('arms') }}">
                    </div>
                </div>
                <div class="col-md-4">
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
                <div class="col-md-4">
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

            <!-- Row 5 -->
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Experienced in Gym</label>
                        <select class="form-control" name="experienced_in_gym">
                            <option value="">Select</option>
                            <option value="yes" {{ old('experienced_in_gym')=='yes'?'selected':'' }}>Yes</option>
                            <option value="no" {{ old('experienced_in_gym')=='no'?'selected':'' }}>No</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
