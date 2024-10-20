<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EmployeeValidate extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    protected $user;
    protected $form;
    protected $view;
    protected $bccEmails;

    public function __construct($user, $form, $view, $bccEmails = null)
    {
        $this->user = $user;
        $this->form = $form;
        $this->view = $view;
        $this->bccEmails = $bccEmails; // Initialiser les adresses CCI si fournies
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $mail = (new MailMessage)
            // ->subject('Welcome to Our Platform')
            // ->line("The introduction to the notification.{$this->user->firstName}");
            ->subject('Relocation AfricaRice')
            ->markdown('emails.' . $this->view, [
                'user' => $this->user,
                'form' => $this->form
            ]);

        if ($this->bccEmails) {
            $mail->bcc($this->bccEmails);
        }

        return $mail;
        // ->action('Notification Action', url('/'))
        // ->line('Thank you for using our application!');firstName
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
