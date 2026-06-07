<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $email = strtolower($request->email);

        if ($this->isAdminEmail($email)) {
            throw ValidationException::withMessages([
                'email' => 'Compte administrateur : la réinitialisation se fait via l\'accès admin ou contactez le propriétaire du blog.',
            ]);
        }

        if ($this->mailNotConfigured()) {
            throw ValidationException::withMessages([
                'email' => 'L\'envoi d\'emails n\'est pas configuré sur le serveur. Contactez l\'administrateur du blog.',
            ]);
        }

        try {
            $status = Password::sendResetLink(['email' => $email]);
        } catch (\Throwable $e) {
            report($e);

            throw ValidationException::withMessages([
                'email' => 'Impossible d\'envoyer l\'email pour le moment. Réessayez dans quelques minutes ou contactez l\'administrateur.',
            ]);
        }

        return $status == Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withInput($request->only('email'))
                ->withErrors(['email' => __($status)]);
    }

    protected function isAdminEmail(string $email): bool
    {
        return in_array($email, config('blog.admin_emails', []), true)
            || User::where('email', $email)->where('role', 'admin')->exists();
    }

    protected function mailNotConfigured(): bool
    {
        $mailer = config('mail.default');

        if (in_array($mailer, ['log', 'array'], true)) {
            return app()->environment('production');
        }

        if ($mailer === 'smtp') {
            return blank(config('mail.mailers.smtp.username'))
                || blank(config('mail.mailers.smtp.password'));
        }

        return false;
    }
}
