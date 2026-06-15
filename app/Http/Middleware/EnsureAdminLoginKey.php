<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminLoginKey
{
    public function handle(Request $request, Closure $next): Response
    {
        $key = config('blog.admin_login_key');

        if (blank($key)) {
            if (app()->environment('local')) {
                return $next($request);
            }

            abort(404);
        }

        $provided = $request->query('key') ?? $request->session()->get('admin_login_key');

        if (! is_string($provided) || ! hash_equals($key, $provided)) {
            abort(404);
        }

        $request->session()->put('admin_login_key', $key);

        return $next($request);
    }
}
