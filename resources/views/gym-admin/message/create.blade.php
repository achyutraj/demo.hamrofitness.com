<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title">Compose Message</h4>
</div>
<div class="modal-body">
    <div class="portlet-body">
        {!! Form::open(['route'=>'gym-admin.message.store','id'=>'composeMailForm','class'=>'ajax-form form-material','method'=>'POST']) !!}
        <div class="form-group">
            <label class="col-sm-12">Choose Member</label>
            <div class="col-sm-12">
                <select class="form-control select2" name="user_id" id="user_id">
                    <option selected disabled>Select Member</option>
                    @foreach($customers as $customer)
                        <option value="customer|{{ $customer->id }}">{{ $customer->fullName }} - Customer</option>
                    @endforeach
                    @foreach($employees as $employee)
                        <option value="employee|{{ $employee->id }}">{{ $employee->fullName }} - Employee</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group">
            <textarea class="textarea_editor form-control" rows="16" placeholder="Enter text ..."></textarea>
        </div>
        {!! Form::close() !!}
    </div>
</div>
<div class="modal-footer">
    <button type="button" data-dismiss="modal" class="btn dark btn-outline">Close</button>
    <button type="button" class="btn green" id="send-mail">Send</button>
</div>

<script>
    $(function() {
        $('.textarea_editor').wysihtml5();
    });

    $('#send-mail').on('click', function () {
        var text = $('.textarea_editor').val();
        var user_id = $('#user_id').val();
        $.easyAjax({
            type: 'POST',
            url: "{{ route('gym-admin.message.store') }}",
            data: {
                text: text,
                user_id: user_id
            },
            success: function (response) {
                $('#mailModal').modal("hide");
            }
        });
    });
</script>
