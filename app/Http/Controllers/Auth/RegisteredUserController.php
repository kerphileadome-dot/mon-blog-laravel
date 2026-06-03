<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\WelcomeNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->role = 'visitor'; // Par défaut, tous les nouveaux utilisateurs sont des visiteurs
        $user->save();

        event(new Registered($user));

        // Envoyer l'email de bienvenue (désactivé en production si MAIL_MAILER=log)
        try {
            $user->notify(new WelcomeNotification());
        } catch (\Exception $e) {
            // Email échoué mais on continue l'inscription
            \Log::warning('Email de bienvenue non envoyé: ' . $e->getMessage());
        }

        Auth::login($user);

        return redirect(route('posts.index', absolute: false))->with('success', 'Bienvenue sur le blog !');
    }
}
