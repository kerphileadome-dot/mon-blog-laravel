<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SyncExportMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = config('blog.sync_export_token');

        if (blank($token)) {
            abort(404);
        }

        $provided = $request->bearerToken()
            ?? $request->header('X-Sync-Token');

        if (! hash_equals($token, (string) $provided)) {
            abort(404);
        }

        return $next($request);
    }
}
