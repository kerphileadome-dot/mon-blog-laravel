<?php

namespace App\Support;

class MailConfigured
{
    public static function isReady(): bool
    {
        $mailer = config('mail.default');

        if (in_array($mailer, ['log', 'array'], true)) {
            return ! app()->environment('production');
        }

        if ($mailer === 'smtp') {
            return filled(config('mail.mailers.smtp.username'))
                && filled(config('mail.mailers.smtp.password'));
        }

        if ($mailer === 'brevo') {
            return filled(config('services.brevo.key'));
        }

        return true;
    }
}
