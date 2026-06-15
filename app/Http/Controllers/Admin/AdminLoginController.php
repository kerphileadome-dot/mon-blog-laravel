<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AdminLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('admin')->attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            $user = Auth::guard('admin')->user();

            if ($user->blocked) {
                Auth::guard('admin')->logout();
                throw ValidationException::withMessages([
                    'email' => 'Votre compte a été bloqué.',
                ]);
            }

            if (!$user->isAdmin()) {
                Auth::guard('admin')->logout();
                throw ValidationException::withMessages([
                    'email' => 'Accès administrateur uniquement.',
                ]);
            }

            $request->session()->regenerate();

            return redirect()->intended(route('admin.dashboard'));
        }

        throw ValidationException::withMessages([
            'email' => 'Ces identifiants ne correspondent pas à nos enregistrements.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->regenerateToken();

        return redirect()->route('posts.index');
    }
}
