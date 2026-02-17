<?php

namespace App\Console\Commands;

use App\Models\GymMembershipFreeze;
use App\Models\GymPurchase;
use Illuminate\Console\Command;

class CheckSubscriptionFreeze extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'freeze:subscription';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Freeze Membership Subscription';

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
        \Log::info('freeze data');
        $freezes = GymMembershipFreeze::where('start_date', now()->format('Y-m-d'))->pluck('purchase_id')->toArray();
        GymPurchase::whereIn('id',$freezes)->update([
                            'status' => 'freeze'
                        ]);
        return 0;
    }
}
