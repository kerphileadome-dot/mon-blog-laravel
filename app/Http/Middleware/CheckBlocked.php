<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckBlocked
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::guard('web')->check() && Auth::guard('web')->user()->blocked) {
            Auth::guard('web')->logout();

            return redirect()->route('login')->with('error', 'Votre compte a été bloqué. Contactez l\'administrateur.');
        }

        return $next($request);
    }
}
