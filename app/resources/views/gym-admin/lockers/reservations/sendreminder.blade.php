<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <span class="caption-subject font-red bold uppercase"><i class="font-red fa fa-send"></i> Send Expire locker reminder to {{$client_data->first_name}}</span>
</div>
<div class="modal-body tabbable-line">
    <div class="portlet-body">
        <form action="#" class="form-horizontal">
            <div class="form-body">
                <div class="form-group">
                    <div class="col-md-12">
                        <label class="control-label">Locker Expire: </label>

                        <p class="form-control-static">
                            {{ $client_data->locker_number }}
                        </p>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-12">
                        <label class="control-label">Remainder Type: </label>

                        <p class="form-control-static">
                            <label for="sms_reminder" class="@if($smsSetting[0]->sms_status == 'disabled') disabled @endif">
                                <input type="checkbox" id="sms_reminder" name="sms_reminder" @if($smsSetting[0]->sms_status == 'disabled') disabled @endif>
                                Sms
                            </label>
                        </p>
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
                <button type="button" class="btn green send-reminder">Submit</button>
                <button type="button" class="btn default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>

    if (!$('#email_reminder').is(':checked') || !$('#sms_reminder').is(':checked')) {
        $('.send-reminder').addClass('disabled');
    }

    $('#email_reminder').change(function () {
        check();
    });

    $('#sms_reminder').change(function () {
        check();
    });

    function check() {
        if ($('#email_reminder').is(":checked") || $('#sms_reminder').is(":checked")) {
            $('.send-reminder').removeClass('disabled');
        } else {
            $('.send-reminder').addClass('disabled');
        }
    }

    $('.send-reminder').click(function () {
        if($('#sms_reminder').is(':checked')){
            var smsReminder = 1;
        }else{
            var smsReminder = 0;
        }

        if($('#email_reminder').is(':checked')){
            var emailReminder = 1;
        }else{
            var emailReminder = 0;
        }

        $.easyAjax({
            container: '#reminderModal',
            url: '{{ route("gym-admin.reservations.sendReminder") }}',
            type: "POST",
            data: {
                mobile: '{{$client_data->mobile}}',
                smsReminder: smsReminder,
                locker: '{{$client_data->locker_number}}',
                '_token': '{{ csrf_token() }}',
                reservationId: '{{ $client_data->reservationId }}'
            },
            success: function (response) {
                if (response.status == 'fail') {
                    $(".help-block").css('margin-left', '170px');
                }
                else {
                    $("#reminderModal").modal("hide");
                }

            }
        });
    });

</script>
