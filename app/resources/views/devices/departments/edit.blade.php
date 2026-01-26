<div class="modal" tabindex="-1" id="departmentEditModel{{$department->id}}" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 style="font-weight: 600;" class="modal-title">Edit Device department</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('device.departments.update', $department->id)}}" method="POST">
                {{csrf_field()}}
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name"><h5>Department Name *</h5></label>
                        <input type="text" value="{{$department->name}}" class="form-control" name="name"
                               placeholder="Department Name"  required>
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