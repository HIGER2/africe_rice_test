<?php

namespace App\Jobs;

use App\Mail\GroupEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $email;
    public $data;
    public $view;
    // public $timeout = 120;

    public function __construct($email, $data, $view)
    {
        $this->email = $email;
        $this->data = $data;
        $this->view = $view;
    }

    public function handle()
    {
        try {
            Mail::to($this->email)->send(new GroupEmail($this->data, $this->view));
            Log::info("E-mail envoyé à {$this->email} avec la vue {$this->view}");
        } catch (\Exception $e) {
            Log::error("Erreur d'envoi de mail à {$this->email}: " . $e->getMessage());
            throw $e; // Relance l'exception pour marquer le job comme échoué
        }
    }
}