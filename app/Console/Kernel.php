<?php

// ==============================
// COMPLETE KERNEL.PHP FILE
// Create this file at: app/Console/Kernel.php
// ==============================

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule)
    {
        // Reset leave balances daily at 12:01 AM
        $schedule->command('leave:reset-balances')->dailyAt('00:01');

        // Generate recurring holidays for next year on December 1st
        $schedule->command('holidays:generate-recurring')->yearly()->monthlyOn(12, 1);

        // You can add more scheduled tasks here as needed
        // Examples:
        // $schedule->command('inspire')->hourly();
        // $schedule->command('emails:send')->daily();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }

    /**
     * Get the timezone that should be used by default for scheduled events.
     */
    protected function scheduleTimezone()
    {
        return 'UTC'; // Change this to your preferred timezone like 'America/New_York'
    }
}
