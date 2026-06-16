<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestMailCommand extends Command
{
    protected $signature = 'blog:test-mail {email? : Adresse de test}';

    protected $description = 'Envoie un email de test pour vérifier la configuration MAIL';

    public function handle(): int
    {
        $mailer = config('mail.default');
        $to = $this->argument('email')
            ?? config('mail.mailers.smtp.username')
            ?? config('mail.from.address');

        if (blank($to)) {
            $this->error('Aucune adresse de test. Passez un email en argument ou définissez MAIL_FROM_ADDRESS.');

            return self::FAILURE;
        }

        if ($mailer === 'smtp' && blank(config('mail.mailers.smtp.password'))) {
            $this->error('MAIL_PASSWORD manquant pour le mailer smtp.');

            return self::FAILURE;
        }

        if ($mailer === 'resend' && blank(config('services.resend.key'))) {
            $this->error('RESEND_API_KEY manquant pour le mailer resend.');

            return self::FAILURE;
        }

        if ($mailer === 'brevo' && blank(config('services.brevo.key'))) {
            $this->error('BREVO_API_KEY manquant pour le mailer brevo.');

            return self::FAILURE;
        }

        try {
            Mail::raw(
                "Test KerpheX Blog — si vous recevez ce message, l'envoi d'emails fonctionne.",
                fn ($message) => $message->to($to)->subject('Test email · '.config('app.name'))
            );

            $this->info("Email de test envoyé à {$to} via {$mailer}");

            return self::SUCCESS;
        } catch (\Throwable $e) {
            $this->error('Échec envoi : '.$e->getMessage());

            return self::FAILURE;
        }
    }
}
