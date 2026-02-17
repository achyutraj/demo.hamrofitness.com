<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Helpers\ADMSHelper;
use App\Models\MerchantBusiness;
use Carbon\Carbon;

class ProcessDailyAdmsSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'adms:process-daily-sync {--date=} {--business-id=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process ADMS logs and convert to gym_client_attendance records';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $date = $this->option('date') ?: Carbon::yesterday()->format('Y-m-d');
        $businessId = $this->option('business-id');

        $this->info("Processing ADMS daily sync for date: {$date}");

        if ($businessId) {
            // Process specific business
            $this->processBusiness($businessId, $date);
        } else {
            // Process all businesses
            $businesses = MerchantBusiness::all();

            foreach ($businesses as $business) {
                $this->processBusiness($business->id, $date);
            }
        }

        $this->info('Daily sync processing completed!');
        return 0;
    }

    /**
     * Process ADMS logs for a specific business
     */
    private function processBusiness($businessId, $date)
    {
        $this->info("Processing business ID: {$businessId}");

        try {
            $processedCount = ADMSHelper::processAdmsLogsToAttendance($businessId, $date);

            $this->info("Business {$businessId}: Processed {$processedCount} attendance records");

        } catch (\Exception $e) {
            $this->error("Business {$businessId}: Error - " . $e->getMessage());
        }
    }
}
