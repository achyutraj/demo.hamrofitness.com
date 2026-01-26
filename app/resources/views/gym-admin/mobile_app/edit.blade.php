@extends('layouts.gym-merchant.gymbasic')

@section('CSS')
    {!! HTML::style('admin/global/plugins/bootstrap-select/css/bootstrap-select.min.css') !!}
    {!! HTML::style('admin/global/plugins/dropzone/dropzone.min.css') !!}
    {!! HTML::style('admin/global/plugins/dropzone/basic.min.css') !!}
    {!! HTML::style('admin/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.css') !!}
    {!! HTML::style('css/cropper.css')!!}
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
                <span>Mobile App</span>
            </li>
        </ul>
        <!-- END PAGE BREADCRUMBS -->
        <!-- BEGIN PAGE CONTENT INNER -->
        <div class="page-content-inner">

            <div class="row">
                <div class="col-md-12">
                    @if(session()->has('message'))
                        <div class="alert alert-message alert-success">
                            {{ session()->get('message') }}
                        </div>
                    @endif
                    <div class="portlet light ">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="icon-phone font-green-sharp"></i>
                                <span class="caption-subject font-green-sharp bold uppercase">Mobile App</span>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <ul class="nav nav-pills">
                                <li class="active" id="details">
                                    <a href="#tab_2_1" data-toggle="tab"> Details </a>
                                </li>
                                <li id="social_link">
                                    <a href="#tab_2_2" data-toggle="tab"> Social Links </a>
                                </li>
                                <li id="images">
                                    <a href="#tab_2_3" data-toggle="tab"> Images </a>
                                </li>
                            </ul>

                            <div class="tab-content">
                                <div class="tab-pane fade active in" id="tab_2_1">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="portlet light ">
                                                <div class="portlet-title">
                                                    <div class="caption font-dark">
                                                        <i class="icon-badge font-red"></i>
                                                        <span class="caption-subject font-red bold uppercase"> Details</span>
                                                    </div>
                                                </div>
                                                <div class="portlet-body">
                                                    <form action="{{ route('gym-admin.mobile-app.update') }}" id="detailData" class="ajax_form" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="mobile_app" value="{{ $mobileApp->id }}">
                                                        <div class="row">
                                                            <div class="form-body">
                                                                <div class="form-group form-md-line-input ">
                                                                    <select  class="bs-select form-control" data-live-search="true" data-size="8" name="detail_id" id="detail_id">
                                                                        @foreach($branches as $branch)
                                                                            <option value="{{ $branch->commonDetails->id }}" @if($mobileApp->detail_id == $branch->commonDetails->id) selected @endif>{{ $branch->commonDetails->title }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                    <label for="title">Select Branch Name*</label>
                                                                    <span class="help-block"></span>
                                                                </div>

                                                                <div class="form-group form-md-line-input ">
                                                                    <input type="email" class="form-control" id="contact_mail" name="contact_mail" value="{{ $mobileApp->contact_mail }}"  >
                                                                    <label for="form_control_1">Email*</label>
                                                                    <div class="form-control-focus"></div>
                                                                </div>

                                                                <div class=" form-group form-md-line-input ">
                                                                    <textarea class="form-control" name="address" id="address" rows="5" required>{{ $mobileApp->address }}</textarea>
                                                                    <label for="form_control_1">Address*</label>
                                                                    <span class="help-block">Please enter Address.</span>
                                                                </div>

                                                            </div>
                                                            <div class="form-body">
                                                                <div class=" form-group form-md-line-input ">
                                                                    <textarea class="form-control textarea_editor" name="about" id="about" rows="10" required>{{ $mobileApp->about }}</textarea>
                                                                    <label for="form_control_1">About*</label>
                                                                    <span class="help-block">Please enter About.</span>
                                                                </div>

                                                                <div class=" form-group form-md-line-input ">
                                                                    <textarea class="form-control textarea_editor" name="services" id="services" rows="10" required>{{ $mobileApp->services }}</textarea>
                                                                    <label for="form_control_1">Services*</label>
                                                                    <span class="help-block">Please enter Services.</span>
                                                                </div>

                                                                <div class=" form-group form-md-line-input ">
                                                                    <textarea class="form-control textarea_editor" name="price_plan" id="price_plan" rows="10" required>{{ $mobileApp->price_plan }}</textarea>
                                                                    <label for="form_control_1">Price Plan*</label>
                                                                    <span class="help-block">Please enter Price Plan.</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class=" col-md-offset-5 col-md-2">
                                                                <button type="button" class="btn btn-primary" id="updateDetails">Update</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade in" id="tab_2_2">
                                    @include('gym-admin.mobile_app.social')
                                </div>
                                <div class="tab-pane fade in" id="tab_2_3">
                                    @include('gym-admin.mobile_app.images')
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!-- END PAGE CONTENT INNER -->
    </div>
@stop

@section('footer')
    {!! HTML::script('admin/global/plugins/bootstrap-select/js/bootstrap-select.min.js') !!}
    {!! HTML::script('admin/pages/scripts/components-bootstrap-select.min.js') !!}
    <script src="https://maps.googleapis.com/maps/api/js?key=@if(!is_null($gymSettings->maps_api_key) && $gymSettings->maps_api_key != '') {{ $gymSettings->maps_api_key }} @endif&libraries=places"></script>
    {!! HTML::script('admin/global/plugins/dropzone/dropzone.min.js')  !!}
    {!! HTML::script('admin/pages/scripts/form-dropzone.min.js')  !!}
    {!! HTML::script('admin/global/plugins/bootstrap-wysihtml5/wysihtml5-0.3.0.js') !!}
    {!! HTML::script('admin/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.js') !!}
    {!! HTML::script('js/cropper.js') !!}

    <script>
        $('.textarea_editor').wysihtml5();
        $('#updateDetails').click(function(){
            $.easyAjax({
                url: "{{route('gym-admin.mobile-app.update')}}",
                type:"Post",
                container:'#detailData',
                data:$('#detailData').serialize()
            })
        });

        $('#updateSocialDetails').click(function(){
            $.easyAjax({
                url: "{{route('gym-admin.mobile-app.update')}}",
                type:"Post",
                container:'#detailSocialData',
                data:$('#detailSocialData').serialize()
            })
        });
    </script>

    @include('gym-admin.mobile_app.script')
@stop
