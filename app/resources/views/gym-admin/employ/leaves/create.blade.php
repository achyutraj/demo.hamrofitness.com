<div class="modal fade bs-modal-md in" id="addLeave" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" id="modal-data-application">
        <div class="modal-content">
            <div class="modal-header">
                <h4>Create Leave</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <span class="caption-subject font-red-sunglo bold uppercase" id="modelHeading"></span>
            </div>
            <form action="{{ route('gym-admin.employ.createLeave') }}" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                <input type="hidden" name="branch_id" value="{{ $user->id }}">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group form-md-line-input">
                                <label for="employName">
                                    <h4>Select Employ</h4>
                                </label>
                                <select onchange="getEmployId()" id="employName" class="bs-select" class="form-control"
                                    name="employ_id" style="width:100%;min-height:25px !important;" required>
                                    <option value="">Select Employ</option>
                                    @foreach ($employees as $employ)
                                        <option value="{{ $employ->id }}">
                                            {{ $employ->first_name . ' ' . $employ->middle_name . ' ' . $employ->last_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group form-md-line-input">
                                <label for="leaveType">
                                    <h4>Select Leave</h4>
                                </label>
                                <select class="bs-select" class="form-control" id="leaveType" name="leaveType"
                                    style="width:100%;min-height:25px !important;" required>
                                    <option value="">Select Leave Type</option>
                                    @foreach ($leaveType as $type)
                                        <option value="{{ $type->name }}">
                                            {{ $type->name . ' ' . $type->days . ' ' . 'days' }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Leave Days Taken</th>
                                <th>Leave From</th>
                                <th>Leave To</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><input type="number" min="0" class="form-control" placeholder="Leave Taken" name="days">
                                </td>
                                <td><input name="startDate" type="text" class="form-control date"
                                        data-date-format="yyyy-mm-dd" placeholder="Leave Starts from"></td>
                                <td><input name="endDate" type="text" class="form-control date"
                                        data-date-format="yyyy-mm-dd" placeholder="Leave Starts To"></td>
                            </tr>
                            <div id="hiddenData"></div>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Create</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
