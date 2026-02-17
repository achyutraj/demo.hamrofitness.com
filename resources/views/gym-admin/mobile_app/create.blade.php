@extends('layouts.gym-merchant.gymbasic')

@section('CSS')
    {!! HTML::style('admin/global/plugins/bootstrap-select/css/bootstrap-select.min.css') !!}
    {!! HTML::style('admin/global/plugins/dropzone/dropzone.min.css') !!}
    {!! HTML::style('admin/global/plugins/dropzone/basic.min.css') !!}
    {!! HTML::style('admin/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.css') !!}
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
                    <div class="portlet light ">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="icon-phone font-green-sharp"></i>
                                <span class="caption-subject font-green-sharp bold uppercase">Mobile App</span>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <form action="{{ route('gym-admin.mobile-app.store') }}" class="ajax_form" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="portlet light ">
                                            <div class="portlet-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-body">
                                                            <div class="form-group form-md-line-input ">
                                                                <select  class="bs-select form-control" data-live-search="true" data-size="8" name="detail_id" id="detail_id">
                                                                    @foreach($branches as $branch)
                                                                        <option value="{{ $branch->commonDetails->id }}">{{ $branch->commonDetails->title }}</option>
                                                                    @endforeach
                                                                </select>
                                                                <label for="title">Select Branch Name*</label>
                                                                <span class="help-block"></span>
                                                            </div>

                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-body">
                                                            <div class="form-group form-md-line-input ">
                                                                <input type="email" class="form-control" id="contact_mail" name="contact_mail" value=""  >
                                                                <label for="form_control_1">Email*</label>
                                                                <span class="help-block">Please enter your email.</span>
                                                            </div>

                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-body">
                                                            <div class=" form-group form-md-line-input ">
                                                                <textarea class="form-control" name="address" id="address" rows="5" required></textarea>
                                                                <label for="form_control_1">Address*</label>
                                                                <span class="help-block">Please enter Address.</span>
                                                            </div>

                                                            <div class=" form-group form-md-line-input ">
                                                                <textarea class="form-control textarea_editor" name="about" id="about" rows="10" required></textarea>
                                                                <label for="form_control_1">About*</label>
                                                                <span class="help-block">Please enter About.</span>
                                                            </div>

                                                            <div class=" form-group form-md-line-input ">
                                                                <textarea class="form-control textarea_editor" name="services" id="services" rows="10" required></textarea>
                                                                <label for="form_control_1">Services*</label>
                                                                <span class="help-block">Please enter Services.</span>
                                                            </div>

                                                            <div class=" form-group form-md-line-input ">
                                                                <textarea class="form-control textarea_editor" name="price_plan" id="price_plan" rows="10" required></textarea>
                                                                <label for="form_control_1">Price Plan*</label>
                                                                <span class="help-block">Please enter Price Plan.</span>
                                                            </div>


                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class=" col-md-offset-5 col-md-2">
                                                        <button type="submit" class="btn btn-primary">Add</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>

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
    {!! HTML::script('admin/global/plugins/bootstrap-wysihtml5/wysihtml5-0.3.0.js') !!}
    {!! HTML::script('admin/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.js') !!}

    <script>
        $('.textarea_editor').wysihtml5();
    </script>
@stop
