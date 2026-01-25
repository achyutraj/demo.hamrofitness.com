<?php

namespace App\Http\Controllers\GymAdmin;

use App\Models\Activity;
use App\Classes\Reply;
use App\Http\Requests\GymAdmin\GymSetting\StoreFileUploadCredentialRequest;
use App\Http\Requests\GymAdmin\GymSetting\StoreMailRequest;
use App\Http\Requests\GymAdmin\GymSetting\StoreOtherSettingRequest;
use App\Http\Requests\GymAdmin\GymSetting\StoreSmsRequest;
use App\Models\BusinessBranch;
use App\Models\GymSetting;
use App\Models\MobileApp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

class GymAdminSettingController extends GymAdminBaseController
{
    public function index()
    {
        if (!$this->data['user']->can("view_settings")) {
            return App::abort(401);
        }

        $this->data['merchantSetting'] = GymSetting::GetMerchantInfo($this->data['user']->detail_id);
        $this->data['title'] = "Settings";

        return View::make('gym-admin.setting.general', $this->data);
    }

    public function store()
    {
        $validator = Validator::make(request()->all(), GymSetting::rules('id'));
        if ($validator->fails()) {
            return Reply::formErrors($validator);
        }
        $setting = GymSetting::firstOrNew(['detail_id' => $this->data['user']->detail_id]);

        if (request()->get('img_name') != '') {
            $setting->image = request()->get('img_name');
        }
        $setting->save();

        return Reply::success('Setting updated');
    }

    public function image(Request $request)
    {
        $validator = Validator::make(request()->all(), GymSetting::rules('image'));
        if ($validator->fails()) {
            return Reply::formErrors($validator);
        }

        if ($request->ajax()) {
            $id = $this->data['user']->id;

            $output = [];
            $image = request()->file('file');

            $x = intval($request->xCoordOne);
            $y = intval($request->yCoordOne);
            $width = intval($request->profileImageWidth);
            $height = intval($request->profileImageHeight);

            $extension = request()->file('file')->getClientOriginalExtension();
            $filename = $id . "-" . rand(10000, 99999) . "." . $extension;

            if ($this->data['gymSettings']->local_storage == 0) {
                $destinationPathMaster = "/uploads/gym_setting/master/$filename";
                $destinationPathThumb = "/uploads/gym_setting/thumb/$filename";
                $image1 = Image::make($image->getRealPath())
                    ->resizeCanvas($width, $height, 'center', false, 'rgba(0, 0, 0, 0)');

                $this->uploadImageS3($image1, $destinationPathMaster);

                $image2 = Image::make($image->getRealPath())
                    ->resizeCanvas($width, $height, 'center', false, 'rgba(0, 0, 0, 0)')
                    ->resize(40, 40);

                $this->uploadImageS3($image2, $destinationPathThumb);
            } else {
                if (!file_exists(public_path() . "/uploads/gym_setting/master/") &&
                    !file_exists(public_path() . "/uploads/gym_setting/thumb/")) {
                    File::makeDirectory(public_path() . "/uploads/gym_setting/master/", $mode = 0777, true, true);
                    File::makeDirectory(public_path() . "/uploads/gym_setting/thumb/", $mode = 0777, true, true);
                }

                $destinationPathMaster = public_path() . "/uploads/gym_setting/master/$filename";
                $destinationPathThumb = public_path() . "/uploads/gym_setting/thumb/$filename";
                $image1 = Image::make($image->getRealPath())
                    ->resizeCanvas($width, $height, 'center', false, 'rgba(0, 0, 0, 0)');
                $image1->save($destinationPathMaster);

                $image2 = Image::make($image->getRealPath())
                    ->resizeCanvas($width, $height, 'center', false, 'rgba(0, 0, 0, 0)')
                    ->resize(40, 40);
                $image2->save($destinationPathThumb);
            }

            $setting = GymSetting::firstOrNew(['detail_id' => $this->data['user']->detail_id]);
            $setting->image = $filename;
            $setting->save();

            $output['image'] = $filename;
            return json_encode($output);
        } else {
            return "Illegal request method";
        }

    }

