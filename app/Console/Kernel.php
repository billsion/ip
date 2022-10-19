<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\Auth\NewCommand::class,
        Commands\Auth\RenameCommand::class,
        Commands\Auth\ResetCommand::class,
        Commands\Auth\ToggleCommand::class,
        Commands\Auth\ListCommand::class,
        Commands\Auth\DeleteCommand::class,
        Commands\Auth\NonceCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //
    }
}
