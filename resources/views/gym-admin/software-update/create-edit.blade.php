@extends('layouts.gym-merchant.gymbasic')

@section('CSS')
    <link rel="stylesheet" href="{{ asset("admin/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.css") }}">
    <link rel="stylesheet" href="{{ asset("admin/global/plugins/bootstrap-datepicker/css/datepicker.css") }}">
    <link rel="stylesheet" href="{{ asset("admin/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css") }}">
@stop

@section('content')
    <div class="container-fluid"      >
        <!-- BEGIN PAGE BREADCRUMBS -->
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <a href="{{route('gym-admin.dashboard.index')}}">Home</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>Software Updates</span>
            </li>
        </ul>
        <!-- END PAGE BREADCRUMBS -->
        <!-- BEGIN PAGE CONTENT INNER -->
        <div class="page-content-inner">
            <div class="row">
                <div class="col-md-12">
                    <div class="portlet light ">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="icon-phone font-green-sharp"></i>
                                <span class="caption-subject font-green-sharp bold uppercase">Software Update</span>
                            </div>
                        </div>
                        <div class="portlet-body">
                             {{ html()->form()->open(['id'=>'gym-software-data','class'=>'ajax-form']) }}
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="portlet light ">
                                            <div class="portlet-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group form-md-line-input ">
                                                            <input type="text" data-provide="datepicker" data-date-today-highlight="true"
                                                                   class="form-control date-picker"
                                                                   value="{{ \Carbon\Carbon::today()->format('m/d/Y') }}" name="date" id="date">
                                                            <label for="form_control_1">Upcoming Date</label>
                                                            <div class="form-control-focus"></div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-body">
                                                            <div class="form-group form-md-line-input ">
                                                                <input type="text" class="form-control" id="title" name="title" value="{{ $info->title ?? old('title') }}">
                                                                <label for="form_control_1">Title*</label>
                                                                <span class="help-block">Please enter title.</span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="form-body">
                                                            <div class=" form-group form-md-line-input ">
                                                                <textarea class="form-control textarea_editor" name="details" id="details" rows="10" required>{{ $info->details ?? old('details') }}</textarea>
                                                                <label for="form_control_1">Detail*</label>
                                                                <span class="help-block">Please enter About.</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class=" col-md-offset-5 col-md-2">
                                                        <button type="button" id="save_data" class="btn btn-primary">Add</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            {{ html()->form()->close() }}
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!-- END PAGE CONTENT INNER -->
    </div>
@stop

@section('footer')
    <script src="{{ asset("admin/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js") }}"></script>
    <link rel="stylesheet" href="{{ asset("admin/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css") }}">
    <script src="{{ asset("admin/global/plugins/bootstrap-wysihtml5/wysihtml5-0.3.0.js") }}"></script>
    <script src="{{ asset("admin/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.js") }}"></script>

    <script>
        $('.textarea_editor').wysihtml5();
        @if(isset($info))
        $('#save_data').click(function(){
            $.easyAjax({
                url: "{{route('gym-admin.upcoming.update',$info->id)}}",
                container:'#gym-software-data',
                type: "PUT",
                data:$('#gym-software-data').serialize()
            });
        });
        @else
        $('#save_data').click(function(){
            $.easyAjax({
                url: "{{route('gym-admin.upcoming.store')}}",
                container:'#gym-software-data',
                type: "POST",
                data:$('#gym-software-data').serialize()
            });
        });
        @endif
    </script>
@stop
