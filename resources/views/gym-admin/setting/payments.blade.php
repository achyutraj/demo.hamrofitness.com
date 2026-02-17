@extends('gym-admin.setting.master-setting')
@push('sms-styles')
    {!! HTML::style('admin/global/plugins/bootstrap-summernote/summernote.css') !!}
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
                    <li class="active">
                        <a href="javascript:;"> Payment Gateway </a>
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
                    {!! Form::open(['route'=>'gym-admin.setting.storePayments','id'=>'paymentCredentialForm','class'=>'ajax-form form-horizontal','method'=>'POST']) !!}
                    <div class="form-body col-md-6 col-md-offset-1">
                        <div class="form-group form-md-line-input">
                            <label class="control-label" for="form_control_1">Payment Status</label>
                            <select class="form-control" name="payment_status">
                                <option @if($merchantSetting !='' && $merchantSetting->payment_status == 'disabled') selected @endif value="disabled">
                                    Disabled
                                </option>
                                <option @if($merchantSetting !='' && $merchantSetting->payment_status == 'enabled') selected @endif value="enabled">
                                    Enabled
                                </option>
                            </select>
                            <div class="form-control-focus"></div>
                        </div>
                        <div
                            class="form-group form-md-line-input payment-credentials @if($merchantSetting !='' && $merchantSetting->payment_status == 'disabled') payment-credentials-hide @endif">
                            <label class="control-label" for="form_control_1">Esewa Merchant ID</label>
                            <div class="input-icon right">
                                <input type="text" class="form-control" placeholder="Esewa Merchant ID" id="esewa_merchant_id" name="esewa_merchant_id"
                                       value="@if($merchantSetting !=''){{ $merchantSetting->esewa_merchant_id }}@endif">
                                <div class="form-control-focus"></div>
                                <span class="help-block">Enter Esewa Merchant ID</span>
                            </div>
                        </div>
                        <div
                            class="form-group form-md-line-input payment-credentials @if($merchantSetting !='' && $merchantSetting->payment_status == 'disabled') payment-credentials-hide @endif">
                            <label class="control-label" for="form_control_1">Khalti Public Key</label>
                            <div class="input-icon right">
                                <input type="text" class="form-control" placeholder="Khalti Public Key" id="khalti_public_key" name="khalti_public_key"
                                       value="@if($merchantSetting !=''){{ $merchantSetting->khalti_public_key }}@endif">
                                <div class="form-control-focus"></div>
                                <span class="help-block">Enter Khalti Public Key</span>
                            </div>
                        </div>
                        <div
                            class="form-group form-md-line-input payment-credentials @if($merchantSetting !='' && $merchantSetting->payment_status == 'disabled') payment-credentials-hide @endif">
                            <label class="control-label" for="form_control_1">Khalti Secret Key</label>
                            <div class="input-icon right">
                                <input type="text" class="form-control" placeholder="Khalti Secret Key" id="khalti_secret_key" name="khalti_secret_key"
                                       value="@if($merchantSetting !=''){{ $merchantSetting->khalti_secret_key }}@endif">
                                <div class="form-control-focus"></div>
                                <span class="help-block">Enter Khalti Secret Key</span>
                            </div>
                        </div>
                    </div>
                    <div class="form-body col-md-10 col-md-offset-1">
                        <div class="form-group form-md-line-input">
                            <label class="control-label" for="form_control_1">Offline Payment</label>
                            <div class="form-group form-md-line-input">
                                <textarea name="offline_text" id="offline_text" cols="30" rows="10">
                                    @if($merchantSetting !='') {{ $merchantSetting->offline_text }}@endif
                                </textarea>
                            </div>
                        </div>
                    </div>
                    <div class="form-actions">
                        <div class="row">
                            <div class="col-md-offset-3 col-md-9">
                                <a href="javascript:;" class="btn green" id="paymentUpdate">Submit</a>
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
    {!! HTML::script('admin/global/plugins/bootstrap-summernote/summernote.min.js') !!}

    <script>
        $('#offline_text').summernote();
        $('#paymentUpdate').click(function () {
            $.easyAjax({
                url: "{{ route('gym-admin.setting.storePayments') }}",
                container: '#paymentCredentialForm',
                type: "POST",
                data: {
                    'payment_status': $('select[name=payment_status]').val(),
                    'esewa_merchant_id': $('#esewa_merchant_id').val(),
                    'khalti_public_key': $('#khalti_public_key').val(),
                    'khalti_secret_key': $('#khalti_secret_key').val(),
                    'offline_text': $('#offline_text').code(),
                }
            });
        });

        $('select[name=payment_status]').change(function () {
            var driver = $('select[name=payment_status]').val();
            if (driver == 'disabled') {
                $('.payment-credentials-hide').hide();
                $('.payment-credentials').hide();
            } else {
                $('.payment-credentials-hide').show();
                $('.payment-credentials').show();
            }
        });
    </script>
@endpush
