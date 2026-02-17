<?php

namespace App\Console\Commands;

use App\Models\GymPurchase;
use App\Models\GymSetting;
use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CheckSubscriptionExpire extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:expire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check Expire Membership Subscription';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $gymSettings = GymSetting::whereNotNull('options')->where('sms_status', 'enabled')->get();
        foreach ($gymSettings as $setting) {
            $date = Carbon::today('Asia/Kathmandu')->toDateString();

            Log::channel('sms_log')->info('Subscription Expire Cron Start of Business ID: '.$setting->detail_id.' at Date: '.$date);

            $options = json_decode($setting->options,true);
            if($options['membership_expire_status'] == 1 && $options['membership_expire_notify'] == 'sms'){

                $expireSubscription = GymPurchase::has('client')->select('gym_clients.first_name','gym_clients.middle_name', 'gym_clients.last_name','gym_clients.mobile' ,'gym_clients.email',
                    'gym_client_purchases.expires_on as expire_date', 'gym_memberships.title as membership','gym_client_purchases.next_payment_date as due_date',
                    'gym_client_purchases.paid_amount', 'merchants.id as merch_id','common_details.title as company',
                    'gym_client_purchases.client_id','gym_client_purchases.id as purchase_id')
                    ->selectRaw('(amount_to_be_paid - paid_amount) as due_amount')
                    ->leftJoin('gym_clients', 'gym_clients.id', '=', 'client_id')
                    ->leftJoin('gym_memberships', 'gym_memberships.id', '=', 'membership_id')
                    ->leftJoin('common_details', 'common_details.id', '=', 'gym_client_purchases.detail_id')
                    ->leftJoin('merchants', 'merchants.detail_id', '=', 'gym_client_purchases.detail_id')
                    ->where('gym_client_purchases.detail_id', $setting->detail_id)
                    ->where('gym_client_purchases.expires_on',$date)
                    ->groupBy('gym_client_purchases.id')
                    ->cursor();

                    $this->getExpireData($expireSubscription,$setting->detail_id,$setting->getMerchantID($setting->detail_id));
            }

        }
        return 0;
    }

    public function getExpireData($collection,$businessId,$merchantId){
        $result = processSmsWithCreditValidation(
            $collection,
            $businessId,
            $merchantId,
            'expire',
            'CheckSubscriptionExpire'
        );

        if (!$result['success']) {
            Log::channel('sms_log')->warning("Failed to process subscription expire SMS for Business ID: {$businessId} - {$result['message']}");
        }

        return $result;

    }
}
