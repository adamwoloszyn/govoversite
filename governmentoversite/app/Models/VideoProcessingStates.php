<?php

namespace App\Models;
use Illuminate\Support\Facades\Log;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoProcessingStates extends Model
{
    use HasFactory;

    protected $table = 'video_processing_states';

    protected $primaryKey = 'id';

    // Get associated videos
    public function Videos()
    {
        return $this->hasMany('App\Models\Videos', 'video_processing_state_id');
    }   // end of Videos()

    public static function GetAdminUploadedVideo()
    {
        return VideoProcessingStates::findOrFail(1);
    }   // end of GetAdminUploadedVideo()

    public static function GetCreateSubDirectoryInAWS()
    {
        return VideoProcessingStates::findOrFail(2);
    }   // end of GetCreateSubDirectoryInAWS()

    public static function GetUploadingToAWS()
    {
        return VideoProcessingStates::findOrFail(3);
    }   // end of GetUploadingToAWS()

    public static function GetUploadedToAWS()
    {
        return VideoProcessingStates::findOrFail(4);
    }   // end of UploadedToAWS()

    public static function GetCompressingAWS()
    {
        return VideoProcessingStates::findOrFail(5);
    }   // end of GetCompressingAWS()

    public static function GetCompressedAWS()
    {
        return VideoProcessingStates::findOrFail(6);
    }   // end of GetCompressedAWS()

    public static function GetUploadedToSonix()
    {
        return VideoProcessingStates::findOrFail(7);
    }   // end of GetUploadedToSonix()

    public static function GetSonixTranscriptionDone()
    {
        return VideoProcessingStates::findOrFail(8);
    }   // end of SonixTranscriptionDone()

    public static function GetSonixTranscriptionFileDownloaded()
    {
        return VideoProcessingStates::findOrFail(9);
    }   // end of GetSonixTranscriptionFile()

    public static function GetTranscriptionUploadedIntoAWS()
    {
        return VideoProcessingStates::findOrFail(10);
    }   // end of GetTranscriptionUploadedIntoAWS()

    public static function GetTranscriptionParsed()
    {
        return VideoProcessingStates::findOrFail(11);
    }   // end of GetTranscriptionParsed()

    public static function GetPublished()
    {
        return VideoProcessingStates::findOrFail(12);
    }   // end of GetPublished()

    public static function GetEmailsSent()
    {
        return VideoProcessingStates::findOrFail(13);
    }   // end of GetEmailsSent()

    public static function GetNextState($currentState)
    {
        $nextState = VideoProcessingStates::where('parent_state_id', '=', $currentState->id)->first();

        return $nextState;
    }   // end of GetNextState()

}   // end of VideoProcessingStates class
