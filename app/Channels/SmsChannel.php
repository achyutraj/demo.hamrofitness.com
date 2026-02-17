<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\DB;

class SmsChannel
{
    public function send($notifiable, Notification $notification)
    {
        $data = $notification->toSms($notifiable);

        // Call your existing global helper function
        $result = processSmsWithCreditValidation(
            $data['collection'],
            $data['businessId'],
            $data['merchantId'],
            'expire',
            'CheckSubscriptionExpire'
        );

        // Audit Logging
        DB::table('sms_logs')->insert([
            'detail_id'    => $data['businessId'],
            'client_id'    => $notifiable->id,
            'phone_number' => $notifiable->mobile,
            'status'       => $result['success'] ? 'sent' : 'failed',
            'error_message'=> $result['success'] ? null : ($result['message'] ?? 'Unknown Error'),
            'created_at'   => now(),
            'updated_at'   => now(),
            // 'msg_type'     => 'auto/manual/semiauto/insufficient credit'
        ]);

        return $result;
    }
}
