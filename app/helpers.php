<?php
/*Global Function can be call anywhere, but if some changes are done then no page would be
shown. Add this file in composer.json file in order to run it all over project
*/

use App\Helpers\ADMSHelper;
use App\Models\CustomerSms;
use App\Models\Device;
use App\Models\GymClient;
use App\Models\GymMembership;
use App\Models\GymPurchase;
use App\Models\GymSetting;
use App\Models\RedeemPoint;
use App\Models\Template;
use Illuminate\Support\Facades\Log;

if (!function_exists('merchantInformation')) {
    function merchantInformation()
    {
        return auth()->guard('merchant')->user();
    }
}

if (!function_exists('progressStatus')) {
    function progressStatus($measurement){
        if($measurement == 0){
            return "<label class='btn btn-sm btn-info'> Equal</label>";
        }elseif ($measurement > 1){
        return "<label class='btn btn-sm btn-success'> Gain</label>";
        }else{
            return "<label class='btn btn-sm btn-danger'> Loss </label>";
        }
    }
}

if (!function_exists('weekends')) {
    function weekends()
        {
            return [
                'sunday' => 'Sunday',
                'monday' => 'Monday',
                'tuesday' => 'Tuesday',
                'wednesday' => 'Wednesday',
                'thursday' => 'Thursday',
                'friday' => 'Friday',
                'saturday' => 'Saturday',
            ];
        }
}

if (!function_exists('calculateTotalDays')) {
    function calculateTotalDays($startDate, $endDate)
    {
        return $startDate->diffInDays($endDate);
    }
}

if (!function_exists('calculateRedeemPoints')) {
    function calculateRedeemPoints($amount)
    {
        //we assume that NPR 1000 = 1 point and NPR 2000 = 2 points
        //also we assume NPR 1500 = 1 point and NPR 10000 = 10 points
        //we deduct last 3 digits number and get point from first two number in integer not in decimal
        // also NPR 100000 = 100 points and all amt above 1 lakh will be 100 points only
        $res = floor($amount / 1000);
        return $res;
    }
}

if (!function_exists('getActiveRedeemPoint')) {
    function getActiveRedeemPoint($businessId)
    {
        return RedeemPoint::active()->where('detail_id', $businessId)->first();
    }
}

if (!function_exists('getADMSAttendanceLog')) {
    function getADMSAttendanceLog($start_date, $end_date, $serial_num)
    {
        try {
            $data = ADMSHelper::GetAllAttendanceData($start_date, $end_date, $serial_num);

            if ($data === null) {
                Log::error("ADMSHelper::GetAllAttendanceData returned null for device: {$serial_num}");
                return null;
            }

            if (!isset($data['Data'])) {
                Log::error("ADMSHelper::GetAllAttendanceData returned invalid data structure for device: {$serial_num}");
                return null;
            }

            return $data['Data'];
        } catch (\Exception $e) {
            Log::error("Error in getADMSAttendanceLog for device {$serial_num}: " . $e->getMessage());
            return null;
        }
    }
}
if (!function_exists('getDeviceClientData')) {
    function getDeviceClientData($deviceId, $businessId)
    {
        try {
            $device = Device::where('detail_id', $businessId)->where('id', $deviceId)->first();

            if (!$device) {
                Log::error("Device not found for ID: {$deviceId}, business ID: {$businessId}");
                return collect([]);
            }

            return $device->clients()->pluck('client_id');
        } catch (\Exception $e) {
            Log::error("Error in getDeviceClientData for device ID {$deviceId}: " . $e->getMessage());
            return collect([]);
        }
    }
}

if (!function_exists('addRedeemPointToUser')) {
    function addRedeemPointToUser($purchaseId)
    {
        $purchase = GymPurchase::find($purchaseId);
        $membership = GymMembership::find($purchase->membership_id);
        //check membership is unlimited or not
        if($membership->duration_type != 'unlimited'){
            $activeOffer = RedeemPoint::active()->where('detail_id',$purchase->detail_id)->first();
            $client = GymClient::find($purchase->client_id);
            //check payer has referrer client and offer is active now
            if(!is_null($activeOffer) && $client->referred_client_id != null){
                $referredBy =  GymClient::find($client->referred_client_id);
                $referredBy->update([
                    'redeem_points' => $referredBy->redeem_points + calculateRedeemPoints($purchase->amount_to_be_paid)
                ]);
            }
        }
    }
}

