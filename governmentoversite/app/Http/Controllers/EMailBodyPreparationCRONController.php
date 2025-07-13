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

class EMailBodyPreparationCRONController extends CRONJobBaseController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $message = "Starting EMail Body Preparation CRON";
        parent::DebugOutput($message);

        $emailsToBuild = User_Video_Notifications::GetEmailsToBuild();

        foreach( $emailsToBuild as $emailNotification )
        {
            $currentUser = User::find($emailNotification->user_id);
            $currentVideo = Videos::find($emailNotification->video_id);
            $notficationHTML = 'Agenda Items for: <b>' . $currentVideo->title . "</b><br /><ul>";

            // get keywords user subscribed to
            $listOfKeywordUserSubscribedTo = $currentUser->Keywords();

            // get keywords found in video
            $getAllKeywordsAssociatedToThisVideo = $currentVideo->Video_Keywords();
            // foreach( $getAllKeywordsAssociatedToThisVideo as $associatedKeywordReference )
            // {
            //     $currentKeyword = Keywords::find( $associatedKeywordReference->keyword_id );
            //     parent::DebugOutput( $currentKeyword->id . ' ' .$currentKeyword->keyword . ' ' . $associatedKeywordReference->position_in_video);
            // }

            // foreach( $listOfKeywordUserSubscribedTo as $subscribedKeyword )
            // {
            //     parent::DebugOutput( $subscribedKeyword->id . ' ' . $subscribedKeyword->keyword );
            // }

            $message = 'Processing ' . $emailNotification->id . ' to ' . $currentUser->email;
            parent::DebugOutput($message);

            $listOfKeywordsForVideo = [];
            foreach( $getAllKeywordsAssociatedToThisVideo as $associatedKeywordReference )
            {
                // gather a list of keywords from the video that this user subscribed to
                if ( $listOfKeywordUserSubscribedTo->contains( 'id', $associatedKeywordReference->keyword_id ) )
                {
                    array_push($listOfKeywordsForVideo, $associatedKeywordReference);
                }
                //parent::DebugOutput( $currentKeyword->id . ' ' .$currentKeyword->keyword . ' ' . $associatedKeywordReference->position_in_video);
            }

            parent::DebugOutput( 'Keywords found in video' );
            foreach ( $listOfKeywordsForVideo as $associatedKeywordReference )
            {
                $currentKeyword = Keywords::find( $associatedKeywordReference->keyword_id );
                parent::DebugOutput( $currentKeyword->id . ' ' . $currentKeyword->keyword . ' ' . $associatedKeywordReference->position_in_video);
            }

            parent::DebugOutput( 'Agenda items for video' );
            $associatedAgendaTimeStamps = $currentVideo->AgendaItemTimeStamps();
            foreach ( $associatedAgendaTimeStamps as $associatedAgendaTimeStamp )
            {
                parent::DebugOutput( 'Video ID: ' . $currentVideo->id . ' Agenda Item Jump Point: ' . $associatedAgendaTimeStamp->video_jump_point );
            }

            // parent::DebugOutput( 'Output links for keywords' );
            $lastKeywordID = -1;
            $lastAgendaTimeStampID = -1;
            foreach ( $listOfKeywordsForVideo as $associatedKeywordReference )
            {
                $currentKeyword = Keywords::find( $associatedKeywordReference->keyword_id );
                // parent::DebugOutput( $currentKeyword->id . ' ' . $currentKeyword->keyword . ' ' . $associatedKeywordReference->position_in_video);

                // find which agenda item contains this keyword
                foreach ( $associatedAgendaTimeStamps->sortByDesc('video_jump_point') as $associatedAgendaTimeStamp )
                {
                    // parent::DebugOutput( 'Current keyword time stamp ' . $associatedKeywordReference->position_in_video . ' Agenda item time stamp ' . $associatedAgendaTimeStamp->video_jump_point . ' current keyword time stamp ' . $associatedKeywordReference->position_in_video);
                    // parent::DebugOutput( $associatedAgendaTimeStamp->video_jump_point >= $associatedKeywordReference->position_in_video);
                    if ( $associatedAgendaTimeStamp->video_jump_point <= $associatedKeywordReference->position_in_video)
                    {
                        break;
                    }
                }   

                // parent::DebugOutput($associatedAgendaTimeStamp);

                if ( !( $lastKeywordID == $currentKeyword->id && $associatedAgendaTimeStamp->id == $lastAgendaTimeStampID ) )
                {
                    // parent::DebugOutput( '*******' );
                    // parent::DebugOutput( 'Subscripted to keyword: ' . $currentKeyword->keyword . ' ' . $associatedAgendaTimeStamp->id . ' Agenda item time stamp ' . $associatedAgendaTimeStamp->video_jump_point);
                    //$associatedAgendaTimeStamp->id
                    $newRow = '<li>Agenda Item with keyword: <a href="http://127.0.0.1:8000/Video/'.$currentVideo->slug.'?part=1&offset='.$associatedAgendaTimeStamp->video_jump_point.'&autoplay=1">'.$currentKeyword->keyword.' - ' . $associatedAgendaTimeStamp->video_jump_point . '</a>' . '</li>';

                    $notficationHTML = $notficationHTML . $newRow;
                }

                // remember what I just outputed to not allow for duplicate keywords for same agenda item
                $lastKeywordID = $currentKeyword->id;
                $lastAgendaTimeStampID = $associatedAgendaTimeStamp->id;
            }
            $notficationHTML .= "</ul>";
            parent::DebugOutput( $notficationHTML );

            // update email body
            $emailNotification->email_body = $notficationHTML;

            // set notification's email address
            $emailNotification->email_address = $currentUser->email;

            // mark record as ready to be sent out
            $emailNotification->was_email_body_built = true;

            $emailNotification->save();
        }

        parent::DebugOutput('end of EMailBodyPreparationCRONController __invoke()');
    }   // end of __invoke()
}   // end of EMailBodyPreparationCRONController class