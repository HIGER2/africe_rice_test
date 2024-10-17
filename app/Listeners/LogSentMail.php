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

        // Extraire les emails
        $recipients = [];
        foreach ($recipientsArray as $address) {
            // Accéder à la propriété 'address' de l'objet Address
            $recipients[] = $address->getAddress();
        }

        // Si aucun destinataire n'est trouvé, définir une valeur par défaut
        if (empty($recipients)) {
            $recipients = 'No recipient';
        } else {
            $recipients = implode(', ', $recipients); // Convertir le tableau en chaîne
        }

        // Récupérer le sujet
        $subject = $message->getSubject() ?? 'No Subject';

        // Créer l'entrée de log
        $logEntry = sprintf(
            "Date: %s\nTo: %s\nSubject: %s\n***\n",
            now()->format('Y-m-d H:i:s'),
            $recipients,
            $subject
        );


        Log::channel('mail')->info($logEntry);
    }
}