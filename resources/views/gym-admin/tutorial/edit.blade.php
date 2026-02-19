@extends('layouts.gym-merchant.gymbasic')

@section('CSS')

    <link rel="stylesheet" href="{{ asset("admin/global/plugins/ladda/ladda-themeless.min.css") }}">
    <link rel="stylesheet" href="{{ asset("admin/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.css") }}">
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
                <a href="{{ route('gym-admin.tutorials.index') }}">Tutorial</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>Edit Tutorial<span>
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
                                <i class="icon-pencil font-red"></i><span class="caption-subject font-red bold uppercase">Edit Tutorial</span>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <!-- BEGIN FORM-->
                            <form action="{{ route('gym-admin.tutorials.update',$tutorial->uuid) }}" method="POST"
                                    enctype="multipart/form-data">
                                @method('PUT')
                                @csrf
                                <div class="form-body">
                                    <div class="form-group form-md-line-input form-md-floating-label">
                                        <input type="text" class="form-control" name="title" id="title" value="{{ $tutorial->title ?? old('title') }}">
                                        <label for="title">Title <span class="required" aria-required="true"> * </span></label>
                                    </div>
                                    
                                    <div class="form-group form-md-line-input form-md-floating-label">
                                        <select class="form-control" id="type" name="type" required>
                                            <option value="">Select Type</option>
                                            <option value="text" @if($tutorial->type == 'text') selected @endif>Text</option>
                                            <option value="video" @if($tutorial->type == 'video') selected @endif>Video</option>
                                            <option value="audio" @if($tutorial->type == 'audio') selected @endif>Audio</option>
                                            <option value="image" @if($tutorial->type == 'image') selected @endif>Image</option>
                                        </select>
                                        <label for="type">Type <span class="required" aria-required="true"> * </span></label>
                                    </div>

                                    <div class="form-group form-md-line-input">
                                        <textarea class="form-control" name="iframe_code" rows="3">{{ $tutorial->iframe_code }}</textarea>
                                        <label for="iframe_code">IFrame Code</label>
                                    </div>

                                    <div class="form-group form-md-line-input ">
                                        <textarea class="form-control wysihtml5"  name="description" rows="3">{{ $tutorial->description }}</textarea>
                                        <label for="description">Tutorial Description</label>
                                    </div>

                                    <div class="form-group form-md-line-input">
                                        <label class="col-md-2 control-label" for="image">Upload Image</label>
                                        <div class="">
                                            <div class="input-icon right">
                                                <input type="file" class="btn blue" name="image">
                                                @if($tutorial->image != null)
                                                    <img height="150" width="150" src="{{ asset('uploads/gym_tutorial/'.$tutorial->image) }}" alt="">
                                                @endif
                                            </div>
                                            <div id="error-msg" class="error-msg"></div>
                                        </div>
                                    </div>

                                    @if($user->is_admin == 1)
                                    <div class="form-group form-md-line-input">
                                        <div class="md-checkbox">
                                            <input type="checkbox" name="is_default" id="is_default" value="1" @if($tutorial->is_default == '1') selected @endif class="md-check">
                                            <label for="is_default">
                                                <span></span>
                                                <span class="check"></span>
                                                <span class="box"></span> Is Default To All Gym
                                            </label>
                                        </div>
                                    </div>
                                    @endif

                                    <div class="form-group form-md-line-input form-md-floating-label">
                                        <select class="form-control" id="status" name="status" required>
                                            <option value="">Select Status</option>
                                            <option value="1" @if($tutorial->status == '1') selected @endif>Active</option>
                                            <option value="0" @if($tutorial->status == '0') selected @endif>InActive</option>
                                        </select>
                                        <label for="type">Status</label>
                                    </div>

                                </div>
                                <div class="form-actions">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class=" col-md-offset-5 col-md-2">
                                                <button type="submit" class="btn btn-primary">Update</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
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

    <script src="{{ asset("admin/global/plugins/bootstrap-select/js/bootstrap-select.min.js") }}"></script>
    <script src="{{ asset("admin/pages/scripts/components-bootstrap-select.min.js") }}"></script>
    <script src="{{ asset("admin/global/plugins/bootstrap-wysihtml5/wysihtml5-0.3.0.js") }}"></script>
    <script src="{{ asset("admin/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.js") }}"></script>

    <script>
        $('.wysihtml5').wysihtml5();
    </script>
@stop
