<?php

namespace App\Listeners;

use Illuminate\Mail\Events\MessageSent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class LogSentMail
{
    /**
     * Handle the event.
     *
     * @param  \Illuminate\Mail\Events\MessageSent  $event
     * @return void
     */
    public function handle(MessageSent $event)
    {
        $message = $event->message;

        // Récupérer les destinataires
        $recipientsArray = $message->getTo();
        $bccArray = $message->getBcc();

        // Extraire les emails des destinataires principaux
        $recipients = [];
        foreach ($recipientsArray as $address) {
            $recipients[] = $address->getAddress();
        }

        // Extraire les emails des destinataires BCC
        $bccRecipients = [];
        foreach ($bccArray as $address) {
            $bccRecipients[] = $address->getAddress();
        }

        // Préparer les chaînes de destinataires
        $toRecipients = empty($recipients) ? 'No recipient' : implode(', ', $recipients);
        $bccRecipientsString = empty($bccRecipients) ? 'No BCC recipient' : implode(', ', $bccRecipients);

        // Récupérer le sujet
        $subject = $message->getSubject() ?? 'No Subject';

        // Créer l'entrée de log
        $logEntry = sprintf(
            "Date: %s\nTo: %s\nBCC: %s\nSubject: %s\n***\n",
            now()->format('Y-m-d H:i:s'),
            $toRecipients,
            $bccRecipientsString,
            $subject
        );


        Log::channel('mail')->info($logEntry);
    }
}