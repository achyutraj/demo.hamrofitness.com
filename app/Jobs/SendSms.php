<?php
/**
 * Created by PhpStorm.
 * User: dexter
 * Date: 2/8/2019
 * Time: 12:38 PM
 */

namespace App\Jobs;

use App\Classes\Reply;
use App\Models\Sms;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Traits\SmsSettingsTrait;

class SendSms implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels,Queueable, SmsSettingsTrait;

    protected $sms;

    public function __construct(Sms $sms)
    {
        $this->sms = $sms;
        $this->getSmsSettings();
    }

    public function handle()
    {
        $sms = $this->sms;
        try {
            $client = new Client(); // initialize guzzle client
            $client->request('POST', $this->URL, [
                'form_params' => [
                    'sender'      => $this->SENDER_ID,
                    'username'    => $this->SMS_USERNAME,
                    'password'    => $this->SMS_PASSWORD,
                    'message'     => $sms->message,
                    'destination' => $sms->phone,
                ]
            ]);
            $sms->status = 1;
            $sms->save();
        } catch (\Exception $e) {
            session()->flash('failed', $e->getMessage());
            return Reply::success('SMS sending failed.');
        }
    }
}
