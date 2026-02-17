<div class="modal" tabindex="-1" id="deviceEditModel{{$device->id}}" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 style="font-weight: 600;" class="modal-title">Edit Device</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('device.info.update', $device->id)}}" method="POST">
                {{csrf_field()}}
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group form-md-line-input col-md-12">
                                <label for="department">Department <span class="required" aria-required="true"> * </span></label>
                                <select class="bs-select form-control" data-live-search="true" data-size="8" id="department"
                                        name="department[]" multiple required>
                                    <option>Select Department</option>
                                    @foreach($departments as $department)
                                        <option class="todo-username pull-left"
                                            @if($device->departments->contains($department->id)) selected @endif
                                                value="{{$department->id}}">{{$department->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group form-md-line-input col-md-6">
                            <label>Device Code <span class="required" aria-required="true"> * </span></label>
                            <input type="text" class="form-control" placeholder="Enter Device Code"
                                    name="code" value="{{ $device->code}}" required>
                            <div class="form-control-focus"></div>
                            <span class="help-block">Enter Device Code</span>
                        </div>
                        <div class="form-group form-md-line-input col-md-6">
                            <label>Device Brand Name <span class="required" aria-required="true"> * </span></label>
                            <input type="text" class="form-control" placeholder="Enter Device Name"
                                    name="name" value="{{ $device->name}}" required>
                            <div class="form-control-focus"></div>
                            <span class="help-block">Enter Device Brand Name</span>
                        </div>
                        <div class="form-group form-md-line-input col-md-6">
                            <label>Ip Address <span class="required" aria-required="true"> * </span></label>
                            <input type="text" class="form-control" placeholder="Enter Ip Address"
                                    name="ip_address" value="{{ $device->ip_address}}" required>
                            <div class="form-control-focus"></div>
                            <span class="help-block">Enter Ip Address</span>
                        </div>
                        <div class="form-group form-md-line-input col-md-6">
                            <label>Serial No. <span class="required" aria-required="true"> * </span></label>
                            <input type="text" class="form-control" placeholder="Enter Serial No."
                                    name="serial_num" value="{{ $device->serial_num}}" required>
                            <div class="form-control-focus"></div>
                            <span class="help-block">Enter Serial No.</span>
                        </div>
                        <div class="form-group form-md-line-input col-md-6">
                            <label>Port No. <span class="required" aria-required="true"> * </span></label>
                            <input type="text" class="form-control" placeholder="Enter Port No."
                                    name="port_num" value="{{ $device->port_num}}" required>
                            <div class="form-control-focus"></div>
                            <span class="help-block">Enter Port No.</span>
                        </div>
                        <div class="form-group form-md-line-input col-md-6">
                            <label>Device Type</label>
                            <input type="text" class="form-control" placeholder="Enter Device Type"
                                    name="device_type" value="{{ $device->device_type}}">
                            <div class="form-control-focus"></div>
                            <span class="help-block">Enter Device Type</span>
                        </div>
                        <div class="form-group form-md-line-input col-md-6">
                            <label>Device Model</label>
                            <input type="text" class="form-control" placeholder="Enter Device Model"
                                    name="device_model" value="{{ $device->device_model}}">
                            <div class="form-control-focus"></div>
                            <span class="help-block">Enter Device Model</span>
                        </div>
                        <div class="form-group form-md-line-input col-md-6">
                            <label>Vendor Name <span class="required" aria-required="true"> * </span></label>
                            <input type="text" class="form-control" placeholder="Enter Vendor Name"
                                    name="vendor_name" value="{{ $device->vendor_name}}" required>
                            <div class="form-control-focus"></div>
                            <span class="help-block">Enter Vendor Name</span>
                        </div>
                        <div class="form-group form-md-line-input col-md-6">
                            <label>Device Status</label>
                            <select class="form-control todo-taskbody-tags" id="device_status" name="device_status">
                                <option class="todo-username pull-left"
                                    value="1" @if($device->device_status == 1) selected @endif>Active</option>
                                <option class="todo-username pull-left"
                                    value="0" @if($device->device_status == 0) selected @endif>InActive</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                         Close
                    </button>
                    <button type="submit" class="btn btn-primary">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
 </div>