<?php

namespace App\Jobs;

use App\Classes\Reply;
use App\Models\AdminSms;
use App\Traits\SmsSettingsTrait;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class BulkAdminSms implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    use SmsSettingsTrait;

    protected $sms = [];

    // Job configuration
    public $tries = 3;
    public $backoff = [30, 60, 120];
    public $timeout = 120;
    public $retryUntil = 3600; // 1 hour from now

    /**
     * Create a new job instance.
     *
     * @param array $sms
     */
    public function __construct($sms)
    {
        $this->getSmsSettings();  // Load SMS settings
        $this->sms = $sms;        // Set SMS data
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (empty($this->URL)) {
            Log::channel('sms_log')->error("SMS URL is missing.");
            return null;
        }
        $client = new Client(); // Initialize Guzzle client

        foreach ($this->sms as $data) {
            try {
                $sms = AdminSms::find($data->id);
                if (!$sms) {
                    Log::warning("Admin SMS with ID {$data->id} not found.");
                    continue;
                }

                // Old SMS client
                if ($this->SMS_METHOD) {
                    $response = $client->post($this->URL, [
                        'form_params' => [
                            'sender'      => $this->SENDER_ID,
                            'username'    => $this->SMS_USERNAME,
                            'password'    => $this->SMS_PASSWORD,
                            'message'     => $sms->message,
                            'destination' => $sms->phone,
                        ],
                    ]);
                }
                // New SMS client
                else {
                    $response = $client->post($this->URL, [
                        'form_params' => [
                            'senderid'    => $this->SENDER_ID,
                            'key'    => $this->API_KEY,
                            'responsetype'        => 'json',
                            'msg'         => $sms->message,
                            'contacts'    => $sms->phone,
                        ],
                    ]);
                }

                // Check if the response is successful
                if ($response->getStatusCode() === 200) {
                    $sms->status = 1;  // Mark SMS as sent
                    $sms->save();
                } else {
                    Log::error("Failed to send AdminSMS. Response code: " . $response->getStatusCode());
                }
            } catch (\Exception $e) {
                // Log error details
                Log::error('AdminSMS sending failed for ' . $data->phone, [
                    'line' => $e->getLine(),
                    'error_message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'attempt' => $this->attempts(),
                ]);

                // If we've hit max retries, mark as failed
                if ($this->attempts() >= $this->tries) {
                    if (isset($sms)) {
                        $sms->status = 2; // Failed status
                        $sms->save();
                    }
                }

                throw $e; // Rethrow for retry handling
            }
        }
    }
}
