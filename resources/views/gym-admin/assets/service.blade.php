<table class="table table-striped table-bordered table-hover table-100"
        id="asset-service">
    <thead>
    <tr>
        <th class="desktop"> Added By</th>
        <th class="desktop"> Assets</th>
        <th class="desktop"> Service By</th>
        <th class="desktop"> Service Date</th>
        <th class="desktop">Next Service Date</th>
        <th class="desktop">Remarks</th>
        <th class="desktop">Action</th>
    </tr>
    </thead>
    <tbody>
    @if(count($assetServices) > 0)
    @foreach($assetServices as $service)
        <tr>
            <td>{{$service->merchant->fullName}}</td>
            <td>{{$service->assets->name}}</td>
            <td>{{$service->service_by}}</td>
            <td>{{$service->service_date->toFormattedDateString()}}</td>
            <td>{{$service->next_service_date?->toFormattedDateString()}}</td>
            <td>{{$service->remarks}}</td>
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
                                data-target="#editAssetService{{$service->id}}"><i
                                        class="fa fa-edit"></i> Edit</a>
                        </li>
                        <li>
                            <a data-service-url="{{route('gym-admin.assetManagement.assetServicesDelete',$service->id)}}"
                                class="service-remove"><i class="fa fa-trash"></i> Delete</a>
                        </li>
                    </ul>
                </div>
            </td>
            <div class="modal fade bs-modal-md in"
                    id="editAssetService{{$service->id}}" role="dialog"
                    aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-md" id="modal-data-application">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4>Edit Assets Servicing </h4>
                            <button type="button" class="close" data-dismiss="modal"
                                    aria-hidden="true"></button>
                            <span class="caption-subject font-red-sunglo bold uppercase"
                                    id="modelHeading"></span>
                        </div>
                        <form action="{{route('gym-admin.assetManagement.assetServicesUpdate',$service->id)}}"
                                method="post" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <input type="hidden" name="service_id" value="{{$service->id}}">
                            <div class="modal-body">
                                <div class="row">
                                    <div class="form-group form-md-line-input col-md-4">
                                        <label><h4>Service By</h4></label>
                                        <input type="text" class="form-control" value="{{ $service->service_by}}" placeholder="Add Maintainer Person Name "
                                                name="service_by" required>
                                        <div class="form-control-focus"></div>
                                        <span class="help-block">Enter Service By</span>
                                    </div>
                                    <div class="form-group form-md-line-input col-md-4">
                                        <label><h4>Service Date</h4></label>
                                        <input type="text" class="form-control date-picker" placeholder="Service Date"
                                            name="service_date" value="{{ $service->service_date->format('Y-m-d')}}" required>
                                        <div class="form-control-focus"></div>
                                        <span class="help-block">Enter Service Date</span>
                                    </div>
                                    <div class="form-group form-md-line-input col-md-4">
                                        <label><h4>Next Service Date</h4></label>
                                        <input type="text" class="form-control date-picker" placeholder="Next Service Date"
                                            name="next_service_date" value="{{ $service->next_service_date?->format('Y-m-d')}}">
                                        <div class="form-control-focus"></div>
                                        <span class="help-block">Enter Next Service Date</span>
                                    </div>
                                </div>
                                <div class="form-group form-md-line-input col-md-12">
                                    <label><h4>Remarks</h4></label>
                                    <input type="text" value="{{$service->remarks}}"
                                            class="form-control" placeholder="Remarks" name="remarks"
                                            required>
                                    <div class="form-control-focus"></div>
                                    <span class="help-block">Remarks</span>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn green">Update</button>
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