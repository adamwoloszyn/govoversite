<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

use App\Http\Controllers\CRONController;
use App\Http\Controllers\AWSVideoUploadCRONController;
use App\Http\Controllers\MediaConvertCronController;
use App\Http\Controllers\SonixUploadCRONController;
use App\Http\Controllers\SonixTranscriptionStatusCheckCRONController;
use App\Http\Controllers\SonixTranscriptionDownLoadCRONController;
use App\Http\Controllers\AWSTranscriptionUploadCRONController;
use App\Http\Controllers\TranscriptionParseCRONController;
use App\Http\Controllers\PlayGroundCRONController;
use App\Http\Controllers\VideoNotificationCRONController;
use App\Http\Controllers\EMailBodyPreparationCRONController;
use App\Http\Controllers\SendNotificationsCRONController;
use App\Http\Controllers\LocalFileCleanUpCRONController;
use App\Http\Controllers\AWSVideoCompressionCRONController;
use App\Http\Controllers\AWSCreateSubDirectoryCRONController;
use App\Http\Controllers\AWSVideoCompressionStatusCheckCRONController;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        //$schedule->call(new CRONController)->everyTwoMinutes()->withoutOverlapping();
        //$schedule->call(new CRONController)->everyMinute()->name('parseTranscriptTask')->withoutOverlapping();

        $schedule->call(new PlayGroundCRONController)->everyMinute()->name('PlayGroundCRONController')->withoutOverlapping();

        // CRON index better corresponds to state id to reduce confusion
        $schedule->call(new AWSCreateSubDirectoryCRONController)->everyMinute()->name('CreateSubDirectoryInAWS')->withoutOverlapping();        
        //$schedule->call(new AWSVideoUploadCRONController)->everyMinute()->name('UploadVideoToAWS')->withoutOverlapping();
        $schedule->call(new AWSVideoCompressionCRONController)->everyMinute()->name('CompressVideoInAWS')->withoutOverlapping();
        $schedule->call(new AWSVideoCompressionStatusCheckCRONController)->everyMinute()->name('CheckCompressVideoStatusInAWS')->withoutOverlapping();
        //$schedule->call(new MediaConvertCronController)->everyMinute()->name('ConvertVideoInAWS')->withoutOverlapping();
        $schedule->call(new SonixUploadCRONController)->everyMinute()->name('UploadToSonix')->withoutOverlapping(); 
        $schedule->call(new SonixTranscriptionStatusCheckCRONController)->everyMinute()->name('SonixTranscriptionStatus')->withoutOverlapping();
        $schedule->call(new SonixTranscriptionDownLoadCRONController)->everyMinute()->name('SonixTranscriptionDownload')->withoutOverlapping();
        $schedule->call(new AWSTranscriptionUploadCRONController)->everyMinute()->name('AWSTranscriptionUpload')->withoutOverlapping();
        $schedule->call(new TranscriptionParseCRONController)->everyMinute()->name('TranscriptionParseCRONController')->withoutOverlapping();
        $schedule->call(new VideoNotificationCRONController)->everyMinute()->name('VideoNotificationCRONController')->withoutOverlapping();
        $schedule->call(new EMailBodyPreparationCRONController)->everyMinute()->name('EMailBodyPreparationCRONController')->withoutOverlapping();
        $schedule->call(new SendNotificationsCRONController)->everyMinute()->name('SendNotificationsCRONController')->withoutOverlapping(); 
        //$schedule->call(new LocalFileCleanUpCRONController)->everyMinute()->name('LocalFileCleanUpCRONController')->withoutOverlapping();
    }   // end of schedule()

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }   // end of commands()
    
}   // end of Kernel class
