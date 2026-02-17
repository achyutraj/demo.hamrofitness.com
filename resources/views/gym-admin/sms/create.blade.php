@extends('layouts.gym-merchant.gymbasic')

@section('CSS')
    {!! HTML::style('admin/global/plugins/bootstrap-select/css/bootstrap-select.min.css') !!}
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
                <a href="javascript:;">SMS</a>
            </li>
            <li>
                <span>Add</span>
            </li>
        </ul>
        <!-- END PAGE BREADCRUMBS -->
        <!-- BEGIN PAGE CONTENT INNER -->
        <div class="page-content-inner">
            <div class="row">
                <div class="col-md-10 col-xs-12">
                    <div class="portlet light portlet-fit">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="icon-plus font-red"></i><span
                                    class="caption-subject font-red bold uppercase">Compose</span>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <!-- BEGIN FORM-->
                            {!! Form::open(['id' => 'storePayments', 'class' => 'ajax-form', 'method' => 'POST']) !!}

                            <div class="form-body">
                                <div class="row">
                                    <div class="form-group form-md-line-input">
                                        <select class="bs-select form-control" data-live-search="true" data-size="8"
                                            name="recipient" id="recipient" required>
                                            <option value="">Select Recepients</option>
                                            @if (count($customers) > 1)
                                                <option value="customers">All Customers</option>
                                            @endif
                                            @if (count($active_customers) > 1)
                                                <option value="active_customers">All Active Customers</option>
                                            @endif
                                            @if (count($inactive_customers) > 1)
                                                <option value="inactive_customers">All Inactive Customers</option>
                                            @endif
                                            @if (count($employees) > 1)
                                                <option value="employees">All Employees</option>
                                            @endif
                                            @if ($user->is_admin == 1)
                                                <option value="admins">All Branch Manager</option>
                                                @foreach ($admins as $admin)
                                                    <option value="admin|{{ $admin->id }}">{{ $admin->fullName }} -
                                                        Merchant</option>
                                                @endforeach
                                            @endif

                                            @foreach ($customers as $customer)
                                                <option value="customer|{{ $customer->id }}">{{ $customer->fullName }} -
                                                    Customer</option>
                                            @endforeach
                                            @foreach ($employees as $employee)
                                                <option value="employee|{{ $employee->id }}">{{ $employee->fullName }} -
                                                    Employee</option>
                                            @endforeach
                                        </select>
                                        <label for="title">Recipients
                                            <span class="required" aria-required="true"> *</span>
                                        </label>
                                        <span class="help-block" id="recipient-error"></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group form-md-line-input ">
                                        <textarea name="message" class="form-control" id="message" cols="30" rows="6" placeholder="Enter your message here..." required></textarea>
                                        <div class="sms-counter-info">
                                            <span class="" id="remaining">0 characters remaining</span>
                                            <span class="" id="messages">0 Message(s)</span>
                                            <span class="" id="message_type">Unknown Type</span>
                                        </div>
                                        <label for="form_control_1">Message <span class="required" aria-required="true"> *</span></label>
                                        <div class="form-control-focus"></div>
                                        <span class="help-block" id="message-error"></span>
                                    </div>
                                    <input type="hidden" id="total_messages" name="total_messages" value="0" />
                                </div>
                            </div>

                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="button" class="btn dark mt-ladda-btn ladda-button"
                                            data-style="zoom-in" id="save-form">
                                            <span class="ladda-label"><i class="fa fa-save"></i> Send</span>
                                        </button>
                                        <a type="button" class="btn default"
                                            href="{{ route('gym-admin.sms.index') }}">Close</a>
                                    </div>
                                </div>
                            </div>
                            {!! Form::close() !!}
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
    {!! HTML::script('admin/global/plugins/bootstrap-select/js/bootstrap-select.min.js') !!}
    {!! HTML::script('admin/pages/scripts/components-bootstrap-select.min.js') !!}
     {!! HTML::script('admin/global/scripts/sms-counter.js') !!}

        <script type="text/javascript">

        (function(){
	window.SMSCounter = SMSCounter = (function(){
		function SMSCounter() {}
        SMSCounter.NOTRLOCK = true;
        SMSCounter.gsm7bitChars = "@£$¥èéùìòÇ\\nØø\\rÅåΔ_ΦΓΛΩΠΨΣΘΞÆæßÉ !\\\"#¤%&'()*+,-./0123456789:;<=>?¡ABCDEFGHIJKLMNOPQRSTUVWXYZÄÖÑÜ§¿abcdefghijklmnopqrstuvwxyzäöñüà";
        SMSCounter.gsm7bitExChar = "\\^{}\\\\\\[~\\]|€";
        SMSCounter.gsm7bitExTRChars = "ŞşĞğçıİ";
        SMSCounter.gsm7bitExTRArr = SMSCounter.gsm7bitExTRChars.split("");
        SMSCounter.gsm7bitExTRENChars = "SsGgciI";
        SMSCounter.gsm7bitExTRENArr = SMSCounter.gsm7bitExTRENChars.split("");
        SMSCounter.gsm7bitRegExp = RegExp("^[" + SMSCounter.gsm7bitChars + "]*$");
        SMSCounter.gsm7bitExRegExp = RegExp("^[" + SMSCounter.gsm7bitChars + SMSCounter.gsm7bitExChar + "]*$");
        SMSCounter.gsm7bitExTRExp = RegExp("^[" + SMSCounter.gsm7bitChars + SMSCounter.gsm7bitExChar + SMSCounter.gsm7bitExTRChars + "]*$");
        SMSCounter.gsm7bitExOnlyRegExp = RegExp("^[\\" + SMSCounter.gsm7bitExChar + "]*$");
        SMSCounter.GSM_7BIT = 'GSM_7BIT';
        SMSCounter.GSM_7BIT_EX = 'GSM_7BIT_EX';
        SMSCounter.GSM_7BIT_EX_TR = 'GSM_7BIT_EX_TR';
        SMSCounter.UTF16 = 'UTF16';
        SMSCounter.messageLength = {
            GSM_7BIT: 160,
            GSM_7BIT_EX: 160,
            GSM_7BIT_EX_TR: 155,
            UTF16: 70
        };
        SMSCounter.multiMessageLength = {
            GSM_7BIT: 153,
            GSM_7BIT_EX: 153,
            GSM_7BIT_EX_TR: 149,
            UTF16: 67
        };
        SMSCounter.TR_enabled = false;
        SMSCounter.TR_ascii = function(text) {
            var res = text;
            for (var j = 0; j < text.length; j++) {
                for (var i = 0; i < this.gsm7bitExTRArr.length; i++) {
                    res = res.replace(this.gsm7bitExTRArr[i], this.gsm7bitExTRENArr[i]);
                }
            }
            return res;
        };
        SMSCounter.countGsm7bitExTR = function(text) {
            var cnt = 0;
            for (var j = 0; j < text.length; j++) {
                if (this.gsm7bitExTRArr.indexOf(text[j]) > -1) {
                    cnt++;
                }
            }
            return cnt;
        };
        SMSCounter.count = function(text, TR_enabled,multipart) {
            var count, encoding, length, part_count, per_message, remaining;

            if (typeof multipart == "undefined") {
                this.multipart = true
            }

            if (typeof TR_enabled == "undefined") {
                this.TR_enabled = false
            } else {
                this.TR_enabled = TR_enabled;
            }
            if (!this.TR_enabled) {
                text = this.TR_ascii(text);
            }
            encoding = this.detectEncoding(text);
            length = text.length;
            if (encoding === this.GSM_7BIT_EX || encoding === this.GSM_7BIT_EX_TR) {
                length += this.countGsm7bitEx(text);
            }
            if (this.TR_enabled && self.SMSCounter.NOTRLOCK) {
                length += SMSCounter.countGsm7bitExTR(text);
            }
            per_message = this.messageLength[encoding];

            if (this.multipart) {
                if (length > per_message) {
                    per_message = this.multiMessageLength[encoding];
                }
            }
            part_count = this.multipart ?  Math.ceil(length / per_message) : 1;
            remaining = length > 0 ? (per_message * part_count) - length : '&nbsp;';
            return {
                encoding: encoding,
                length: length,
                per_message: per_message,
                remaining: remaining,
                part_count: part_count,
                text: text
            };
        };
        SMSCounter.detectEncoding = function(text) {
            switch (false) {
                case text.match(this.gsm7bitRegExp) == null:
                    return this.GSM_7BIT;
                case text.match(this.gsm7bitExRegExp) == null:
                    return this.GSM_7BIT_EX;
                case (text.match(this.gsm7bitExTRExp) == null) || (this.TR_enabled === false):
                    return this.GSM_7BIT_EX_TR;
                default:
                    return this.UTF16;
            }
        };
        SMSCounter.countGsm7bitEx = function(text) {
            var char2, chars;
            var that = this;
            chars = (function() {
                var _i, _len, _results;
                _results = [];
                for (_i = 0, _len = text.length; _i < _len; _i++) {
                    char2 = text[_i];
                    if (char2.match(that.gsm7bitExOnlyRegExp) != null) {
                        _results.push(char2);
                    }
                }
                return _results;
            }).call(this);
            return chars.length;
        };
        return SMSCounter;
	})();
    if (typeof jQuery !== "undefined" && jQuery !== null) {
        $ = jQuery;
        $.fn.countSms = function(target) {
            var count_sms, input;
            input = this;
            target = $(target);
            count_sms = function() {
                var count, k, v, _results;
                count = SMSCounter.count(input.val());
                _results = [];
                for (k in count) {
                    v = count[k];
                    _results.push(target.find("." + k).text(v));
                }
                return _results;
            };
            this.on('keyup', count_sms);
            return count_sms();
        };
    }
}).call(this);

          $(function(){
            $('#message').on("change keyup paste", function(){
              var data = SMSCounter.count($(this).val(), true);
              var length = data["length"];
              var remaining = $.isNumeric(data["remaining"]) ? data["remaining"] : 0;
              var part_count = data["part_count"];
              var text = data["text"];
              var per_message = data["per_message"];
              var encoding = data['encoding'];
              var sms_type = "";
              if (encoding == "GSM_7BIT") {
                sms_type = "Normal";
              }else if (encoding == "GSM_7BIT_EX") {
                sms_type = "Extended"; // for 7 bit GSM: ^ { } \ [ ] ~ | €
              } else if (encoding == "GSM_7BIT_EX_TR") {
                sms_type = "Turkish"; // Only for Turkish Characters "Ş ş Ğ ğ ç ı İ" encoding see https://en.wikipedia.org/wiki/GSM_03.38#Turkish_language_.28Latin_script.29
              } else if (encoding == "UTF16") {
                sms_type = "Unicode"; // for other languages "Arabic, Chinese, Russian" see http://en.wikipedia.org/wiki/GSM_03.38#UCS-2_Encoding
              }

            //   console.log(length);
            //   console.log(remaining);
            //   console.log(part_count);
            //   console.log(per_message);
            //   console.log(encoding);
            //   console.log(sms_type);
            $("#remaining").text(remaining+' character remaining');
            $("#messages").text(part_count + ' message(s)');
            $("#total_messages").val(part_count);
            $("#message_type").text(sms_type + ' type');
            });
          })
        </script>

    <script>
        $(document).ready(function(){
            // let get_msg = $('#message'),
            //     remaining = $('#remaining'),
            //     messages = $('#messages'),
            //     merge_state = $('#tags');

            // // Initialize SMS counter with enhanced functionality
            // get_msg.smsCounter({
            //     remainingElement: '#remaining',
            //     messagesElement: '#messages',
            //     onUpdate: function(count) {
            //         // Visual feedback when multiple SMS messages are needed
            //         if (count.messages > 1) {
            //             messages.addClass('text-warning');
            //         } else {
            //             messages.removeClass('text-warning');
            //         }
            //     }
            // });

            // // Handle tag insertion if tags dropdown exists
            // if (merge_state.length > 0) {
            //     merge_state.on('change', function(){
            //         const ch_pos = get_msg[0].selectionStart;
            //         const textAreaVal = get_msg.val();
            //         let addTag = this.value;
            //         if(addTag){
            //             addTag = '{'+addTag+'}';
            //         }
            //         get_msg.val(textAreaVal.substring(0,ch_pos) + addTag + textAreaVal.substring(ch_pos));
            //         // Trigger counter update after tag insertion
            //         get_msg.trigger('input');
            //     });
            // }

            // // Enhanced character counting function
            // function get_character(){
            //     if(get_msg[0].value !== null){
            //         let data = SmsCounter.count(get_msg[0].value, true);
            //         remaining.text(data.remaining + " characters remaining");
            //         messages.text(data.messages + " Message(s)");

            //         // Visual feedback
            //         if (data.messages > 1) {
            //             messages.addClass('text-warning');
            //         } else {
            //             messages.removeClass('text-warning');
            //         }
            //     }
            // }

            // // Bind events for real-time counting
            // get_msg.on('input keyup paste', get_character);

            // // Real-time validation
            // $('#message').on('input', function() {
            //     var message = $(this).val().trim();
            //     if (message) {
            //         $('#message-error').text('');
            //         $(this).closest('.form-group').removeClass('has-error');
            //     }
            // });

            $('#recipient').on('change', function() {
                var recipient = $(this).val();
                if (recipient) {
                    $('#recipient-error').text('');
                    $(this).closest('.form-group').removeClass('has-error');
                }
            });
        });

        $('#save-form').click(function(event) {
            event.preventDefault();

            // Clear previous error messages
            $('.help-block').text('');
            $('.form-group').removeClass('has-error');

            var message = $('#message').val().trim();
            var recipient = $('#recipient').val();
            var isValid = true;

            // Validate message
            if (!message) {
                $('#message-error').text('Please enter a message');
                $('#message').closest('.form-group').addClass('has-error');
                isValid = false;
            }

            // Validate recipient
            if (!recipient) {
                $('#recipient-error').text('Please select recipients');
                $('#recipient').closest('.form-group').addClass('has-error');
                isValid = false;
            }

            if (!isValid) {
                return;
            }

            $.easyAjax({
                url: "{{ route('gym-admin.sms.store') }}",
                container: '#storePayments',
                type: "POST",
                data: $('#storePayments').serialize(),
                formReset: true,
                success: function(responce) {
                    if (responce.status == 'success') {
                        $('#recipient').val('');
                        $('#message').val('');
                        $('#recipient').selectpicker('refresh');
                        // Reset counter display
                        $('#remaining').text('160 characters remaining');
                        $('#messages').text('1 Message(s)').removeClass('text-warning');
                        // Clear error states
                        $('.help-block').text('');
                        $('.form-group').removeClass('has-error');
                    }
                }
            });
        });
    </script>

    <style>
        .sms-counter-info {
            margin-top: 5px;
            font-size: 12px;
        }
        .sms-counter-info span {
            margin-right: 15px;
        }
        .text-warning {
            color: #f39c12 !important;
            font-weight: bold;
        }
        .has-error .form-control {
            border-color: #e73d4a !important;
        }
        .has-error .help-block {
            color: #e73d4a !important;
        }
        .help-block {
            margin-top: 5px;
            font-size: 12px;
        }
    </style>
@stop
