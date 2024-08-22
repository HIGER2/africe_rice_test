<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class GroupEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $data;
    public $view;

    /**
     * Create a new message instance.
     */
    public function __construct($data, $view)
    {
        $this->data = $data;
        $this->view = $view;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Group Email',
        );
    }

    /**
     * Get the message content definition.
     */

    // public function build()
    // {
    //     return $this->subject('Group Email')
    //         ->text($this->data); // Utilise le texte brut
    // }

    public function content(): Content
    {
        return new Content(
            markdown: "emails.{$this->view}",
            with: ['data' => $this->data], // Pass the data to the view
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
