<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\EmailController;
use App\Http\Controllers\OldVideosController;
use App\Http\Controllers\OldVideoImportController;
use App\Http\Controllers\MediaConvertCronController;
use App\Http\Controllers\JobAWSUploadVideoController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//emails
Route::get('/send-email', [EmailController::class, 'sendEmail']);
Route::get('/old-videos', [OldVideosController::class, 'index']);
Route::get('/convert-video', [MediaConvertCronController::class, 'convertTo720p']);

Route::get('/old-video-query', [OldVideoImportController::class, 'query']);

Route::get('/dispatch-job', [JobAWSUploadVideoController::class, 'dispatchJob']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
