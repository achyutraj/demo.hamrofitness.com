<?php $__env->startSection('CSS'); ?>
    <?php echo HTML::style('css/cropper.css'); ?>

    <?php echo HTML::style('admin/global/plugins/bootstrap-datepicker/css/datepicker.css'); ?>

    <?php echo HTML::style('admin/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css'); ?>

    <?php echo HTML::style('admin/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css'); ?>

    <?php echo HTML::style('admin/global/plugins/ladda/ladda-themeless.min.css'); ?>

    <?php echo HTML::style('admin/pages/css/profile.min.css'); ?>

    <?php echo HTML::style('admin/global/plugins/datatables/datatables.min.css'); ?>

    <?php echo HTML::style('admin/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>

    <style>
        .error-msg {
            color: red;
            display: none;
        }
        .table-scrollable {
            width: 100%;
            overflow-x: hidden;
            overflow-y: hidden;
            border: 1px solid #e7ecf1;
            margin: 10px 0!important;
        }

    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
        <!-- BEGIN PAGE BREADCRUMBS -->
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <a href="<?php echo e(route('gym-admin.dashboard.index')); ?>">Home</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>Clients</span>
            </li>
            <li>
                <span>Create</span>
            </li>
        </ul>
        <!-- END PAGE BREADCRUMBS -->
        <!-- BEGIN PAGE CONTENT INNER -->
        <div class="page-content-inner">
            <div class="row">
                <div class="col-md-12">
                    <!-- BEGIN PROFILE SIDEBAR -->
                    <div class="profile-sidebar">
                        <!-- PORTLET MAIN -->
                        <div class="portlet light profile-sidebar-portlet ">
                            <!-- SIDEBAR USERPIC -->
                            <div class="profile-userpic">
                                <?php if($client->image != ''): ?>
                                    <img id="changeProfile" src="<?php echo e($profileHeaderPath.$client->image); ?>"
                                            class="img-responsive image-change-profile"/>
                                <?php else: ?>
                                    <img src="<?php echo e(asset('/fitsigma/images/user.svg')); ?>"
                                         class="img-responsive image-change-profile" alt="">
                                <?php endif; ?>
                            </div>

                            <div class="profile-usertitle">
                                <div class="profile-usertitle-name"> <?php echo e($client->first_name); ?> <?php echo e($client->middle_name); ?> <?php echo e($client->last_name); ?> </div>
                            </div>

                        </div>
                        <!-- END PORTLET MAIN -->
                        <!-- PORTLET MAIN -->
                        <div class="portlet light ">
                            <!-- STAT -->
                            <div class="row list-separated profile-stat">
                                <div class="col-xs-12">
                                    <div class="col-md-4 col-sm-3 col-xs-6">
                                        <div class="uppercase profile-stat-title"> <?php echo e(($client->weight != '')? $client->weight: '-'); ?> </div>
                                        <div class="uppercase profile-stat-text"> Weight</div>
                                    </div>
                                    <div class="col-md-2 col-sm-3 col-xs-6">
                                        <div class="uppercase profile-stat-title"><?php echo e($age); ?></div>
                                        <div class="uppercase profile-stat-text"> Age</div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <div class="uppercase profile-stat-title"> <?php echo e($client->gender); ?> </div>
                                        <div class="uppercase profile-stat-text"> Gender</div>
                                    </div>
                                </div>
                            </div>

                            <!-- END STAT -->
                            <div>
                                <div class="margin-top-20 profile-desc-link">
                                    <i class="fa fa-calendar"></i>
                                    <a href="javascript:;">Member Since <?php echo e(date('M d, Y',strtotime($client->joining_date))); ?></a>
                                </div>
                                <div class="margin-top-20 profile-desc-link">
                                    <i class="fa fa-info-circle"></i>
                                    <a href="javascript:;"><?php echo e(($client->status == 1) ?  'Active' : 'Inactive'); ?> Client</a>
                                </div>
                                <?php if($client->card != null): ?>
                                <div class="margin-top-20 profile-desc-link">
                                    <i class="fa fa-credit-card"></i>
                                    <a href="javascript:;"><?php echo e($client->card); ?> </a>
                                </div>
                                <?php endif; ?>
                                <div class="margin-top-20 profile-desc-link">
                                    <i class="fa fa-envelope"></i>
                                    <a href="javascript:;"><?php echo e($client->email); ?></a>
                                </div>
                                <div class="margin-top-20 profile-desc-link">
                                    <i class="fa fa-phone"></i>
                                    <a href="javascript:;"><?php echo e($client->mobile); ?></a>
                                </div>
                                <?php if($client->referred_client_id != null): ?>
                                <div class="margin-top-20 profile-desc-link">
                                    <i class="fa fa-user"></i>
                                    <a href="javascript:;">Referred By <?php echo e($client->referred_by?->fullName); ?></a>
                                </div>
                                <?php endif; ?>
                                <?php if($client->redeem_points > 0): ?>
                                <div class="margin-top-20 profile-desc-link">
                                    <i class="fa fa-user"></i>
                                    <a href="javascript:;">Redeem Points: <?php echo e($client->redeem_points); ?></a>
                                </div>
                                <?php endif; ?>
                            </div>
                            <div>
                                <div class="margin-top-20 profile-desc-link">
                                    <i class="fa fa-download"></i>
                                    <a href="<?php echo e(route('gym-admin.client.downloadProfile',$client->id)); ?>">Download</a>
                                </div>
                            </div>
                        </div>
                        <!-- END PORTLET MAIN -->
                    </div>
                    <!-- END BEGIN PROFILE SIDEBAR -->
                    <!-- BEGIN PROFILE CONTENT -->
                    <div class="profile-content">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="portlet light ">
                                    <div class="portlet-title tabbable-line">
                                        <div class="caption caption-md">
                                            <i class="icon-globe theme-font hide"></i>
                                            <span class="caption-subject font-blue-madison bold uppercase">Profile Account</span>
                                        </div>
                                        <ul class="nav nav-tabs">
                                            <li class="active">
                                                <a href="#tab_1_1" data-toggle="tab">Personal Info</a>
                                            </li>
                                            <li>
                                                <a href="#tab_1_2" class="hidden-xs hidden-sm" data-toggle="tab">Change Photo</a>
                                            </li>
                                            <li class="hidden-xs">
                                                <a href="#tab_1_3" data-toggle="tab">Memberships/Reservations</a>
                                            </li>
                                            <li class="hidden-xs">
                                                <a href="#tab_1_4" data-toggle="tab">Payments</a>
                                            </li>
                                            <li class="hidden-xs">
                                                <a href="#tab_1_5" data-toggle="tab">Dues</a>
                                            </li>
                                            <?php if($client->redeem_points > 0): ?>
                                            <li class="hidden-xs">
                                                <a href="#tab_1_6" data-toggle="tab">My Referred</a>
                                            </li>
                                            <?php endif; ?>
                                            <li class="dropdown visible-xs">
                                                <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown"><i
                                                            class="fa fa-chevron-down  font-green"></i>
                                                </a>
                                                <ul class="dropdown-menu pull-right" role="menu">
                                                    <li>
                                                        <a href="#tab_1_3" data-toggle="tab">Memberships</a>
                                                    </li>
                                                    <li>
                                                        <a href="#tab_1_4" data-toggle="tab">Payments</a>
                                                    </li>
                                                    <li>
                                                        <a href="#tab_1_5" data-toggle="tab">Dues</a>
                                                    </li>
                                                </ul>
                                            </li>

                                        </ul>
                                    </div>
                                    <div class="portlet-body">
                                        <div class="tab-content">
                                            <!-- PERSONAL INFO TAB -->
                                            <div class="tab-pane active" id="tab_1_1">
                                                <?php echo Form::open(['id'=>'personal_details','class'=>'ajax-form','method'=>'POST']); ?>

                                                <input type="hidden" name="id" value="<?php echo e($client->id); ?>">
                                                <input type="hidden" name="type" value="general">

                                                <div class="row">
                                                    <div class="col-md-4 form-group">
                                                        <label>First Name</label>
                                                        <input type="text" placeholder="First Name" class="form-control"
                                                               name="first_name" id="first_name"
                                                               value="<?php echo e($client->first_name); ?>"/>
                                                        <span class="help-block"></span>
                                                    </div>
                                                    <div class="col-md-4 form-group">
                                                        <label>Middle Name</label>
                                                        <input type="text" placeholder="Middle Name"
                                                               class="form-control" name="middle_name" id="middle_name"
                                                               value="<?php echo e($client->middle_name); ?>"/>
                                                        <span class="help-block"></span>
                                                    </div>
                                                    <div class="col-md-4 form-group">
                                                        <label>Last Name</label>
                                                        <input type="text" placeholder="Last Name" class="form-control"
                                                               name="last_name" id="last_name"
                                                               value="<?php echo e($client->last_name); ?>"/>
                                                        <span class="help-block"></span>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-4 form-group">
                                                        <label>Mobile Number</label>
                                                        <input type="number" placeholder="Mobile Number" name="mobile"
                                                               id="mobile" class="form-control" value="<?php echo e($client->mobile); ?>"/>
                                                        <span class="help-block"></span>
                                                    </div>
                                                    <div class="col-md-4 form-group">
                                                        <label>Emergency Contact</label>
                                                        <input type="number" placeholder="Emergency Contact" name="emergency_contact" id="emergency_contact"
                                                               class="form-control" value="<?php echo e($client->emergency_contact); ?>"/>
                                                        <span class="help-block"></span>
                                                    </div>
                                                    <div class="col-md-4 form-group">
                                                        <label>Email</label>
                                                        <input type="email" placeholder="Email" name="email" id="email"
                                                               class="form-control" value="<?php echo e($client->email); ?>"/>
                                                        <span class="help-block"></span>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6 form-group">
                                                        <label>Address</label>
                                                        <textarea class="form-control" name="address" id="address"
                                                                  rows="3"><?php echo e($client->address); ?></textarea>
                                                        <span class="help-block"></span>
                                                    </div>
                                                    <div class="col-md-6 form-group">
                                                        <div class="form-group ">
                                                            <div class="form/group form-md-radios">
                                                                <label>Marital Status</label>
                                                                <div class="md-radio-inline">
                                                                    <div class="md-radio">
                                                                        <input type="radio" value="yes" id="yes_radio"
                                                                               name="marital_status" class="md-radiobtn"
                                                                               <?php if($client->marital_status == 'yes'): ?> checked <?php endif; ?>>
                                                                        <label for="yes_radio">
                                                                            <span></span>
                                                                            <span class="check"></span>
                                                                            <span class="box"></span> Married </label>
                                                                    </div>
                                                                    <div class="md-radio ">
                                                                        <input type="radio" value="no" id="no_radio"
                                                                               name="marital_status" class="md-radiobtn"
                                                                               <?php if($client->marital_status == 'no'): ?> checked <?php endif; ?>>
                                                                        <label for="no_radio">
                                                                            <span></span>
                                                                            <span class="check"></span>
                                                                            <span class="box"></span> Unmarried </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <span class="help-block"></span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6 form-group">
                                                        <label for="">Occupation</label>
                                                        <input class="form-control" type="text" placeholder="Occupation"
                                                               name="occupation" value="<?php echo e($client->occupation); ?>">
                                                    </div>
                                                    <div class="col-md-6 form-group">
                                                        <label>Occupation Details</label>
                                                        <textarea class="form-control" name="occupation_details"
                                                                  id="occupation_details"
                                                                  rows="3"><?php echo e($client->occupation_details); ?></textarea>
                                                        <span class="help-block"></span>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6 form-group">
                                                        <label>Date of Birth</label>
                                                        <input readonly
                                                               class="form-control form-control-inline input-medium date-picker"
                                                               placeholder="Date of Birth" size="16" type="text"
                                                               <?php if($client && isset($client->dob)): ?> value="<?php echo e($client->dob->format('m/d/Y')); ?>"
                                                               <?php endif; ?> id="dob" name="dob"/>
                                                        <span class="help-block"></span>
                                                    </div>
                                                    <div class="col-md-6 form-group">
                                                        <label>Age</label>
                                                        <input type="number" placeholder="Age" class="form-control"
                                                               name="age" value="<?php echo e($age); ?>" id="age" readonly/>
                                                        <span class="help-block"></span>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6 form-group">
                                                        <label>Gender</label>
                                                        <select class="form-control edited" id="gender" name="gender">
                                                            <option value="" selected>Select Gender</option>
                                                            <option value="male"
                                                                    <?php if($client->gender == 'male'): ?>selected <?php endif; ?> >Male
                                                            </option>
                                                            <option value="female"
                                                                    <?php if($client->gender == 'female'): ?>selected <?php endif; ?>>
                                                                Female
                                                            </option>
                                                        </select>
                                                        <span class="help-block"></span>
                                                    </div>
                                                    <div class="col-md-6 form-group">
                                                        <label>Blood Group</label>
                                                        <select class="form-control" id="blood_group" name="blood_group">
                                                            <option value="" selected>Select Blood Group</option>
                                                            <option value="a+" <?php if($client->blood_group == "a+"): ?> selected <?php endif; ?> >A+</option>
                                                            <option value="a-"<?php if($client->blood_group == "a-"): ?> selected <?php endif; ?>>A-</option>
                                                            <option value="b+" <?php if($client->blood_group == "b+"): ?> selected <?php endif; ?> >B+</option>
                                                            <option value="b-"<?php if($client->blood_group == "b-"): ?> selected <?php endif; ?>>B-</option>
                                                            <option value="ab+" <?php if($client->blood_group == "ab+"): ?> selected <?php endif; ?> >AB+</option>
                                                            <option value="ab-"<?php if($client->blood_group == "ab-"): ?> selected <?php endif; ?>>AB-</option>
                                                            <option value="o+" <?php if($client->blood_group == "o+"): ?> selected <?php endif; ?> >O+</option>
                                                            <option value="o-"<?php if($client->blood_group == "o-"): ?> selected <?php endif; ?>>O-</option>
                                                        </select>
                                                        <span class="help-block"></span>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <label>Height</label>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group form-md-line-input ">
                                                            <input type="number" min="0" class="form-control"
                                                                   id="height_feet" name="height_feet"
                                                                   placeholder="feet"
                                                                   value="<?php echo e($client->height_feet); ?>">
                                                            <label for="">Feet</label>
                                                            <span class="help-block">Enter feet.</span>
                                                        </div>

                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group form-md-line-input ">
                                                            <input type="number" min="0" class="form-control"
                                                                   id="height_inches" name="height_inches"
                                                                   placeholder="inches"
                                                                   value="<?php echo e($client->height_inches); ?>">
                                                            <label for="">Inches</label>
                                                            <span class="help-block">Enter inches.</span>
                                                        </div>

                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 form-group">
                                                        <div class="form-group">
                                                            <div class="form/group form-md-radios">
                                                                <label>Experience in Gym</label>
                                                                <div class="md-radio-inline">
                                                                    <div class="md-radio">
                                                                        <input type="radio" value="1" id="yes"
                                                                               name="is_gym_experience" class="md-radiobtn"
                                                                               <?php if($client->is_gym_experience == 1): ?> checked <?php endif; ?>>
                                                                        <label for="yes">
                                                                            <span></span>
                                                                            <span class="check"></span>
                                                                            <span class="box"></span> Yes </label>
                                                                    </div>
                                                                    <div class="md-radio ">
                                                                        <input type="radio" value="0" id="no"
                                                                               name="is_gym_experience" class="md-radiobtn"
                                                                               <?php if($client->is_gym_experience == 0): ?> checked <?php endif; ?>>
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
                                                    <div class="col-md-6 form-group">
                                                        <label>Chest</label>
                                                        <input type="number" min="0" placeholder="Chest"
                                                               class="form-control" name="chest" id="chest"
                                                               value="<?php echo e($client->chest); ?>"/>
                                                        <span class="help-block"></span>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6 form-group">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <label>Weight</label>
                                                                <input type="number" min="0" placeholder="Weight"
                                                                       class="form-control" name="weight" id="weight"
                                                                       value="<?php echo e($client->weight); ?>"/>
                                                                <span class="help-block"></span>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label>Fat</label>
                                                                <input type="number" min="0" placeholder="Fat"
                                                                       class="form-control" name="fat" id="fat"
                                                                       value="<?php echo e($client->fat); ?>"/>
                                                                <span class="help-block"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 form-group">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <label>Waist</label>
                                                                <input type="number" min="0" placeholder="Waist"
                                                                       class="form-control" name="waist" id="waist"
                                                                       value="<?php echo e($client->waist); ?>"/>
                                                                <span class="help-block"></span>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label>Arms</label>
                                                                <input type="number" min="0" placeholder="Arms"
                                                                       class="form-control" name="arms" id="arms"
                                                                       value="<?php echo e($client->arms); ?>"/>
                                                                <span class="help-block"></span>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6 form-group">
                                                        <label>Password</label>
                                                        <input type="password"
                                                               placeholder="Leave it blank to keep current password"
                                                               name="password" class="form-control"/>
                                                        <span class="help-block"></span>
                                                    </div>
                                                    <div class="col-md-6 form-group" id="anniversaryDiv">
                                                        <label>Anniversary</label>

                                                        <input readonly
                                                               class="form-control form-control-inline input-medium date-picker"
                                                               placeholder="Anniversary" size="16" type="text"
                                                               value="<?php if(!is_null($client->anniversary)): ?><?php echo e($client->anniversary->format('m/d/Y')); ?><?php endif; ?>"
                                                               id="anniversary" name="anniversary"/>
                                                        <span class="help-block"></span>
                                                    </div>
                                                    <div class="col-md-4 form-group">
                                                        <label>Referred By</label>
                                                        <select class="form-control edited" id="referred_by" name="referred_by">
                                                            <option value="">Select Referred By</option>
                                                            <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <option value="<?php echo e($customer->customer_id); ?>" <?php if($client->referred_client_id ==  $customer->customer_id): ?> selected <?php endif; ?> ><?php echo e($customer->fullName); ?></option>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </select>
                                                        <span class="help-block"></span>
                                                    </div>
                                                    <div class="col-md-4 form-group">
                                                        <label>Client Status</label>
                                                        <select class="form-control" id="status" name="status">
                                                            <option value="" selected>Select Status</option>
                                                            <option value="1" <?php if($client->status == "1"): ?> selected <?php endif; ?> >Active</option>
                                                            <option value="0" <?php if($client->status == "0"): ?> selected <?php endif; ?>>Inactive</option>
                                                        </select>
                                                        <span class="help-block"></span>
                                                    </div>
                                                    <div class="col-md-4 form-group">
                                                        <label>Card Number</label>
                                                        <input type="text" placeholder="Client Card Number"
                                                        class="form-control" name="card" id="card"
                                                        value="<?php echo e($client->card); ?>"/>
                                                        <span class="help-block"></span>
                                                    </div>
                                                    <div class="col-md-6 form-group">
                                                        <label>Remarks</label>
                                                        <textarea class="form-control" name="remarks"
                                                        id="remarks" rows="3"><?php echo e($client->remarks); ?></textarea>
                                                        <span class="help-block"></span>
                                                    </div>
                                                </div>

                                                <div class="margiv-top-10">
                                                    <a href="javascript:;" class="btn green" id="save_personal"> Save
                                                        Changes </a>
                                                    <a href="<?php echo e(URL::previous()); ?>" class="btn default"> Cancel </a>
                                                </div>
                                                <?php echo Form::close(); ?>

                                            </div>
                                            <!-- CHANGE AVATAR TAB -->
                                            <div class="tab-pane" id="tab_1_2">
                                                <p> Change Image of Client </p>
                                                <?php echo Form::open(['id'=>'update_image','class'=>'ajax-form','method'=>'POST']); ?>

                                                <div class="form-group">
                                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                                        <div class="fileinput-new thumbnail"
                                                             style="width: 200px; height: 150px;">
                                                            <?php if($client->image == ''): ?>
                                                                <img id="changeMainProfile"
                                                                     src="<?php echo e(asset('/fitsigma/images/').'/'.'user.svg'); ?>"
                                                                     alt=""/>
                                                            <?php else: ?>
                                                                <img id="changeMainProfile"
                                                                        src="<?php echo e($profileHeaderPath.$client->image); ?>"
                                                                        alt=""/>
                                                            <?php endif; ?>
                                                        </div>

                                                        <div class="fileinput-preview fileinput-exists thumbnail"
                                                             style="max-width: 200px; max-height: 150px;"></div>
                                                        <div>
                                                            <button class="btn blue" rel="upload" onclick="forImage(this)">Upload Image</button>

                                                            <button class="btn blue" id="use-webcam"><i class="icon-camera"></i> Use Webcam</button>

                                                            <input type="hidden" name="id" value="<?php echo e($client->id); ?>">
                                                            <input type="hidden" name="type" value="file">
                                                            <input type="hidden" name="img_name" id="img_name">
                                                        </div>
                                                        <div id="error-msg" class="error-msg"></div>
                                                    </div>

                                                </div>

                                                <?php echo Form::close(); ?>

                                            </div>
                                            <!-- Membership TAB -->
                                            <div class="tab-pane" id="tab_1_3">
                                                <?php echo $__env->make('gym-admin.gymclients.membership', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                            </div>
                                            
                                            <div class="tab-pane" id="tab_1_4">
                                                <?php echo $__env->make('gym-admin.gymclients.payment', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                            </div>
                                            
                                            <div class="tab-pane" id="tab_1_5">
                                                <?php echo $__env->make('gym-admin.gymclients.dues', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                            </div>
                                            <?php if($client->redeem_points > 0): ?>
                                            <div class="tab-pane" id="tab_1_6">
                                                <?php echo $__env->make('gym-admin.gymclients.referred', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END PROFILE CONTENT -->
                </div>
            </div>
        </div>
        <!-- END PAGE CONTENT INNER -->
    </div>

    <div class="modal fade bs-modal-md in" id="receiptModal" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-md" id="modal-data-application">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <span class="caption-subject font-red-sunglo bold uppercase" id="modelHeading"></span>
                </div>
                <div class="modal-body">
                    Loading...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn default" data-dismiss="modal">Close</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <!--Start Image Upload-->
    <div class="modal fade" id="uploadImage" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" style="text-align: left">Upload Profile Image</h4>
                </div>
                <div id="imageUploadDiv" class="text-center">
                    <div class="uploadMsg"></div>
                    <div class="modal-body">
                        <div id="choose" class="margin-bottom-10 margin-top-10">
                            <form method="post" id="imageUploadForm" role="form" enctype="multipart/form-data"
                                  class="avatar-form">
                                <input class="avatar-task" type="hidden" id="task">
                                <input type="hidden" name="xCoordOne" id="xCoordOne">
                                <input type="hidden" name="yCoordOne" id="yCoordOne">
                                <input type="hidden" name="profileImageWidth" id="profileImageWidth">
                                <input type="hidden" name="profileImageHeight" id="profileImageHeight">

                                <span class="btn green btn-file ">
                           Browse <input type="file" name="file" id="image" class="avatar-input"
                                         onchange="readImageURL(this)">
                            </span>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End For Upload Image-->
    <!--Start Image Crop Modal-->
    <div class="modal fade" id="cropImage" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" style="text-align: left">Upload Profile Image</h4>
                </div>
                <div id="imageUploadDiv">
                    <div class="uploadMsg"></div>
                    <div class="modal-body">
                        <div id="choose">
                            <img id="croppedImage" height="300px">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn red" data-dismiss="modal">CLOSE</button>
                        <button type="button" class="btn green" id="advertImageCropButton">UPLOAD</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End For Image Crop Modal-->

    
    <div class="modal" id="webcam-modal" aria-hidden="true" aria-labelledby="avatar-modal-label" role="dialog" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <button class="close" data-dismiss="modal" type="button">&times;</button>
                    <h4 class="modal-title">Webcam</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div id="my_camera"></div>
                        <div id="my_webcam_result"></div>

                        <div class="col-md-12 text-center margin-top-15">
                            <button class="btn blue" id="capture-image"><i class="icon-camera"></i> Take Picture</button>
                            <button class="btn red" id="recapture-image"><i class="icon-refresh"></i> Retake Picture</button>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn green" disabled id="save-webcam-image">Done</button>
                    <button class="btn btn-default" data-dismiss="modal" type="button">Close</button>
                </div>

            </div>
        </div>
    </div><!-- /.modal -->

<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer'); ?>
    <?php echo HTML::script('admin/global/plugins/moment.min.js'); ?>

    <?php echo HTML::script('admin/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js'); ?>

    <?php echo HTML::style('admin/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css'); ?>

    <?php echo HTML::script('admin/pages/scripts/components-date-time-pickers.min.js'); ?>

    <?php echo HTML::script('admin/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js'); ?>

    <?php echo HTML::script('admin/global/plugins/ladda/spin.min.js'); ?>

    <?php echo HTML::script('admin/global/plugins/ladda/ladda.min.js'); ?>

    <?php echo HTML::script('admin/pages/scripts/ui-buttons.min.js'); ?>

    <?php echo HTML::script('admin/pages/scripts/profile.min.js'); ?>

    <?php echo HTML::script('admin/global/scripts/datatable.js'); ?>

    <?php echo HTML::script('admin/pages/scripts/table-datatables-managed.js'); ?>

    <?php echo HTML::script('admin/global/plugins/datatables/datatables.min.js'); ?>

    <?php echo HTML::script('admin/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js'); ?>

    <?php echo HTML::script('js/cropper.js'); ?>

    <?php echo HTML::script('admin/webcam/webcam.js'); ?>

    <?php echo HTML::script('admin/webcam/webcam.min.js'); ?>


    <script>
        $('#dob').change(function () {
            var lre = /^\s*/;

            var inputDate = document.getElementById('dob').value;
            inputDate = inputDate.replace(lre, "");

            age = get_age(new Date(inputDate));

            $('#age').val(age);
            $('.age').text(age);
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

        $('#save_personal').click(function () {
            $.easyAjax({
                url: "<?php echo e(route('gym-admin.client.update')); ?>",
                container: '#personal_details',
                type: "POST",
                data: $('#personal_details').serialize()
            })
        });
        $('#save_others').click(function () {
            $.easyAjax({
                url: "<?php echo e(route('gym-admin.client.update')); ?>",
                container: '#other_details',
                type: "POST",
                data: $('#other_details').serialize()
            })
        });
        $('#save_image').click(function () {
            $.easyAjax({
                url: "<?php echo e(route('gym-admin.client.update')); ?>",
                container: '#update_image',
                type: "POST",
                file: true
            })
        });

        $('input[name=marital_status]').on('change', function () {
            var value = $('input[name=marital_status]:checked').val();
            if (value == 'no') {
                $('#anniversaryDiv').css('display', 'none');
            } else {
                $('#anniversaryDiv').css('display', 'block');
            }
        });
    </script>
    <script>
        var subscriptionTable = $('#memberships');
        var subscription = subscriptionTable.dataTable({
            processing: true,
            serverSide: true,
            ajax: "<?php echo e(route('gym-admin.client.ajax-subscriptions',$client->id)); ?>",
            columns: [
                {data: 'membership', name: 'membership'},
                {data: 'amount_to_be_paid', name: 'amount_to_be_paid'},
                {data: 'status', name: 'status'},
                {data: 'start_date', name: 'start_date'},
                {data: 'expires_on', name: 'expires_on'},
            ]
        });

        var reservationTable = $('#reservations');
        var reservation = reservationTable.dataTable({
            processing: true,
            serverSide: true,
            ajax: "<?php echo e(route('gym-admin.client.ajax-reservations',$client->id)); ?>",
            columns: [
                {data: 'locker', name: 'locker'},
                {data: 'amount_to_be_paid', name: 'amount_to_be_paid'},
                {data: 'status', name: 'status'},
                {data: 'start_date', name: 'start_date'},
                {data: 'end_date', name: 'end_date'},
            ]
        });

        var memPaymentTable = $('#mem-payments');
        var table1 = memPaymentTable.dataTable({
            processing: true,
            serverSide: true,
            ajax: "<?php echo e(route('gym-admin.client.ajax-payments',$client->id)); ?>",
            columns: [
                {data: 'payment_amount', name: 'payment_amount'},
                {data: 'membership', name: 'membership'},
                {data: 'payment_source', name: 'payment_source'},
                {data: 'payment_date', name: 'payment_date'},
                {data: 'payment_id', name: 'payment_id'},
            ]
        });

        var productPaymentTable = $('#product-payments');
        var table2 = productPaymentTable.dataTable({
            processing: true,
            serverSide: true,
            ajax: "<?php echo e(route('gym-admin.client.ajax-product-payments',$client->id)); ?>",
            columns: [
                {data: 'payment_amount', name: 'payment_amount'},
                {data: 'product_name', name: 'product_name'},
                {data: 'product_quantity', name: 'product_quantity'},
                {data: 'payment_source', name: 'payment_source'},
                {data: 'payment_date', name: 'payment_date'},
            ]
        });

        var productDueTable = $('#product-dues');
        var table3 = productDueTable.dataTable({
            processing: true,
            serverSide: true,
            ajax: "<?php echo e(route('gym-admin.client.ajax-product-due',$client->id)); ?>",
            columns: [
                {data: 'total_amount', name: 'total_amount'},
                {data: 'product_name', name: 'product_name'},
                {data: 'created_at', name: 'created_at'},
                {data: 'next_payment_date', name: 'next_payment_date'},
            ]
        });

        var memDueTable = $('#mem-dues');
        var table4 = memDueTable.dataTable({
            processing: true,
            serverSide: true,
            ajax: "<?php echo e(route('gym-admin.client.ajax-due',$client->id)); ?>",
            columns: [
                {data: 'amount_to_be_paid', name: 'amount_to_be_paid'},
                {data: 'membership', name: 'membership'},
                {data: 'purchase_date', name: 'purchase_date'},
                {data: 'next_payment_date', name: 'next_payment_date'},
            ]
        });

        var lockerPaymentTable = $('#locker-payments');
        var table5 = lockerPaymentTable.dataTable({
            processing: true,
            serverSide: true,
            ajax: "<?php echo e(route('gym-admin.client.ajax-locker-payments',$client->id)); ?>",
            columns: [
                {data: 'payment_amount', name: 'payment_amount'},
                {data: 'locker_id', name: 'locker_id'},
                {data: 'payment_source', name: 'payment_source'},
                {data: 'payment_date', name: 'payment_date'},
                {data: 'payment_id', name: 'payment_id'},
            ]
        });

        var lockerduePaymentTable = $('#locker-dues');
        var table6 = lockerduePaymentTable.dataTable({
            processing: true,
            serverSide: true,
            ajax: "<?php echo e(route('gym-admin.client.ajax-locker-dues',$client->id)); ?>",
            columns: [
                {data: 'remaining', name: 'remaining'},
                {data: 'locker_id', name: 'locker_id'},
                {data: 'start_date', name: 'start_date'},
                {data: 'next_payment_date', name: 'next_payment_date'},
            ]
        });

    </script>

    <script>
        $('document').ready(function () {
            var value = $('input[name=marital_status]:checked').val();
            if (value == 'no') {
                $('#anniversaryDiv').css('display', 'none');
            }
        });
    </script>
    <?php echo $__env->make('gym-admin.gymclients.imageupload', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('gym-admin.gymclients.webcam', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.gym-merchant.gymbasic', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/stage.hamrofitness.com/public_html/resources/views/gym-admin/gymclients/edit.blade.php ENDPATH**/ ?>