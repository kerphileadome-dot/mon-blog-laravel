<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        Auth::shouldUse('admin');

        if (! Auth::guard('admin')->check() || ! Auth::guard('admin')->user()->isAdmin()) {
            abort(403, 'Accès non autorisé.');
        }

        return $next($request);
    }
}
