<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class HandleEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    public $view;
    public $ccAddresses;
    public $cci;
    /**
     * Create a new message instance.
     */

    public function __construct($data, $view, $ccAddresses = [], $cci = null)
    {
        $this->data = $data;
        $this->view = $view;
        $this->ccAddresses = $ccAddresses; // Initialiser les adresses CC
        $this->cci = $cci;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Relocation AfricaRice",
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

    // public function content(): Content
    // {
    //     return new Content(
    //         markdown: "emails.{$this->view}",
    //         with: ['data' => $this->data], // Pass the data to the view
    //     );
    // }

    public function build()
    {
        $email = $this->markdown('emails.' . $this->view)
            ->subject('Relocation AfricaRice')
            ->with(['data' => $this->data]);

        if (!empty($this->ccAddresses)) {
            $email->cc($this->ccAddresses);
        }

        if ($this->cci) {
            $email->bcc($this->cci);
        }

        return $email;
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
