@extends('gym-admin.sms.index')

@push('show-styles')
    {!! HTML::style('admin/global/plugins/ladda/ladda-themeless.min.css') !!}
    {!! HTML::style('admin/global/plugins/datatables/datatables.min.css') !!}
    {!! HTML::style('admin/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css') !!}
    {!! HTML::style('fitsigma/css/merchant-chat.css') !!}
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

@section('sms')
    <div class="inbox-header">
        <h3 class="pull-left">All SMS Sent to {{ucfirst($title)}}</h3>
    </div>
    <div class="inbox-content">
        <table class="table table-striped table-bordered table-checkable order-column" style="width: 100%"
            id="{{ $title }}">
            <thead>
                <tr>
                    <th><input type="checkbox" id="selectAll" /></th>
                    <th>Date</th>
                    <th>{{ucfirst($title)}}</th>
                    <th>Mobile</th>
                    <th>Message</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <!-- Data will be loaded via AJAX -->
            </tbody>
        </table>
    </div>
@endsection

@push('show-scripts')
    {!! HTML::script('admin/global/scripts/datatable.js') !!}
    {!! HTML::script('admin/pages/scripts/table-datatables-managed.js') !!}
    {!! HTML::script('admin/global/plugins/datatables/datatables.min.js') !!}
    {!! HTML::script('admin/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js') !!}
    {!! HTML::script('admin/global/plugins/ladda/spin.min.js') !!}
    {!! HTML::script('admin/global/plugins/ladda/ladda.min.js') !!}
    {!! HTML::script('admin/pages/scripts/ui-buttons.min.js') !!}
    <script>
        $(document).ready(function () {
            var tableId = '{{ $title }}';
            var table = $('#' + tableId);
            var allDeleteUrl = '';
            var deleteUrl = '';
            var ajaxUrl = '';

            if(tableId == 'admin') {
                var allDeleteUrl = "{{route('gym-admin.admin_sms.massremove')}}" ;
                var deleteUrl = "{{ route('gym-admin.sms.destroy-admin-sms', ':id') }}";
                var ajaxUrl = "{{ route('gym-admin.sms.ajax-admin-sms') }}";

            }else if(tableId == 'employee'){
                var allDeleteUrl = "{{ route('gym-admin.employee_sms.massremove')  }}";
                var deleteUrl = "{{ route('gym-admin.sms.destroy-employee-sms', ':id') }}";
                var ajaxUrl = "{{ route('gym-admin.sms.ajax-employee-sms') }}";

            }else{
                var allDeleteUrl = "{{ route('gym-admin.customer_sms.massremove') }}";
                var deleteUrl = "{{ route('gym-admin.sms.destroy-customer-sms', ':id') }}";
                var ajaxUrl = "{{ route('gym-admin.sms.ajax-customer-sms') }}";
            }

            // Initialize DataTable with Yajra DataTables
            var dataTable = table.DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: ajaxUrl,
                    type: 'POST',
                    data: function(d) {
                        d._token = '{{ csrf_token() }}';
                    }
                },
                columns: [
                    {
                        data: 'id',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return '<input type="checkbox" name="customer_checkbox[]" value="' + data + '" class="customer_checkbox">';
                        }
                    },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'gym_clients.first_name', name: 'gym_clients.first_name' },
                    { data: 'phone', name: 'phone' },
                    { data: 'message', name: 'message' },
                    { data: 'status', name: 'status' },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ],
                responsive: true,
                dom: "Blfrtip",
                buttons: [
                    {
                        text: 'Delete Selected',
                        className: 'red',
                        action: function (e, dt, node, config) {
                            var ids = $('.customer_checkbox:checked').map(function () {
                                return $(this).val();
                            }).get();

                            if (ids.length === 0) {
                                alert("Please select at least one checkbox");
                                return;
                            }

                            if (confirm("Are you sure you want to delete this data?")) {
                                $.ajax({
                                    url: allDeleteUrl,
                                    type: "DELETE",
                                    data: { id: ids, _token: '{{ csrf_token() }}' },
                                    success: function () {
                                        dataTable.ajax.reload();
                                    }
                                });
                            }
                        }
                    }
                ],
                lengthMenu: [
                    [25, 50, 75, 100, -1],
                    ['25', '50', '75', '100', 'All']
                ],
                pageLength: 25,
                order: [[1, 'desc']], // Sort by date descending by default (latest first)
                stateSave: false,
                stateDuration: 0
            });

            // Handle remove message action
            table.on('click', '.remove-message', function () {
                var id = $(this).data('id');
                var url = deleteUrl.replace(':id', id);

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
                            $.easyAjax({
                                url: url,
                                type: "DELETE",
                                data: { id: id, _token: '{{ csrf_token() }}' },
                                success: function () {
                                    dataTable.ajax.reload();
                                }
                            });
                        }
                    }
                });
            });

            // Handle resend message action
            table.on('click', '.resend-message', function () {
                var id = $(this).data('id');
                var type = $(this).data('type');

                $.easyAjax({
                    type: 'POST',
                    url: "{{ route('gym-admin.sms.resend') }}",
                    data: { id: id, type: type },
                    success: function (response) {
                        // Handle success response if needed
                    }
                });
            });

            // Handle select all checkbox
            $('#selectAll').on('change', function() {
                $('.customer_checkbox').prop('checked', this.checked);
            });
        });
    </script>
@endpush
