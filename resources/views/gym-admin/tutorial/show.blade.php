@extends('layouts.gym-merchant.gymbasic')

@section('CSS')
    {!! HTML::style('admin/global/plugins/ladda/ladda-themeless.min.css') !!}
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
                <span>Tutorials</span>
            </li>
        </ul>
        <!-- END PAGE BREADCRUMBS -->
        <!-- BEGIN PAGE CONTENT INNER -->
        <div class="page-content-inner">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <div class="portlet light portlet-fit ">
                        <div class="portlet-title">
                            <div class="caption">
                                <span class="caption-subject font-green bold uppercase">{{ ucwords($tutorial->title) }}</span>
                                <div class="caption-desc font-grey-cascade">{{ ucfirst($tutorial->description) }}</div>
                            </div>
                        </div>
                        @if($tutorial->type == 'audio' || $tutorial->type == 'video')
                        <div class="portlet-body">
                            <div class="mt-element-overlay">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mt-overlay-3">
                                            <img src="{{ asset('uploads/ace-tut.jpg') }}" />
                                            <div class="mt-overlay">
                                                <h2>{{ ucwords($tutorial->title) }}</h2>
                                                <a id="{{ $tutorial->uuid }}" data-target="#viewTutorial"
                                                    data-toggle="modal" class="mt-info watch-tutorial btn sbold dark"> Play
                                                        <i class="fa fa-play"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if($tutorial->type == 'image')
                        <div class="portlet-body">
                            <div class="mt-element-overlay">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mt-overlay-3">
                                            <img src="{{ asset('uploads/gym_tutorial/'.$tutorial->image) }}" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
        <!-- END PAGE CONTENT INNER -->
    </div>

    <div class="modal fade bs-modal-md in" id="viewTutorial" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" id="modal-data-application">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <span class="caption-subject font-red-sunglo bold uppercase">{{ ucwords($tutorial->title) }}</span>
                </div>
                <div class="modal-body">
                    <div class="portlet-body">
                        <div class="row">
                            <div class="col-md-12">
                                {!! $tutorial->iframe_code !!}
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="modal-footer">
                    <div class="form-actions">
                        <div class="row">
                            <div class="col-md-offset-3 col-md-9">
                                <button type="button" class="btn default" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop

@section('footer')
    {!! HTML::script('admin/global/plugins/ladda/spin.min.js') !!}
    {!! HTML::script('admin/global/plugins/ladda/ladda.min.js') !!}
@stop