if (!function_exists('listPaymentType')) {
    function listPaymentType(){
        $paymentSources = [
            'cash' => ['label' => 'Cash', 'icon' => 'money'],
            'phone_pay' => ['label' => 'Fonepay', 'icon' => 'money'],
            'esewa' => ['label' => 'Esewa', 'icon' => 'money'],
            'khalti' => ['label' => 'Khalti', 'icon' => 'money'],
            'ime_pay' => ['label' => 'Imepay', 'icon' => 'money'],
            'cheque' => ['label' => 'Cheque', 'icon' => 'cash'],
            'credit_card' => ['label' => 'Credit Card', 'icon' => 'credit-card'],
            'debit_card' => ['label' => 'Debit Card', 'icon' => 'cc-visa'],
            'net_banking' => ['label' => 'Net Banking', 'icon' => 'internet-explorer'],
            'other' => ['label' => 'Other', 'icon' => 'money'],
        ];
        return $paymentSources;
    }
}

if (!function_exists('getPaymentType')) {
    function getPaymentType($value){

        if ($value == 'cash') {
            return "<div class='font-dark'> Cash <i class='fa fa-money'></i> </div>";
        }
        if ($value == 'cheque') {
            return "<div class='font-dark'> Cheque <i class='fa fa-money'></i> </div>";
        }
        if ($value == 'esewa') {
            return "<div class='font-dark'> Esewa <i class='fa fa-money'></i> </div>";
        }
        if ($value == 'khalti') {
            return "<div class='font-dark'> Khalti <i class='fa fa-money'></i> </div>";
        }
        if ($value == 'phone_pay') {
            return "<div class='font-dark'> Fonepay <i class='fa fa-money'></i> </div>";
        }
        if ($value == 'ime_pay') {
            return "<div class='font-dark'> Imepay <i class='fa fa-money'></i> </div>";
        }
        if ($value == 'other') {
            return "<div class='font-dark'> Other <i class='fa fa-money'></i> </div>";
        }
        if ($value == 'credit_card') {
            return "<div class='font-dark'> Credit Card <i class='fa fa-credit-card'></i> </div>";
        }
        if ($value == 'debit_card') {
            return "<div class='font-dark'> Debit Card <i class='fa fa-cc-visa'></i> </div>  ";
        }
        if ($value == 'net_banking') {
            return "<div class='font-dark'> Net Banking <i class='fa fa-internet-explorer '></i> </div>";
        }else {
            return "";
        }
    }
}


if (!function_exists('getPaymentTypeForReport')) {
    function getPaymentTypeForReport($value){

        if ($value == 'cash') {
            return "Cash";
        }
        if ($value == 'cheque') {
            return "Cheque";
        }
        if ($value == 'esewa') {
            return "Esewa";
        }
        if ($value == 'khalti') {
            return "Khalti";
        }
        if ($value == 'phone_pay') {
            return "Fonepay";
        }
        if ($value == 'ime_pay') {
            return "Imepay";
        }
        if ($value == 'other') {
            return "Other";
        }
        if ($value == 'credit_card') {
            return "Credit Card";
        }
        if ($value == 'debit_card') {
            return "Debit Card  ";
        }
        if ($value == 'net_banking') {
            return "Net Banking  ";
        }else {
            return "";
        }
    }
}

/**
 * Decode JSON fields in a given model instance.
 *
 * @param  object  $model
 * @param  array  $fields
 * @return object
 */
if (!function_exists('decodeJsonFields')) {
    function decodeJsonFields($model, array $fields)
    {
        foreach ($fields as $field) {
            if (isset($model->$field)) {
                $model->$field = decodeJsonField($model->$field);
            }
        }
        return $model;
    }
}
/**
 * Decode a JSON-encoded field.
 *
 * @param  mixed  $field
 * @return array
 */
if (!function_exists('decodeJsonField')) {
    function decodeJsonField($field)
    {
        if (is_string($field)) {
            $decoded = json_decode($field, true);
            return is_array($decoded) ? $decoded : [];
        }
        return is_array($field) ? $field : [];
    }
}

