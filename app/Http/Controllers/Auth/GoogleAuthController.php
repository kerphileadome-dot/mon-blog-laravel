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
                // L'utilisateur existe déjà
                Auth::login($user);
            } else {
                // Créer un nouveau compte uniquement si l'email est autorisé
                // Pour un blog personnel, on limite à ton email
                if ($googleUser->getEmail() === 'kerphileadome@gmail.com') {
                    $user = User::create([
                        'name' => $googleUser->getName(),
                        'email' => $googleUser->getEmail(),
                        'password' => bcrypt(uniqid()), // Mot de passe aléatoire (non utilisé)
                        'role' => 'admin',
                        'email_verified_at' => now(),
                    ]);

                    Auth::login($user);
                } else {
                    // Email non autorisé
                    return redirect()->route('login')->with('error', 'Accès non autorisé. Seul l\'administrateur peut se connecter.');
                }
            }

            return redirect()->route('admin.dashboard');

        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Erreur lors de la connexion avec Google. Veuillez réessayer.');
        }
    }
}
