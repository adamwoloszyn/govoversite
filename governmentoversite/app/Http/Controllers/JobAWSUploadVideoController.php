<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\AWSVideoUpload;

class JobAWSUploadVideoController extends Controller
{
    public function dispatchJob()
    {
        AWSVideoUpload::dispatch()
            ->onConnection('database')
            ->onQueue('adam');
        return null;
    }
}
