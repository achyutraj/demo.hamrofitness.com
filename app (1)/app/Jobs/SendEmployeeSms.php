<?php

namespace App\Jobs;

use App\Classes\Reply;
use App\Models\EmployeeSms;
use App\Traits\SmsSettingsTrait;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendEmployeeSms implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels, Queueable, SmsSettingsTrait;

    protected $sms;

    // Job configuration
    public $tries = 3;
    public $backoff = [30, 60, 120];
    public $timeout = 120;
    public $retryUntil = 3600; // 1 hour from now

    public function __construct(EmployeeSms $sms)
    {
        $this->getSmsSettings();
        $this->sms = $sms;
        $this->handle();
    }

    public function handle()
    {
        if (empty($this->URL)) {
            Log::channel('sms_log')->error("SMS URL is missing.");
            return false;
        }

        $sms = $this->sms;
        try {
            $client = new Client(); // initialize guzzle client
            //for old sms client
            if ($this->SMS_METHOD == true) {
                $client->request('POST', $this->URL, [
                    'form_params' => [
                        'sender' => $this->SENDER_ID,
                        'username' => $this->SMS_USERNAME,
                        'password' => $this->SMS_PASSWORD,
                        'message' => $sms->message,
                        'destination' => $sms->phone,
                    ]
                ]);
            } else {
                //for new sms client
                $client->request('POST', $this->URL, [
                    'form_params' => [
                        'senderid' => $this->SENDER_ID,
                        'key' => $this->API_KEY,
                        'responsetype' => 'json',
                        'msg' => $sms->message,
                        'contacts' => $sms->phone,
                    ]
                ]);
            }
            $sms->status = 1;
            $sms->save();
        } catch (\Exception $e) {
            session()->flash('failed', $e->getMessage());
            return Reply::success('SMS sending failed.');
        }

    }
}
