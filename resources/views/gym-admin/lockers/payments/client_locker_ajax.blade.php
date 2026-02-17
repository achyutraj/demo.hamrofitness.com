<div class="form-group form-md-line-input ">
    <select  class="form-control" name="reservation_id" id="reservation_id">
        <option value="">Select Reservation</option>
        @forelse($purchases as $purc)
            <option value="{{$purc->id}}" data-price="{{ $purc->diff }}">{{ ucwords($purc->locker->locker_num) }} - [Purchased on: {{$purc->purchase_date->format('d-M')}}]</option>
        @empty
            <option value="">No purchase by this client</option>
        @endforelse
    </select>
    <label for="title">Payment For</label>
    <span class="help-block"></span>
</div>
