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
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('db:backup')->dailyAt('01:00');
        $schedule->command('db:backup')->dailyAt('08:00');
        $schedule->command('db:backup')->dailyAt('09:00');
        $schedule->command('db:backup')->dailyAt('10:00');
        $schedule->command('db:backup')->dailyAt('11:00');
        $schedule->command('db:backup')->dailyAt('12:00');
        $schedule->command('db:backup')->dailyAt('13:00');
        $schedule->command('db:backup')->dailyAt('14:00');
        $schedule->command('db:backup')->dailyAt('17:00');
        $schedule->command('db:backup')->dailyAt('18:00');
        $schedule->command('db:backup')->dailyAt('19:00');
        $schedule->command('db:backup')->dailyAt('20:00');
        $schedule->command('db:backup')->dailyAt('21:00');
        $schedule->command('db:uploadbackup')->dailyAt('21:10');
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
