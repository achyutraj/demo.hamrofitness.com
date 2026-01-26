<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <span class="caption-subject font-red-sunglo bold uppercase"><i class="fa fa-list"></i> Renew History</span>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label class="control-label sbold">Branch Name:</label>
                <p class="form-control-static"> {{ ucwords($branch->title) }} </p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label class="control-label sbold">Branch Admin:</label>
                <p class="form-control-static"> {{ ucwords($branch->owner_incharge_name) }} </p>
            </div>
        </div>
        <!--/span-->
        <div class="col-md-4">
            <div class="form-group">
                <label class="control-label sbold">Contact Number:</label>
                <p class="form-control-static"> {{ $branch->phone }} </p>
            </div>
        </div>
        <!--/span-->
    </div>

    <div class="row">
        <div class="col-md-12 ">
            <table class="table table-striped table-bordered table-hover row-border">
                <thead>
                    <tr>
                        <th>Created Date</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Package Offered</th>
                        <th>Package Amount</th>
                        <th>Remark</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($branch->histories as $follow)
                    <tr>
                        <td>{{ $follow->created_at->toFormattedDateString() }}</td>
                        <td>{{ $follow->renew_start_date->toFormattedDateString() }}</td>
                        <td>{{ $follow->renew_end_date->toFormattedDateString() }}</td>
                        <td>{{ $follow->package_offered}} Month</td>
                        <td>{{ $follow->package_amount}}</td>
                        <td>{{ $follow->remark}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="modal-footer">
        <a href="javascript:;" class="btn blue"  data-dismiss="modal" aria-hidden="true" >OK</a>
    </div>
</div>
