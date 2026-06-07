<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestMailCommand extends Command
{
    protected $signature = 'blog:test-mail {email? : Adresse de test (défaut : MAIL_USERNAME)}';

    protected $description = 'Envoie un email de test pour vérifier la configuration MAIL locale';

    public function handle(): int
    {
        $to = $this->argument('email') ?? config('mail.mailers.smtp.username');

        if (blank($to)) {
            $this->error('MAIL_USERNAME non défini dans .env');

            return self::FAILURE;
        }

        if (blank(config('mail.mailers.smtp.password'))) {
            $this->error('MAIL_PASSWORD manquant. Ajoutez-le dans .env ou mail.secret.env puis relancez setup-local-env.ps1');

            return self::FAILURE;
        }

        try {
            Mail::raw(
                "Test KerpheX Blog — si vous recevez ce message, l'envoi d'emails fonctionne en local.",
                fn ($message) => $message->to($to)->subject('Test email · '.config('app.name'))
            );

            $this->info("Email de test envoyé à {$to}");

            return self::SUCCESS;
        } catch (\Throwable $e) {
            $this->error('Échec envoi : '.$e->getMessage());

            return self::FAILURE;
        }
    }
}
