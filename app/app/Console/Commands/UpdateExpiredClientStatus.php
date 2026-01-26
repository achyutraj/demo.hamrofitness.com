<?php

namespace App\Console\Commands;

use App\Models\GymClient;
use Illuminate\Console\Command;
use App\Models\GymPurchase;
use App\Models\Device;
use Carbon\Carbon;

class UpdateExpiredClientStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:expired_client';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update those client status whose subscription has expired';

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
        \Log::info('update expired client status data');

        $date = Carbon::today();
        $startOfWeek = $date->subDays(7)->format('Y-m-d');
        $endOfWeek = $date->addDays(7)->format('Y-m-d');

        $devices = Device::where('device_status',1)->get();
        foreach($devices as $device){
            try{
                $active_users = GymClient::getActiveClient($device->detail_id)->pluck('id');
                $expiredClientIds = GymPurchase::where('expires_on', '>=', $startOfWeek)
                        ->where('expires_on', '<=', $endOfWeek)
                        ->where('detail_id',$device->detail_id)
                        ->whereNotIn('client_id',$active_users)
                        ->pluck('client_id')
                        ->toArray();

                GymClient::whereIn('id',$expiredClientIds)->update([
                    'is_expired' => true
                ]);
            }catch (\Exception $e) {
                \Log::error("Error processing device ID {$device->id}: " . $e->getMessage());
            }
        }
        return 0;
    }
}
