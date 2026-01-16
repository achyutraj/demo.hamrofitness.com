<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <span class="caption-subject font-red bold uppercase"><i class="font-red fa fa-money"></i> Payment Reminder to {{$client_data->first_name}}</span>
</div>
<div class="modal-body tabbable-line">
    <div class="portlet-body">
        <form action="#" class="form-horizontal">
            <div class="form-body">
                <div class="form-group">
                    <label class="control-label col-md-3">Payment Due</label>
                    <div class="col-md-9">
                        <input type="number" class="form-control" id="payment" name="payment"
                               value="{{$client_data->amount_to_be_paid - $client_data->paid_amount }}">
                        <span class="help-block"> Enter Payment in Rupees, eg: 1000</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3">Remainder Type</label>
                    <div class="col-md-9">
                        <label class="checkbox-inline @if($smsSetting[0]->sms_status == 'disabled') disabled @endif">
                            <input type="checkbox" id="sms-checkbox" value="sms" @if($smsSetting[0]->sms_status == 'disabled') disabled @endif> SMS
                        </label>
                        @if($smsSetting[0]->sms_status == 'disabled')
                            <span class="help-block"> SMS is disabled, <a href="{{url('gym-admin/setting/sms')}}">Goto</a> settings to enable </span>
                        @endif
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<hr>
<div class="modal-footer">
    <div class="form-actions">
        <div class="row">
            <div class="col-md-offset-3 col-md-9">
                <button id="send-button" type="button"
                        class="btn green send-reminder @if($smsSetting[0]->sms_status == 'disabled' && $emailSetting[0]->email_status == 'disabled') disabled @endif">
                    Submit
                </button>
                <button type="button" class="btn default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>

    if (!$('#email-checkbox').is(':checked') || !$('#sms-checkbox').is(':checked')) {
        $('#send-button').addClass('disabled');
    }

    $('#email-checkbox').change(function () {
        check();
    });

    $('#sms-checkbox').change(function () {
        check();
    });

    function check() {
        email = $('#email-checkbox').is(":checked");
        sms = $('#sms-checkbox').is(":checked");
        if (email || sms) {
            $('#send-button').removeClass('disabled');
        } else {
            $('#send-button').addClass('disabled');
        }
    }

    $('.send-reminder').click(function () {
        var payment = $("#payment").val();
        $.easyAjax({
            container: '#reminderModal',
            url: '{{ route("gym-admin.client-purchase.sendReminder") }}',
            type: "POST",
            data: {
                email: '{{$client_data->email}}',
                mobile: '{{$client_data->mobile}}',
                sendEmail: $('#email-checkbox').is(":checked"),
                sendSms: $('#sms-checkbox').is(":checked"),
                payment: payment,
                offer: '{{$client_data->offer}}',
                membership: '{{$client_data->membership}}',
                '_token': '{{ csrf_token() }}',
                purchaseId: '{{ $id }}'
            },
            success: function (response) {
                if (response.status == 'fail') {
                    $(".help-block").css('margin-left', '170px');
                } else {
                    $("#reminderModal").modal("hide");
                }

            }
        });
    });

</script>

