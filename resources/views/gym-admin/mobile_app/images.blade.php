<div class="row">
    <div class="col-md-12">
        <div class="portlet light ">
            <div class="portlet-title">
                <div class="caption font-dark">
                    <i class="icon-badge font-red"></i>
                    <span class="caption-subject font-red bold uppercase"> Images</span>
                </div>
            </div>
            <div class="portlet-body">
                <form action="{{ route('gym-admin.mobile-app.imagesStore') }}" method="POST"
                      enctype="multipart/form-data" class="form form-horizontal">
                    @csrf
                    <input type="hidden" name="mobile_app_id" value="{{ $mobileApp->id }}">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-body">
                                <div class="form-group form-md-line-input ">
                                    <label class="col-md-2 control-label" for="logo">Logo Image*</label>
                                    <div class="">
                                        <div class="input-icon right">
                                            <input type="file" class="btn blue" name="logo">
                                            @if($mobileApp->logo != null)
                                                <img height="150" width="150" src="{{ asset('uploads/mobile_app/'.$mobileApp->logo) }}" alt="">
                                            @endif
                                        </div>
                                        <div id="error-msg" class="error-msg"></div>
                                    </div>
                                </div>

                                <div class="form-group form-md-line-input ">
                                    <label class="col-md-2 control-label" for="offer_image">Offer Image*</label>
                                    <div class="">
                                        <div class="input-icon right">
                                            <input type="file" class="btn blue" name="offer_image">
                                            @if($mobileApp->offer_image != null)
                                                <img height="150" width="150" src="{{ asset('uploads/mobile_app/'.$mobileApp->offer_image) }}" alt="">
                                            @endif
                                        </div>
                                        <div id="error-msg" class="error-msg"></div>
                                    </div>
                                </div>
                                <div class="form-group form-md-line-input ">
                                    <label class="col-md-2 control-label" for="notice_image">Notice Image*</label>
                                    <div class="">
                                        <div class="input-icon right">
                                            <input type="file" class="btn blue" name="notice_image">
                                            @if($mobileApp->notice_image != null)
                                                <img height="150" width="150" src="{{ asset('uploads/mobile_app/'.$mobileApp->notice_image) }}" alt="">
                                            @endif
                                        </div>
                                        <div id="error-msg" class="error-msg"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-body">
                                <div class="form-group form-md-line-input ">
                                    <label class="col-md-2 control-label" for="banner_image1">Banner Image 1*</label>
                                    <div class="">
                                        <div class="input-icon right">
                                            <input type="file" class="btn blue" name="banner_image1">
                                            @if($mobileApp->banner_image1 != null)
                                                <img height="150" width="150" src="{{ asset('uploads/mobile_app/'.$mobileApp->banner_image1) }}" alt="">
                                            @endif
                                        </div>
                                        <div id="error-msg" class="error-msg"></div>
                                    </div>
                                </div>

                                <div class="form-group form-md-line-input ">
                                    <label class="col-md-2 control-label" for="banner_image2">Banner Image 2
                                        (Optional)</label>
                                    <div class="">
                                        <div class="input-icon right">
                                            <input type="file" class="btn blue" name="banner_image2">
                                            @if($mobileApp->banner_image2 != null)
                                                <img height="150" width="150" src="{{ asset('uploads/mobile_app/'.$mobileApp->banner_image2) }}" alt="">
                                            @endif
                                        </div>
                                        <div id="error-msg" class="error-msg"></div>
                                    </div>
                                </div>

                                <div class="form-group form-md-line-input ">
                                    <label class="col-md-2 control-label" for="banner_image3">Banner Image 3
                                        (Optional)</label>
                                    <div class="">
                                        <div class="input-icon right">
                                            <input type="file" class="btn blue" name="banner_image3">
                                            @if($mobileApp->banner_image3 != null)
                                                <img height="150" width="150" src="{{ asset('uploads/mobile_app/'.$mobileApp->banner_image3) }}" alt="">
                                            @endif
                                        </div>
                                        <div id="error-msg" class="error-msg"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                      
                    </div>
                    <div class="row">
                        <div class=" col-md-offset-5 col-md-2">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