    public function storeFrontImage(Request $request)
    {
        $validator = Validator::make(request()->all(), GymSetting::rules('image'));
        if ($validator->fails()) {
            return Reply::formErrors($validator);
        }

        if ($request->ajax()) {
            $id = $this->data['user']->id;

            $output = [];
            $image = request()->file('file');

            $extension = request()->file('file')->getClientOriginalExtension();
            $filename = $id . "-" . rand(10000, 99999) . "." . $extension;

            if (
                !is_null($this->data['gymSettings']->aws_key) || $this->data['gymSettings']->aws_key != '' ||
                !is_null($this->data['gymSettings']->aws_secret) || $this->data['gymSettings']->aws_secret != '' ||
                !is_null($this->data['gymSettings']->aws_region) || $this->data['gymSettings']->aws_region != '' ||
                !is_null($this->data['gymSettings']->aws_bucket) || $this->data['gymSettings']->aws_bucket != ''
            ) {
                $destinationPathMaster = "/uploads/gym_setting/master/$filename";
                $destinationPathThumb = "/uploads/gym_setting/thumb/$filename";
                $image1 = Image::make($image->getRealPath());
                $this->uploadImageS3($image1, $destinationPathMaster);

                $image2 = Image::make($image->getRealPath())
                    ->resize(40, 40);

                $this->uploadImageS3($image2, $destinationPathThumb);
            } else {
                if (!file_exists(public_path() . "/uploads/gym_setting/master/") &&
                    !file_exists(public_path() . "/uploads/gym_setting/thumb/")) {
                    File::makeDirectory(public_path() . "/uploads/gym_setting/master/", $mode = 0777, true, true);
                    File::makeDirectory(public_path() . "/uploads/gym_setting/thumb/", $mode = 0777, true, true);
                }

                $destinationPathMaster = public_path() . "/uploads/gym_setting/master/$filename";
                $destinationPathThumb = public_path() . "/uploads/gym_setting/thumb/$filename";
                $image1 = Image::make($image->getRealPath());
                $image1->save($destinationPathMaster);

                $image2 = Image::make($image->getRealPath())
                    ->resize(40, 40);
                $image2->save($destinationPathThumb);
            }

            $setting = GymSetting::firstOrNew(['detail_id' => $this->data['user']->detail_id]);
            $setting->front_image = $filename;
            $setting->save();

            $output['image'] = $filename;
            return json_encode($output);
        } else {
            return "Illegal request method";
        }
    }

    public function uploadImageS3($imageMake, $filePath)
    {
        if (get_class($imageMake) === 'Intervention\Image\Image') {
            Storage::put($filePath, $imageMake->stream()->__toString(), 'public');
        } else {
            Storage::put($filePath, fopen($imageMake, 'r'), 'public');
        }
    }

    //Store Mail Credentials
    public function mailPage()
    {
        $this->data['merchantSetting'] = GymSetting::GetMerchantInfo($this->data['user']->detail_id);

        return view('gym-admin.setting.mail', $this->data);
    }

    public function storeMailCredentials(StoreMailRequest $request)
    {
        if (!$this->data['user']->can("update_settings")) {
            return Reply::error('Setting Update Permission Denied');
        }
        $mailSetting = GymSetting::firstOrNew(['detail_id' => $this->data['user']->detail_id]);
        if ($request->get('email_status') == 'enabled') {
            $mailSetting->email_status = $request->get('email_status');
            $mailSetting->mail_driver = $request->get('mail_driver');
            $mailSetting->mail_host = $request->get('mail_host');
            $mailSetting->mail_port = $request->get('mail_port');
            $mailSetting->mail_username = $request->get('mail_username');
            $mailSetting->mail_password = $request->get('mail_password');
            $mailSetting->mail_encryption = $request->get('mail_encryption');
            $mailSetting->mail_name = $request->get('mail_name');
            $mailSetting->mail_email = $request->get('mail_email');
        } else {
            $mailSetting->email_status = $request->get('email_status');
        }
        $mailSetting->save();

        return Reply::success('Setting updated');
    }

    //Store SMS Credentials
    public function smsPage()
    {
        $this->data['merchantSetting'] = GymSetting::GetMerchantInfo($this->data['user']->detail_id);

        return view('gym-admin.setting.sms', $this->data);
    }

