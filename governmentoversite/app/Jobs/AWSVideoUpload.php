<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

use App\Http\Controllers\AWSVideoUploadCRONController;

class AWSVideoUpload implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        Log::info("Adding the job: ");
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        echo("starting to handle...");
        Log::info("starting to handle...");
        $awsVideoUpload = new AWSVideoUploadCRONController();
        $awsVideoUpload();
    }
}
