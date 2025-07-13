<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Queue\SerializesModels;

use Illuminate\Support\Facades\Log;

class KeywordSubscriptionEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $bodyContents;
    /**
     * Create a new message instance.
     */
    public function __construct($bodyContents)
    {
        $this->bodyContents = $bodyContents;
        $this->content_type = 'text/html';
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $from = new Address(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_ADDRESS_DESCRIPTION'));
        
        // Log::info( env('MAIL_FROM_ADDRESS') );
        // Log::info( env('MAIL_FROM_ADDRESS_DESCRIPTION') );

        return new Envelope(
            subject: 'Videos That Might Interest You',
            from: $from
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails/keywordsubscription',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
