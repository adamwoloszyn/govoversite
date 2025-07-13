<?php

namespace App\Http\Controllers;

use App\Helpers\SendEmail;
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

class SendNotificationsCRONController extends CRONJobBaseController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $message = "Starting Send Notifications CRON";
        parent::DebugOutput($message);

        $emailsToSend = User_Video_Notifications::GetEmailsToSendOut();
        $maxEMailsToSendOutAtOnce = env('MAIL_MAX_EMAILS_TO_SEND_AT_ONCE');

        foreach( $emailsToSend as $email )
        {
            $message = 'Emailing: ' . $email->email_address . ' for video id: ' . $email->video_id;
            parent::DebugOutput($message);

            try
            {
                $se = new SendEmail();
                $se->SendIt($email->email_address, $email->email_body);

                // mark record as ready to be sent out
                $email->was_email_sent_out = true;
                $email->save();

                // only send out so many emails at a time
                $maxEMailsToSendOutAtOnce = $maxEMailsToSendOutAtOnce - 1;
                if ( $maxEMailsToSendOutAtOnce <= 0 )
                {
                    break;
                }
            }
            catch( Exception $e)
            {
                Log::error('EMail ID ' . $email->id);
                Log::error('Problem sending email to ' . $email->email_address);
                Log::error($e);
            }
        }

        parent::DebugOutput('end of SendNotificationsCRONController __invoke()');
    }   // end of __invoke()
}   // end of SendNotificationsCRONController class