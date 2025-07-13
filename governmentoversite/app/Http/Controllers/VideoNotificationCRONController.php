<?php

namespace App\Http\Controllers;

use App\Models\Keywords;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Log;

use App\Models\VideoProcessingStates;
use App\Models\Video_AgendaItemTimeStamps;
use App\Models\Videos;
use App\Models\User;
use App\Models\User_Video_Notifications;

// AWS S3 Stuff
use Illuminate\Support\Facades\Storage;
use Aws\S3\S3Client;
use Aws\Exception\AwsException;

use Exception;

class VideoNotificationCRONController extends CRONJobBaseController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $message = "Starting Video Notification CRON";
        parent::DebugOutput($message);

        // get videos that are published
        $VideosToWorkOn = VideoProcessingStates::GetPublished();

        $nextState = VideoProcessingStates::GetNextState($VideosToWorkOn);

        $videosToProcess = $VideosToWorkOn->Videos()->get();
        parent::DebugOutput( "Number Of Videos to notify users about: " . $videosToProcess->count() );

        foreach( $videosToProcess as $currentVideo)
        {
            parent::DebugOutput( "Video ID: " . $currentVideo->id );
            $listOfUserIDsToNotifyForThisVideo = [];

            $getAllKeywordsAssociatedToThisVideo = $currentVideo->AssocatedKeywords();

            foreach( $getAllKeywordsAssociatedToThisVideo as $currentKeyword)
            {
                parent::DebugOutput( '*********************************');
                parent::DebugOutput( 'keyword ID: ' . $currentKeyword->id . " Keyword[" . $currentKeyword->keyword . ']');

                $listOfUsersSubscribed = $currentKeyword->Users()->get();
                parent::DebugOutput( "Number Of Users Subscribed: " . $listOfUsersSubscribed->count() );
                foreach( $listOfUsersSubscribed as $aUser)
                {
                    parent::DebugOutput( 'User subscribed [' . $aUser->name . ']');
                    array_push($listOfUserIDsToNotifyForThisVideo, $aUser->id);
                }
            }

            parent::DebugOutput( '*********************************');
            parent::DebugOutput( '*********************************');
            $uniqueSetOfUserIDs = array_unique($listOfUserIDsToNotifyForThisVideo);
            foreach( $uniqueSetOfUserIDs as $aUserID)
            {
                parent::DebugOutput( 'Notify this User ID [' . $aUserID . ']');

                $newUserVideoNotification = new User_Video_Notifications();
                $newUserVideoNotification->user_id = $aUserID;
                $newUserVideoNotification->video_id = $currentVideo->id;
                $newUserVideoNotification->email_body = "Need to create";
                $newUserVideoNotification->was_email_sent_out = false;
                $newUserVideoNotification->was_email_body_built = false;

                $newUserVideoNotification->save();
            }

            // change state to emails sent
            $currentVideo->VideoProcessingState()->associate($nextState);

            // save changes
            $currentVideo->save();

            // only process one video at a time.
            break;
        }

        parent::DebugOutput('end of VideoNotificationCRONController __invoke()');
    }   // end of __invoke()
}   // end of VideoNotificationCRONController class