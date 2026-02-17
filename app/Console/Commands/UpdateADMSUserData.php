<?php

namespace App\Console\Commands;

use App\Helpers\ADMSHelper;
use App\Models\GymClient;
use Illuminate\Console\Command;

class UpdateADMSUserData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update User who are inactive in ADMS database';

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
        \Log::info('user update log');
        $chunks = [];
        $inactive_clientIds = GymClient::where('is_expired',1)->where('is_device_deleted',0)->pluck('id')->toArray();
        $chunks = array_chunk($inactive_clientIds,10);
        foreach($chunks as $data){
            foreach($data as $id){
                try {
                    $user = GymClient::has('devices')->findOrFail($id);
                    $device = $user->devices()->firstOrFail();
                    if($device != null){
                        ADMSHelper::deleteUserFromDevice($id, $device->code);
                        $user->update([
                            'is_device_deleted' => true,
                            'is_denied' => true
                        ]);
                    }
                } catch (\Exception $e) {
                    \Log::error("Error processing user with ID: $id - " . $e->getMessage());
                }
            }
        }
        return 0;
    }
}
