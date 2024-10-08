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

    public function __construct($user, $form)
    {
        $this->user = $user;
        $this->form = $form;
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
        return (new MailMessage)
            // ->subject('Welcome to Our Platform')
            // ->line("The introduction to the notification.{$this->user->firstName}");
            ->subject('Nouveau formulaire soumis')
            ->markdown('emails.form_submission', [
                'user' => $this->user,
                'form' => $this->form
            ]);
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
