<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\DeleteOldNewsletters;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        DeleteOldNewsletters::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        $schedule->command('newsletter:delete-old')
                 ->everyMinute()
                 ->appendOutputTo(storage_path('logs/scheduler.log'));
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
