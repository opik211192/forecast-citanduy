<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;


class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */

    protected $commands = [
        Commands\fetchJtengah::class,
        Commands\fetchJbarat::class,
        Commands\NotifHujan::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        $schedule->command('fetch-jbarat')->dailyAt('12:00');
        $schedule->command('fetch-jtengah')->dailyAt('12:08');
        $schedule->command('notif-hujan')->dailyAt('12:15');
        // $schedule->call(function () {
        //         Log::info('Scheduled task fetch-jtengah is executed.');
        // })->dailyAt('12:13');
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
