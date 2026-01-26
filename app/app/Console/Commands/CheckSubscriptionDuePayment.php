<?php

namespace App\Console\Commands;

use App\Models\GymPurchase;
use App\Models\GymSetting;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckSubscriptionDuePayment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:due_payment';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check membership due payment';

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
        $gymSettings = GymSetting::whereNotNull('options')->where('sms_status', 'enabled')->cursor();
        foreach ($gymSettings as $setting){
            $options = json_decode($setting->options,true);

            $expireDays = $options['membership_due_pay_notify_days'] ?? 1;
            $date = Carbon::today()->addDays($expireDays);
            Log::channel(channel: 'sms_log')->info('Subscription Due Payment Cron Start of Business ID: '.$setting->detail_id.' at Date: '.Carbon::today());

            $paymentCollection = GymPurchase::has('client')->select('gym_clients.first_name','gym_clients.middle_name', 'gym_clients.last_name','gym_clients.mobile' ,'gym_clients.email',
                'gym_client_purchases.expires_on as expired_date', 'gym_memberships.title as membership','gym_client_purchases.next_payment_date as due_date',
                'gym_client_purchases.paid_amount', 'merchants.id as merch_id','common_details.title as company',
                'gym_client_purchases.client_id','gym_client_purchases.id as purchase_id')
                ->selectRaw('(amount_to_be_paid - paid_amount) as due_amount')
                ->leftJoin('gym_clients', 'gym_clients.id', '=', 'client_id')
                ->leftJoin('gym_memberships', 'gym_memberships.id', '=', 'membership_id')
                ->leftJoin('common_details', 'common_details.id', '=', 'gym_client_purchases.detail_id')
                ->leftJoin('merchants', 'merchants.detail_id', '=', 'gym_client_purchases.detail_id')
                ->where('gym_client_purchases.detail_id', $setting->detail_id)
                ->where('gym_client_purchases.next_payment_date',$date)
                ->groupBy('gym_client_purchases.id')
                ->cursor();

            if($options['membership_due_payment_status'] == 1 && $options['membership_due_pay_notify'] == 'sms'){
                $this->getExpireData($paymentCollection,$setting->detail_id,$setting->getMerchantID($setting->detail_id));
            }

        }
        return 0;

    }

    public function getExpireData($collection,$businessId,$merchantId){
        $result = processSmsWithCreditValidation(
            $collection,
            $businessId,
            $merchantId,
            'due_payment',
            'CheckSubscriptionDuePayment'
        );

        if (!$result['success']) {
            Log::channel('sms_log')->warning("Failed to process subscription due payment SMS for Business ID: {$businessId} - {$result['message']}");
        }

        return $result;

    }

}
