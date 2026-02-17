<?php

    namespace App\Traits;

    use App\Models\GymSetting;

    trait SmsSettingsTrait
    {
        private $SENDER_ID;
        private $SMS_USERNAME;
        private $SMS_PASSWORD;
        private $URL;
        private $BUSINESS_ID;
        private $API_KEY;
        private $SMS_METHOD; //sms_method refers to sms api url is old or not

        public function getSmsSettings($BUSINESS_ID = null)
        {
            if($BUSINESS_ID !== null){
                $settings = GymSetting::GetMerchantInfo($BUSINESS_ID);
            }else{
                if(auth()->guard('merchant')->user()->id == 1){
                    $settings = GymSetting::GetMerchantInfo(1);
                }else{
                    $settings = GymSetting::GetMerchantInfo(auth()->guard('merchant')->user()->detail_id);
                }
            }
            if (!is_null($settings)) {
                $this->URL          = $settings->sms_api_url;
                $this->API_KEY          = $settings->api_key;
                $this->SENDER_ID    = $settings->sms_sender_id;
                $this->SMS_USERNAME = $settings->sms_username;
                $this->SMS_PASSWORD = $settings->sms_password;
                $this->SMS_METHOD  = $settings->is_old;
            }
        }
    }
