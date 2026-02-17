<div class="form-group form-md-line-input ">
    <select  class="form-control" name="purchase_id" id="purchase_id">
        <option value="">Select Purchase</option>
        @forelse($purchases as $purc)
            <option value="{{$purc->id}}" data-price="{{ $purc->diff }}">{{ ucwords($purc->membership->title) }} [{{ $purc->membership->duration }} {{ $purc->membership->duration_type }}] - [Purchased on: {{$purc->purchase_date->format('d-M')}}]</option>
        @empty
            <option value="">No purchase by this client</option>
        @endforelse
    </select>
    <label for="title">Payment For</label>
    <span class="help-block"></span>
</div>
