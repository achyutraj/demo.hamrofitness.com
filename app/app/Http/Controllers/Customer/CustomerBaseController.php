<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Models\BusinessCustomer;
use App\Models\GymClient;
use App\Models\GymSetting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Jenssegers\Agent\Agent;

class CustomerBaseController extends Controller
{
    public $data = [];

    public function __construct()
    {
        $this->data['customerCheck'] = null;
        $this->data['customerValues'] = null;

        $this->middleware(function($request, $next) {
            if(Auth::guard('customer')->check()) {
                $this->data['customerCheck'] = Auth::guard('customer')->check();
                $this->data['customerValues'] = Auth::guard('customer')->user();
            }

            // Assign default business
            if($this->data['customerValues']) {
                $business = BusinessCustomer::where('customer_id','=', $this->data['customerValues']->id)
                    ->first();
                $this->data['customerValues']->detail_id = $business->detail_id;
                $this->data['customerBusiness'] = BusinessCustomer::findByCustomer($this->data['customerValues']->id);
                $this->data['gymSettings'] = GymSetting::where('detail_id',$business->detail_id)->first();
                $customer = GymClient::find($this->data['customerValues']->id);

                $this->data['notifications'] = [];
                foreach ($customer->unreadNotifications  as $notification) {
                    if($notification->data['customer_id'] == $this->data['customerValues']->id) {
                        array_push($this->data['notifications'], $notification->data);
                    }
                }

                $count = 0;
                foreach ($customer->unreadNotifications as $notification) {
                    if($notification->data['customer_id'] == $this->data['customerValues']->id) {
                        $count++;
                    }
                }
                $this->data['unreadNotifications'] = $count;
            }

            return $next($request);
        });

        $agent = new Agent();
        $this->data['isPhone'] = $agent->isPhone();
        $this->data['isDesktop'] = $agent->isDesktop();


        $bucket = '';
        
        $s3Urls = BaseController::getS3Urls($bucket);
        $this->data['profilePath'] = $s3Urls['profilePath'];
        $this->data['profileHeaderPath'] = asset('/uploads/profile_pic/master/').'/';
        $this->data['gymSettingPathThumb'] = asset('/uploads/gym_setting/thumb/').'/';
        $this->data['gymSettingPath'] = asset('/uploads/gym_setting/master/').'/';
        $this->data['gymOffersPath'] = '';
        $this->data['expenseUrl'] = asset('/uploads/bill/').'/';
        $this->data['logo'] = $s3Urls['logo'];

        Config::set('auth.model', 'Customer');
    }
}
