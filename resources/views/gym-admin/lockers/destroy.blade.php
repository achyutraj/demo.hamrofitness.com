<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <span class="caption-subject font-red-sunglo bold uppercase">Remove Locker</span>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <p> Are you Sure you want to Remove {{ $locker->locker_num }} ?</p>
        </div>
    </div>
</div>
</div>
<div class="modal-footer">
    <button type="button" data-dismiss="modal" class="btn">Cancel</button>
    <a href="javascript:;" data-locker-id="{{ $locker->uuid }}" class="btn blue" id="remove" >Remove</a>
</div>
