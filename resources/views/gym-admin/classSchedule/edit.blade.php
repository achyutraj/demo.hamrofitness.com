@extends('layouts.gym-merchant.gymbasic')

@section('CSS')
    <link rel="stylesheet" href="{{ asset("admin/global/plugins/ladda/ladda-themeless.min.css") }}">
    <link rel="stylesheet" href="{{ asset("admin/global/plugins/bootstrap-select/css/bootstrap-select.min.css") }}">
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
                <span>Edit</span>
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
                                <i class="icon-pencil font-red"></i><span class="caption-subject font-red bold uppercase">Edit Schedule</span>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <!-- BEGIN FORM-->
                            {{ html()->form()->open(['id'=>'storeFormData','class'=>'ajax-form','method'=>'POST']) }}

                            <div class="form-body">

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group form-md-line-input">
                                            <select  class="bs-select form-control" data-live-search="true" data-size="8" name="class" id="class" required>
                                                <option value="">Select Class</option>
                                                @foreach($classes as $class)
                                                    <option value="{{$class->id}}" @if($class->id == $schedule->class_id) selected @endif>{{$class->class_name}}</option>
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
                                                    <option value="{{$trainer->id}}" @if($trainer->id == $schedule->trainer_id) selected @endif>{{$trainer->name}}</option>
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
                                                    <option value="{{$key}}" @if(in_array($key, $select_days))selected="selected"@endif>{{$week}}</option>
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
                                                <input type="time" class="form-control" placeholder="Select Start Time" name="startTime" value="{{$schedule->startTime}}" id="startTime" required>
                                                <label for="startTime">Start Time</label>
                                                <span class="help-block">Enter Start Time</span>
                                                <i class="icon-clock"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group form-md-line-input">
                                            <div class="input-icon">
                                                <input type="time" class="form-control" placeholder="Select End Time" name="endTime" value="{{$schedule->endTime}}" id="endTime" required>
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
                                                        <input type="radio" id="assign_to_no" @if($schedule->has_client == 0) checked @endif name="assign_to" value="false"
                                                               class="md-radiobtn">
                                                        <label for="assign_to_no">
                                                            <span></span>
                                                            <span class="check"></span>
                                                            <span class="box"></span> No </label>
                                                    </div>
                                                    <div class="md-radio">
                                                        <input type="radio" id="assign_to_yes" @if($schedule->has_client == 1) checked @endif name="assign_to" class="md-radiobtn"
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
                                                    <option value="{{$client->id}}" @if(in_array($client->id, $client_assign))selected="selected"@endif>{{$client->fullName}}</option>
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
                            {{ html()->form()->close() }}
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
    <script src="{{ asset("admin/global/plugins/bootstrap-select/js/bootstrap-select.min.js") }}"></script>
    <script src="{{ asset("admin/pages/scripts/components-bootstrap-select.min.js") }}"></script>

    <script>
        $('#assign_to_yes').click(function(){
            $('#client_select').show();
        })
        $('#assign_to_no').click(function(){
            $('#client_select').hide();
        })
        $('#save-form').click(function(event){
            event.preventDefault();
            $.easyAjax({
                url: "{{route('gym-admin.class-schedule.update',$schedule->uuid)}}",
                container:'#storeFormData',
                type: "PUT",
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
