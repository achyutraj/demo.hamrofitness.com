<?php

namespace App\Http\Controllers;

use App\Mail\EmailVerification;
use App\Models\Setting;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;
use Jenssegers\Agent\Agent;

class BaseController extends Controller
{
    public $data = [];

    public function sortByOrder($a, $b)
    {
        return $a['time'] - $b['time'];
    }

    public function __construct()
    {
        View::composer(
            ['layouts.frontend.email_system'], function ($view) {
            $setting = Setting::first();
            $view->with('settingDetail', $setting);
        }
        );

        $this->data['cookieDefault'] = ['user_id' => null, 'location' => null, 'latitude' => '26.90000000', 'longitude' => '75.80000000'];
        /*detect device type*/
        $agent = new Agent();
        $this->data['isPhone'] = $agent->isPhone();
        $this->data['isDesktop'] = $agent->isDesktop();
        $this->data['isAndroid'] = $agent->isAndroidOS();

    }

    public static function getS3Urls($bucket = null)
    {
        return [
            // Gym Image S3 Urls
            'profilePath' => asset('/uploads').'/profile_pic/thumb/',
            'profileHeaderPath' => asset('/uploads/profile_pic/master/').'/',
            'logo' => '',
        ];
    }

    public function emailNotification($email, $eText, $eTitle, $eHeading, $url = NULL)
    {
        $this->data['email'] = $email;
        $this->data['title'] = $eTitle;
        $this->data['mailHeading'] = $eHeading;
        $this->data['emailText'] = $eText;
        $this->data['url'] = $url;
        $data = $this->data;

        try {
            Mail::to($data['email'])->send(new EmailVerification($this->data));
        } catch (\Exception $e) {
            $response['errorEmailMessage'] = 'error';
        }

    }
   
}
