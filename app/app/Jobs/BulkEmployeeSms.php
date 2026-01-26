<?php

namespace App\Jobs;

use App\Models\EmployeeSms;
use App\Traits\SmsSettingsTrait;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class BulkEmployeeSms implements ShouldQueue
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
     * @return void
     */
    public function __construct($sms)
    {
        $this->getSmsSettings();
        $this->sms = $sms;
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
        $results = $this->sms;

        foreach($results as $data){
            try {
                $sms = EmployeeSms::find($data->id);
                if (!$sms) {
                    Log::warning("Employee SMS with ID {$data->id} not found.");
                    continue;
                }
                $client = new Client(); // initialize guzzle client
                 //for old sms client
                 if($this->SMS_METHOD == true){
                    $response = $client->request('POST', $this->URL, [
                        'form_params' => [
                            'sender'      => $this->SENDER_ID,
                            'username'    => $this->SMS_USERNAME,
                            'password'    => $this->SMS_PASSWORD,
                            'message'     => $sms->message,
                            'destination' => $sms->phone,
                        ]
                    ]);
                }else{
                    //for new sms client
                    $response = $client->request('POST', $this->URL, [
                        'form_params' => [
                            'senderid'      => $this->SENDER_ID,
                            'key'      => $this->API_KEY,
                            'responsetype' => 'json',
                            'msg'     => $sms->message,
                            'contacts' => $sms->phone,
                        ]
                    ]);
                }
                 // Check if the response is successful
                 if ($response->getStatusCode() === 200) {
                    $sms->status = 1;  // Mark SMS as sent
                    $sms->save();
                } else {
                    Log::error("Failed to send EmployeeSMS to {$sms->phone}. Response code: " . $response->getStatusCode());
                }
            } catch (\Exception $e) {
                Log::error('EmployeeSMS sending failed for ' . $data->phone, [
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
