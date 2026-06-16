<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\RegistrationOtpNotification;
use App\Services\RegistrationOtpService;
use App\Support\AdminSession;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisterVerificationController extends Controller
{
    public function __construct(
        protected RegistrationOtpService $otpService,
    ) {}

    public function create(Request $request): View|RedirectResponse
    {
        $email = $request->session()->get('registration_email');

        if (! is_string($email) || $this->otpService->get($email) === null) {
            return redirect()->route('register')->with('error', 'Votre session d\'inscription a expiré. Recommencez l\'inscription.');
        }

        return view('auth.verify-registration', [
            'email' => $email,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $email = $request->session()->get('registration_email');

        if (! is_string($email) || $this->otpService->get($email) === null) {
            return redirect()->route('register')->with('error', 'Votre session d\'inscription a expiré. Recommencez l\'inscription.');
        }

        $request->validate([
            'otp' => ['required', 'string', 'digits:6'],
        ]);

        if (! $this->otpService->verify($email, $request->string('otp')->toString())) {
            throw ValidationException::withMessages([
                'otp' => 'Code incorrect ou expiré. Vérifiez votre boîte Gmail ou demandez un nouveau code.',
            ]);
        }

        $pending = $this->otpService->get($email);

        if ($pending === null) {
            return redirect()->route('register')->with('error', 'Votre session d\'inscription a expiré. Recommencez l\'inscription.');
        }

        if (User::where('email', $email)->exists()) {
            $this->otpService->forget($email);
            $request->session()->forget('registration_email');

            return redirect()->route('login')->with('status', 'Un compte existe déjà avec cet email. Connectez-vous.');
        }

        $user = User::create([
            'name' => $pending['name'],
            'email' => $pending['email'],
            'password' => $pending['password'],
            'role' => 'visitor',
            'blocked' => false,
            'email_verified_at' => now(),
        ]);

        $this->otpService->forget($email);
        $request->session()->forget('registration_email');

        AdminSession::clearUnlessAdminEmail($user->email);
        Auth::guard('web')->login($user);
        $request->session()->regenerate();

        return redirect()->route('posts.index')->with('success', 'Compte confirmé. Bienvenue sur KerpheX !');
    }

    public function resend(Request $request): RedirectResponse
    {
        $email = $request->session()->get('registration_email');

        if (! is_string($email)) {
            return redirect()->route('register')->with('error', 'Session d\'inscription introuvable.');
        }

        $pending = $this->otpService->get($email);

        if ($pending === null) {
            return redirect()->route('register')->with('error', 'Votre session d\'inscription a expiré. Recommencez l\'inscription.');
        }

        try {
            $regenerated = $this->otpService->regenerateOtp($email);

            if ($regenerated === null) {
                return redirect()->route('register')->with('error', 'Votre session d\'inscription a expiré. Recommencez l\'inscription.');
            }

            Notification::route('mail', $email)->notify(
                new RegistrationOtpNotification($regenerated['otp'], $pending['name'])
            );
        } catch (\Throwable $e) {
            logger()->error('registration.otp.resend.failed', ['message' => $e->getMessage()]);

            return back()->with('error', 'Impossible d\'envoyer le code. Vérifiez la configuration email du serveur.');
        }

        return back()->with('status', 'Un nouveau code a été envoyé à votre adresse Gmail.');
    }
}
