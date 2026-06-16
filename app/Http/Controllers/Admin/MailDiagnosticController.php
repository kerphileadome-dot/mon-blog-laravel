<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
            'ssl_verify' => config('mail.mailers.smtp.verify_peer'),
            'openssl' => extension_loaded('openssl'),
            'from_matches_username' => strtolower((string) config('mail.from.address')) === strtolower((string) $username),
        ];

        if (! filled($username) || ! filled($password)) {
            $status['probe'] = 'failed';
            $status['error'] = 'MAIL_USERNAME ou MAIL_PASSWORD manquant après config:cache.';

            return response()->json($status);
        }

        try {
            Mail::raw(
                'Diagnostic SMTP KerpheX — test interne admin.',
                fn ($message) => $message->to($username)->subject('Diagnostic SMTP · '.config('app.name'))
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
