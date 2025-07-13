<?php

namespace App\Helpers;

use App\Mail\KeywordSubscriptionEmail;
use Illuminate\Support\Facades\Mail;

use Illuminate\Support\Facades\Log;

class SendEmail
{
    public function SendIt($toAddress, $bodyContents)
    {
        $emailAddress = $toAddress;
        
        // Instantiate the Mailable class
        $emailInstance = new KeywordSubscriptionEmail($bodyContents);
        
        // Send the email
        Mail::to($emailAddress)->send($emailInstance);
    }   // end of SendIt()
    
}   // end of SendEmail class