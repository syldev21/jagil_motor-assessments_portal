<?php

namespace App\Console;

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
        \App\Console\Commands\ResizeImages::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
        $schedule->command('image:resize')->everyMinute()->withoutOverlapping();
        $schedule->command('claimform:sendnotification')->dailyAt('09:00')->withoutOverlapping();
        $schedule->command('fetchclaimform:withoutclaimform')->dailyAt('08:00')->withoutOverlapping();
        $schedule->command('command:nhifSendMail')->everyMinute()->withoutOverlapping();
        $schedule->command('command:fetch_safaricom_customers')->daily()->withoutOverlapping();

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
