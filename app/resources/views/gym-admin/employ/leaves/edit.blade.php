<div class="modal fade bs-modal-lg in" id="editLeave{{ $leave->id }}{{ $loop->index }}" role="dialog"
    aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" id="modal-data-application">
        <div class="modal-content">
            <form action="{{ route('gym-admin.employ.editLeave', $leave->id) }}" method="post"
                enctype="multipart/form-data">
                {{ csrf_field() }}
                <input type="hidden" name="employ_id" value="{{ $leave->employ_id }}">
                @if (isset($leave->is_legacy) && $leave->is_legacy)
                    <input type="hidden" name="index" value="{{ $leave->legacy_index }}">
                @endif
                <div class="modal-header">
                    <h4>Edit Leave
                        For {{ $leave->first_name . ' ' . $leave->middle_name . ' ' . $leave->last_name }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <span class="caption-subject font-red-sunglo bold uppercase" id="modelHeading"></span>
                </div>
                <div class="modal-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Leave Type</th>
                                <th>Leave Days Taken</th>
                                <th>Leave From</th>
                                <th>Leave To</th>
                            </tr>
                        </thead>
                        <tbody>
                            <td>
                                <input type="text" class="form-control" placeholder="Leave Type" readonly
                                    value="{{ $leave->leaveType }}" name="leaveType"><br>
                            </td>
                            <td>
                                <input type="number" class="form-control" placeholder="Days"
                                    value="{{ $leave->days }}" name="days"><br>
                            </td>
                            <td>
                                <input name="startDate" data-date-format="yyyy-mm-dd" class="form-control date"
                                    value="{{ $leave->startDate }}" type="text"><br>
                            </td>
                            <td>
                                <input name="endDate" data-date-format="yyyy-mm-dd" class="form-control date"
                                    value="{{ $leave->endDate }}" type="text"><br>
                            </td>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Update
                    </button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
