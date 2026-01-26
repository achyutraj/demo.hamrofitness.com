<?php

namespace App\Jobs;

use App\Models\CustomerSms;
use App\Traits\SmsSettingsTrait;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;

class BulkCustomerSms implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    use SmsSettingsTrait;

    protected $sms = [];
    protected $businessId = null;
    // Job configuration
    public $uniqueFor = 3600; // 1 hour
    public $tries = 3; // Number of retries
    public $maxExceptions = 3;
    public $backoff = [30, 60, 120]; // Retry delays in seconds
    public $timeout = 120; // 2 minutes

    private $uniqueId;

    public function __construct($sms, $businessId = null)
    {
        if ($businessId !== null) {
            $this->getSmsSettings($businessId);
        } else {
            $this->getSmsSettings();
        }
        $this->sms = $sms;
        $this->businessId = $businessId;
        $this->uniqueId = $this->generateUniqueId();
    }

    private function generateUniqueId()
    {
        if ($this->sms instanceof \Illuminate\Support\Collection) {
            $phones = $this->sms->pluck('phone')->unique()->sort()->values()->toArray();
        } else {
            $phones = collect($this->sms)->pluck('phone')->unique()->sort()->values()->toArray();
        }

        return $this->businessId . '-' . implode('-', $phones) . '-' . date('Y-m-d');
    }

    public function uniqueId()
    {
        return $this->uniqueId;
    }

    public function handle()
    {
        $results = $this->sms;

        foreach ($results as $data) {
            try {
                // Check if SMS was already sent (using Cache)
                $cacheKey = "sms_sent_{$data->id}_{$data->phone}_" . date('Y-m-d');
                if (Cache::has($cacheKey)) {
                    Log::channel('sms_log')->info("Skipping duplicate SMS to {$data->phone}");
                    continue;
                }

                // Rate limiting
                $limiterKey = "sms_rate_limit_{$this->businessId}";
                if (RateLimiter::tooManyAttempts($limiterKey, 100)) { // 100 SMS per minute
                    $seconds = RateLimiter::availableIn($limiterKey);
                    throw new \Exception("Rate limit exceeded. Try again in {$seconds} seconds.");
                }
                RateLimiter::hit($limiterKey, 60); // Keep rate limit for 60 seconds

                $sms = CustomerSms::find($data->id);
                if (!$sms) {
                    continue;
                }

                // Send SMS using separated logic
                $success = $this->sendSms($sms);

                if ($success) {
                    $sms->status = 1;
                    $sms->save();

                    // Cache successful send to prevent duplicates
                    Cache::put($cacheKey, true, now()->addDay());
                }

            } catch (\Exception $e) {
                Log::channel('sms_log')->error('CustomerSMS sending failed for ' . $data->phone, [
                    'line' => $e->getLine(),
                    'error_message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'attempt' => $this->attempts(),
                ]);

                // If we've hit max retries, mark as failed
                if ($this->attempts() >= $this->tries) {
                    if (isset($sms)) {
                        $sms->status = 2; // Failed status
                        $sms->response_message = $e->getMessage();
                        $sms->save();
                    }
                }

                throw $e; // Rethrow for retry handling
            }
        }
    }

    /**
     * Separated SMS sending logic
     */
    private function sendSms($sms)
    {
        $client = new Client();

        try {
            if ($this->SMS_METHOD == true) {
                $response = $this->sendOldMethod($client, $sms);
            } else {
                $response = $this->sendNewMethod($client, $sms);
            }

            $isSuccess = $response->getStatusCode() === 200;
            $responseBody = $response->getBody()->getContents();
            // Store response message regardless of success/failure
            $sms->response_message = $responseBody;
            $sms->save();
            return $isSuccess;

        } catch (\Exception $e) {
            Log::channel('sms_log')->error("SMS sending error: " . $e->getMessage());
            // Store error message
            $sms->response_message = $e->getMessage();
            $sms->save();
            throw $e;
        }
    }

    /**
     * Old SMS client method
     */
    private function sendOldMethod($client, $sms)
    {
        if (empty($this->URL)) {
            Log::channel('sms_log')->error("SMS URL is missing for sendOldMethod.");
            return false;
        }

        return $client->request('POST', $this->URL, [
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
    }

    /**
     * New SMS client method
     */
    private function sendNewMethod($client, $sms)
    {
        if (empty($this->URL)) {
            Log::channel('sms_log')->error("SMS URL is missing for sendNewMethod.");
            return false;
        }

        return $client->request('POST', $this->URL, [
            'form_params' => [
                'senderid' => $this->SENDER_ID,
                'key'      => $this->API_KEY,
                'responsetype' => 'json',
                'msg' => $sms->message,
                'contacts' => $sms->phone,
            ],
            'timeout' => 30,
            'connect_timeout' => 10
        ]);
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception)
    {
        Log::channel('sms_log')->error('SMS Job failed permanently', [
            'error' => $exception->getMessage(),
            'sms_ids' => collect($this->sms)->pluck('id'),
            'business_id' => $this->businessId
        ]);
    }
}
