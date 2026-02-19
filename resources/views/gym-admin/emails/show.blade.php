@extends('gym-admin.emails.index')

@push('show-styles')
    <link rel="stylesheet" href="{{ asset("admin/global/plugins/ladda/ladda-themeless.min.css") }}">
    <link rel="stylesheet" href="{{ asset("admin/global/plugins/datatables/datatables.min.css") }}">
    <link rel="stylesheet" href="{{ asset("admin/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css") }}">
    <link rel="stylesheet" href="{{ asset("fitsigma/css/merchant-chat.css") }}">
    <style>
        .inbox .inbox-content {
            min-height: auto;
        }
        .dt-button.red {
            color: #fff;
            background-color: #e12330;
            border-color: #dc1e2b;
            margin-left: 10px;
        }
    </style>
@endpush

@section('email')
    <div class="inbox-header">
        <h3 class="pull-left">All Emails Sent to {{$title}}</h3>
    </div>
    <div class="inbox-content" style="">
        <table class="table table-striped table-bordered table-checkable order-column" style="width: 100%"
               id="{{ ($title == 'Customers') ? 'gym_clients' : 'gym_employees' }}">
        <thead>
        <tr>
            <th><input type="checkbox" id="selectAll" /></th>
            <th>{{$title}}</th>
            <th>Message</th>
            <th>Subject</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
            @if($title == 'Customers')
                @foreach($customer_emails as $customer_email)
                    <tr>
                        <td><input type="checkbox" name="customer_checkbox[]" value="{{$customer_email->id}}" class="customer_checkbox"> </td>
                        <td>{{$customer_email->first_name .' '.$customer_email->middle_name .' '.$customer_email->last_name}}</td>
                        <td>{{$customer_email->message}}</td>
                        <td>{{$customer_email->subject}}</td>
                        <td>{{($customer_email->status == 1) ? 'sent' : 'error'}}</td>
                        <td><div class="btn-group">
                                <button class="btn blue btn-xs dropdown-toggle" type="button" data-toggle="dropdown"><i class="fa fa-gears"></i> <span class="hidden-xs">Actions</span>
                                    <i class="fa fa-angle-down"></i>
                                </button>
                                <ul class="dropdown-menu pull-right" role="menu">
                                    <li>
                                        <a href="javascript:;" data-id="{{$customer_email->id }}" class="remove-message"> <i class="fa fa-trash"></i>Remove </a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                @endforeach
                @elseif($title == 'Employees')
                @foreach($employee_emails as $employee_email)
                    <tr>
                        <td><input type="checkbox" name="customer_checkbox[]" value="{{$employee_email->id}}" class="customer_checkbox"> </td>
                        <td>{{$employee_email->first_name .' '.$employee_email->middle_name .' '.$employee_email->last_name}}</td>
                        <td>{{$employee_email->message}}</td>
                        <td>{{$employee_email->subject}}</td>
                        <td>{{($employee_email->status == 1) ? 'sent' : 'error'}}</td>
                        <td><div class="btn-group">
                                <button class="btn blue btn-xs dropdown-toggle" type="button" data-toggle="dropdown"><i class="fa fa-gears"></i> <span class="hidden-xs">Actions</span>
                                    <i class="fa fa-angle-down"></i>
                                </button>
                                <ul class="dropdown-menu pull-right" role="menu">
                                    <li>
                                        <a href="javascript:;" data-id="{{$employee_email->id }}" class="remove-message"> <i class="fa fa-trash"></i>Remove </a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
        </table>
    </div>
@endsection