if (!function_exists('getSMSCreditBalance')) {
    function getSMSCreditBalance($businessId){
        $setting = GymSetting::where('detail_id', $businessId)->first();

        if (!$setting) {
            return 0;
        }

        $type = $setting->is_old;
        $api_key = $setting->api_key;
        $credit = 0;

        if ($api_key == null) {
            return 0;
        }

        // Timeout context for file_get_contents
        $context = stream_context_create([
            'http' => [
                'timeout' => 10,       // seconds
                'ignore_errors' => true
            ]
        ]);

        try {
            if ($type) {
                // OLD API
                $api_url = "http://api.ininepal.com/api/getCredit/" . $api_key;

                $response = @file_get_contents($api_url, false, $context);

                if ($response !== false && is_numeric($response)) {
                    $credit = (float) $response;
                }
            } else {
                // NEW API
                $api_url = "https://user.kantipursms.com/miscapi/" . $api_key . "/getBalance/true/";

                $response = @file_get_contents($api_url, false, $context);

                if ($response !== false) {
                    $credit_balance = json_decode($response, true);

                    if (json_last_error() === JSON_ERROR_NONE && isset($credit_balance[0]['BALANCE'])) {
                        $credit = (float) $credit_balance[0]['BALANCE'];
                    }
                }
            }
        } catch (\Throwable $e) {
            Log::error("SMS Balance API Error: " . $e->getMessage());
            return 0; // return safe value
        }

        return $credit;
    }
}


/**
 * Process SMS with credit validation and chunking
 *
 * @param \Illuminate\Support\Collection $collection
 * @param int $businessId
 * @param int $merchantId
 * @param string $templateType
 * @param string $commandName
 * @return array
 */
if (!function_exists('processSmsWithCreditValidation')) {
    function processSmsWithCreditValidation($collection, $businessId, $merchantId, $templateType, $commandName = 'Unknown')
    {
        // Get SMS credit balance for this business
        $creditBalance = getSMSCreditBalance($businessId);
        $totalRecipients = $collection->count();

        Log::channel('sms_log')->info("SMS Credit Check - Command: {$commandName}, Business ID: {$businessId}, Available Credits: {$creditBalance}, Required: {$totalRecipients}");

        // Check if we have sufficient credits
        if ($creditBalance <= 0) {
            Log::channel('sms_log')->warning("SMS Credit Check Failed - Command: {$commandName}, Business ID: {$businessId}, Available Credits: {$creditBalance}, Required: {$totalRecipients} - No credits available");
            return [
                'success' => false,
                'processed' => 0,
                'skipped' => $totalRecipients,
                'message' => 'No SMS credits available'
            ];
        }

        $temp = Template::businessTemplateMessage($businessId, $templateType);
        $chunk_data = [];
        $processedCount = 0;
        $remainingCredits = $creditBalance;

        foreach ($collection as $item) {
            // Check if we still have credits before processing each SMS
            if ($remainingCredits <= 0) {
                Log::channel('sms_log')->warning("SMS Credit Exhausted - Command: {$commandName}, Business ID: {$businessId}, Processed: {$processedCount}, Skipped: " . ($totalRecipients - $processedCount) . ", Available Credits: {$creditBalance}");
                break;
            }

            $message = $temp->renderSMS($temp->message, $item->toArray());
            $chunk_data[] = [
                'message' => $message,
                'status' => 0,
                'phone' => $item->mobile,
                'recipient_id' => $item->client_id ?? $item->c_id,
                'sender_id' => $item->merch_id,
                 'sent_from' => 'auto'
            ];
            $processedCount++;
            $remainingCredits--;
        }

        // Log the processing summary
        if ($processedCount < $totalRecipients) {
            Log::channel('sms_log')->warning("SMS Credit Insufficient - Command: {$commandName}, Business ID: {$businessId}, Processed: {$processedCount}, Skipped: " . ($totalRecipients - $processedCount) . ", Available Credits: {$creditBalance}");
        } else {
            Log::channel('sms_log')->info("SMS Credit Sufficient - Command: {$commandName}, Business ID: {$businessId}, Processed: {$processedCount}, Available Credits: {$creditBalance}");
        }

        // Only proceed if we have data to process
        if (empty($chunk_data)) {
            Log::channel('sms_log')->warning("No SMS data to process for Command: {$commandName}, Business ID: {$businessId} - Insufficient credits");
            return [
                'success' => false,
                'processed' => 0,
                'skipped' => $totalRecipients,
                'message' => 'Insufficient credits'
            ];
        }

        // Insert SMS records in chunks
        $chunks = array_chunk($chunk_data, 100);
        foreach ($chunks as $chunk) {
            CustomerSms::insert($chunk);
        }

        // Dispatch jobs for the processed SMS records
        $sms = CustomerSms::where('status', 0)
            ->where('sender_id', $merchantId)
            ->whereDate('created_at', today())
            ->get();

        $sms->chunk(100)->each(function ($sms) use ($businessId) {
            \App\Jobs\BulkCustomerSms::dispatch($sms, $businessId)
                ->delay(now()->addSeconds(5))
                ->onQueue('bulk_customer_sms');
        });

        Log::channel('sms_log')->info("SMS Jobs Dispatched - Command: {$commandName}, Business ID: {$businessId}, Jobs Count: " . $sms->count());

        return [
            'success' => true,
            'processed' => $processedCount,
            'skipped' => $totalRecipients - $processedCount,
            'message' => $processedCount < $totalRecipients ? 'Partial processing due to insufficient credits' : 'All SMS processed successfully'
        ];
    }
}

