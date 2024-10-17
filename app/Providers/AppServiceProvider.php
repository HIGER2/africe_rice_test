<?php

namespace App\Providers;

use App\Listeners\LogSentMail;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Queue;
use Illuminate\Queue\Events\JobFailed;
use App\Notifications\QueueFailedNotification;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        Event::listen(LogSentMail::class);
        //
        // View::addNamespace('mail', resource_path('views/vendor/mail'));
        // Queue::failing(function (JobFailed $event) {
        //     // Envoyer une notification à l'administrateur
        //     Notification::route('mail', 'k.sams@cgiar.org')
        //         ->notify(new QueueFailedNotification($event->exception));
        // });
    }
}