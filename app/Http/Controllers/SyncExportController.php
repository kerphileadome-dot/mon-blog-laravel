<?php

namespace App\Http\Controllers;

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
        $username = config('mail.mailers.smtp.username');
        $password = config('mail.mailers.smtp.password');

        $status = [
            'mailer' => config('mail.default'),
            'host' => config('mail.mailers.smtp.host'),
            'port' => config('mail.mailers.smtp.port'),
            'scheme' => config('mail.mailers.smtp.scheme'),
            'username' => $username,
            'from_address' => config('mail.from.address'),
            'password_set' => filled($password),
            'password_length' => is_string($password) ? strlen($password) : 0,
            'ssl_verify' => config('mail.mailers.smtp.verify_peer'),
            'from_matches_username' => strtolower((string) config('mail.from.address')) === strtolower((string) $username),
        ];

        if ($request->boolean('probe')) {
            if (! filled($username) || ! filled($password)) {
                $status['probe'] = 'failed';
                $status['error'] = 'MAIL_USERNAME ou MAIL_PASSWORD manquant.';

                return response()->json($status);
            }

            try {
                Mail::raw(
                    'Test SMTP KerpheX — diagnostic interne.',
                    fn ($message) => $message->to($username)->subject('Probe SMTP · '.config('app.name'))
                );
                $status['probe'] = 'ok';
            } catch (\Throwable $e) {
                $status['probe'] = 'failed';
                $status['error'] = $e->getMessage();
            }
        }

        return response()->json($status);
    }
}