/**
 * Process SMS with credit validation and chunking - TEST VERSION
 * This version uses TestBulkCustomerSms job for testing purposes
 *
 * @param \Illuminate\Support\Collection $collection
 * @param int $businessId
 * @param int $merchantId
 * @param string $templateType
 * @param string $commandName
 * @return array
 */
if (!function_exists('processSmsWithCreditValidationTest')) {
    function processSmsWithCreditValidationTest($collection, $businessId, $merchantId, $templateType, $commandName = 'Unknown')
    {
        // Get SMS credit balance for this business - Different amounts for testing
        $creditBalance = 50; // Default for testing

        // Set different credit amounts for different business IDs for testing
        switch($businessId) {
            case 3:
                $creditBalance = 30; // Business ID 3 gets 30 credits
                break;
            case 7:
                $creditBalance = 75; // Business ID 7 gets 75 credits
                break;
            case 9:
                $creditBalance = 50; // Business ID 9 gets 50 credits
                break;
            default:
                $creditBalance = 25; // Other business IDs get 25 credits
                break;
        }
        $totalRecipients = $collection->count();

        Log::channel('sms_log')->info("TEST SMS Credit Check - Command: {$commandName}, Business ID: {$businessId}, Available Credits: {$creditBalance}, Required: {$totalRecipients}");

        // Check if we have sufficient credits
        if ($creditBalance <= 0) {
            Log::channel('sms_log')->warning("TEST SMS Credit Check Failed - Command: {$commandName}, Business ID: {$businessId}, Available Credits: {$creditBalance}, Required: {$totalRecipients} - No credits available");
            return [
                'success' => false,
                'processed' => 0,
                'skipped' => $totalRecipients,
                'message' => 'No SMS credits available'
            ];
        }

        $temp = Template::businessTemplateMessage(1, $templateType);
        $chunk_data = [];
        $processedCount = 0;
        $remainingCredits = $creditBalance;

        foreach ($collection as $item) {
            // Check if we still have credits before processing each SMS
            if ($remainingCredits <= 0) {
                Log::channel('sms_log')->warning("TEST SMS Credit Exhausted - Command: {$commandName}, Business ID: {$businessId}, Processed: {$processedCount}, Skipped: " . ($totalRecipients - $processedCount) . ", Available Credits: {$creditBalance}");
                break;
            }

            $message = $temp->renderSMS($temp->message, $item->toArray());
            $chunk_data[] = [
                'message' => $message,
                'status' => 0,
                'phone' => $item->mobile,
                'recipient_id' => $item->client_id ?? $item->c_id,
                'sender_id' => $item->merch_id,
            ];
            $processedCount++;
            $remainingCredits--;
        }

        // Log the processing summary
        if ($processedCount < $totalRecipients) {
            Log::channel('sms_log')->warning("TEST SMS Credit Insufficient - Command: {$commandName}, Business ID: {$businessId}, Processed: {$processedCount}, Skipped: " . ($totalRecipients - $processedCount) . ", Available Credits: {$creditBalance}");
        } else {
            Log::channel('sms_log')->info("TEST SMS Credit Sufficient - Command: {$commandName}, Business ID: {$businessId}, Processed: {$processedCount}, Available Credits: {$creditBalance}");
        }

        // Only proceed if we have data to process
        if (empty($chunk_data)) {
            Log::channel('sms_log')->warning("TEST: No SMS data to process for Command: {$commandName}, Business ID: {$businessId} - Insufficient credits");
            return [
                'success' => false,
                'processed' => 0,
                'skipped' => $totalRecipients,
                'message' => 'Insufficient credits'
            ];
        }

        // Insert SMS records in chunks
        $chunks = array_chunk($chunk_data, 100);
        foreach ($chunks as $chunk) {
            CustomerSms::insert($chunk);
        }

        // Dispatch TEST jobs for the processed SMS records
        $sms = CustomerSms::where('status', 0)
            ->where('sender_id', $merchantId)
            ->whereDate('created_at', today())
            ->get();

        $sms->chunk(100)->each(function ($sms) use ($businessId) {
            \App\Jobs\TestBulkCustomerSms::dispatch($sms, $businessId)
                ->delay(now()->addSeconds(5))
                ->onQueue('test_bulk_customer_sms');
        });

        Log::channel('sms_log')->info("TEST SMS Jobs Dispatched - Command: {$commandName}, Business ID: {$businessId}, Jobs Count: " . $sms->count());

        return [
            'success' => true,
            'processed' => $processedCount,
            'skipped' => $totalRecipients - $processedCount,
            'message' => $processedCount < $totalRecipients ? 'Partial processing due to insufficient credits' : 'All SMS processed successfully (TEST MODE)'
        ];
    }
}

