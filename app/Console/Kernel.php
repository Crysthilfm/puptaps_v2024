<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        /* Runs Automated Email command every day, Sends email if
        * its been 6 months since the previous reminder was sent
        */
        $schedule->command('command:AutomatedEmail')->dailyAt('9:00')->runInBackground();
        $schedule->command('backup:run --only-db')->dailyAt('9:00')->runInBackground();
        // Tester
        //$schedule->command('command:AutomatedEmail')->everyMinute()->runInBackground();
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
