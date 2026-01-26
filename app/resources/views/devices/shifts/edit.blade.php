<div class="modal" tabindex="-1" id="branchEditModel{{$shift->id}}" role="dialog">
   <div class="modal-dialog" role="document">
       <div class="modal-content">
           <div class="modal-header">
               <h4 style="font-weight: 600;" class="modal-title">Edit Device Shift</h4>
               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                   <span aria-hidden="true">&times;</span>
               </button>
           </div>
           <form action="{{route('device.shifts.update', $shift->id)}}" method="POST">
               {{csrf_field()}}
               <div class="modal-body">
                   <div class="form-group col-md-4">
                       <label for="name"><h5>Name *</h5></label>
                       <input type="text" value="{{$shift->name}}" class="form-control" name="name"
                              placeholder=" Name"  required>
                   </div>
                   <div class="form-group col-md-4">
                        <label for="from_time"><h5>From Time *</h5></label>
                        <input type="time" class="form-control" name="from_time" placeholder="Enter From Time" required
                            value="{{$shift->from_time}}" required>
                        @if ($errors->has('from_time'))
                            <span class="invalid-feedback danger" role="alert">
                                <strong>{{ $errors->first('from_time') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group col-md-4">
                        <label for="to_time"><h5>To Time *</h5></label>
                        <input type="time" class="form-control" name="to_time" placeholder="Enter To Time" required
                            value="{{$shift->to_time }}" required>
                        @if ($errors->has('to_time'))
                            <span class="invalid-feedback danger" role="alert">
                                <strong>{{ $errors->first('to_time') }}</strong>
                            </span>
                        @endif
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