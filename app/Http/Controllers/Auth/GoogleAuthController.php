<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\AdminSession;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user) {
                if ($user->blocked) {
                    return redirect()->route('login')->with('error', 'Votre compte a été bloqué. Contactez l\'administrateur.');
                }

                if ($user->isAdmin()) {
                    return redirect()->to($this->adminLoginUrl())
                        ->with('error', 'Compte administrateur : utilisez la connexion admin.');
                }

                Auth::guard('web')->login($user);
            } else {
                if ($this->isAdminEmail($googleUser->getEmail())) {
                    return redirect()->to($this->adminLoginUrl())
                        ->with('error', 'Compte administrateur : utilisez la connexion admin.');
                }

                if (!$this->isGmailAddress($googleUser->getEmail())) {
                    return redirect()->route('login')->with('error', 'Seules les adresses Gmail (@gmail.com) peuvent créer un compte.');
                }

                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'password' => bcrypt(uniqid()),
                    'role' => 'visitor',
                    'email_verified_at' => now(),
                    'blocked' => false,
                ]);

                Auth::guard('web')->login($user);
            }

            AdminSession::clearUnlessAdminEmail($googleUser->getEmail());

            return redirect()->route('posts.index')->with('success', 'Bienvenue sur le blog !');
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Erreur lors de la connexion avec Google. Veuillez réessayer.');
        }
    }

    protected function isAdminEmail(string $email): bool
    {
        return in_array($email, config('blog.admin_emails', []), true)
            || User::where('email', $email)->where('role', 'admin')->exists();
    }

    protected function isGmailAddress(string $email): bool
    {
        return str_ends_with(strtolower($email), '@gmail.com');
    }

    protected function adminLoginUrl(): string
    {
        $key = config('blog.admin_login_key');

        return blank($key)
            ? route('admin.login')
            : route('admin.login', ['key' => $key]);
    }
}
