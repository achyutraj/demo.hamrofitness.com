@extends('layouts.gym-merchant.gymbasic')

@section('CSS')
    {!! HTML::style('admin/global/plugins/ladda/ladda-themeless.min.css') !!}
    {!! HTML::style('admin/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.css') !!}
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
                <a href="{{ route('gym-admin.tutorials.index') }}">Tutorials</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>Add Tutorial</span>
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
                                <i class="icon-plus font-red"></i><span class="caption-subject font-red bold uppercase">Add Tutorial</span>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <div class="form-body">
                                <form action="{{ route('gym-admin.tutorials.store') }}" method="POST"
                                    enctype="multipart/form-data" class="form">
                                    @csrf
                                    <div class="form-group form-md-line-input form-md-floating-label">
                                        <input type="text" class="form-control" name="title" id="title" required>
                                        <label for="title">Title <span class="required" aria-required="true"> * </span></label>
                                    </div>

                                    <div class="form-group form-md-line-input form-md-floating-label">
                                        <select class="form-control" id="type" name="type" required>
                                            <option value="">Select Type</option>
                                            <option value="text">Text</option>
                                            <option value="video">Video</option>
                                            <option value="audio">Audio</option>
                                            <option value="image">Image</option>
                                        </select>
                                        <label for="type">Type <span class="required" aria-required="true"> * </span></label>
                                    </div>

                                    <div class="form-group form-md-line-input" id="iframe_code_div" style="display: none;">
                                        <textarea class="form-control" name="iframe_code" rows="3"></textarea>
                                        <label for="iframe_code">IFrame Code</label>
                                    </div>

                                    <div class="form-group form-md-line-input" id="description_div" style="display: none;">
                                        <textarea class="form-control wysihtml5" name="description" rows="3"></textarea>
                                        <label for="description">Tutorial Description</label>
                                    </div>

                                    <div class="form-group form-md-line-input" id="image_upload_div" style="display: none;">
                                        {!! Form::file('image', ['class' => 'form-control']) !!}
                                        <label for="image">Upload Image</label>
                                    </div>

                                    @if($user->is_admin == 1)
                                        <div class="form-group form-md-line-input">
                                            <div class="md-checkbox">
                                                <input type="checkbox" name="is_default" id="is_default" value="1" class="md-check">
                                                <label for="is_default">
                                                    <span></span>
                                                    <span class="check"></span>
                                                    <span class="box"></span> Is Default To All Gyms
                                                </label>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="row">
                                        <div class=" col-md-offset-5 col-md-2">
                                            <button type="submit" class="btn btn-primary">Save</button>
                                        </div>
                                    </div>
                                </form>
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
    {!! HTML::script('admin/global/plugins/bootstrap-wysihtml5/wysihtml5-0.3.0.js') !!}
    {!! HTML::script('admin/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.js') !!}

    <script>
         /* Show/Hide fields based on selected type */
         function toggleFieldsBasedOnType(type) {
            $('#iframe_code_div').hide();
            $('#description_div').hide();
            $('#image_upload_div').hide();

            if (type === 'video' || type === 'audio') {
                $('#iframe_code_div').show();
            } else if (type === 'text') {
                $('#description_div').show();
            } else if (type === 'image') {
                $('#image_upload_div').show();
            }
        }
        jQuery(document).ready(function() {
            // Initial check
            toggleFieldsBasedOnType($('#type').val());

            // On type change
            $('#type').change(function() {
                toggleFieldsBasedOnType($(this).val());
            });
        });

        $('.wysihtml5').wysihtml5();
    </script>
@stop