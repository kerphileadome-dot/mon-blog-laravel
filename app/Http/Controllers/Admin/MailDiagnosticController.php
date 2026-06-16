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

        $status = [
            'mailer' => config('mail.default'),
            'host' => config('mail.mailers.smtp.host'),
            'port' => config('mail.mailers.smtp.port'),
            'scheme' => config('mail.mailers.smtp.scheme'),
            'username' => $username,
            'from' => config('mail.from.address'),
            'password_set' => filled($password),
            'password_length' => is_string($password) ? strlen($password) : 0,
            'resend_key_set' => filled(config('services.resend.key')),
            'ssl_verify' => config('mail.mailers.smtp.verify_peer'),
            'openssl' => extension_loaded('openssl'),
            'from_matches_username' => strtolower((string) config('mail.from.address')) === strtolower((string) $username),
            'railway_smtp_note' => 'Sur Railway Hobby, SMTP (ports 587/465) est bloqué. Utilisez MAIL_MAILER=resend + RESEND_API_KEY.',
        ];

        if (! MailConfigured::isReady()) {
            $status['probe'] = 'failed';
            $status['error'] = 'Configuration mail incomplète (SMTP ou Resend).';

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
        } catch (\Throwable $e) {
            $status['probe'] = 'failed';
            $status['error'] = $e->getMessage();
            $status['exception'] = $e::class;
        }

        return response()->json($status);
    }
}
