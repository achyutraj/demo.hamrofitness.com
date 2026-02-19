@extends('layouts.gym-merchant.gymbasic')

@section('CSS')
    <link rel="stylesheet" href="{{ asset("admin/global/plugins/ladda/ladda-themeless.min.css") }}">
    <link rel="stylesheet" href="{{ asset("admin/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.css") }}">
    <style>
        .help-block{
            color: red;
        }
    </style>
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
                <a href="{{ route('gym-admin.suppliers.index') }}">Party</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>Add Party</span>
            </li>
        </ul>
        <!-- END PAGE BREADCRUMBS -->
        <!-- BEGIN PAGE CONTENT INNER -->
        <div class="page-content-inner">
            <div class="row">
                <div class="col-md-8">
                    <div class="portlet light portlet-fit">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="icon-plus font-red"></i><span class="caption-subject font-red bold uppercase">Create Party</span>
                            </div>


                        </div>
                        <div class="portlet-body">
                            <!-- BEGIN FORM-->
                            {{ html()->form->open(['id'=>'form_sample_3','class'=>'ajax-form','method'=>'POST']) !!}
                            <div class="form-body">
                                <div class="form-group form-md-line-input form-md-floating-label">
                                    <input type="text" class="form-control" name="name" id="name">
                                    <div class="form-control-focus"> </div>
                                    @if(!$errors->isEmpty())
                                        <span class="help-block">{{$errors->first('name')}}</span>
                                    @else
                                        <span class="help-block">Please enter suppliers name.</span>
                                    @endif
                                    <label for="name">Party Name *</label>
                                </div>
                                <div class="form-group form-md-line-input form-md-floating-label">
                                    <input type="email" class="form-control" name="email" id="email">
                                    <div class="form-control-focus"> </div>
                                    @if(!$errors->isEmpty())
                                        <span class="help-block">{{$errors->first('email')}}</span>
                                    @else
                                        <span class="help-block">Please enter suppliers email.</span>
                                    @endif
                                    <label for="email">Email</label>
                                </div>
                                <div class="form-group form-md-line-input form-md-floating-label">
                                    <input type="text" class="form-control" name="phone" id="phone">
                                    <div class="form-control-focus"> </div>
                                    @if(!$errors->isEmpty())
                                        <span class="help-block">{{$errors->first('phone')}}</span>
                                    @else
                                        <span class="help-block">Please enter suppliers phone number.</span>
                                    @endif
                                    <label for="phone">Phone *</label>
                                </div>
                                <div class="form-group form-md-line-input form-md-floating-label">
                                    <input type="text" class="form-control" name="address" id="address">
                                    <div class="form-control-focus"> </div>
                                    @if(!$errors->isEmpty())
                                        <span class="help-block">{{$errors->first('address')}}</span>
                                    @else
                                        <span class="help-block">Please enter suppliers address.</span>
                                    @endif
                                    <label for="address">Address</label>
                                </div>
                            </div>
                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn dark mt-ladda-btn ladda-button"
                                                data-style="zoom-in" id="save-form">
                                            <span class="ladda-label">
                                                <i class="fa fa-save"></i> SAVE</span>
                                            <span class="ladda-spinner"></span>
                                            <div class="ladda-progress" style="width: 0px;"></div>
                                        </button>
                                        <button type="reset" class="btn default">Reset</button>
                                    </div>
                                </div>
                            </div>
                        {{ html()->form->close() !!}
                        <!-- END FORM-->
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
    <script src="{{ asset("admin/pages/scripts/ui-buttons.min.js") }}"></script>

    <script src="{{ asset("admin/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js") }}"></script>
    <script src="{{ asset("admin/global/plugins/jquery-validation/js/jquery.validate.min.js") }}"></script>
    <script src="{{ asset("admin/global/plugins/jquery-validation/js/additional-methods.min.js") }}"></script>

    <script>
        var FormValidationMd = function () {

            var handleValidation3 = function () {
                // for more info visit the official plugin documentation:
                // http://docs.jquery.com/Plugins/Validation
                var form1 = $('#form_sample_3');
                var error1 = $('.alert-danger', form1);
                var success1 = $('.alert-success', form1);

                form1.validate({
                    errorElement: 'span', //default input error message container
                    errorClass: 'help-block help-block-error', // default input error message class
                    focusInvalid: false, // do not focus the last invalid input
                    ignore: "", // validate all fields including form hidden input
                    rules: {
                        name: {
                            required: true
                        },
                        phone: {
                            required: true,
                            number: true
                        },
                    },

                    invalidHandler: function (event, validator) { //display error alert on form submit
                        success1.hide();
                        error1.show();
                        App.scrollTo(error1, -200);
                    },

                    errorPlacement: function(error, element) {
                        if (element.is(':checkbox')) {
                            error.insertAfter(element.closest(".md-checkbox-list, .md-checkbox-inline, .checkbox-list, .checkbox-inline"));
                        } else if (element.is(':radio')) {
                            error.insertAfter(element.closest(".md-radio-list, .md-radio-inline, .radio-list,.radio-inline"));
                        } else {
                            error.insertAfter(element); // for other inputs, just perform default behavior
                        }
                    },

                    highlight: function (element) { // hightlight error inputs
                        $(element)
                            .closest('.form-group').addClass('has-error'); // set error class to the control group
                    },

                    unhighlight: function (element) { // revert the change done by hightlight
                        $(element)
                            .closest('.form-group').removeClass('has-error'); // set error class to the control group
                    },

                    success: function (label) {
                        label
                            .closest('.form-group').removeClass('has-error'); // set success class to the control group
                    },

                    submitHandler: function (form) {
                        success1.show();
                        error1.hide();
                        $.easyAjax({
                            url: "{{route('gym-admin.suppliers.store')}}",
                            container: '#form_sample_3',
                            type: "POST",
                            formReset: true,
                            data: $('#form_sample_3').serialize()
                        });
                        return false;
                    }
                });
            }

            return {
                //main function to initiate the module
                init: function () {
                    handleValidation3();
                }
            };
        }();

        jQuery(document).ready(function () {
            FormValidationMd.init();
        });
    </script>
@stop
