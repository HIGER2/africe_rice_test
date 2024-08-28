<?php

namespace App\Jobs;

use App\Mail\GroupEmail;
use App\Mail\HandleEmail;
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
    public $cc;

    // public $timeout = 120;

    public function __construct($email, $data, $view, $cc = [])
    {
        $this->email = $email;
        $this->data = $data;
        $this->view = $view;
        $this->cc = $cc;
    }

    public function handle()
    {
        try {
            Mail::to($this->email)->send(new HandleEmail($this->data, $this->view, $this->cc));
            Log::info("E-mail envoyé à {$this->email} avec la vue {$this->view}");
        } catch (\Exception $e) {
            Log::error("Erreur d'envoi de mail à {$this->email}: " . $e->getMessage());
            throw $e; // Relance l'exception pour marquer le job comme échoué
        }
    }
}