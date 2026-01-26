<?php

namespace App\Jobs;

use App\Models\CustomerSms;
use App\Traits\SmsSettingsTrait;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendCustomerSms implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels, Queueable, SmsSettingsTrait;

    protected $sms;

    // Job configuration
    public $tries = 3;
    public $backoff = [30, 60, 120];
    public $timeout = 120;
    public $retryUntil = 3600; // 1 hour from now

    public function __construct(CustomerSms $sms)
    {
        $this->getSmsSettings();
        $this->sms = $sms;
        $this->handle();
    }

    public function handle()
    {
        if (empty($this->URL)) {
            Log::channel('sms_log')->error("SMS URL is missing.");
            $this->sms->response_message = "SMS URL is missing.";
            $this->sms->status = 2; // Failed status
            $this->sms->save();
            return false;
        }
        $sms = $this->sms;
        try {
            $client = new Client(); // initialize guzzle client
            $response = null;

            //for old sms client
            if ($this->SMS_METHOD == true) {
                $response = $client->request('POST', $this->URL, [
                    'form_params' => [
                        'sender' => $this->SENDER_ID,
                        'username' => $this->SMS_USERNAME,
                        'password' => $this->SMS_PASSWORD,
                        'message' => $sms->message,
                        'destination' => $sms->phone,
                    ],
                    'timeout' => 30,
                    'connect_timeout' => 10
                ]);
            } else {
                //for new sms client
                $response = $client->request('POST', $this->URL, [
                    'form_params' => [
                        'senderid' => $this->SENDER_ID,
                        'key' => $this->API_KEY,
                        'responsetype' => 'json',
                        'msg' => $sms->message,
                        'contacts' => $sms->phone,
                    ],
                    'timeout' => 30,
                    'connect_timeout' => 10
                ]);
            }

            // Store response message
            $responseBody = $response->getBody()->getContents();
            $sms->response_message = $responseBody;

            // Check if response is successful
            if ($response->getStatusCode() === 200) {
                $sms->status = 1; // Success status
            } else {
                $sms->status = 2; // Failed status
                $sms->response_message = "HTTP Error: " . $response->getStatusCode() . " - " . $responseBody;
            }

            $sms->save();

        } catch (\Exception $e) {
            Log::channel('sms_log')->error('CustomerSMS sending failed for ' . $sms->phone, [
                'line' => $e->getLine(),
                'error_message' => $e->getMessage(),
                'file' => $e->getFile(),
                'attempt' => $this->attempts(),
            ]);

            // Store error message and mark as failed
            $sms->response_message = $e->getMessage();
            $sms->status = 2; // Failed status
            $sms->save();
        }

    }
}
