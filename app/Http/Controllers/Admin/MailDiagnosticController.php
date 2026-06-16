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
        $mailer = config('mail.default');

        $status = [
            'mailer' => $mailer,
            'from' => config('mail.from.address'),
            'brevo_key_set' => filled(config('services.brevo.key')),
            'smtp_configured' => filled(config('mail.mailers.smtp.username'))
                && filled(config('mail.mailers.smtp.password')),
            'railway_note' => 'Sur Railway Hobby, utilisez Brevo (API HTTPS). SMTP Gmail est bloqué.',
        ];

        if (! MailConfigured::isReady()) {
            $status['probe'] = 'failed';
            $status['error'] = 'Configuration mail incomplète.';

            return response()->json($status);
        }

        $recipient = config('mail.from.address');

        if (! filled($recipient)) {
            $status['probe'] = 'failed';
            $status['error'] = 'MAIL_FROM_ADDRESS manquant.';

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