@push('show-scripts')
    <script src="{{ asset("admin/global/scripts/datatable.js") }}"></script>
    <script src="{{ asset("admin/pages/scripts/table-datatables-managed.js") }}"></script>
    <script src="{{ asset("admin/global/plugins/datatables/datatables.min.js") }}"></script>
    <script src="{{ asset("admin/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js") }}"></script>
    <script src="{{ asset("admin/global/plugins/ladda/spin.min.js") }}"></script>
    <script src="{{ asset("admin/global/plugins/ladda/ladda.min.js") }}"></script>
    <script src="{{ asset("admin/pages/scripts/ui-buttons.min.js") }}"></script>
    <script>
        $("#selectAll").click(function(){
            $("input[type=checkbox]").prop('checked', $(this).prop('checked'));

        });
        function load_dataTable() {
            @if($title == 'Customers')
                var table = $('#gym_clients');
            @else
                var table = $('#gym_employees');
            @endif
            // begin first table
            table.DataTable({
                "responsive": true,

                // Internationalisation. For more info refer to http://datatables.net/manual/i18n
                "language": {
                    "aria": {
                        "sortAscending": ": activate to sort column ascending",
                        "sortDescending": ": activate to sort column descending"
                    },
                    "emptyTable": "No data available in table",
                    "info": "Showing _START_ to _END_ of _TOTAL_ records",
                    // "infoEmpty": "No records found",
                    "infoFiltered": "(filtered1 from _MAX_ total records)",
                    "lengthMenu": "Show _MENU_",
                    "search": "Search:",
                    "processing": "<i class='fa fa-spinner faa-spin animated'></i> Processing",
                    "zeroRecords": "No matching records found",
                    "paginate": {
                        "previous": "Prev",
                        "next": "Next",
                        "last": "Last",
                        "first": "First"
                    }
                },
                // "dom": "<'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r>t<'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
                "dom": "Bfrtip",

                "bStateSave": true, // save datatable state(pagination, sort, etc) in cookie.
                buttons: [
                    {
                        text: 'Deleted Selected',
                        className: 'red',
                        action: function ( e, dt, node, config ) {
                            var id = [];
                            $('.customer_checkbox:checked').each(function(){
                                id.push($(this).val());
                            });
                            if(id.length == 0)
                            {
                                alert("Please select at least one checkbox");
                            }
                            if(id.length > 0)
                            {
                                if(confirm("Are you sure you want to Delete this data?"))
                                {
                                    @if($title == 'Customers')
                                        var url = '{{ route('gym-admin.customer_email.massremove')}}';
                                        @else
                                        var url = '{{ route('gym-admin.employee_email.massremove')}}';
                                    @endif
                                    $.ajax({
                                        url: url,
                                        type: "DELETE",
                                        data: {id: id, _token: '{{ csrf_token() }}'},
                                        success: function (data) {
                                            window.location.reload();
                                        }
                                    });
                                }
                            }
                        }
                    }
                ],
                "lengthMenu": [
                    [5, 15, 20, -1],
                    [5, 15, 20, "All"] // change per page values here
                ],
                // set the initial value
                "pageLength": 5,
                "pagingType": "bootstrap_full_number",
                "bJQueryUI" : true,
            });
        }

    </script>
    <script>
        $(document).ready(function () {
            load_dataTable();
        });
    </script>
    <script>
                @if($title == 'Customers')
        var table = $('#gym_clients');
                @else
        var table = $('#gym_employees');
        @endif
        table.on('click', '.remove-message', function () {
            var id = $(this).data('id');
            bootbox.confirm({
                message: "Do you want to delete this message?",
                buttons: {
                    confirm: {
                        label: "Yes",
                        className: "btn-primary"
                    }
                },
                callback: function (result) {
                    if (result) {
                                @if($title == 'Customers')
                        var url = '{{route('gym-admin.emails.destroy-customer-email',':id')}}';
                                @else
                        var url = '{{route('gym-admin.emails.destroy-employee-email',':id')}}';
                        @endif
                            url = url.replace(':id', id);

                        $.easyAjax({
                            url: url,
                            type: "DELETE",
                            data: {id: id, _token: '{{ csrf_token() }}'},
                            success: function () {
                                window.location.reload();
                            }
                        });
                    }
                    else {
                        console.log('cancel');
                    }
                }
            })
        });
    </script>
@endpush
