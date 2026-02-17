<?php

namespace App\Console\Commands;

use App\Models\GymSetting;
use App\Models\LockerReservation;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckLockerDue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:locker_due';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check locker due';

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
        foreach ($gymSettings as $setting){
            $options = json_decode($setting->options,true);

            $expireDays = $options['locker_due_notify_days'] ?? 1;
            $date = Carbon::today()->addDays($expireDays);
            Log::channel('sms_log')->info('Locker Due Cron Start of Business ID: '.$setting->detail_id.' at Date: '.$date);

            $dueCollection = LockerReservation::has('client')->select('gym_clients.first_name','gym_clients.middle_name', 'gym_clients.last_name','gym_clients.mobile' ,'gym_clients.email',
                'locker_reservations.end_date as expire_date', 'lockers.locker_num as locker_number','locker_reservations.next_payment_date as due_date',
                'locker_reservations.paid_amount', 'merchants.id as merch_id','common_details.title as company',
                'locker_reservations.client_id','locker_reservations.id as purchase_id')
                ->selectRaw('(amount_to_be_paid - paid_amount) as due_amount')
                ->leftJoin('gym_clients', 'gym_clients.id', '=', 'client_id')
                ->leftJoin('lockers', 'lockers.id', '=', 'locker_id')
                ->leftJoin('common_details', 'common_details.id', '=', 'locker_reservations.detail_id')
                ->leftJoin('merchants', 'merchants.detail_id', '=', 'locker_reservations.detail_id')
                ->where('locker_reservations.detail_id', $setting->detail_id)
                ->where('locker_reservations.end_date',$date)
                ->groupBy('locker_reservations.id')
                ->cursor();

            if(isset($options['locker_due_status']) && $options['locker_due_status'] == 1 && $options['locker_due_notify'] == 'sms'){
                $this->getDueData($dueCollection,$setting->detail_id,$setting->getMerchantID($setting->detail_id));
            }

        }
        return 0;

    }

    public function getDueData($collection,$businessId,$merchantId){
        $result = processSmsWithCreditValidation(
            $collection,
            $businessId,
            $merchantId,
            'locker_due',
            'CheckLockerDue'
        );

        if (!$result['success']) {
            Log::channel('sms_log')->warning("Failed to process locker due SMS for Business ID: {$businessId} - {$result['message']}");
        }

        return $result;

    }

}
