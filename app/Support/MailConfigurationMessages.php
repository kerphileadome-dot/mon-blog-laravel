<?php

namespace App\Support;

class MailConfigurationMessages
{
    public static function notConfigured(): string
    {
        return match (config('mail.default')) {
            'brevo' => 'L\'envoi d\'emails n\'est pas configuré sur le serveur. Vérifiez BREVO_API_KEY sur Railway.',
            'smtp' => 'L\'envoi d\'emails n\'est pas configuré. Vérifiez MAIL_USERNAME et MAIL_PASSWORD.',
            default => 'L\'envoi d\'emails n\'est pas configuré. Contactez l\'administrateur du blog.',
        };
    }

    public static function sendFailed(): string
    {
        return match (config('mail.default')) {
            'brevo' => 'Impossible d\'envoyer l\'email pour le moment. Vérifiez BREVO_API_KEY et l\'expéditeur vérifié sur Brevo.',
            'smtp' => 'Impossible d\'envoyer l\'email pour le moment. Vérifiez la configuration SMTP.',
            default => 'Impossible d\'envoyer l\'email pour le moment. Réessayez plus tard.',
        };
    }
}
