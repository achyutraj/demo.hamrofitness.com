

<?php $__env->startPush('general-styles'); ?>
    <style>
        .padding-bottom-btn {
            padding-bottom: 20px;
        }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('settingBody'); ?>
    <div class="portlet-body">
        <div class="row">
            <div class="col-md-3 col-sm-3 col-xs-3">
                <ul class="nav nav-tabs tabs-left">
                    <li class="active">
                        <a href="javascript:;"> Gym Logo </a>
                    </li>
                    <li>
                        <a href="<?php echo e(route('gym-admin.setting.sms')); ?>"> SMS </a>
                    </li>
                    <?php if($user->can("templates")): ?>
                        <li>
                            <a href="<?php echo e(route('gym-admin.templates.index')); ?>">
                                SMS Template
                            </a>
                        </li>
                    <?php endif; ?>
                    <li>
                        <a href="<?php echo e(route('gym-admin.setting.notification')); ?>"> SMS Notifications </a>
                    </li>
                    <li>
                        <a href="<?php echo e(route('gym-admin.setting.payment-gateways')); ?>"> Payment Gateway </a>
                    </li>
                    <li>
                        <a href="<?php echo e(route('gym-admin.setting.others')); ?>"> Others </a>
                    </li>
                    <li>
                        <a href="<?php echo e(route('gym-admin.setting.apps')); ?>"> Mobile Apps </a>
                    </li>
                </ul>
            </div>
            <div class="col-md-9 col-sm-9 col-xs-9">
                <div class="tab-content">
                    <?php echo Form::open(['route'=>'gym-admin.setting.store','id'=>'settingUpdateForm','class'=>'ajax-form form-horizontal','method'=>'POST','files' => true]); ?>

                        <div class="form-body col-md-6 col-md-offset-1">
                            <?php if($user->is_admin == 1): ?>
                            <div class="form-group form-md-line-input hidden-xs hidden-sm">
                                <label class="control-label" for="form_control_1">Main Logo</label>
                                <div class="input-icon right">
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
                                            <?php if($merchantSetting == ''): ?>
                                                <img id="changeProfile" src="<?php echo e(asset('/fitsigma/images/').'/'.'fitness-plus.png'); ?>" alt="" />
                                            <?php elseif($merchantSetting->image == ''): ?>
                                                <img src="<?php echo e(asset('/fitsigma/images/').'/'.'fitness-plus.png'); ?>" alt="" />
                                            <?php else: ?>
                                                <img id="changeProfile" src="<?php echo e($gymSettingPath.$merchantSetting->image); ?>" alt="" />
                                            <?php endif; ?>
                                        </div>
                                        <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"> </div>
                                    </div>
                                    <div class="clear-fix"></div>
                                    <button class="btn blue" rel="upload" onclick="forImage(this)" >Upload Image</button>
                                </div>
                            </div>
                            <?php endif; ?>
                            <div class="form-group form-md-line-input hidden-xs hidden-sm">
                                <label class="control-label" for="form_control_1">Logo</label>
                                <div class="input-icon right">
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
                                            <?php if($merchantSetting == ''): ?>
                                                <img id="loginImage" src="<?php echo e(asset("admin/pages/img/login/bg1.jpg")); ?>" alt="" />
                                            <?php elseif($merchantSetting->front_image == ''): ?>
                                                <img src="<?php echo e(asset("admin/pages/img/login/bg1.jpg")); ?>" alt="" />
                                            <?php else: ?>
                                                <img id="loginImage" src="<?php echo e($gymSettingPath.$merchantSetting->front_image); ?>" alt="" />
                                            <?php endif; ?>
                                        </div>
                                        <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"> </div>
                                    </div>
                                    <div class="clear-fix"></div>
                                    <button class="btn blue" rel="upload" onclick="forFrontImage(this)" >Upload Image</button>
                                </div>
                            </div>

                            <div class="form-group form-md-line-input hidden-xs hidden-sm">
                                <label class="control-label" for="form_control_1">Customer Panel Logo</label>
                                <div class="input-icon right">
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
                                            <?php if($merchantSetting == ''): ?>
                                                <img id="customerImage" src="<?php echo e(asset('/fitsigma/images/').'/'.'fitness-plus.png'); ?>" alt="" />
                                            <?php elseif($merchantSetting->customer_logo == ''): ?>
                                                <img src="<?php echo e(asset('/fitsigma/images/').'/'.'fitness-plus.png'); ?>" alt="" />
                                            <?php else: ?>
                                                <img id="customerImage" src="<?php echo e($gymSettingPath.$merchantSetting->customer_logo); ?>" alt="" />
                                            <?php endif; ?>
                                        </div>
                                        <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"> </div>
                                    </div>
                                    <div class="clear-fix"></div>
                                    <button class="btn blue" rel="upload" onclick="forCustomerImage(this)" >Upload Image</button>
                                </div>
                            </div>

                        </div>
                        <input type="hidden" name="id" value="<?php echo e($user->id); ?>">
                        <div class="form-actions">
                        <div class="row">
                            <div class="col-md-offset-3 col-md-9">
                                <a href="javascript:;" class="btn green" id="settingUpdate">Submit</a>
                                <a href="javascript:;" class="btn default">Cancel</a>
                            </div>
                        </div>
                    <?php echo Form::close(); ?>

                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('general-scripts'); ?>
    <?php echo HTML::script('admin/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js'); ?>

    <?php echo HTML::script('js/cropper.js'); ?>

    <script>
        $('#settingUpdate').click(function(){
            $.easyAjax({
                url: "<?php echo e(route('gym-admin.setting.store')); ?>",
                container:'#settingUpdateForm',
                type: "POST",
                file: true
            });
        });

        function forImage(task)
        {

            $('#task').val($(task).attr('rel'));
            $('#image').val('');
            if($('#task').val() == "upload")
            {
                $("#deleteProfileImage").hide();
            }
            else
            {
                $("#deleteProfileImage").removeAttr('style');
            }
            $('#uploadImage').modal('show');
        }

        function forFrontImage(task) {
            $('#task').val($(task).attr('rel'));
            $('#image').val('');
            if($('#task').val() == "upload")
            {
                $("#deleteProfileImage").hide();
            }
            else
            {
                $("#deleteProfileImage").removeAttr('style');
            }
            $('#uploadFrontImage').modal('show');
        }

        function uploadLoginImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#choose > img').attr('src', e.target.result);
                };
                reader.readAsDataURL(input.files[0]);
            }
            var formData = new FormData();
            formData.append('file', input.files[0]);
            $.ajax({
                type: 'post',
                url: "<?php echo e(route('gym-admin.setting.frontImage')); ?>",
                cache: false,
                processData: false,
                contentType: false,
                data: formData
            }).done(
                function( response ) {
                    if(response.status == 'fail') {

                    }
                    var obj = jQuery.parseJSON( response );
                    $('#uploadFrontImage').modal('hide');
                    $('#loginImage').attr('src', "<?php echo e($gymSettingPath); ?>" + obj.image);
                    $('.popover ').hide();
                });
        }

        function readImageURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#choose > img').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
            $('#cropImage').modal('show');
            $('#uploadImage').modal('hide');
        }

        $(document).ready(function() {
            $('#cropImage').on('shown.bs.modal', function () {
                $('#choose > img').cropper({
                    dragMode: 'move',
                    guides: true,
                    highlight: true,
                    dragCrop: true,
                    cropBoxMovable: true,
                    cropBoxResizable: true,
                    mouseWheelZoom: true,
                    touchDragZoom: false,
                    built: function () {

                        // Width and Height params are number types instead of string
                        $('#choose > img').cropper('setCropBoxData', {width: 800, height: 500});
                        var $clone = $(this).clone();
                        $clone.css({
                            display: 'block',
                            width: '100%',
                            minWidth: 0,
                            minHeight: 0,
                            maxWidth: 'none',
                            maxHeight: 'none'
                        });
                        $clone.removeAttr("class");
                    },
                    crop: function(e) {
                        var imageDataCrops = $(this).cropper('getImageData');
                        $('#xCoordOne').val(e.x);
                        $('#yCoordOne').val(e.y);
                        $('#profileImageWidth').val(e.width);
                        $('#profileImageHeight').val(e.height);
                    },
                    cropmove: function (e) {
                        var cropBoxData = $(this).cropper('getCropBoxData');
                        var cropBoxWidth = cropBoxData.width;
                        var cropBoxHeight = cropBoxData.height;
                    }
                });
            }).on('hidden.bs.modal', function () {
                advertCropBoxData = $('#choose > img').cropper('getCropBoxData');
                advertCanvasData = $('#choose > img').cropper('getCanvasData');
                $('#choose > img').cropper('destroy');
            });

            $("#advertImageCropButton").click(function () {
                uploadImage();
                $('#cropImage').modal('hide');
            });
        });

        function uploadImage() {

            var image = $('#image')[0];
            var xCoordinate = $('#xCoordOne').val();
            var yCoordinate = $('#yCoordOne').val();
            var profileImageWidth = $('#profileImageWidth').val();
            var profileImageHeight = $('#profileImageHeight').val();
            var formData = new FormData();
            formData.append('xCoordOne', xCoordinate);
            formData.append('yCoordOne', yCoordinate);
            formData.append('profileImageWidth', profileImageWidth);
            formData.append('profileImageHeight', profileImageHeight);
            formData.append('file', image.files[0]);
            $.ajax({
                type: 'post',
                url: "<?php echo e(route('gym-admin.gymsetting.image')); ?>",
                cache: false,
                processData: false,
                contentType: false,
                data: formData
            }).done(
                function( response ) {
                    if(response.status == 'fail') {

                    }
                    var obj = jQuery.parseJSON( response );
                    $(".profile-img-container_before").hide();
                    $("#img_name").val(obj.image);
                    $('.profile-img-container').removeAttr('style');
                    $( ".profile-img-container" ).wrap( "<div class='imageDelete'></div>" );
                    $('#uploadImage').modal('hide');
                    $('#changeProfile').attr('src', "<?php echo e($gymSettingPath); ?>" + obj.image);
                    var data = '<div class="profile-big-container"> <img src="<?php echo e($gymSettingPath); ?>' + obj.image + '" class="profile-img-big"><span rel="change" class="change-photo" onclick="forImage(this)">Change Photo</span></div>';
                    $('.changeAfterProfile').attr('src', "<?php echo e($gymSettingPathThumb); ?>" + obj.image);
                    $('.image-change').attr('src', "<?php echo e($gymSettingPath); ?>" + obj.image);
                    profile = '<img src="<?php echo e($gymSettingPathThumb); ?>' + obj.image + '">';
                    $('.popover ').hide();
                });
        }


        (function (factory) {
            if (typeof define === "function" && define.amd) {
                define(["jquery"], factory);
            } else {
                factory(jQuery);
            }
        })(function ($) {

            "use strict";

            var console = window.console || {
                log: $.noop
            };
        });

        function forCustomerImage(task)
        {
            $('#task').val($(task).attr('rel'));
            $('#image').val('');
            $('#uploadCustomerImage').modal('show');
        }

        var originalImage;

        function readCustomerImageURL(input)
        {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#chooseDiv > img').attr('src', e.target.result);
                }
                originalImage = input.files[0];
                reader.readAsDataURL(input.files[0]);
            }
            $('#cropCustomerImage').modal('show');
            $('#uploadCustomerImage').modal('hide');
        }

        $(function() {
            $('#cropCustomerImage').on('shown.bs.modal', function () {
                $('#chooseDiv > img').cropper({
                    dragMode: 'move',
                    guides: true,
                    highlight: true,
                    dragCrop: true,
                    cropBoxMovable: true,
                    cropBoxResizable: true,
                    mouseWheelZoom: true,
                    touchDragZoom: false,
                    built: function () {

                        // Width and Height params are number types instead of string
                        $('#chooseDiv > img').cropper('setCropBoxData', {width: 800, height: 500});
                        var $clone = $(this).clone();
                        $clone.css({
                            display: 'block',
                            width: '100%',
                            minWidth: 0,
                            minHeight: 0,
                            maxWidth: 'none',
                            maxHeight: 'none'
                        });
                        $clone.removeAttr("class");
                    },
                    crop: function(e) {
                        var imageDataCrops = $(this).cropper('getImageData');
                        $('#xCoordOne').val(e.x);
                        $('#yCoordOne').val(e.y);
                        $('#profileImageWidth').val(e.width);
                        $('#profileImageHeight').val(e.height);
                    },
                    cropmove: function (e) {
                        var cropBoxData = $(this).cropper('getCropBoxData');
                        var cropBoxWidth = cropBoxData.width;
                        var cropBoxHeight = cropBoxData.height;
                    }
                });
            }).on('hidden.bs.modal', function () {
                advertCropBoxData = $('#chooseDiv > img').cropper('getCropBoxData');
                advertCanvasData = $('#chooseDiv > img').cropper('getCanvasData');
                $('#chooseDiv > img').cropper('destroy');
            });

            $("#cropButton").click(function () {
                uploadCustomerImage();
                $('#cropCustomerImage').modal('hide');
            });
        });

        function uploadCustomerImage()
        {
            var image = originalImage;
            var xCoordinate = $('#xCoordOne').val();
            var yCoordinate = $('#yCoordOne').val();
            var profileImageWidth = $('#profileImageWidth').val();
            var profileImageHeight = $('#profileImageHeight').val();
            var formData = new FormData();
            formData.append('xCoordOne', xCoordinate);
            formData.append('yCoordOne', yCoordinate);
            formData.append('profileImageWidth', profileImageWidth);
            formData.append('profileImageHeight', profileImageHeight);
            formData.append('file', image);
            $.ajax({
                type: 'post',
                url: "<?php echo e(route('gym-admin.setting.customerImage')); ?>",
                cache: false,
                processData: false,
                contentType: false,
                data: formData,
                success: function (response) {
                    var obj = jQuery.parseJSON( response );
                    $('#uploadCustomerImage').modal('hide');
                    $('#customerImage').attr('src', "<?php echo e($gymSettingPath); ?>" + obj.image);
                }
            })
        }
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('gym-admin.setting.master-setting', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/stage.hamrofitness.com/public_html/resources/views/gym-admin/setting/general.blade.php ENDPATH**/ ?>