<div class="row">
    <div class="col-md-12">
        <div class="portlet light ">
            <div class="portlet-title">
                <div class="caption font-dark">
                    <i class="icon-badge font-red"></i>
                    <span class="caption-subject font-red bold uppercase"> Social Icons</span>
                </div>
            </div>
            <div class="portlet-body">
                <form action="{{ route('gym-admin.mobile-app.update') }}" id="detailSocialData" class="ajax_form" method="POST">
                    @csrf
                    <input type="hidden" name="mobile_app" value="{{ $mobileApp->id }}">
                    <input type="hidden" name="type" value="social">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-body">
                                <div class="form-group form-md-line-input ">
                                    <input type="text" class="form-control" id="fb_url" name="fb_url" value="{{ $mobileApp->fb_url }}"  >
                                    <label for="form_control_1">Fb Url</label>
                                    <span class="help-block">Please enter your facebook url.</span>
                                </div>

                                <div class="form-group form-md-line-input ">
                                    <input type="text" class="form-control" id="google_url" name="google_url" value="{{ $mobileApp->google_url }}"  >
                                    <label for="form_control_1">Google Url</label>
                                    <span class="help-block">Please enter your google url.</span>
                                </div>

                                <div class="form-group form-md-line-input ">
                                    <input type="text" class="form-control" id="youtube_url" name="youtube_url" value="{{ $mobileApp->youtube_url }}"  >
                                    <label for="form_control_1">Youtube Url</label>
                                    <span class="help-block">Please enter your youtube url.</span>
                                </div>

                                <div class="form-group form-md-line-input ">
                                    <input type="text" class="form-control" id="twitter_url" name="twitter_url" value="{{ $mobileApp->twitter_url }}"  >
                                    <label for="form_control_1">Twitter Url</label>
                                    <span class="help-block">Please enter your twitter url.</span>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class=" col-md-offset-5 col-md-2">
                            <button type="button" class="btn btn-primary" id="updateSocialDetails">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
