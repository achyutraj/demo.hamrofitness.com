@extends('gym-admin.setting.master-setting')
@push('sms-styles')
    <style>
        .sms-credentials-hide {
            display: none;
        }
    </style>

@endpush
@section('settingBody')
    <div class="portlet-body">
        <div class="row">
            <div class="col-md-3 col-sm-3 col-xs-3">
                <ul class="nav nav-tabs tabs-left">
                    <li>
                        <a href="{{ route('gym-admin.setting.index') }}"> Gym Logo </a>
                    </li>
                    <li class="active">
                        <a href="javascript:;"> SMS </a>
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
                    <li>
                        <a href="{{ route('gym-admin.setting.others') }}"> Others </a>
                    </li>
                    <li>
                        <a href="{{ route('gym-admin.setting.apps') }}"> Mobile Apps </a>
                    </li>
                </ul>
            </div>
            <div class="col-md-9 col-sm-9 col-xs-9">
                <div class="tab-content">
                    {!! Form::open(['route'=>'gym-admin.setting.storeSmsCredentials','id'=>'smsCredentialForm','class'=>'ajax-form form-horizontal','method'=>'POST']) !!}
                    <div class="form-body col-md-6 col-md-offset-1">
                        <div class="form-group form-md-line-input">
                            <label class="control-label" for="form_control_1">Status</label>
                            <select class="form-control" name="sms_status">
                                <option @if($merchantSetting !='' && $merchantSetting->sms_status == 'disabled') selected @endif value="disabled">
                                    Disabled
                                </option>
                                <option @if($merchantSetting !='' && $merchantSetting->sms_status == 'enabled') selected @endif value="enabled">
                                    Enabled
                                </option>
                            </select>
                            <div class="form-control-focus"></div>
                        </div>
                        <div class="form-group form-md-line-input">
                            <label class="control-label" for="form_control_1">Method</label>
                            <select class="form-control" name="is_old">
                                <option @if($merchantSetting !='' && $merchantSetting->is_old == '0') selected @endif value="0">
                                    New Method
                                </option>
                                <option @if($merchantSetting !='' && $merchantSetting->is_old == '1') selected @endif value="1">
                                    Old Method
                                </option>
                            </select>
                            <div class="form-control-focus"></div>
                        </div>
                        <div class="form-group form-md-line-input sms-credentials @if($merchantSetting !='' && $merchantSetting->sms_status == 'disabled') sms-credentials-hide @endif">
                            <label class="control-label" for="form_control_1">API URL</label>
                            <div class="input-icon right">
                                <input type="text" class="form-control" placeholder="Sender ID" id="sms_api_url" name="sms_api_url"
                                       value="@if($merchantSetting !=''){{ $merchantSetting->sms_api_url }}@endif">
                                <div class="form-control-focus"></div>
                                <span class="help-block">Enter API URL</span>
                            </div>
                        </div>
                        <div class="form-group form-md-line-input sms-credentials @if($merchantSetting !='' && $merchantSetting->sms_status == 'disabled') sms-credentials-hide @endif">
                            <label class="control-label" for="form_control_1">Sender ID</label>
                            <div class="input-icon right">
                                <input type="text" class="form-control" placeholder="Sender ID" id="sender_id" name="sender_id"
                                       value="@if($merchantSetting !=''){{ $merchantSetting->sms_sender_id }}@endif">
                                <div class="form-control-focus"></div>
                                <span class="help-block">Enter Sender ID</span>
                            </div>
                        </div>
                        <div class="form-group form-md-line-input sms-credentials @if($merchantSetting !='' && $merchantSetting->sms_status == 'disabled') sms-credentials-hide @endif">
                            <label class="control-label" for="form_control_1">Username</label>
                            <div class="input-icon right">
                                <input type="text" class="form-control" placeholder="Username" id="username" name="username"
                                       value="@if($merchantSetting !=''){{ $merchantSetting->sms_username }}@endif">
                                <div class="form-control-focus"></div>
                                <span class="help-block">Enter Username</span>
                            </div>
                        </div>
                        <div class="form-group form-md-line-input sms-credentials @if($merchantSetting !='' && $merchantSetting->sms_status == 'disabled') sms-credentials-hide @endif">
                            <label class="control-label" for="form_control_1">Password</label>
                            <div class="input-icon right">
                                <input type="password" class="form-control col-md-8" placeholder="Password" id="password" name="password"
                                       value="@if($merchantSetting !=''){{ $merchantSetting->sms_password }}@endif">
                                <a class="btn btn-xs blue icn-only view pull-right" style="margin-top: -30px;"><i class="fa fa-eye size-icon"></i></a>
                                <div class="form-control-focus"></div>
                                <span class="help-block">Enter Password</span>
                            </div>
                        </div>
                        <div class="form-group form-md-line-input sms-credentials @if($merchantSetting !='' && $merchantSetting->sms_status == 'disabled') sms-credentials-hide @endif">
                            <label class="control-label" for="form_control_1">Campaign Id</label>
                            <div class="input-icon right">
                                <input type="text" class="form-control" placeholder="Campaign" id="campaign_id" name="campaign_id"
                                       value="@if($merchantSetting !=''){{ $merchantSetting->campaign_id }}@endif">
                                <div class="form-control-focus"></div>
                                <span class="help-block">Enter Campaign</span>
                            </div>
                        </div>
                        <div class="form-group form-md-line-input sms-credentials @if($merchantSetting !='' && $merchantSetting->sms_status == 'disabled') sms-credentials-hide @endif">
                            <label class="control-label" for="form_control_1">Route Id</label>
                            <div class="input-icon right">
                                <input type="text" class="form-control" placeholder="Route" id="route_id" name="route_id"
                                       value="@if($merchantSetting !=''){{ $merchantSetting->route_id }}@endif">
                                <div class="form-control-focus"></div>
                                <span class="help-block">Enter Route</span>
                            </div>
                        </div>
                        <div class="form-group form-md-line-input sms-credentials @if($merchantSetting !='' && $merchantSetting->sms_status == 'disabled') sms-credentials-hide @endif">
                            <label class="control-label" for="api_key">API Key</label>
                            <div class="input-icon right">
                                <input type="text" class="form-control" placeholder="API Key" id="api_key" name="api_key"
                                       value="@if($merchantSetting !=''){{ $merchantSetting->api_key }}@endif">
                                <div class="form-control-focus"></div>
                                <span class="help-block">Enter API Key</span>
                            </div>
                        </div>
                    </div>
                    <div class="form-actions">
                        <div class="row">
                            <div class="col-md-offset-3 col-md-9">
                                <a href="javascript:;" class="btn green" id="smsSettingUpdate">Submit</a>
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
@push('sms-scripts')
    <script>
        $('.view').on('click',function(){
            var p = document.getElementById('password');
            if(p.getAttribute("type") == 'password'){
                p.setAttribute('type', 'text');
            }else{
                p.setAttribute('type', 'password');
            }
        })

        $('#smsSettingUpdate').click(function () {
            $.easyAjax({
                url: "{{ route('gym-admin.setting.storeSmsCredentials') }}",
                container: '#smsCredentialForm',
                type: "POST",
                data: $('#smsCredentialForm').serialize()
            });
        });

        $('select[name=sms_status]').change(function () {
            var driver = $('select[name=sms_status]').val();
            if (driver == 'disabled') {
                $('.sms-credentials-hide').hide();
                $('.sms-credentials').hide();
            } else {
                $('.sms-credentials-hide').show();
                $('.sms-credentials').show();
            }
        });
    </script>
@endpush
