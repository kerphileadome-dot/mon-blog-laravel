<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

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

    public function storageManifest(): \Illuminate\Http\JsonResponse
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
}
