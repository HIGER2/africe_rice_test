<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class HandleEmailNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $data;
    public $view;
    public $ccAddresses;

    /**
     * Create a new notification instance.
     */
    public function __construct($data, $view, $ccAddresses = [])
    {
        $this->data = $data;
        $this->view = $view;
        $this->ccAddresses = $ccAddresses; // Initialiser les adresses CC
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array<int, string>
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $email = (new MailMessage)
            ->subject('Group Email')
            ->view("emails.{$this->view}", ['data' => $this->data]);

        // Ajouter des adresses CC si disponibles
        if (!empty($this->ccAddresses)) {
            $email->cc($this->ccAddresses);
        }

        return $email;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array<string, mixed>
     */
    public function toArray($notifiable)
    {
        return [
            // Ici, vous pouvez retourner des données additionnelles si nécessaire.
        ];
    }
}