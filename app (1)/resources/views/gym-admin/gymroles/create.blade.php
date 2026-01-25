@extends('layouts.gym-merchant.gymbasic')

@section('CSS')
    {!! HTML::style('admin/global/plugins/ladda/ladda-themeless.min.css') !!}
@stop

@section('content')
    <div class="container-fluid"  >
        <!-- BEGIN PAGE BREADCRUMBS -->
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <a href="{{ route('gym-admin.users.index') }}">Users</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>Add Role</span>
            </li>
        </ul>
        <!-- END PAGE BREADCRUMBS -->
        <!-- BEGIN PAGE CONTENT INNER -->
        <div class="page-content-inner">
            <div class="row">
                <div class="col-md-12 col-xs-12">

                    <div class="portlet light ">
                        <div class="portlet-title">
                            <div class="caption font-dark">
                                <i class="icon-plus font-red"></i>
                                <span class="caption-subject font-red bold uppercase"> Add Role</span>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <!-- {!! Form::open(['id'=>'profileUpdateForm','class'=>'ajax-form form-horizontal','method'=>'POST','files' => true]) !!} -->
                            <form action="{{route('gym-admin.gymmerchantroles.store')}}" method="post">
                            {{csrf_field()}}
                            <div class="form-body">
                                <div class="form-group form-md-line-input">
                                    <label class="col-md-2 control-label" for="form_control_1">Role Name</label>
                                    <div class="col-md-6">
                                        <div class="input-icon right">
                                            <input type="text" class="form-control" placeholder="Role Name" name="name" id="name" required>
                                            <div class="form-control-focus"> </div>
                                            @if(!$errors->isEmpty())
                                                <span style="color:red">{{$errors->first('name')}}</span>
                                            @else
                                                <span class="help-block">Enter role name</span>
                                            @endif
                                            <i class="icon-user"></i>
                                            

                                        </div>
                                    </div>
                                </div>

                                <hr>
                            </div>

                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-offset-3 col-md-9">
                                        <button type="submit" class="btn green">Submit</button>
                                        <a href="{{ route('gym-admin.users.index') }}" class="btn default">Cancel</a>
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
    {!! HTML::script('admin/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') !!}
    {!! HTML::style('admin/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') !!}
    {!! HTML::script('admin/global/plugins/ladda/spin.min.js') !!}
    {!! HTML::script('admin/global/plugins/ladda/ladda.min.js') !!}
    {!! HTML::script('admin/pages/scripts/ui-buttons.min.js') !!}

    <script>

        $('#date_of_birth').datepicker({
            rtl: App.isRTL(),
            orientation: "left",
            autoclose: true,
            endDate: '+0d',
            startView: 'decades'
        });

    </script>
    <script>
        $('#updateProfile').click(function(){
            var url = "{{route('gym-admin.gymmerchantroles.store')}}";
            $.easyAjax({
                url:url,
                container:'#profileUpdateForm',
                type: "POST",
                data:$('#profileUpdateForm').serialize()
            })
        });

    </script>

@stop