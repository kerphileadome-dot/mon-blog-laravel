<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\MailConfigurationMessages;
use App\Support\MailConfigured;
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
        try {
            $request->validate([
                'email' => ['required', 'email'],
            ]);

            $email = strtolower($request->email);

            if ($this->isAdminEmail($email)) {
                throw ValidationException::withMessages([
                    'email' => 'Compte administrateur : la réinitialisation se fait via l\'accès admin ou contactez le propriétaire du blog.',
                ]);
            }

            if (! MailConfigured::isReady()) {
                throw ValidationException::withMessages([
                    'email' => MailConfigurationMessages::notConfigured(),
                ]);
            }

            $status = Password::sendResetLink(['email' => $email]);

            return $status == Password::RESET_LINK_SENT
                ? back()->with('status', __($status))
                : back()->withInput($request->only('email'))
                    ->withErrors(['email' => __($status)]);
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            logger()->error('password.reset.failed', ['message' => $e->getMessage()]);

            throw ValidationException::withMessages([
                'email' => MailConfigurationMessages::sendFailed(),
            ]);
        }
    }

    protected function isAdminEmail(string $email): bool
    {
        return in_array($email, config('blog.admin_emails', []), true)
            || User::where('email', $email)->where('role', 'admin')->exists();
    }
}
