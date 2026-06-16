<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\MailConfigured;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;

class MailDiagnosticController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $username = config('mail.mailers.smtp.username');
        $password = config('mail.mailers.smtp.password');
        $mailer = config('mail.default');

        $status = [
            'mailer' => $mailer,
            'from' => config('mail.from.address'),
            'password_set' => filled($password),
            'resend_key_set' => filled(config('services.resend.key')),
            'brevo_key_set' => filled(config('services.brevo.key')),
            'railway_note' => 'Sur Railway Hobby, utilisez brevo ou resend (API HTTPS). SMTP Gmail est bloqué.',
        ];

        if (! MailConfigured::isReady()) {
            $status['probe'] = 'failed';
            $status['error'] = 'Configuration mail incomplète.';

            return response()->json($status);
        }

        $recipient = $username ?: config('mail.from.address');

        if (! filled($recipient)) {
            $status['probe'] = 'failed';
            $status['error'] = 'Aucune adresse de test disponible.';

            return response()->json($status);
        }

        try {
            Mail::raw(
                'Diagnostic email KerpheX — test interne admin.',
                fn ($message) => $message->to($recipient)->subject('Diagnostic email · '.config('app.name'))
            );
            $status['probe'] = 'ok';
            $status['test_sent_to'] = $recipient;
        } catch (\Throwable $e) {
            $status['probe'] = 'failed';
            $status['error'] = $e->getMessage();
            $status['exception'] = $e::class;
        }

        return response()->json($status);
    }
}
