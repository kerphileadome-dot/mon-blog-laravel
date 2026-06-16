<?php

namespace App\Http\Controllers;

use App\Support\MailConfigured;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class SyncExportController extends Controller
{
    public function database(): BinaryFileResponse
    {
        $path = database_path('database.sqlite');

        if (! File::exists($path) || File::size($path) === 0) {
            abort(404);
        }

        return response()->download($path, 'database.sqlite', [
            'Content-Type' => 'application/x-sqlite3',
        ]);
    }

    public function settings()
    {
        $path = storage_path('app/blog_settings.json');

        if (! File::exists($path)) {
            return response()->json([]);
        }

        return response()->file($path, [
            'Content-Type' => 'application/json',
        ]);
    }

    public function storageManifest(): JsonResponse
    {
        $root = storage_path('app/public');
        $files = [];

        if (File::isDirectory($root)) {
            foreach (File::allFiles($root) as $file) {
                $relative = str_replace('\\', '/', substr($file->getPathname(), strlen($root) + 1));
                $files[] = [
                    'path' => $relative,
                    'size' => $file->getSize(),
                ];
            }
        }

        return response()->json(['files' => $files]);
    }

    public function storageFile(Request $request, string $path): BinaryFileResponse
    {
        $path = str_replace('\\', '/', $path);
        if ($path === '' || str_contains($path, '..')) {
            abort(404);
        }

        $fullPath = storage_path('app/public/'.$path);

        if (! File::isFile($fullPath)) {
            abort(404);
        }

        return response()->download($fullPath);
    }

    public function mailHealth(Request $request): JsonResponse
    {
        $mailer = config('mail.default');

        $status = [
            'mailer' => $mailer,
            'from_address' => config('mail.from.address'),
            'brevo_key_set' => filled(config('services.brevo.key')),
            'smtp_configured' => filled(config('mail.mailers.smtp.username'))
                && filled(config('mail.mailers.smtp.password')),
        ];

        if ($request->boolean('probe')) {
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
                    'Test email KerpheX — diagnostic interne.',
                    fn ($message) => $message->to($recipient)->subject('Probe email · '.config('app.name'))
                );
                $status['probe'] = 'ok';
                $status['test_sent_to'] = $recipient;
            } catch (\Throwable $e) {
                $status['probe'] = 'failed';
                $status['error'] = $e->getMessage();
            }
        }

        return response()->json($status);
    }
}