    public function storeSmsCredentials(StoreSmsRequest $request)
    {
        if (!$this->data['user']->can("update_settings")) {
            return Reply::error('Setting Update Permission Denied');
        }
        $smsSetting = GymSetting::firstOrNew(['detail_id' => $this->data['user']->detail_id]);
        if ($request->get('sms_status') == 'enabled') {
            $smsSetting->is_old = $request->get('is_old');
            $smsSetting->sms_api_url = $request->get('sms_api_url');
            $smsSetting->sms_status = $request->get('sms_status');
            $smsSetting->sms_sender_id = $request->get('sender_id');
            $smsSetting->sms_username = $request->get('username');
            $smsSetting->sms_password = $request->get('password');
            $smsSetting->campaign_id = $request->get('campaign_id');
            $smsSetting->route_id = $request->get('route_id');
            $smsSetting->api_key = $request->get('api_key');
        } else {
            $smsSetting->sms_status = $request->get('sms_status');
        }
        $smsSetting->save();
        return Reply::success('Setting updated');
    }

    //Store File
    public function fileUploadPage()
    {
        $this->data['merchantSetting'] = GymSetting::GetMerchantInfo($this->data['user']->detail_id);

        return view('gym-admin.setting.file-upload', $this->data);
    }

    public function storeFileUploadCredentials(StoreFileUploadCredentialRequest $request)
    {
        if (!$this->data['user']->can("update_settings")) {
            return Reply::error('Setting Update Permission Denied');
        }
        $fileUploadSetting = GymSetting::firstOrNew(['detail_id' => $this->data['user']->detail_id]);
        $fileUploadSetting->local_storage = $request->get('storage');
        if ($request->get('storage') == 0) {
            $fileUploadSetting->file_storage = 's3';
        }
        $fileUploadSetting->aws_key = $request->get('aws_key');
        $fileUploadSetting->aws_secret = $request->get('aws_secret');
        $fileUploadSetting->aws_region = $request->get('aws_region');
        $fileUploadSetting->aws_bucket = $request->get('aws_bucket');
        $fileUploadSetting->save();

        return Reply::success('Setting updated');
    }

    //Store PaymentGateways Credentials
    public function paymentsPage()
    {
        $this->data['merchantSetting'] = GymSetting::GetMerchantInfo($this->data['user']->detail_id);

        return view('gym-admin.setting.payments', $this->data);
    }

    public function storePayments(Request $request)
    {
        if (!$this->data['user']->can("update_settings")) {
            return Reply::error('Setting Update Permission Denied');
        }
        $fileUploadSetting = GymSetting::firstOrNew(['detail_id' => $this->data['user']->detail_id]);
        $fileUploadSetting->payment_status = $request->get('payment_status');
        $fileUploadSetting->esewa_merchant_id = $request->get('esewa_merchant_id');
        $fileUploadSetting->khalti_public_key = $request->get('khalti_public_key');
        $fileUploadSetting->khalti_secret_key = $request->get('khalti_secret_key');
        $fileUploadSetting->offline_text = $request->get('offline_text');
        $fileUploadSetting->save();

        return Reply::success('Setting updated');
    }

    //Store Other Credentials
    public function othersPage()
    {
        $this->data['merchantSetting'] = GymSetting::GetMerchantInfo($this->data['user']->detail_id);
        $this->data['options'] = json_decode($this->data['merchantSetting']->options, true);
        return view('gym-admin.setting.other', $this->data);
    }

    public function storeOtherSettingCredentials(StoreOtherSettingRequest $request)
    {
        if (!$this->data['user']->can("update_settings")) {
            return Reply::error('Setting Update Permission Denied');
        }

        $otherSetting = GymSetting::firstOrNew(['detail_id' => $this->data['user']->detail_id]);
        $post_data = $request->only(['subscription_expire_days', 'product_expire_days','idle_time']);
        $get_options = json_decode($otherSetting->options, true);
        $output = array_replace($get_options, $post_data);
        $otherSetting->options = json_encode($output);
        $otherSetting->idle_time = $post_data['idle_time'];
        $otherSetting->save();

        return Reply::success('Setting updated');
    }

    public function appPage()
    {
        if (!$this->data['user']->can("mobile_app")) {
            return App::abort(401);
        }
        $this->data['title'] = "Edit Mobile App";
        $this->data['mobileApp'] = MobileApp::where('detail_id', $this->data['user']->detail_id)->first();
        $this->data['branches'] = BusinessBranch::where('detail_id', $this->data['user']->detail_id)->get();
        return view('gym-admin.mobile_app.edit', $this->data);
    }

    //Store Footer Credentials
    public function footerPage()
    {
        $this->data['merchantSetting'] = GymSetting::GetMerchantInfo($this->data['user']->detail_id);

        return view('gym-admin.setting.footer', $this->data);
    }

