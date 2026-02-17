<?php

namespace App\Console\Commands;

use App\Models\GymClient;
use App\Models\GymSetting;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckCustomerBirthDay extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:birthday';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send birthday wishes to customer';

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
            $month = Carbon::today()->month;
            $day = Carbon::today()->day;
            $date = Carbon::today();

            Log::channel('sms_log')->info('BirthDay Cron Start of Business ID: '.$setting->detail_id.' at Date: '.$date);
            $options = json_decode($setting->options,true);

            if($options['customer_birth_status'] == 1 && $options['customer_birthday_notify'] == 'sms'){

                $customers =  GymClient::join('business_customers', 'business_customers.customer_id', '=', 'gym_clients.id')
                    ->join('common_details', 'common_details.id', '=', 'business_customers.detail_id')
                    ->join('merchants', 'merchants.detail_id', '=', 'business_customers.detail_id')
                    ->select('common_details.title as company','gym_clients.id as c_id','gym_clients.first_name','gym_clients.dob as birth_date', 'gym_clients.email', 'gym_clients.mobile', 'gym_clients.middle_name', 'gym_clients.last_name',
                        'merchants.id as merch_id')
                    ->whereMonth('gym_clients.dob', $month)
                    ->whereDay('gym_clients.dob', $day)
                    ->where('gym_clients.is_client','yes')
                    ->where('business_customers.detail_id',  $setting->detail_id)
                    ->groupBy('gym_clients.id')
                    ->cursor();

                $this->getCustomerData($customers,$setting->detail_id,$setting->getMerchantID($setting->detail_id));
            }

        }

        return 0;
    }

    public function getCustomerData($customers,$businessId,$merchantId) {
        $result = processSmsWithCreditValidation(
            $customers,
            $businessId,
            $merchantId,
            'birthday',
            'CheckCustomerBirthDay'
        );

        if (!$result['success']) {
            Log::channel('sms_log')->warning("Failed to process birthday SMS for Business ID: {$businessId} - {$result['message']}");
        }

        return $result;
    }
}
