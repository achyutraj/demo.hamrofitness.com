@extends('layouts.gym-merchant.gymbasic')

@section('CSS')
    {!! HTML::style('admin/global/plugins/datatables/datatables.min.css') !!}
    {!! HTML::style('admin/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css') !!}
    {!! HTML::style('admin/global/plugins/ladda/ladda-themeless.min.css') !!}
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
                <span>ADMS Logs</span>
            </li>
        </ul>
        <!-- END PAGE BREADCRUMBS -->

        <div class="page-content-inner">
            <div class="row">
                <div class="col-md-12">
                    <div class="portlet light portlet-fit">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="icon-settings font-red"></i>
                                <span class="caption-subject font-red bold uppercase">ADMS Real-time Sync</span>
                            </div>
                            <div class="actions">
                                <div class="btn-group">
                                    <button id="syncRealtimeBtn" class="btn btn-success" {{ !$canSync ? 'disabled' : '' }}>
                                        <i class="fa fa-sync"></i> Sync Real-time Data
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="portlet-body">
                            <!-- Sync Status Card -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="alert alert-info">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <strong>Today's Sync Count:</strong>
                                                <span id="todaySyncCount">{{ $todaySyncCount }}</span>/3
                                            </div>
                                            <div class="col-md-6">
                                                <strong>Status:</strong>
                                                <span id="syncStatus" class="{{ $canSync ? 'text-success' : 'text-danger' }}">
                                                    {{ $canSync ? 'Available' : 'Limit Reached' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- ADMS Logs Table -->
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="admsLogsTable">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Device</th>
                                            <th>Status</th>
                                            <th>Records Count</th>
                                            <th>Sync Time</th>
                                            <th>Error Message</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($logs as $log)
                                            <tr>
                                                <td>{{ $log->date->format('Y-m-d') }}</td>
                                                <td>{{ $log->device->name ?? 'N/A' }}</td>
                                                <td>
                                                    @if($log->status == 'success')
                                                        <span class="label label-success">Success</span>
                                                    @elseif($log->status == 'failed')
                                                        <span class="label label-danger">Failed</span>
                                                    @else
                                                        <span class="label label-warning">Pending</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($log->adms_response && isset($log->adms_response['Data']))
                                                        {{ count($log->adms_response['Data']) }}
                                                    @else
                                                        0
                                                    @endif
                                                </td>
                                                <td>{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                                                <td>
                                                    @if($log->error_message)
                                                        <span class="text-danger">{{ Str::limit($log->error_message, 50) }}</span>
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>
                                                    <button class="btn btn-xs btn-info view-details" data-log-id="{{ $log->id }}">
                                                        <i class="fa fa-eye"></i> View
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="row">
                                <div class="col-md-12">
                                    {{ $logs->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- View Details Modal -->
    <div class="modal fade" id="viewDetailsModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">ADMS Log Details - Filtered by Branch Users</h4>
                </div>
                <div class="modal-body">
                    <!-- Tabs -->
                    <ul class="nav nav-tabs" id="logTabs" style="display: none;">
                        <li class="active">
                            <a href="#filteredData" data-toggle="tab">Found in Branch (<span id="filteredCount">0</span>)</a>
                        </li>
                        <li>
                            <a href="#notFoundData" data-toggle="tab">Not Found (<span id="notFoundCount">0</span>)</a>
                        </li>
                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content" id="logTabContent" style="display: none;">
                        <!-- Filtered Data Tab -->
                        <div class="tab-pane active" id="filteredData">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>User ID</th>
                                            <th>UserName</th>
                                            <th>Check In</th>
                                            <th>Check Out</th>
                                            <th>Total Entries</th>
                                        </tr>
                                    </thead>
                                    <tbody id="filteredDataTable">
                                        <!-- Data will be populated here -->
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Not Found Data Tab -->
                        <div class="tab-pane" id="notFoundData">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>User ID</th>
                                            <th>UserName</th>
                                            <th>Check In</th>
                                            <th>Check Out</th>
                                            <th>Total Entries</th>
                                        </tr>
                                    </thead>
                                    <tbody id="notFoundDataTable">
                                        <!-- Data will be populated here -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" id="refreshDataBtn">
                        <i class="fa fa-refresh"></i> Refresh Data
                    </button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer')
    {!! HTML::script('admin/global/plugins/datatables/datatables.min.js') !!}
    {!! HTML::script('admin/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js') !!}
    {!! HTML::script('admin/global/plugins/ladda/spin.min.js') !!}
    {!! HTML::script('admin/global/plugins/ladda/ladda.min.js') !!}

    <script>
        $(document).ready(function() {
            // Initialize DataTable
            var table = $('#admsLogsTable').DataTable({
                lengthMenu: [
                    [25, 50, 75 , 100, -1],
                    ['25', '50','75' ,'100', 'All']
                ],
                pageLength: 25,

            });

            // Sync Real-time Data
            $('#syncRealtimeBtn').click(function() {
                var btn = $(this);
                var l = Ladda.create(btn[0]);

                l.start();

                $.ajax({
                    url: '{{ route("device.adms.sync-realtime") }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        l.stop();

                        if (response.success) {
                            alert('Success: ' + response.message);
                            location.reload();
                        } else {
                            alert('Error: ' + response.message);
                        }
                    },
                    error: function(xhr) {
                        l.stop();
                        var response = xhr.responseJSON;
                        alert('Error: ' + (response.message || 'An error occurred'));
                    }
                });
            });

            var forceRefresh = false;
            // View Details - Use event delegation for dynamically loaded content
            $(document).on('click', '.view-details', function(e) {
                e.preventDefault();
                var logId = $(this).data('log-id');
                loadLogDetails(logId, false);
            });

            // Refresh Data Button
            $(document).on('click', '#refreshDataBtn', function(e) {
                e.preventDefault();
                var logId = $(this).data('log-id');
                if (logId) {
                    loadLogDetails(logId, true);
                }
            });

            function loadLogDetails(logId, forceRefresh) {
                // Show loading state
                $('.view-details[data-log-id="' + logId + '"]').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Loading...');

                $.ajax({
                    url: '{{ route("device.adms.log-details") }}',
                    type: 'GET',
                    data: {
                        log_id: logId,
                        force_refresh: forceRefresh
                    },
                    success: function(response) {
                        console.log('Response received:', response);
                        if (response.success) {
                            displayLogDetails(response);
                            $('#viewDetailsModal').modal('show');
                        } else {
                            alert('Error: ' + response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log('AJAX Error:', status, error);
                        console.log('Response text:', xhr.responseText);
                        alert('Failed to load log details: ' + error);
                    },
                    complete: function() {
                        // Reset button state
                        $('.view-details[data-log-id="' + logId + '"]').prop('disabled', false).html('<i class="fa fa-eye"></i> View');
                    }
                });
            }

            function displayLogDetails(response) {
                var log = response.log;
                var filteredData = response.filtered_data;

                // Update summary cards
                $('#totalRecords').text(filteredData.total_records || 0);
                $('#filteredRecords').text(filteredData.filtered_records || 0);
                $('#notFoundRecords').text(filteredData.not_found_records || 0);
                $('#cacheStatus').text(forceRefresh ? 'Refreshed' : 'Cached');

                // Update tab counts
                $('#filteredCount').text(filteredData.filtered_records || 0);
                $('#notFoundCount').text(filteredData.not_found_records || 0);

                // Show summary cards and tabs
                $('#logTabs').show();
                $('#logTabContent').show();

                // Populate filtered data table
                populateFilteredTable('#filteredDataTable', filteredData.filtered_data || []);

                // Populate not found data table
                populateNotFoundTable('#notFoundDataTable', filteredData.user_not_found || []);

                // Store log ID for refresh button
                $('#refreshDataBtn').data('log-id', log.id);
            }

            function populateFilteredTable(tableId, data) {
                var tbody = $(tableId);
                tbody.empty();

                if (data.length === 0) {
                    tbody.append('<tr><td colspan="6" class="text-center">No data available</td></tr>');
                    return;
                }

                data.forEach(function(record) {
                    var row = '<tr>';
                    row += '<td>' + (record.UserPin || 'N/A') + '</td>';
                    row += '<td>' + (record.UserName || 'N/A') + '</td>';
                    row += '<td>' + (record.CheckIn || 'N/A') + '</td>';
                    row += '<td>' + (record.CheckOut || 'N/A') + '</td>';
                    row += '<td>' + (record.TotalEntries || 1) + '</td>';
                    row += '</tr>';

                    tbody.append(row);
                });
            }

            function populateNotFoundTable(tableId, data) {
                var tbody = $(tableId);
                tbody.empty();

                if (data.length === 0) {
                    tbody.append('<tr><td colspan="6" class="text-center">No data available</td></tr>');
                    return;
                }

                data.forEach(function(record) {
                    var row = '<tr>';
                    row += '<td>' + (record.UserPin || 'N/A') + '</td>';
                    row += '<td>' + (record.UserName || 'N/A') + '</td>';
                    row += '<td>' + (record.CheckIn || 'N/A') + '</td>';
                    row += '<td>' + (record.CheckOut || 'N/A') + '</td>';
                    row += '<td>' + (record.TotalEntries || 1) + '</td>';
                    row += '</tr>';

                    tbody.append(row);
                });
            }

            // Auto-refresh sync status every 30 seconds
            setInterval(function() {
                $.ajax({
                    url: '{{ route("device.adms.sync-stats") }}',
                    type: 'GET',
                    success: function(response) {
                        $('#todaySyncCount').text(response.todaySyncCount);
                        $('#syncStatus').text(response.canSync ? 'Available' : 'Limit Reached');
                        $('#syncStatus').removeClass('text-success text-danger')
                                      .addClass(response.canSync ? 'text-success' : 'text-danger');

                        if (response.canSync) {
                            $('#syncRealtimeBtn').prop('disabled', false);
                        } else {
                            $('#syncRealtimeBtn').prop('disabled', true);
                        }
                    }
                });
            }, 30000);
        });
    </script>
@endsection
