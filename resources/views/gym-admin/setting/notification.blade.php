@extends('gym-admin.setting.master-setting')

@push('notification-styles')
    <link rel="stylesheet" href="{{ asset("admin/global/plugins/ladda/ladda-themeless.min.css") }}">
    <link rel="stylesheet" href="{{ asset("admin/global/plugins/bootstrap-select/css/bootstrap-select.min.css") }}">
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
                    <li class="active">
                        <a href="javascript:;"> SMS Notifications </a>
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
                    {{ html()->form()->open(['id'=>'notificationStore','class'=>'ajax-form form-horizontal','method'=>'POST']) }}
                    <div class="form-body">
                        <table class="table table-striped table-responsive">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Status</th>
                                    <th>SMS</th>
                                    <th>Time Interval</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>New Customer Registration Notification</td>
                                    <td>
                                        <div class="md-radio">
                                            <input type="radio" name="customer_register_status" id="radio-status-1" value="1" @if(!is_null($options) && $options['customer_register_status'] == 1) checked @endif class="md-check">
                                            <label for="radio-status-1">
                                                <span></span>
                                                <span class="check"></span>
                                                <span class="box"></span> Active </label>
                                        </div>
                                        <div class="md-radio">
                                            <input type="radio" name="customer_register_status" id="radio-status-2" value="0" @if(!is_null($options) && $options['customer_register_status'] == 0) checked @endif class="md-check">
                                            <label for="radio-status-2">
                                                <span></span>
                                                <span class="check"></span>
                                                <span class="box"></span> InActive </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="md-radio">
                                            <input type="radio" name="customer_register_notify" id="radio-1" value="sms" @if(!is_null($options) && $options['customer_register_notify'] == 'sms') checked @endif class="md-check">
                                            <label for="radio-1">
                                                <span></span>
                                                <span class="check"></span>
                                                <span class="box"></span> SMS </label>
                                        </div>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>Customer Payment Notification</td>
                                    <td>
                                        <div class="md-radio">
                                            <input type="radio" name="customer_payment_status" id="radio-status-3" value="1" @if(!is_null($options) && $options['customer_payment_status'] == 1) checked @endif class="md-check">
                                            <label for="radio-status-3">
                                                <span></span>
                                                <span class="check"></span>
                                                <span class="box"></span> Active </label>
                                        </div>
                                        <div class="md-radio">
                                            <input type="radio" name="customer_payment_status" id="radio-status-4" value="0" @if(!is_null($options) && $options['customer_payment_status'] == 0) checked @endif class="md-check">
                                            <label for="radio-status-4">
                                                <span></span>
                                                <span class="check"></span>
                                                <span class="box"></span> InActive </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="md-radio">
                                            <input type="radio" name="customer_payment_notify" id="radio-4" value="sms"  @if(!is_null($options) && $options['customer_payment_notify'] == 'sms') checked @endif class="md-check">
                                            <label for="radio-4">
                                                <span></span>
                                                <span class="check"></span>
                                                <span class="box"></span> SMS </label>
                                        </div>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>Membership Reminder Notification</td>
                                    <td>
                                        <div class="md-radio">
                                            <input type="radio" name="membership_due_status" id="radio-status-5" value="1" @if(!is_null($options) && $options['membership_due_status'] == 1) checked @endif class="md-check">
                                            <label for="radio-status-5">
                                                <span></span>
                                                <span class="check"></span>
                                                <span class="box"></span> Active </label>
                                        </div>
                                        <div class="md-radio">
                                            <input type="radio" name="membership_due_status" id="radio-status-6" value="0" @if(!is_null($options) && $options['membership_due_status'] == 0) checked @endif class="md-check">
                                            <label for="radio-status-6">
                                                <span></span>
                                                <span class="check"></span>
                                                <span class="box"></span> InActive </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="md-radio">
                                            <input type="radio" name="membership_due_notify" id="radio-7" value="sms" @if(!is_null($options) && $options['membership_due_notify'] == 'sms') checked @endif class="md-check">
                                            <label for="radio-7">
                                                <span></span>
                                                <span class="check"></span>
                                                <span class="box"></span> SMS </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="number" min="1" class="form-control" placeholder="Notice Before Days" name="membership_due_notify_days"
                                                       value="{{ $options['membership_due_notify_days'] ?? 1 }}">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Customer Due Payment Notification</td>
                                    <td>
                                        <div class="md-radio">
                                            <input type="radio" name="membership_due_payment_status" id="radio-status-7" value="1" @if(!is_null($options) && $options['membership_due_payment_status'] == 1) checked @endif class="md-check">
                                            <label for="radio-status-7">
                                                <span></span>
                                                <span class="check"></span>
                                                <span class="box"></span> Active </label>
                                        </div>
                                        <div class="md-radio">
                                            <input type="radio" name="membership_due_payment_status" id="radio-status-8" value="0" @if(!is_null($options) && $options['membership_due_payment_status'] == 0) checked @endif class="md-check">
                                            <label for="radio-status-8">
                                                <span></span>
                                                <span class="check"></span>
                                                <span class="box"></span> InActive </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="md-radio">
                                            <input type="radio" name="membership_due_pay_notify" id="radio-10" value="sms" @if(!is_null($options) && $options['membership_due_pay_notify'] == 'sms') checked @endif class="md-check">
                                            <label for="radio-10">
                                                <span></span>
                                                <span class="check"></span>
                                                <span class="box"></span> SMS </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="number" min="1" class="form-control" placeholder="Notice Before Days" name="membership_due_pay_notify_days"
                                                   value="{{ $options['membership_due_pay_notify_days'] ?? 1 }}">
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td>Membership Expired Notification</td>
                                    <td>
                                        <div class="md-radio">
                                            <input type="radio" name="membership_expire_status" id="radio-status-9" value="1" @if(!is_null($options) && $options['membership_expire_status'] == 1) checked @endif class="md-check">
                                            <label for="radio-status-9">
                                                <span></span>
                                                <span class="check"></span>
                                                <span class="box"></span> Active </label>
                                        </div>
                                        <div class="md-radio">
                                            <input type="radio" name="membership_expire_status" id="radio-status-10" value="0" @if(!is_null($options) && $options['membership_expire_status'] == 0) checked @endif class="md-check">
                                            <label for="radio-status-10">
                                                <span></span>
                                                <span class="check"></span>
                                                <span class="box"></span> InActive </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="md-radio">
                                            <input type="radio" name="membership_expire_notify" id="radio-13" value="sms" @if(!is_null($options) && $options['membership_expire_notify'] == 'sms') checked @endif class="md-check">
                                            <label for="radio-13">
                                                <span></span>
                                                <span class="check"></span>
                                                <span class="box"></span> SMS </label>
                                        </div>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>Membership Renew Notification</td>
                                    <td>
                                        <div class="md-radio">
                                            <input type="radio" name="membership_renew_status" id="radio-status-11" value="1" @if(!is_null($options) && $options['membership_renew_status'] == 1) checked @endif class="md-check">
                                            <label for="radio-status-11">
                                                <span></span>
                                                <span class="check"></span>
                                                <span class="box"></span> Active </label>
                                        </div>
                                        <div class="md-radio">
                                            <input type="radio" name="membership_renew_status" id="radio-status-12" value="0" @if(!is_null($options) && $options['membership_renew_status'] == 0) checked @endif class="md-check">
                                            <label for="radio-status-12">
                                                <span></span>
                                                <span class="check"></span>
                                                <span class="box"></span> InActive </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="md-radio">
                                            <input type="radio" name="membership_renew_notify" id="radio-16" value="sms" @if(!is_null($options) && $options['membership_renew_notify'] == 'sms') checked @endif class="md-check">
                                            <label for="radio-16">
                                                <span></span>
                                                <span class="check"></span>
                                                <span class="box"></span> SMS </label>
                                        </div>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>Membership Extend Notification</td>
                                    <td>
                                        <div class="md-radio">
                                            <input type="radio" name="membership_extend_status" id="radio-status-13" value="1" @if(!is_null($options) && $options['membership_extend_status'] == 1) checked @endif class="md-check">
                                            <label for="radio-status-13">
                                                <span></span>
                                                <span class="check"></span>
                                                <span class="box"></span> Active </label>
                                        </div>
                                        <div class="md-radio">
                                            <input type="radio" name="membership_extend_status" id="radio-status-14" value="0" @if(!is_null($options) && $options['membership_extend_status'] == 0) checked @endif class="md-check">
                                            <label for="radio-status-14">
                                                <span></span>
                                                <span class="check"></span>
                                                <span class="box"></span> InActive </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="md-radio">
                                            <input type="radio" name="membership_extend_notify" id="radio-19" value="sms" @if(!is_null($options) && $options['membership_extend_notify'] == 'sms') checked @endif class="md-check">
                                            <label for="radio-19">
                                                <span></span>
                                                <span class="check"></span>
                                                <span class="box"></span> SMS </label>
                                        </div>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>Send Customer Birthday Notification</td>
                                    <td>
                                        <div class="md-radio">
                                            <input type="radio" name="customer_birth_status" id="radio-status-15" value="1" @if(!is_null($options) && $options['customer_birth_status'] == 1) checked @endif class="md-check">
                                            <label for="radio-status-15">
                                                <span></span>
                                                <span class="check"></span>
                                                <span class="box"></span> Active </label>
                                        </div>
                                        <div class="md-radio">
                                            <input type="radio" name="customer_birth_status" id="radio-status-16" value="0" @if(!is_null($options) && $options['customer_birth_status'] == 0) checked @endif class="md-check">
                                            <label for="radio-status-16">
                                                <span></span>
                                                <span class="check"></span>
                                                <span class="box"></span> InActive </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="md-radio">
                                            <input type="radio" name="customer_birthday_notify" id="radio-22" value="sms" @if(!is_null($options) && $options['customer_birthday_notify'] == 'sms') checked @endif class="md-check">
                                            <label for="radio-22">
                                                <span></span>
                                                <span class="check"></span>
                                                <span class="box"></span> SMS </label>
                                        </div>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>Send Customer Anniversary Notification</td>
                                    <td>
                                        <div class="md-radio">
                                            <input type="radio" name="customer_anniversary_status" id="radio-status-17" value="1" @if(!is_null($options) && $options['customer_anniversary_status'] == 1) checked @endif class="md-check">
                                            <label for="radio-status-17">
                                                <span></span>
                                                <span class="check"></span>
                                                <span class="box"></span> Active </label>
                                        </div>
                                        <div class="md-radio">
                                            <input type="radio" name="customer_anniversary_status" id="radio-status-18" value="0" @if(!is_null($options) && $options['customer_anniversary_status'] == 0) checked @endif class="md-check">
                                            <label for="radio-status-18">
                                                <span></span>
                                                <span class="check"></span>
                                                <span class="box"></span> InActive </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="md-radio">
                                            <input type="radio" name="customer_anniversary_notify" id="radio-25" value="sms" @if(!is_null($options) && $options['customer_anniversary_notify'] == 'sms') checked @endif class="md-check">
                                            <label for="radio-25">
                                                <span></span>
                                                <span class="check"></span>
                                                <span class="box"></span> SMS </label>
                                        </div>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>Locker Expired Notification</td>
                                    <td>
                                        <div class="md-radio">
                                            <input type="radio" name="locker_expire_status" id="radio-status-19" value="1" @if(!is_null($options) && isset($options['locker_expire_status']) && $options['locker_expire_status'] == 1) checked @endif class="md-check">
                                            <label for="radio-status-19">
                                                <span></span>
                                                <span class="check"></span>
                                                <span class="box"></span> Active </label>
                                        </div>
                                        <div class="md-radio">
                                            <input type="radio" name="locker_expire_status" id="radio-status-20" value="0" @if(!is_null($options) && isset($options['locker_expire_status']) && $options['locker_expire_status'] == 0) checked @endif class="md-check">
                                            <label for="radio-status-20">
                                                <span></span>
                                                <span class="check"></span>
                                                <span class="box"></span> InActive </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="md-radio">
                                            <input type="radio" name="locker_expire_notify" id="radio-28" value="sms" @if(!is_null($options) && isset($options['locker_expire_notify']) && $options['locker_expire_notify'] == 'sms') checked @endif class="md-check">
                                            <label for="radio-28">
                                                <span></span>
                                                <span class="check"></span>
                                                <span class="box"></span> SMS </label>
                                        </div>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>Locker Reminder Notification</td>
                                    <td>
                                        <div class="md-radio">
                                            <input type="radio" name="locker_due_status" id="radio-status-21" value="1" @if(!is_null($options) && isset($options['locker_due_status']) && $options['locker_due_status'] == 1) checked @endif class="md-check">
                                            <label for="radio-status-21">
                                                <span></span>
                                                <span class="check"></span>
                                                <span class="box"></span> Active </label>
                                        </div>
                                        <div class="md-radio">
                                            <input type="radio" name="locker_due_status" id="radio-status-22" value="0" @if(!is_null($options) && isset($options['locker_due_status']) && $options['locker_due_status'] == 0) checked @endif class="md-check">
                                            <label for="radio-status-22">
                                                <span></span>
                                                <span class="check"></span>
                                                <span class="box"></span> InActive </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="md-radio">
                                            <input type="radio" name="locker_due_notify" id="radio-31" value="sms" @if(!is_null($options) && isset($options['locker_due_notify']) && $options['locker_due_notify'] == 'sms') checked @endif class="md-check">
                                            <label for="radio-31">
                                                <span></span>
                                                <span class="check"></span>
                                                <span class="box"></span> SMS </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="number" min="1" class="form-control" placeholder="Notice Before Days" name="locker_due_notify_days"
                                                       value="{{ $options['locker_due_notify_days'] ?? 1 }}">
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="form-actions">
                        <div class="row">
                            <div class="col-md-offset-3 col-md-9">
                                <a href="javascript:;" class="btn green" id="store">Submit</a>
                                <a href="javascript:;" class="btn default">Cancel</a>
                            </div>
                        </div>
                    </div>
                    {{ html()->form()->close() }}
                </div>
            </div>
        </div>
    </div>
@stop
@push('notification-scripts')
  <script src="{{ asset("admin/global/plugins/bootstrap-select/js/bootstrap-select.min.js") }}"></script>
  <script src="{{ asset("admin/pages/scripts/components-bootstrap-select.min.js") }}"></script>
  @include('gym-admin.setting.script')
  <script>
      $('#store').click(function () {
          $.easyAjax({
              url: "{{ route('gym-admin.setting.storeNotification') }}",
              container: '#notificationStore',
              type: "POST",
              data: $('#notificationStore').serialize()
          });
      });
  </script>
@endpush
