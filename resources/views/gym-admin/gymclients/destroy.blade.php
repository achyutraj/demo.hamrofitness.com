<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <span class="caption-subject font-red-sunglo bold uppercase">Remove Client</span>
</div>
<div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <p> Are you Sure you want to Remove {{$client->first_name}} {{$client->middle_name}} {{$client->last_name}} ?</p>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <a href="{{route('gym-admin.client.destroy',$client->id)}}" class="btn blue">Remove</a>
    <button type="button" data-dismiss="modal" class="btn">Cancel</button>
</div>
