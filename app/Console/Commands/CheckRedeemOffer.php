<?php

namespace App\Console\Commands;

use App\Models\RedeemPoint;
use Illuminate\Console\Command;

class CheckRedeemOffer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:redeem_offer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check redeem offer is expire or not';

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
        \Log::info('redeem offer expire data');
        $redeem_offers = RedeemPoint::active()->whereDate('end_date','<',today())->pluck('id');
        RedeemPoint::whereIn('id',$redeem_offers)->update([
            'status' => false
        ]);
        return 0;
    }
}
