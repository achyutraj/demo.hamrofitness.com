<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class MerchantBaseController extends BaseController
{

    public $data = [];

    public function __construct()
    {
        $this->data['userCheck'] = null;
        $this->data['userValue'] = null;

        $this->middleware(function ($request, $next) {
            if(Auth::guard('merchant')->check()) {
                $this->data['userCheck'] = Auth::guard('merchant')->check();
                $this->data['userValue'] = Auth::guard('merchant')->user();
            }

            return $next($request);
        });

        $this->data['title'] = "Dashboard";
        $s3Urls = BaseController::getS3Urls();
        $this->data['profilePath'] = $s3Urls['profilePath'];
        $this->data['profileHeaderPath']   = asset('/uploads/profile_pic/master/').'/';
        $this->data['gymSettingPathThumb'] = asset('uploads/gym_setting/thumb/').'/';
        $this->data['gymSettingPath'] = asset('uploads/gym_setting/master/').'/';
        $this->data['gymOffersPath'] = asset('uploads/gyms_offers/').'/';
        $this->data['expenseUrl'] = asset('uploads/bill/').'/';
        #set authorisation model for Admin login
        Config::set('auth.model', 'Merchant');
    }
}
