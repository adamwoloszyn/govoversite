<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Log;

class Videos extends Model
{
    use HasFactory;

    protected $table = 'videos';

    protected $primaryKey = 'id';

    // what properties can be persisted
    protected $fillable = [
        'title',
        'slug',
        'speakers',
        'videofilelocalpath',
        'thumbnail',
        'when_was_video_created'
    ];

    public function __construct()
    {
        $this->title = "";
        $this->slug = "";
        $this->thumbnail = "";
        //$this->description = "";
        $this->video_category_id = 0;
        $this->video_processing_state_id = 0;
        $this->speakers = "";
        $this->videofilelocalpath = "";
        $this->videofileAWSpath = "";
        $this->transcriptfilelocalpath = "";
        $this->transcriptfileAWSpath = "";
        $this->sonix_ai_media_id = "";
        $this->audit_log = "";
        $this->when_was_video_created = date('Y-m-d H:i:s');
    }

    public function VideoCategory()
    {
        return $this->belongsTo('App\Models\VideoCategories', 'video_category_id');
    }   // end of VideoCategory related class

    public function VideoProcessingState()
    {
        return $this->belongsTo('App\Models\VideoProcessingStates', 'video_processing_state_id');
    }

    public function Notifications()
    {
        return $this->hasMany('App\Models\User_Video_Notifications', 'video_id');
    }

    public function AgendaItemTimeStamps_()
    {
        return $this->hasMany('App\Models\Video_AgendaItemTimeStamps', 'video_id');
    }

    public function AgendaItemTimeStamps()
    {
        return $this->hasMany('App\Models\Video_AgendaItemTimeStamps', 'video_id')
            ->where('is_enabled', '=', '1')
            ->orderBy('video_jump_point', 'asc')
            ->get();
    }

    // return a collection of Video_Keywords that are asscoiated to this video
    public function Video_Keywords()
    {
        return Video_Keywords::where('video_id', '=', $this->id)
            ->where('is_enabled', '=', 1)
            ->get();
    }   // end of Video_Keywords related class

    // get associate keywords to this video
    public function AssocatedKeywords()
    {
        $vks = $this->Video_Keywords();
        $keywordIDs = [];

        foreach($vks as $vk)
        {
            // only add to array if id isn't already in array, aka distinct
            if (!in_array($vk->keyword_id, $keywordIDs)) {
                $keywordIDs[] = $vk->keyword_id;
            }
        }

        //Log::info($keywordIDs);

        $returnValue = Keywords::whereIn('id', $keywordIDs)->get();

        return $returnValue;
    }   // end of AssocatedKeywords related class

    public function CanBeEditted()
    {
        // I missed the reprocussions of this change.
        // You can only publish a video once.  If you publish again, emails will not be sent out again.
        return ($this->video_processing_state_id == VideoProcessingStates::GetTranscriptionParsed()->id) ||
                ($this->video_processing_state_id == VideoProcessingStates::GetPublished()->id) ||
                ($this->video_processing_state_id == VideoProcessingStates::GetEmailsSent()->id);
        //return ($this->video_processing_state_id == VideoProcessingStates::GetTranscriptionParsed()->id);
    }   // end of CanBeEditted()

    public function IsProcessingComplete()
    {
        return ($this->video_processing_state_id == VideoProcessingStates::GetPublished()->id) ||
               ($this->video_processing_state_id == VideoProcessingStates::GetEmailsSent()->id);
    }   // end of IsProcessingComplete()

    public function UpdateAuditLog($message)
    {
        // Create a date/time stamp for the current date/time
        $timestamp = date("[Y-m-d H:i:s]");

        $newAuditLog = $timestamp . " " . $message . "\n".  $this->audit_log;

        $this->audit_log = $newAuditLog;

        $this->save();
    }   // end of UpdateAuditLog()

    // returns cloud front URL for compressed version of video
    public function CompressedVideoStreamURL()
    {
        return env('AWS_CLOUD_FRONT_BASE_URL') . '/' . $this->aws_subdirectory . '/' . $this->compressedvideofileAWSpath;
    }   // end of CompressedVideoStreamURL()

    // returns cloud front URL for transcription of video
    public function TranscriptionStreamURL()
    {
        return env('AWS_CLOUD_FRONT_BASE_URL') . '/' . $this->aws_subdirectory . '/' . $this->transcriptfileAWSpath;
    }   // end of TranscriptionStreamURL()

    public static function GetAllEnabled()
    {
        return Videos::where('is_enabled', '=', true)->get();
    }   // end of GetAllEnabled()

}   // end of Videos class
