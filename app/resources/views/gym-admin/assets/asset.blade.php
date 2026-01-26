<table class="table table-striped table-bordered table-hover order-column"
        id="manage-branches">
    <thead>
    <tr>
        <th class="desktop">Asset Tag</th>
        <th class="desktop"> Asset Name</th>
        <th class="desktop"> Brand Name</th>
        <th class="desktop"> Supplier</th>
        <th class="desktop"> Model</th>
        <th class="desktop" style="width: 20%;"> Quantity</th>
        <th class="desktop"> Action</th>
    </tr>
    </thead>

    <tbody>
    @foreach($assets as $asset)
        <tr style="margin-bottom: 20px;">
            <td>{{$asset->tag}}</td>
            <td>{{$asset->name}}</td>
            <td>{{$asset->brand_name}}</td>
            <td>{{$asset->suppliers->name ?? ''}}</td>
            <td>{{$asset->asset_model}}</td>
            <td>
                <i class="fa fa-circle" style="color: #368496;"></i> {{$asset->quantity}} in Total<br>
                <i class="fa fa-circle"
                    style="color: #0069D9;"></i> {{$asset->quantity - ($asset->quantity_working + $asset->quantity_repair + $asset->quantity_damaged)}}
                In Stock<br>
                <i class="fa fa-circle" style="color: #68C217;"></i> {{$asset->quantity_working}} Working<br>
                <i class="fa fa-circle" style="color: #FCCD3E;"></i> {{$asset->quantity_repair}} Repairing <br>
                <i class="fa fa-circle" style="color: #DF3D35;"></i> {{$asset->quantity_damaged}} Damaged<br>
            </td>
            <td class="drop">
                <div class="btn-group">
                    <button class="btn blue btn-xs dropdown-toggle" type="button"
                            data-toggle="dropdown"><i class="fa fa-gears"></i> <span
                                class="hidden-xs">Action</span>
                        <i class="fa fa-angle-down"></i>
                    </button>
                    <ul class="dropdown-menu pull-right" role="menu">
                        @if($asset->quantity !== $asset->quantity_working)
                        <li>
                            <a data-toggle="modal" data-target="#assetAssignUser{{$asset->id}}">
                                <i class="fa fa-user"></i> Assign to</a>
                        </li>
                        @endif
                        <li>
                            <a data-toggle="modal" data-target="#assetService{{$asset->id}}">
                                <i class="fa fa-gear"></i> Services</a>
                        </li>
                        <li>
                            <a data-toggle="modal" data-target="#editAsset{{$asset->id}}">
                                <i class="fa fa-edit"></i> Edit</a>
                        </li>
                        <li>
                            <a data-asset-url="{{route('gym-admin.assetManagement.delete',$asset->id)}}" class="asset-remove">
                                <i class="fa fa-trash"></i> Delete
                            </a>
                        </li>
                    </ul>
                </div>
                @if($asset->quantity !== $asset->quantity_working)
                {{-- assign asset user modal starts--}}
                <div class="modal fade bs-modal-md in" id="assetAssignUser{{$asset->id}}" role="dialog"
                        aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-md" id="modal-data-application">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h2 style="text-transform: capitalize;">Assign {{$asset->name}} to Employ</h2>
                                <button type="button" class="close" data-dismiss="modal"
                                        aria-hidden="true"></button>
                                <span class="caption-subject font-red-sunglo bold uppercase"
                                        id="modelHeading"></span>
                            </div>
                            <form action="{{route('gym-admin.assetManagement.assignUser')}}" method="post">
                                {{ csrf_field() }}
                                <input type="hidden" name="asset_id" value="{{ $asset->id}}">
                                <div class="modal-body">
                                    <div class="status-show" style="margin-top: -20px;">
                                        <h3>Asset Available ({{$asset->quantity}})</h3>
                                        <h4>
                                            <span style="margin-right: 20px;"><i class="fa fa-circle"
                                                                                    style="color: #0069D9;"></i> Stock {{$asset->quantity - ($asset->quantity_working + $asset->quantity_repair + $asset->quantity_damaged)}}</span>
                                            <span style="margin-left: 20px;margin-right: 20px;"><i
                                                        class="fa fa-circle" style="color: #68C217;"></i> Working {{$asset->quantity_working}}</span>
                                            <span style="margin-left: 20px;margin-right: 20px;"><i
                                                        class="fa fa-circle" style="color: #FCCD3E;"></i> Repairing {{$asset->quantity_repair}}</span>
                                            <span style="margin-left: 20px;margin-right: 20px;"><i
                                                        class="fa fa-circle" style="color: #DF3D35;"></i> Damaged {{$asset->quantity_damaged}}</span>
                                        </h4>
                                    </div>
                                    <div class="form-group form-md-line-input col-md-6">
                                        <label for="employName"><h4>Select Employ *</h4></label>
                                        <select class="form-control todo-taskbody-tags" id="employName"
                                                name="employ_id" required>
                                            <option>Select Employ</option>
                                            @foreach($employs as $employ)
                                                <option class="todo-username pull-left"
                                                        value="{{$employ->id}}">{{$employ->fullName}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group form-md-line-input col-md-6">
                                        <label><h4>Quantity *</h4></label>
                                        <input type="number" min="0" class="form-control" placeholder="Asset Quantity"
                                                name="quantity_working" required>
                                        <div class="form-control-focus"></div>
                                        <span class="help-block">Enter Asset Quantity</span>
                                    </div>
                                    <div class="form-group form-md-line-input col-md-12">
                                        <label><h4>Remarks</h4></label>
                                        <input type="text" class="form-control" placeholder="Remarks"
                                                name="working_remarks">
                                        <div class="form-control-focus"></div>
                                        <span class="help-block">Remarks</span>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn blue">Assign</button>
                                    <button class="btn default" data-dismiss="modal">Close
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                {{-- assign asset user modal ends--}}
                @endif

                {{-- asset service modal starts--}}
                <div class="modal fade bs-modal-md in" id="assetService{{$asset->id}}" role="dialog"
                        aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-md" id="modal-data-application">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h2 style="text-transform: capitalize;">Add Servicing of {{$asset->name}} </h2>
                                <button type="button" class="close" data-dismiss="modal"
                                        aria-hidden="true"></button>
                                <span class="caption-subject font-red-sunglo bold uppercase"
                                        id="modelHeading"></span>
                            </div>
                            <form action="{{ route('gym-admin.assetManagement.assetServicesStore')}}" method="post">
                                {{ csrf_field() }}
                                <input type="hidden" name="asset_id" value="{{ $asset->id}}">

                                <div class="modal-body">
                                    <div class="form-group form-md-line-input col-md-4">
                                        <label><h4>Service By *</h4></label>
                                        <input type="text" class="form-control" placeholder="Add Maintainer Name "
                                                name="service_by" required>
                                        <div class="form-control-focus"></div>
                                        <span class="help-block">Enter Service By</span>
                                    </div>
                                    <div class="form-group form-md-line-input col-md-4">
                                        <label><h4>Service Date *</h4></label>
                                        <input type="text" class="form-control date-picker" placeholder="Service Date"
                                            data-provide="datepicker" data-date-autoclose="true" data-date-today-highlight="true"
                                            name="service_date" required>
                                        <div class="form-control-focus"></div>
                                        <span class="help-block">Enter Service Date</span>
                                    </div>
                                    <div class="form-group form-md-line-input col-md-4">
                                        <label><h4>Next Service Date</h4></label>
                                        <input type="text" class="form-control date-picker" placeholder="Next Service Date"
                                            data-provide="datepicker" data-date-autoclose="true" data-date-today-highlight="true"
                                            name="next_service_date">
                                        <div class="form-control-focus"></div>
                                        <span class="help-block">Enter Next Service Date</span>
                                    </div>
                                    <div class="form-group form-md-line-input col-md-12">
                                        <label><h4>Remarks</h4></label>
                                        <input type="text" class="form-control" placeholder="Remarks"
                                                name="remarks">
                                        <div class="form-control-focus"></div>
                                        <span class="help-block">Remarks</span>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn blue">Save</button>
                                    <button type="button" class="btn default" data-dismiss="modal">Close
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                {{-- asset service modal ends--}}

                {{-- edit asset modal--}}
                <div class="modal fade bs-modal-md in" id="editAsset{{$asset->id}}" role="dialog"
                        aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-md" id="modal-data-application">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4>Edit Assets</h4>
                                <button type="button" class="close" data-dismiss="modal"
                                        aria-hidden="true"></button>
                                <span class="caption-subject font-red-sunglo bold uppercase"
                                        id="modelHeading"></span>
                            </div>
                            <form action="{{route('gym-admin.assetManagement.edit',$asset->id)}}" method="post">
                                {{ csrf_field() }}
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="form-group form-md-line-input col-md-6">
                                            <label><h4>Asset Tag *</h4></label>
                                            <input type="text" value="{{$asset->tag}}" class="form-control"
                                                    placeholder="Asset Tag" name="tag" required>
                                            <div class="form-control-focus"></div>
                                            <span class="help-block">Enter Asset Tag</span>
                                        </div>
                                        <div class="form-group form-md-line-input col-md-6">
                                            <label><h4>Asset Name *</h4></label>
                                            <input type="text" value="{{$asset->name}}" class="form-control"
                                                    placeholder="Asset Name" name="name" required>
                                            <div class="form-control-focus"></div>
                                            <span class="help-block">Enter Asset Name</span>
                                        </div>
                                        <div class="form-group form-md-line-input col-md-6">
                                            <label><h4>Asset Brand Name *</h4></label>
                                            <input type="text" value="{{$asset->brand_name}}" class="form-control"
                                                    placeholder="Brand Name" name="brand_name" required>
                                            <div class="form-control-focus"></div>
                                            <span class="help-block">Enter Brand Name</span>
                                        </div>
                                        <div class="form-group form-md-line-input col-md-6">
                                            <label><h4>Asset Supplier *</h4></label>
                                            <select name="supplier" class="form-control" required>
                                                @foreach($suppliers as $s)
                                                    <option value="{{$s->id}}" @if($s->id == $asset->supplier_id) ? selected @endif>{{ $s->name }}</option>
                                                @endforeach
                                            </select>
                                            <div class="form-control-focus"></div>
                                            <span class="help-block">Enter Asset Supplier</span>
                                        </div>
                                        <div class="form-group form-md-line-input col-md-6">
                                            <label><h4>Asset Model *</h4></label>
                                            <input type="text" value="{{$asset->asset_model}}" class="form-control"
                                                    placeholder="Asset Model" name="asset_model" required>
                                            <div class="form-control-focus"></div>
                                            <span class="help-block">Enter Asset Model</span>
                                        </div>
                                        <div class="form-group form-md-line-input col-md-6">
                                            <label><h4>Asset Quantity *</h4></label>
                                            <input type="number" min="0" value="{{$asset->quantity}}" class="form-control"
                                                    placeholder="Asset Quantity" name="quantity" required>
                                            <div class="form-control-focus"></div>
                                            <span class="help-block">Enter Asset Quantity</span>
                                        </div>
                                        <div class="form-group form-md-line-input col-md-6">
                                            <label><h4>Asset Purchase Date *</h4></label>
                                            <input type="text" value="{{$asset->purchase_date}}"
                                                    class="form-control date-picker" placeholder="Purchase Date"
                                                    name="purchase_date" required>
                                            <div class="form-control-focus"></div>
                                            <span class="help-block">Enter Asset Purchase Date</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn blue mt-ladda-btn ladda-button">Update</button>
                                    <button type="button" class="btn default" data-dismiss="modal">Close
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                {{-- edit asset modal ends--}}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>