if (!function_exists('getSmsTemplate')) {
    function getSmsTemplate($businessId){
        $templates = [
            [
                'detail_id'    => $businessId,
                'name'          => 'Customer Registration',
                'type'          => 'registration',
                'message'       => 'Dear {first_name},
                                  Welcome! Thank you for registering with Us.
                                  {company}',
                'status'        => true,
            ],[
                'detail_id'    => $businessId,
                'name'          => 'Customer Payment',
                'type'          => 'payment',
                'message'       => 'Dear {first_name},
                                    Your payment of NPR {paid_amount} received for {membership}.
                                   {company}',
                'status'        => true,
            ],[
                'detail_id'    => $businessId,
                'name'          => 'Membership Due',
                'type'          => 'due',
                'message'       => 'Hi {first_name},
                                    Your {membership} will expire on {expire_date}. Please Renew.
                                  {company}',
                'status'        => true,
            ],[
                'detail_id'    => $businessId,
                'name'          => 'Membership Due Payment Date',
                'type'          => 'due_payment',
                'message'       => 'Hi {first_name},
                                    Your {membership} payment of NPR {due_amount} is due.
                                    {company}',
                'status'        => true,
            ],
            [
                'detail_id'    => $businessId,
                'name'          => 'Membership Extend',
                'type'          => 'extend',
                'message'       => 'Hi {first_name},
                                    Your {membership} has been extended upto {day} days.
                                    Thank You!
                                  {company}',
                'status'        => true,
            ],[
                'detail_id'    => $businessId,
                'name'          => 'Membership Renew',
                'type'          => 'renew',
                'message'       => 'Hi {first_name},
                                    Your {membership} membership has been renewed.
                                    Thank You!
                                  {company}',
                'status'        => true,
            ],[
                'detail_id'    => $businessId,
                'name'          => 'Membership Expire',
                'type'          => 'expire',
                'message'       => 'Hi {first_name} ,
                                  Your {membership} will expire on {expire_date}.
                                  {company}',
                'status'        => true,
            ],
            [
                'detail_id'    => $businessId,
                'name'          => 'Customer Birthday',
                'type'          => 'birthday',
                'message'       => 'Dear {first_name},
                                    Happy Birthday! Wishing you a wonderful year ahead.
                                    {company}',
                'status'        => true,
            ],[
                'detail_id'    => $businessId,
                'name'          => 'Customer Anniversary',
                'type'          => 'anniversary',
                'message'       => 'Dear {first_name},
                                    Happy anniversary! We are grateful for your continued support.
                                    {company}',
                'status'        => true,
            ],
            [
                'detail_id'    => $businessId,
                'name'          => 'Locker Due',
                'type'          => 'locker_due',
                'message'       => 'Dear {first_name},
                                    Your Locker No. {locker_number} will expire on {expire_date}.
                                  Thank You!
                                  {company}',
                'status'        => true,
            ],
            [
                'detail_id'    => $businessId,
                'name'          => 'Locker Expire',
                'type'          => 'locker_expire',
                'message'       => 'Dear {first_name} ,
                                   Your locker no. {locker_number} has expired.
                                  {company}',
                'status'        => true,
            ],
        ];

        foreach ($templates as &$template) {
            $template['message'] = preg_replace("/[ \t]+/", " ", $template['message']); // remove extra spaces
            $template['message'] = preg_replace("/\n\s+/", "\n", $template['message']); // remove indent after newline
            $template['message'] = trim($template['message']); // remove leading/trailing whitespace
        }

        return $templates;
    }
}

if(!function_exists('cleanUpSmsTemplates')){
    function cleanUpSmsTemplates()
    {
        // get all templates for that branch
        $templates = Template::get();

        foreach ($templates as $template) {
            $cleanMessage = preg_replace("/[ \t]+/", " ", $template->message); // collapse multiple spaces
            $cleanMessage = preg_replace("/\n\s+/", "\n", $cleanMessage);      // remove indent after line breaks
            $cleanMessage = trim($cleanMessage);  // trim start & end

            // update only if message is different
            Template::where('id', $template->id)
                ->update(['message' => $cleanMessage]);
        }

        return true;
    }
}
