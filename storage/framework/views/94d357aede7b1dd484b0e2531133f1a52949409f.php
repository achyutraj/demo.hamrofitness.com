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
                        <input type="text" class="form-control" name="first_name" value="<?php echo e(old('first_name')); ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Middle Name</label>
                        <input type="text" class="form-control" name="middle_name" value="<?php echo e(old('middle_name')); ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Last Name <span class="required">*</span></label>
                        <input type="text" class="form-control" name="last_name" value="<?php echo e(old('last_name')); ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Gender <span class="required">*</span></label>
                        <select class="form-control" name="gender">
                            <option value="">Select</option>
                            <option value="male" <?php echo e(old('gender')=='male'?'selected':''); ?>>Male</option>
                            <option value="female" <?php echo e(old('gender')=='female'?'selected':''); ?>>Female</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Row 2 -->
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Date of Birth</label>
                        <input value="<?php echo e(old('dob')); ?>" class="form-control form-control-inline input-small date-picker" placeholder="Date of Birth" size="16" type="text" readonly value="" id="dob" name="dob" />
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
                        <input type="text" class="form-control" name="occupation" value="<?php echo e(old('occupation')); ?>">
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
                        <input type="email" class="form-control" name="email" value="<?php echo e(old('email')); ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Contact No/ Mobile <span class="required">*</span></label>
                        <input type="text" class="form-control" name="mobile" value="<?php echo e(old('mobile')); ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Emergency Contact No</label>
                        <input type="text" class="form-control" name="emergency_contact" value="<?php echo e(old('emergency_contact')); ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Address <span class="required">*</span></label>
                        <input type="text" class="form-control" name="address" value="<?php echo e(old('address')); ?>">
                    </div>
                </div>
            </div>

            <!-- Row 2 -->
            <div class="row">
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Height (Feet)</label>
                        <input type="number" class="form-control" name="height_feet" value="<?php echo e(old('height_feet')); ?>">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Height (Inches)</label>
                        <input type="number" class="form-control" name="height_inches" value="<?php echo e(old('height_inches')); ?>">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Weight (kg)</label>
                        <input type="number" class="form-control" name="weight" value="<?php echo e(old('weight')); ?>">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Body Fat (%)</label>
                        <input type="number" step="0.1" class="form-control" name="fat" value="<?php echo e(old('fat')); ?>">
                    </div>
                </div>
                 <div class="col-md-2">
                    <div class="form-group">
                        <label>Chest (cm)</label>
                        <input type="number" class="form-control" name="chest" value="<?php echo e(old('chest')); ?>">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Waist (cm)</label>
                        <input type="number" class="form-control" name="waist" value="<?php echo e(old('waist')); ?>">
                    </div>
                </div>
            </div>

            <!-- Row 3 -->
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Arms (cm)</label>
                        <input type="number" class="form-control" name="arms" value="<?php echo e(old('arms')); ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Experienced in Gym</label>
                        <select class="form-control" name="experienced_in_gym">
                            <option value="">Select</option>
                            <option value="yes" <?php echo e(old('experienced_in_gym')=='yes'?'selected':''); ?>>Yes</option>
                            <option value="no" <?php echo e(old('experienced_in_gym')=='no'?'selected':''); ?>>No</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>Blood Group</label>
                        <select class="form-control" name="blood_group">
                            <option value="">Select</option>
                            <option value="A+" <?php echo e(old('blood_group')=='A+'?'selected':''); ?>>A+</option>
                            <option value="A-" <?php echo e(old('blood_group')=='A-'?'selected':''); ?>>A-</option>
                            <option value="B+" <?php echo e(old('blood_group')=='B+'?'selected':''); ?>>B+</option>
                            <option value="B-" <?php echo e(old('blood_group')=='B-'?'selected':''); ?>>B-</option>
                            <option value="O+" <?php echo e(old('blood_group')=='O+'?'selected':''); ?>>O+</option>
                            <option value="O-" <?php echo e(old('blood_group')=='O-'?'selected':''); ?>>O-</option>
                            <option value="AB+" <?php echo e(old('blood_group')=='AB+'?'selected':''); ?>>AB+</option>
                            <option value="AB-" <?php echo e(old('blood_group')=='AB-'?'selected':''); ?>>AB-</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Referred By</label>
                        <select class="form-control" name="referred_by">
                            <option value="" >Select Referred By</option>
                            <?php $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($client->customer_id); ?>" <?php if(old('referred_by')== $client->customer_id): ?> selected <?php endif; ?> ><?php echo e($client->fullName); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php /**PATH /var/www/stage.hamrofitness.com/public_html/resources/views/gym-admin/gymclients/personal-details.blade.php ENDPATH**/ ?>