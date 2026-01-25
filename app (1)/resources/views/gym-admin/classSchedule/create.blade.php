@extends('layouts.gym-merchant.gymbasic')

@section('CSS')
    {!! HTML::style('admin/global/plugins/ladda/ladda-themeless.min.css') !!}
    {!! HTML::style('admin/global/plugins/bootstrap-select/css/bootstrap-select.min.css') !!}
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
                <a href="{{ route('gym-admin.class-schedule.index') }}">Class Schedule</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>Add</span>
            </li>
        </ul>
        <!-- END PAGE BREADCRUMBS -->
        <!-- BEGIN PAGE CONTENT INNER -->
        <div class="page-content-inner">
            <div class="row">
                <div class="col-md-10 col-xs-12">
                    <div class="portlet light portlet-fit">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="icon-plus font-red"></i><span class="caption-subject font-red bold uppercase">Add New Schedule</span>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <!-- BEGIN FORM-->
                            {!! Form::open(['id'=>'storeFormData','class'=>'ajax-form','method'=>'POST']) !!}

                            <div class="form-body">

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group form-md-line-input">
                                            <select  class="bs-select form-control" data-live-search="true" data-size="8" name="class" id="class" required>
                                                <option value="">Select Class</option>
                                                @foreach($classes as $class)
                                                    <option value="{{$class->id}}">{{$class->class_name}}</option>
                                                @endforeach
                                            </select>
                                            <label for="title">Classes Name</label>
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group form-md-line-input">
                                            <select  class="bs-select form-control" data-live-search="true" data-size="8" name="trainer" id="trainer" required>
                                                <option value="">Select Trainer</option>
                                                @foreach($trainers as $trainer)
                                                    <option value="{{$trainer->id}}">{{$trainer->name}}</option>
                                                @endforeach
                                            </select>
                                            <label for="title">Trainer</label>
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group form-md-line-input">
                                            <select  class="bs-select form-control" multiple="multiple" data-live-search="true" data-size="8" name="days[]" id="days" required>
                                                @foreach($weekends as $key=> $week)
                                                    <option value="{{$key}}">{{$week}}</option>
                                                @endforeach
                                            </select>
                                            <label for="title">Days</label>
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group form-md-line-input ">
                                            <div class="input-icon">
                                                <input type="time" class="form-control" placeholder="Select Start Time" name="startTime" id="startTime" required>
                                                <label for="startTime">Start Time</label>
                                                <span class="help-block">Enter Start Time</span>
                                                <i class="icon-clock"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group form-md-line-input">
                                            <div class="input-icon">
                                                <input type="time" class="form-control" placeholder="Select End Time" name="endTime" id="endTime" required>
                                                <label for="endTime">End Time</label>
                                                <span class="help-block">Enter End Time</span>
                                                <i class="icon-clock"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="form-group form-md-radios">
                                                <label for="assign_to">Assign this Schedule to Customer?</label>
                                                <div class="md-radio-inline">
                                                    <div class="md-radio">
                                                        <input type="radio" id="assign_to_no" checked name="assign_to" value="false"
                                                               class="md-radiobtn">
                                                        <label for="assign_to_no">
                                                            <span></span>
                                                            <span class="check"></span>
                                                            <span class="box"></span> No </label>
                                                    </div>
                                                    <div class="md-radio">
                                                        <input type="radio" id="assign_to_yes" name="assign_to" class="md-radiobtn"
                                                               value="true">
                                                        <label for="assign_to_yes">
                                                            <span></span>
                                                            <span class="check"></span>
                                                            <span class="box"></span> Yes</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group form-md-line-input" id="client_select">
                                            <select  class="bs-select form-control" multiple="multiple" data-live-search="true" data-size="8"  name="client[]" id="client">
                                                <option value="">Select Client</option>
                                                @foreach($clients as $client)
                                                    <option value="{{$client->id}}">{{$client->fullName}}</option>
                                                @endforeach

                                            </select>
                                            <label for="title">Client</label>
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                </div>


                            </div>

                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="button" class="btn dark mt-ladda-btn ladda-button" data-style="zoom-in" id="save-form">
                                            <span class="ladda-label"><i class="fa fa-save"></i> SAVE</span>
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
    {!! HTML::script('admin/global/plugins/bootstrap-select/js/bootstrap-select.min.js') !!}
    {!! HTML::script('admin/pages/scripts/components-bootstrap-select.min.js') !!}

    <script>
        $('#client_select').hide();
        $('#assign_to_yes').click(function(){
            $('#client_select').show();
        })
        $('#assign_to_no').click(function(){
            $('#client_select').hide();
        })
        $('#save-form').click(function(event){
            event.preventDefault();
            $.easyAjax({
                url: "{{route('gym-admin.class-schedule.store')}}",
                container:'#storeFormData',
                type: "POST",
                data:$('#storeFormData').serialize(),
                formReset:true,
                success:function(responce){
                    if(responce.status == 'success'){
                        $('#client').val('');
                        $('#client').selectpicker('refresh');
                        $('#class').val('');
                        $('#class').selectpicker('refresh');
                        $('#trainer').val('');
                        $('#trainer').selectpicker('refresh');
                    }
                }
            })
        });
    </script>
@stop
