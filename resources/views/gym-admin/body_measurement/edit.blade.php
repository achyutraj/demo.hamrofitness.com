@extends('layouts.gym-merchant.gymbasic')

@section('CSS')
    <link rel="stylesheet" href="{{ asset("admin/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css") }}">
    <link rel="stylesheet" href="{{ asset("admin/global/plugins/ladda/ladda-themeless.min.css") }}">
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
                <a href="{{ route('gym-admin.measurements.index') }}">Body Measurement</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>Edit Body Measurement</span>
            </li>
        </ul>
        <!-- END PAGE BREADCRUMBS -->
        <!-- BEGIN PAGE CONTENT INNER -->
        <div class="page-content-inner">
            <div class="row">
                <div class="col-md-12">

                    <div class="portlet light portlet-fit">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="icon-plus font-red"></i><span class="caption-subject font-red bold uppercase">Edit Measurement</span>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <!-- BEGIN FORM-->
                            {{ html()->form->open(['id'=>'form_sample_3','class'=>'ajax-form','method'=>'POST']) !!}
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group form-md-line-input form-md-floating-label">
                                            <select class="form-control" id="client" name="client" required>
                                                <option value="">Select Client</option>
                                                @foreach($clients as $client)
                                                    <option value="{{ $client->id }}" @if($measurement->client_id == $client->id) selected @endif>{{ $client->fullName }} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group form-md-line-input ">
                                            <div class="input-icon">
                                                <input type="text" readonly class="form-control date-picker" placeholder="Select Date" name="entry_date" id="entry_date" value="{{ $measurement->entry_date->format('m/d/Y') }}">
                                                <label for="form_control_1">Date</label>
                                                <span class="help-block">Enter Date</span>
                                                <i class="icon-calendar"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group form-md-line-input form-md-floating-label">
                                            <input type="number" min="1" class="form-control" name="height_feet" id="height_feet" value="{{ $measurement->height_feet }}">
                                            <label for="height_feet">Height (Feet)</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group form-md-line-input form-md-floating-label">
                                            <input type="number" min="1" class="form-control" name="height_inches" id="height_inches" value="{{ $measurement->height_inches }}">
                                            <label for="height_inches">Height (Inches)</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group form-md-line-input form-md-floating-label">
                                            <input type="number" min="1" class="form-control" name="weight" id="weight" value="{{ $measurement->weight }}">
                                            <label for="weight">Weight (KG)</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group form-md-line-input form-md-floating-label">
                                            <input type="number" min="1" class="form-control" name="fat" id="fat" value="{{ $measurement->fat }}">
                                            <label for="fat">Fat</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group form-md-line-input form-md-floating-label">
                                            <input type="number" min="1" class="form-control" name="chest" id="chest" value="{{ $measurement->chest }}">
                                            <label for="chest">Chest (Inches)</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group form-md-line-input form-md-floating-label">
                                            <input type="number" min="1" class="form-control" name="waist" id="waist" value="{{ $measurement->waist }}">
                                            <label for="waist">Waist (Inches)</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group form-md-line-input form-md-floating-label">
                                            <input type="number" min="1" class="form-control" name="arms" id="arms" value="{{ $measurement->arms }}">
                                            <label for="arms">Arms (Inches)</label>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group form-md-line-input form-md-floating-label">
                                            <input type="number" min="1" class="form-control" name="fore_arms" id="fore_arms" value="{{ $measurement->fore_arms }}">
                                            <label for="fore_arms">Fore Arms (Inches)</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group form-md-line-input form-md-floating-label">
                                            <input type="number" min="1" class="form-control" name="neck" id="neck" value="{{ $measurement->neck }}">
                                            <label for="neck">Neck (Inches)</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group form-md-line-input form-md-floating-label">
                                            <input type="number" min="1" class="form-control" name="shoulder" id="shoulder" value="{{ $measurement->shoulder }}">
                                            <label for="shoulder">Shoulder (Inches)</label>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group form-md-line-input form-md-floating-label">
                                            <input type="number" min="1" class="form-control" name="hip" id="hip" value="{{ $measurement->hip }}">
                                            <label for="hip">Hip (Inches)</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group form-md-line-input form-md-floating-label">
                                            <input type="number" min="1" class="form-control" name="thigh" id="thigh" value="{{ $measurement->thigh }}">
                                            <label for="thigh">Thigh (Inches)</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group form-md-line-input form-md-floating-label">
                                            <input type="number" min="1" class="form-control" name="calves" id="calves" value="{{ $measurement->calves }}">
                                            <label for="calves">Calves (Inches)</label>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn dark mt-ladda-btn ladda-button" data-style="zoom-in" id="save-form">
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
    <script src="{{ asset("admin/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js") }}"></script>
    <script src="{{ asset("admin/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js") }}"></script>

    <script src="{{ asset("admin/global/plugins/jquery-validation/js/jquery.validate.min.js") }}"></script>
    <script src="{{ asset("admin/global/plugins/jquery-validation/js/additional-methods.min.js") }}"></script>

    <script>
        $("#entry_date").datepicker({
            rtl: App.isRTL(),
            orientation: "left",
            autoclose: true,
        });

        var FormValidationMd = function() {

            var handleValidation3 = function() {
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
                        height_feet: {
                            required: true
                        },
                        height_inches: {
                            required: true
                        },
                        weight: {
                            required: true
                        },
                        fat: {
                            required: true
                        },
                        chest: {
                            required: true
                        },
                        waist: {
                            required: true
                        },
                        hip: {
                            required: true
                        },
                        arms: {
                            required: true
                        },
                        fore_arms: {
                            required: true
                        },
                        neck: {
                            required: true
                        },
                        thigh: {
                            required: true
                        },
                        calves: {
                            required: true
                        },
                        shoulder: {
                            required: true
                        },
                    },

                    invalidHandler: function(event, validator) { //display error alert on form submit
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

                    highlight: function(element) { // hightlight error inputs
                        $(element)
                            .closest('.form-group').addClass('has-error'); // set error class to the control group
                    },

                    unhighlight: function(element) { // revert the change done by hightlight
                        $(element)
                            .closest('.form-group').removeClass('has-error'); // set error class to the control group
                    },

                    success: function(label) {
                        label
                            .closest('.form-group').removeClass('has-error'); // set success class to the control group
                    },

                    submitHandler: function(form) {
                        success1.show();
                        error1.hide();

                        $.easyAjax({
                            url: "{{route('gym-admin.measurements.update',$measurement->uuid)}}",
                            container:'#form_sample_3',
                            type: "PUT",
                            data: $('#form_sample_3').serialize()
                        });
                        return false;
                    }
                });
            }

            return {
                //main function to initiate the module
                init: function() {
                    handleValidation3();
                }
            };
        }();

        jQuery(document).ready(function() {
            FormValidationMd.init();
        });
    </script>
@stop
