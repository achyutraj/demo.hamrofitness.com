<?php

namespace App\Jobs;

use App\Classes\Reply;
use App\Models\AdminSms;
use App\Traits\SmsSettingsTrait;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Log;

class SendAdminSms implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels, Queueable, SmsSettingsTrait;

    protected $sms;

    // Job configuration
    public $tries = 3;
    public $backoff = [30, 60, 120];
    public $timeout = 120;
    public $retryUntil = 3600; // 1 hour from now

    public function __construct(AdminSms $sms)
    {
        $this->getSmsSettings();  // Initialize SMS settings
        $this->sms = $sms;        // Set the SMS model instance
    }

    public function handle()
    {
        try {
            if (empty($this->URL)) {
                Log::channel('sms_log')->error("SMS URL is missing.");
                return false;
            }

            $client = new Client();  // Initialize Guzzle HTTP client
            $requestData = $this->buildRequestData(); // Build request data

            // Send request based on the method (old or new SMS client)
            $client->post($this->URL, ['form_params' => $requestData]);

            // Update SMS status to sent
            $this->sms->status = 1;
            $this->sms->save();

        } catch (\Exception $e) {
            // Log error with mobile number for clarity
            Log::error('Admin SMS sending failed for ' . $this->sms->phone, [
                'line' => $e->getLine(),
                'error_message' => $e->getMessage(),
                'file' => $e->getFile(),
            ]);

            session()->flash('failed', $e->getMessage());
            return Reply::error('Admin SMS sending failed.');
        }
    }

    /**
     * Build request data for SMS sending.
     *
     * @return array
     */
    private function buildRequestData()
    {
        // Old SMS client
        if ($this->SMS_METHOD) {
            return [
                'sender'      => $this->SENDER_ID,
                'username'    => $this->SMS_USERNAME,
                'password'    => $this->SMS_PASSWORD,
                'message'     => $this->sms->message,
                'destination' => $this->sms->phone,
            ];
        }

        // New SMS client
        return [
            'senderid'      => $this->SENDER_ID,
            'key'      => $this->API_KEY,
            'responsetype' => 'json',
            'msg'         => $this->sms->message,
            'contacts'    => $this->sms->phone,
        ];
    }
}
