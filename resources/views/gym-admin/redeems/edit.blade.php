@extends('layouts.gym-merchant.gymbasic')

@section('CSS')

    {!! HTML::style('admin/global/plugins/ladda/ladda-themeless.min.css') !!}
    {!! HTML::style('admin/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.css') !!}
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
                <a href="{{ route('gym-admin.redeems.index') }}">Redeem</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>Edit Redeem</span>
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
                                <i class="icon-pencil font-red"></i><span class="caption-subject font-red bold uppercase">Edit Redeem</span></div>


                        </div>
                        <div class="portlet-body">
                            <!-- BEGIN FORM-->
                            {!! Form::open(['id'=>'form_sample_3','class'=>'ajax-form','method'=>'POST']) !!}
                            <input type="hidden" name="id" value="{{$redeem->id}}">
                            <div class="form-body">
                                <div class="form-group form-md-line-input form-md-floating-label col-md-6">
                                    <input type="text" class="form-control" name="title" id="title" value="{{$redeem->title}}">
                                    <div class="form-control-focus"> </div>
                                    @if(!$errors->isEmpty())
                                        <span class="help-block">{{$errors->first('title')}}</span>
                                    @else
                                        <span class="help-block">Please enter offer title.</span>
                                    @endif
                                    <label for="title">Offer Title *</label>
                                </div>
                                <div class="form-group form-md-line-input form-md-floating-label col-md-6">
                                    <input type="number" class="form-control" name="redeem_points" id="redeem_points" value="{{$redeem->redeem_points}}">
                                    <div class="form-control-focus"> </div>
                                    @if(!$errors->isEmpty())
                                        <span class="help-block">{{$errors->first('redeem_points')}}</span>
                                    @else
                                        <span class="help-block">Please enter redeem points.</span>
                                    @endif
                                    <label for="redeem_points">Redeem Point *</label>
                                </div>
                                
                                <div class="form-group form-md-line-input form-md-floating-label col-md-6">
                                    <input type="text" class="form-control date-picker" data-provide="datepicker"
                                        data-date-today-highlight="true" value="{{$redeem->start_date->format('m/d/Y')}}" name="start_date" id="start_date">
                                    <div class="form-control-focus"> </div>
                                    @if(!$errors->isEmpty())
                                        <span class="help-block">{{$errors->first('start_date')}}</span>
                                    @else
                                        <span class="help-block">Please enter start date.</span>
                                    @endif
                                    <label for="start_date">Start Date *</label>
                                </div>
                                <div class="form-group form-md-line-input form-md-floating-label col-md-6">
                                    <input type="text" class="form-control date-picker" data-provide="datepicker"
                                    data-date-today-highlight="true" value="{{$redeem->end_date->format('m/d/Y')}}"
                                     name="end_date" id="end_date">
                                    <div class="form-control-focus"> </div>
                                    @if(!$errors->isEmpty())
                                        <span class="help-block">{{$errors->first('end_date')}}</span>
                                    @else
                                        <span class="help-block">Please enter end date.</span>
                                    @endif
                                    <label for="end_date">End Date *</label>
                                </div>
                                <div class="form-group  col-md-8">
                                    <select class="form-control edited" id="membership" name="membership">
                                        <option value="" selected disabled>Select Membership</option>
                                        @foreach($memberships as $mem)
                                            <option value="{{$mem->id}}" @if($redeem->membership_id == $mem->id) selected @endif>{{$mem->title}} - [{{ $mem->duration }} {{ $mem->duration_type }}]  {{ $gymSettings->currency->acronym }} {{ $mem->price}}</option>
                                        @endforeach
                                    </select>
                                    <label for="membership">Offer Membership *</label>
                                </div>
                               
                                <div class="form-group col-md-4">
                                    <select class="form-control edited" id="status" name="status">
                                        <option value="1" @if($redeem->status == 1) selected @endif>Active</option>
                                        <option value="0" @if($redeem->status == 0) selected @endif>Inactive</option>
                                    </select>
                                    <label for="status">Status</label>
                                </div>
                            </div>
                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn dark mt-ladda-btn ladda-button" data-style="zoom-in" id="save-form">
                                            <span class="ladda-label">
                                                <i class="fa fa-save"></i> Update</span>
                                            <span class="ladda-spinner"></span>
                                            <div class="ladda-progress" style="width: 0px;"></div>
                                        </button>
                                        <button type="reset" class="btn default">Reset</button>
                                    </div>
                                </div>
                            </div>
                        {!! Form::close() !!}
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

    {!! HTML::script('admin/global/plugins/ladda/spin.min.js') !!}
    {!! HTML::script('admin/global/plugins/ladda/ladda.min.js') !!}
    {!! HTML::script('admin/pages/scripts/ui-buttons.min.js') !!}

    {!! HTML::script('admin/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js') !!}
    {!! HTML::script('admin/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') !!}
    {!! HTML::style('admin/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') !!}
    {!! HTML::script('admin/global/plugins/jquery-validation/js/jquery.validate.min.js') !!}
    {!! HTML::script('admin/global/plugins/jquery-validation/js/additional-methods.min.js') !!}

    <script>
        $('.date-picker').datepicker({
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
                        name: {
                            required: true
                        },
                        phone: {
                            required: true,
                            number: true
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
                            url: "{{route('gym-admin.redeems.update')}}",
                            container:'#form_sample_3',
                            type: "POST",
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
