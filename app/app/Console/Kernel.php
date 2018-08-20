<?php

namespace App\Console;

use Illuminate\Http\Request;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

// symfony process for running sub-process.
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\PhpProcess;
// storage/file facade
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Http\RedirectResponse;
use DB;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
        'App\Console\Commands\ocr_new',
        'App\Console\Commands\ocr_txt_img',
        'App\Console\Commands\ocr_force',
        'App\Console\Commands\ocr_reminder'

    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // // run ocrmypdf to new documents added
        $schedule->command('ocr:new')->everyMinute();
        // // make image preview and get text from documents for searching.
        $schedule->command('ocr:txt_img')->everyMinute();
        // // rerun failed ocred documents
        $schedule->command('ocr:force')->everyMinute();
        // shedule reminder
        $schedule->command('ocr:reminder')->everyMinute();
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
