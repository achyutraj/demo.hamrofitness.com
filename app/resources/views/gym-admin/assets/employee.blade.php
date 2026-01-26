<table class="table table-striped table-bordered table-hover table-100"
        id="asset-usage">
    <thead>
    <tr>
        <th class="desktop">Employ</th>
        <th class="desktop"> Assets</th>
        <th class="desktop">Working</th>
        <th class="desktop">In Repair</th>
        <th class="desktop">Damaged</th>
        <th class="desktop"> Assign Date</th>
        <th class="desktop">Action</th>
    </tr>
    </thead>
    <tbody>
    @if(count($employAssets) > 0)
    @foreach($employAssets as $empAsset)
        <tr>
            <td>{{$empAsset->employee->fullName}}</td>
            <td>{{$empAsset->assets->name}}</td>
            <td>{{$empAsset->working_quantity}}</td>
            <td>{{$empAsset->repair_quantity}}</td>
            <td>{{$empAsset->damaged_quantity}}</td>
            <td>{{date('M d, Y',strtotime($empAsset->created_at))}}</td>
            <td class="drop">
                <div class="btn-group">
                    <button class="btn blue btn-xs dropdown-toggle" type="button"
                            data-toggle="dropdown"><i class="fa fa-gears"></i> <span
                                class="hidden-xs">Action</span>
                        <i class="fa fa-angle-down"></i>
                    </button>
                    <ul class="dropdown-menu pull-right" role="menu">
                        <li>
                            <a data-toggle="modal"
                                data-target="#editAssetUsage{{$empAsset->employ_id.$empAsset->id}}"><i
                                        class="fa fa-edit"></i> Edit</a>
                        </li>
                        <li>
                            <a data-employee-url="{{route('gym-admin.assetManagement.deleteAssetUsage',$empAsset->id)}}"
                                class="employ-remove"><i class="fa fa-trash"></i> Delete</a>
                        </li>
                    </ul>
                </div>
            </td>
            <div class="modal fade bs-modal-md in"
                    id="editAssetUsage{{$empAsset->employ_id.$empAsset->id}}" role="dialog"
                    aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-md" id="modal-data-application">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4>Edit Assets Usage </h4>
                            <button type="button" class="close" data-dismiss="modal"
                                    aria-hidden="true"></button>
                            <span class="caption-subject font-red-sunglo bold uppercase"
                                    id="modelHeading"></span>
                        </div>
                        <form action="{{route('gym-admin.assetManagement.editAssetUsage',$empAsset->id)}}"
                                method="post" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="modal-body">
                                <div class="row">
                                    <div class="form-group form-md-line-input col-md-4">
                                        <label><h4>Quantity</h4></label>
                                        <input type="number" value="{{$empAsset->quantity}}" min="0"
                                                class="form-control" placeholder="Asset Quantity"
                                                name="quantity_working" required>
                                        <div class="form-control-focus"></div>
                                        <span class="help-block">Enter Asset Quantity</span>
                                    </div>
                                    <div class="form-group form-md-line-input col-md-4">
                                        <label><h4>Repair Quantity</h4></label>
                                        <input type="number" value="{{$empAsset->repair_quantity}}" min="0"
                                                class="form-control" placeholder="Asset Repairing Quantity"
                                                name="quantity_repairing" required>
                                        <div class="form-control-focus"></div>
                                        <span class="help-block">Enter Asset Quantity</span>
                                    </div>
                                    <div class="form-group form-md-line-input col-md-4">
                                        <label><h4>Damaged Quantity</h4></label>
                                        <input type="number" value="{{$empAsset->damaged_quantity}}" min="0"
                                                class="form-control" placeholder="Asset Damaged Quantity"
                                                name="quantity_damaged" required>
                                        <div class="form-control-focus"></div>
                                        <span class="help-block">Enter Asset Quantity</span>
                                    </div>
                                </div>
                                <div class="form-group form-md-line-input col-md-12">
                                    <label><h4>Remarks</h4></label>
                                    <input type="text" value="{{$empAsset->working_remarks}}"
                                            class="form-control" placeholder="Remarks" name="working_remarks"
                                            required>
                                    <div class="form-control-focus"></div>
                                    <span class="help-block">Remarks</span>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn green">Assign</button>
                                <button type="button" class="btn default" data-dismiss="modal">Close</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </tr>
    @endforeach
    @else
        <td colspan="9" class="text-center">No data available in table</td>
    @endif
    </tbody>
</table>