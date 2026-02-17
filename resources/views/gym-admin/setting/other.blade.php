@extends('gym-admin.setting.master-setting')

@section('settingBody')
    <div class="portlet-body">
        <div class="row">
            <div class="col-md-3 col-sm-3 col-xs-3">
                <ul class="nav nav-tabs tabs-left">
                    <li>
                        <a href="{{ route('gym-admin.setting.index') }}"> Gym Logo </a>
                    </li>
                    <li>
                        <a href="{{ route('gym-admin.setting.sms') }}"> SMS </a>
                    </li>
                    @if($user->can("templates"))
                        <li>
                            <a href="{{route('gym-admin.templates.index')}}">
                                SMS Template
                            </a>
                        </li>
                    @endif
                    <li>
                        <a href="{{ route('gym-admin.setting.notification') }}">SMS Notifications </a>
                    </li>
                    <li>
                        <a href="{{ route('gym-admin.setting.payment-gateways') }}"> Payment Gateway </a>
                    </li>
                    <li class="active">
                        <a href="javascript:;"> Others </a>
                    </li>
                    <li>
                        <a href="{{ route('gym-admin.setting.apps') }}"> Mobile Apps </a>
                    </li>
                </ul>
            </div>
            <div class="col-md-9 col-sm-9 col-xs-9">
                <div class="tab-content">
                    {!! Form::open(['route'=>'gym-admin.setting.storeOtherSettingCredentials','id'=>'otherCredentialForm','class'=>'ajax-form form-horizontal','method'=>'POST','files' => true]) !!}
                        <div class="form-body col-md-6 col-md-offset-1">
                            <div class="form-group form-md-line-input">
                                <label class="control-label" for="form_control_1">Dashboard Subscription Expire Day</label>
                                <div class="input-icon right">
                                    <input type="number" class="form-control" placeholder="Show Subscription Expire Day Before" id="subscription_expire_days" name="subscription_expire_days" value="{{ $options['subscription_expire_days'] ?? 45 }}">
                                    <div class="form-control-focus"> </div>
                                    <span class="help-block">Enter Expire Day</span>
                                </div>
                            </div>
                            <div class="form-group form-md-line-input">
                                <label class="control-label" for="form_control_1">Dashboard Product Expire Day</label>
                                <div class="input-icon right">
                                    <input type="number" class="form-control" placeholder="Show Product Expire Day Before" id="product_expire_days" name="product_expire_days" value="{{ $options['product_expire_days'] ?? 45 }}">
                                    <div class="form-control-focus"> </div>
                                    <span class="help-block">Enter Expire Day</span>
                                </div>
                            </div>
                            <div class="form-group form-md-line-input">
                                <label class="control-label" for="form_control_1">Lock Screen Timeout (in sec)</label>
                                <div class="input-icon right">
                                    <input type="text" class="form-control" placeholder="Idle Time" id="idle_time" name="idle_time" value="@if($merchantSetting !='') {{ $merchantSetting->idle_time }} @endif">
                                    <div class="form-control-focus"> </div>
                                    <span class="help-block">Enter idle time to lock account</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions">
                        <div class="row">
                            <div class="col-md-offset-3 col-md-9">
                                <a href="javascript:;" class="btn green" id="otherSettingUpdate">Submit</a>
                                <a href="javascript:;" class="btn default">Cancel</a>
                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@stop

@push('other-scripts')
    <script>
        $('#otherSettingUpdate').click(function() {
            $.easyAjax({
                url: "{{ route('gym-admin.setting.storeOtherSettingCredentials') }}",
                container: '#otherCredentialForm',
                type: "POST",
                data: $('#otherCredentialForm').serialize()
            });
        });
    </script>
@endpush