    public function storeFooterSettingCredentials(Request $request)
    {
        if (!$this->data['user']->can("update_settings")) {
            return Reply::error('Setting Update Permission Denied');
        }
        $footerSetting = GymSetting::firstOrNew(['detail_id' => $this->data['user']->detail_id]);
        $footerSetting->about = $request->get('about');
        $footerSetting->fb_url = $request->get('fb_url');
        $footerSetting->twitter_url = $request->get('twitter_url');
        $footerSetting->google_url = $request->get('google_url');
        $footerSetting->youtube_url = $request->get('youtube_url');
        $footerSetting->contact_mail = $request->get('contact_mail');
        $footerSetting->save();

        return Reply::success('Setting updated');
    }

    //Store Customer Panel Image Credentials
    public function storeCustomerImage(Request $request)
    {
        $validator = Validator::make(request()->all(), GymSetting::rules('image'));
        if ($validator->fails()) {
            return Reply::formErrors($validator);
        }

        if ($request->ajax()) {
            $id = $this->data['user']->id;

            $output = [];
            $image = request()->file('file');

            $extension = request()->file('file')->getClientOriginalExtension();
            $filename = $id . "-" . rand(10000, 99999) . "." . $extension;

            if (
                !is_null($this->data['gymSettings']->aws_key) || $this->data['gymSettings']->aws_key != '' ||
                !is_null($this->data['gymSettings']->aws_secret) || $this->data['gymSettings']->aws_secret != '' ||
                !is_null($this->data['gymSettings']->aws_region) || $this->data['gymSettings']->aws_region != '' ||
                !is_null($this->data['gymSettings']->aws_bucket) || $this->data['gymSettings']->aws_bucket != ''
            ) {
                $destinationPathMaster = "/uploads/gym_setting/master/$filename";
                $destinationPathThumb = "/uploads/gym_setting/thumb/$filename";
                $image1 = Image::make($image->getRealPath());
                $this->uploadImageS3($image1, $destinationPathMaster);

                $image2 = Image::make($image->getRealPath())
                    ->resize(40, 40);

                $this->uploadImageS3($image2, $destinationPathThumb);
            } else {
                if (!file_exists(public_path() . "/uploads/gym_setting/master/") &&
                    !file_exists(public_path() . "/uploads/gym_setting/thumb/")) {
                    File::makeDirectory(public_path() . "/uploads/gym_setting/master/", $mode = 0777, true, true);
                    File::makeDirectory(public_path() . "/uploads/gym_setting/thumb/", $mode = 0777, true, true);
                }

                $destinationPathMaster = public_path() . "/uploads/gym_setting/master/$filename";
                $destinationPathThumb = public_path() . "/uploads/gym_setting/thumb/$filename";
                $image1 = Image::make($image->getRealPath());
                $image1->save($destinationPathMaster);

                $image2 = Image::make($image->getRealPath())
                    ->resize(40, 40);
                $image2->save($destinationPathThumb);
            }

            $setting = GymSetting::firstOrNew(['detail_id' => $this->data['user']->detail_id]);
            $setting->customer_logo = $filename;
            $setting->save();

            $output['image'] = $filename;
            return json_encode($output);
        } else {
            return "Illegal request method";
        }
    }

    public function activityPage()
    {
        $this->data['levelActivity'] = Activity::where('branch_id', $this->data['user']->detail_id)->get();
        return view('gym-admin.setting.activity', $this->data);
    }

    //Store Notifications Credentials
    public function notificationPage()
    {
        $this->data['merchantSetting'] = GymSetting::GetMerchantInfo($this->data['user']->detail_id);
        $this->data['options'] = json_decode($this->data['merchantSetting']->options, true);
        return view('gym-admin.setting.notification', $this->data);
    }

    public function storeNotification(Request $request)
    {
        if (!$this->data['user']->can("update_settings")) {
            return Reply::error('Setting Update Permission Denied');
        }
        $notification = GymSetting::firstOrNew(['detail_id' => $this->data['user']->detail_id]);
        if ($notification->sms_status !== 'enabled') {
            return Reply::error('Please enable SMS Options.');
        }
        $post_data = $request->all();
        $get_options = json_decode($notification->options, true);
        $output = array_replace($get_options, $post_data);
        $notification->options = json_encode($output);
        $notification->save();
        return Reply::success('Setting updated');
    }
}
