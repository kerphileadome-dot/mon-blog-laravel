<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    /**
     * Rediriger vers Google pour l'authentification
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Gérer le callback de Google
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Chercher ou créer l'utilisateur
            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user) {
                // Vérifier si l'utilisateur est bloqué
                if ($user->blocked) {
                    return redirect()->route('login')->with('error', 'Votre compte a été bloqué. Contactez l\'administrateur.');
                }

                // L'utilisateur existe déjà
                Auth::login($user);
            } else {
                // Créer un nouveau compte visiteur via Google
                $user = new User();
                $user->name = $googleUser->getName();
                $user->email = $googleUser->getEmail();
                $user->password = bcrypt(uniqid()); // Mot de passe aléatoire (non utilisé)
                $user->role = $this->isAdminEmail($googleUser->getEmail()) ? 'admin' : 'visitor';
                $user->email_verified_at = now();
                $user->blocked = false;
                $user->save();

                Auth::login($user);
            }

            // Rediriger selon le rôle
            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard');
            } else {
                return redirect()->route('posts.index')->with('success', 'Bienvenue sur le blog !');
            }

        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Erreur lors de la connexion avec Google. Veuillez réessayer.');
        }
    }

    protected function isAdminEmail(string $email): bool
    {
        return in_array($email, config('blog.admin_emails', []), true)
            || User::where('email', $email)->where('role', 'admin')->exists();
    }
}
