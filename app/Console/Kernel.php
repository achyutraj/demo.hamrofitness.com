<?php

namespace App\Console;

use App\Console\Commands\RunJobsCommand;
use App\Models\GymMembershipPayment;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [

    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Clear old failed jobs daily at midnight
        $schedule->command('queue:flush')->timezone('Asia/Kathmandu')->dailyAt('00:00');

        // Process default queue (individual SMS jobs)
        $schedule->command('queue:work --queue=default --stop-when-empty --max-jobs=20 --tries=3 --backoff=30,60,120')
            ->everyMinute()->withoutOverlapping(3);

        // Process bulk customer SMS queue
        $schedule->command('queue:work --queue=bulk_customer_sms --stop-when-empty --max-jobs=50 --tries=3 --backoff=30,60,120')
            ->everyMinute()->withoutOverlapping(5);

        // Process bulk admin SMS queue
        $schedule->command('queue:work --queue=bulk_admin_sms --stop-when-empty --max-jobs=50 --tries=3 --backoff=30,60,120')
            ->everyMinute()->withoutOverlapping(5);

        // Process bulk employee SMS queue
        $schedule->command('queue:work --queue=bulk_employee_sms --stop-when-empty --max-jobs=30 --tries=3 --backoff=30,60,120')
            ->everyMinute()->withoutOverlapping(5);

        $schedule->command('store:adms_attendance_log')->timezone('Asia/Kathmandu')->daily()->withoutOverlapping();
        $schedule->command('store:attendance_log')->timezone('Asia/Kathmandu')->dailyAt('01:00')->withoutOverlapping();

        $schedule->command('update:expired_client')->timezone('Asia/Kathmandu')->dailyAt('02:00')->withoutOverlapping();
        $schedule->command('update:users')->timezone('Asia/Kathmandu')->dailyAt('04:00')->withoutOverlapping();
        $schedule->command('check:birthday')->timezone('Asia/Kathmandu')->dailyAt('05:00')->withoutOverlapping();
        $schedule->command('check:anniversary')->timezone('Asia/Kathmandu')->dailyAt('06:00')->withoutOverlapping();
        $schedule->command('check:expire')->timezone('Asia/Kathmandu')->dailyAt('08:00')->withoutOverlapping();
        $schedule->command('check:locker_expire')->timezone('Asia/Kathmandu')->dailyAt('09:00')->withoutOverlapping();
        $schedule->command('check:due')->timezone('Asia/Kathmandu')->dailyAt('10:00')->withoutOverlapping();
        $schedule->command('check:due_payment')->timezone('Asia/Kathmandu')->dailyAt('11:00')->withoutOverlapping();
        $schedule->command('freeze:subscription')->timezone('Asia/Kathmandu')->daily()->withoutOverlapping();
        // $schedule->command('check:redeem_offer')->timezone('Asia/Kathmandu')->daily()->withoutOverlapping();
        $schedule->command('check:locker_due')->timezone('Asia/Kathmandu')->dailyAt('14:00')->withoutOverlapping();

        //For Testing Purpose
        // $schedule->command('queue:work --queue=test_bulk_customer_sms --max-jobs=50 --tries=1 --timeout=60')
        //     ->everyMinute()->withoutOverlapping(3);

        // Test command - runs every minute for testing
        // $schedule->command('test:check:expire')->everyMinute()->withoutOverlapping();

    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
