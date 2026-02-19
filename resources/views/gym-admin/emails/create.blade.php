<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title">Compose Email</h4>
</div>
<div class="modal-body">
    <div class="portlet-body">
        {{ html()->form->open(['route'=>'gym-admin.emails.store','id'=>'composeMailForm','class'=>'ajax-form form-material','method'=>'POST']) !!}
        <div class="form-group">
            <label for="customer_id">Choose Member</label>
            <select class="form-control select2" name="customer_id" id="customer_id" required>
                <option selected disabled>Please Select</option>
                @if(count($customers) > 1)
                <option value="customers">All Customers</option>
                @endif
                @if(count($employees) > 1)
                <option value="employees">All Employees</option>
                @endif
                @foreach($customers as $customer)
                    <option value="customer|{{ $customer->id }}">{{ $customer->fullName }} - Customer</option>
                @endforeach
                @foreach($employees as $employee)
                    <option value="employee|{{ $employee->id }}">{{ $employee->fullName }} - Employee</option>
                @endforeach
            </select>
            <div class="form-control-focus"></div>
        </div>
        <div class="form-group">
            <input class="form-control" id="subject" type="text" name="subject" placeholder="Enter Subject ..." required>
            <div class="form-control-focus"></div>
        </div>

        <div class="form-group">
            <textarea class="form-control" id="textarea_editor" rows="10" placeholder="Enter message ..." required></textarea>
            <div class="form-control-focus"></div>
        </div>

        {{ html()->form->close() !!}
    </div>
</div>
<div class="modal-footer">
    <button type="button" data-dismiss="modal" class="btn dark btn-outline">Close</button>
    <button type="button" class="btn green" id="send-mail">Send</button>
</div>

<script>
    $(function () {
        $('#textarea_editor').wysihtml5();
    });

    $('#send-mail').on('click', function () {
        var subject = $('#subject').val();
        var message = $('#textarea_editor').val();
        var customer_id = $('#customer_id').val();
        $.easyAjax({
            type: 'POST',
            url: '{{ route('gym-admin.emails.store') }}',
            data: {
                subject: subject,
                message: message,
                recipient: customer_id
            },
            success: function (response) {
                $('#emailModal').modal("hide");
                location.reload();
            }
        });
    });
</script>
