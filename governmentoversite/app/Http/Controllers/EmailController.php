<?php

namespace App\Http\Controllers;

use App\Mail\KeywordSubscriptionEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;

class EmailController extends Controller
{
    public function sendEmail(Request $request)
    {
        // Retrieve data from the request
        $emailAddress = "adamjwoloszyn@gmail.com";//$request->input('email');
        $bodyContents = "I am something from the controller!";
        
        // Instantiate the Mailable class
        $emailInstance = new KeywordSubscriptionEmail($bodyContents);
        
        // Send the email
        Mail::to($emailAddress)->send($emailInstance);
        
        // Return a response
        return response()->json(['message' => 'Email sent']);
    }
}
