<?php

namespace App\Jobs;

use App\Models\CustomerSms;
use App\Traits\SmsSettingsTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;

class TestBulkCustomerSms implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    use SmsSettingsTrait;

    protected $sms = [];
    protected $businessId = null;

    // Job configuration - NO RETRIES for testing
    public $uniqueFor = 3600; // 1 hour
    public $tries = 1; // Only try once for testing
    public $maxExceptions = 1;
    public $backoff = [30]; // Single retry delay
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

        return 'test-' . $this->businessId . '-' . implode('-', $phones) . '-' . date('Y-m-d');
    }

    public function uniqueId()
    {
        return $this->uniqueId;
    }

    public function handle()
    {
        $results = $this->sms;
        $totalSms = count($results);
        $successCount = 0;
        $failureCount = 0;

        Log::channel('sms_log')->info("TEST SMS JOB STARTED - Business ID: {$this->businessId}, Total SMS: {$totalSms}");

        foreach ($results as $data) {
            try {
                // Check if SMS was already sent (using Cache)
                $cacheKey = "test_sms_sent_{$data->id}_{$data->phone}_" . date('Y-m-d');
                if (Cache::has($cacheKey)) {
                    Log::channel('sms_log')->info("TEST: Skipping duplicate SMS to {$data->phone}");
                    continue;
                }

                // Rate limiting for testing
                // $limiterKey = "test_sms_rate_limit_{$this->businessId}";
                // if (RateLimiter::tooManyAttempts($limiterKey, 100)) { // 100 SMS per minute
                //     $seconds = RateLimiter::availableIn($limiterKey);
                //     throw new \Exception("TEST: Rate limit exceeded. Try again in {$seconds} seconds.");
                // }
                // RateLimiter::hit($limiterKey, 60); // Keep rate limit for 60 seconds

                $sms = CustomerSms::find($data->id);
                if (!$sms) {
                    Log::channel('sms_log')->warning("TEST: CustomerSMS with ID {$data->id} not found");
                    continue;
                }

                // Simulate SMS sending with random success/failure for testing
                $success = $this->simulateSmsSending($sms);

                if ($success) {
                    $sms->status = 1; // Success status
                    $sms->response_message = "TEST: SMS sent successfully (simulated)";
                    $sms->save();
                    $successCount++;

                    // Cache successful send to prevent duplicates
                    Cache::put($cacheKey, true, now()->addDay());

                    // Log::channel('sms_log')->info("TEST: SMS sent successfully to {$data->phone}");
                } else {
                    $sms->status = 2; // Failed status
                    $sms->response_message = "TEST: SMS sending failed (simulated)";
                    $sms->save();
                    $failureCount++;

                    Log::channel('sms_log')->warning("TEST: SMS failed to send to {$data->phone}");
                }

            } catch (\Exception $e) {
                Log::channel('sms_log')->error('TEST: CustomerSMS sending failed for ' . $data->phone, [
                    'line' => $e->getLine(),
                    'error_message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'attempt' => $this->attempts(),
                ]);

                $failureCount++;

                // Mark as failed
                if (isset($sms)) {
                    $sms->status = 2; // Failed status
                    $sms->response_message = "TEST: " . $e->getMessage();
                    $sms->save();
                }

                // Don't rethrow for testing - just log and continue
                Log::channel('sms_log')->warning("TEST: Continuing with next SMS after error: " . $e->getMessage());
            }
        }

        Log::channel('sms_log')->info("TEST SMS JOB COMPLETED - Business ID: {$this->businessId}, Success: {$successCount}, Failed: {$failureCount}, Total: {$totalSms}");
    }

    /**
     * Simulate SMS sending with random success/failure for testing
     */
    private function simulateSmsSending($sms)
    {
        // Simulate different scenarios for testing
        $random = mt_rand(1, 100);

        // 80% success rate for testing
        if ($random <= 80) {
            // Simulate successful SMS sending
            // Log::channel('sms_log')->info("TEST: Simulating successful SMS to {$sms->phone}");
            return true;
        } else {
            // Simulate failed SMS sending
            Log::channel('sms_log')->warning("TEST: Simulating failed SMS to {$sms->phone}");
            return false;
        }
    }

    /**
     * Handle job failure for testing
     */
    public function failed(\Throwable $exception)
    {
        Log::channel('sms_log')->error("TEST SMS JOB FAILED - Business ID: {$this->businessId}", [
            'error' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString()
        ]);
    }
}
