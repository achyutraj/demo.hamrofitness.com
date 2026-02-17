{!! HTML::style('admin/global/plugins/bootstrap-datepicker/css/datepicker.css') !!}
{!! HTML::style('admin/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') !!}

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <span class="caption-subject font-red-sunglo bold uppercase"><i class="fa fa-plus"></i> Renew</span>
</div>
<div class="modal-body">
    {!! Form::open(['id' => 'followUpForm', 'class' => 'ajax-form']) !!}

    <input type="hidden" name="detail_id" value="{{ $enquiry->id }}">

    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label class="control-label sbold">Branch Name:</label>
                <p class="form-control-static"> {{ ucwords($enquiry->title) }} </p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label class="control-label sbold">Branch Admin:</label>
                <p class="form-control-static"> {{ ucwords($enquiry->owner_incharge_name) }} </p>
            </div>
        </div>
        <!--/span-->
        <div class="col-md-4">
            <div class="form-group">
                <label class="control-label sbold">Contact Number:</label>
                <p class="form-control-static"> {{ $enquiry->phone }} </p>
            </div>
        </div>
        <!--/span-->
        <div class="col-md-12">
            <div class="form-group">
                <p class="text-danger">Note: Remaining Days will be auto added in Expiry Date</p>
            </div>
        </div>
        <!--/span-->
    </div>

    <div class="row">
        <div class="col-md-12 ">
            <div class="row">
                <div class="form-group form-md-line-input ">
                    <input type="text" class="form-control" id="package_amount" name="package_amount" />
                    <label for="form_control_1">Package amount <span class="required" aria-required="true"> * </span></label>
                    <div class="form-control-focus"></div>
                </div>
                <div class="form-group form-md-line-input ">
                    <input type="text" value="{{ $remaining_days}}" readonly class="form-control" id="remaining_days" name="remaining_days" />
                    <label for="form_control_1">Remaining Days <span class="required" aria-required="true"> * </span></label>
                    <div class="form-control-focus"></div>
                </div>
                <div class="form-group form-md-line-input ">
                    <input type="text" class="form-control date-picker" id="renew_start_date" name="renew_start_date"
                        readonly data-provide="datepicker" data-date-start-date="-0d" data-date-autoclose="true"
                        value="{{ \Carbon\Carbon::today()->format('m/d/Y') }}" />
                    <label for="form_control_1">Renewal Date <span class="required" aria-required="true"> * </span></label>
                    <div class="form-control-focus"></div>
                </div>

                <div class="form-group form-md-line-input">
                    <label class="control-label">Package offered</label>
                    <div class="form-group form-md-radios">
                        <div class="md-radio-inline">
                            <div class="md-radio">
                                <input type="radio" id="package-monthly" checked name="package_offered" value="1"
                                    class="md-radiobtn">
                                <label for="package-monthly">
                                    <span></span>
                                    <span class="check"></span>
                                    <span class="box"></span> Monthly </label>
                            </div>
                            <div class="md-radio">
                                <input type="radio" id="package-quarterly" name="package_offered" class="md-radiobtn"
                                    value="3">
                                <label for="package-quarterly">
                                    <span></span>
                                    <span class="check"></span>
                                    <span class="box"></span> Quarterly</label>
                            </div>
                            <div class="md-radio">
                                <input type="radio" id="package-half-yearly" name="package_offered"
                                    class="md-radiobtn" value="6">
                                <label for="package-half-yearly">
                                    <span></span>
                                    <span class="check"></span>
                                    <span class="box"></span> Half Yearly</label>
                            </div>

                            <div class="md-radio">
                                <input type="radio" id="package-yearly" name="package_offered" class="md-radiobtn"
                                    value="12">
                                <label for="package-yearly">
                                    <span></span>
                                    <span class="check"></span>
                                    <span class="box"></span> Yearly</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group form-md-line-input ">
                    <textarea name="remark" class="form-control" id="remark" cols="30" rows="3"></textarea>
                    <label for="form_control_1">Remark</label>
                    <div class="form-control-focus"></div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <a href="javascript:;" class="btn blue" id="add-branch-renew">Save</a>
        </div>
        {!! Form::close() !!}
    </div>

    {!! HTML::script('admin/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') !!}
    {!! HTML::style('admin/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') !!}
