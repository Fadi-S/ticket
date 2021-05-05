<?php

namespace App\Console;

use App\Console\Commands\ClearLogins;
use App\Console\Commands\RecurringEvents;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Laravel\Telescope\Console\PruneCommand;
use Spatie\Activitylog\CleanActivitylogCommand;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        PruneCommand::class,
        ClearLogins::class,
        CleanActivitylogCommand::class,
        RecurringEvents::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('telescope:prune')->daily();

        // $schedule->command('queue:work --stop-when-empty')->everyMinute();

        $schedule->command('activitylog:clean')->daily();

        $schedule->command('logins:clear')->daily();

        $schedule->command('events:create')
            ->daily();
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
