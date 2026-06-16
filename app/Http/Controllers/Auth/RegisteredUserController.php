<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\RegistrationOtpNotification;
use App\Rules\GmailEmail;
use App\Services\RegistrationOtpService;
use App\Support\MailConfigured;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function __construct(
        protected RegistrationOtpService $otpService,
    ) {}

    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required', 'string', 'lowercase', 'email', 'max:255',
                'unique:'.User::class,
                new GmailEmail,
                function (string $attribute, mixed $value, \Closure $fail): void {
                    if (in_array($value, config('blog.admin_emails', []), true)) {
                        $fail('Cet email est réservé à l\'administration du blog.');
                    }
                },
            ],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        if (! MailConfigured::isReady()) {
            throw ValidationException::withMessages([
                'email' => 'L\'envoi d\'emails n\'est pas configuré. Contactez l\'administrateur du blog.',
            ]);
        }

        try {
            $started = $this->otpService->start(
                $request->string('name')->toString(),
                $request->string('email')->toString(),
                $request->string('password')->toString(),
            );

            Notification::route('mail', $started['pending']['email'])->notify(
                new RegistrationOtpNotification($started['otp'], $started['pending']['name'])
            );
        } catch (\Throwable $e) {
            logger()->error('registration.otp.send.failed', ['message' => $e->getMessage()]);
            $this->otpService->forget($request->string('email')->toString());

            throw ValidationException::withMessages([
                'email' => 'Impossible d\'envoyer le code de confirmation. Vérifiez que votre adresse Gmail est correcte et réessayez.',
            ]);
        }

        $request->session()->put('registration_email', $started['pending']['email']);

        return redirect()
            ->route('register.verify')
            ->with('status', 'Un code de confirmation a été envoyé à votre adresse Gmail.');
    }
}
