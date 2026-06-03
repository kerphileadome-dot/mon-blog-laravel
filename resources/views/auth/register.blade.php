<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KerpheX · Inscription</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="blog-body" style="display:flex;align-items:center;justify-content:center;min-height:100vh;">

    <div class="noise-overlay"></div>

    <div style="width:100%;max-width:420px;padding:2rem;">

        <!-- Logo -->
        <div style="text-align:center;margin-bottom:2.5rem;">
            <a href="{{ route('posts.index') }}" class="blog-logo" style="justify-content:center;">
                <span class="logo-dot"></span>
                <span>{{ config('app.name') }}</span>
            </a>
            <p style="color:var(--text-muted);margin-top:0.75rem;font-size:0.9rem;">
                Créez votre compte pour accéder aux articles.
            </p>
        </div>

        <!-- Card -->
        <div style="background:var(--card);border:1px solid var(--border);border-radius:20px;padding:2rem;">

            <!-- Container centré pour tous les champs -->
            <div style="max-width:320px;margin:0 auto;">

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <!-- Nom -->
                    <div style="margin-bottom:1.25rem;">
                        <label class="field-label">Nom</label>
                        <input type="text" name="name"
                            class="form-input"
                            value="{{ old('name') }}"
                            placeholder="Votre nom"
                            required autofocus>
                        @error('name')
                            <p class="field-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div style="margin-bottom:1.25rem;">
                        <label class="field-label">Email</label>
                        <input type="email" name="email"
                            class="form-input"
                            value="{{ old('email') }}"
                            placeholder="votre@email.com"
                            required>
                        @error('email')
                            <p class="field-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Mot de passe -->
                    <div style="margin-bottom:1.25rem;">
                        <label class="field-label">Mot de passe</label>
                        <input type="password" name="password"
                            class="form-input"
                            placeholder="Minimum 8 caractères"
                            required>
                        @error('password')
                            <p class="field-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirmation -->
                    <div style="margin-bottom:1.5rem;">
                        <label class="field-label">Confirmer le mot de passe</label>
                        <input type="password" name="password_confirmation"
                            class="form-input"
                            placeholder="Répétez le mot de passe"
                            required>
                    </div>

                    <!-- Bouton -->
                    <button type="submit" class="btn-primary" style="width:100%;justify-content:center;padding:0.75rem;">
                        Créer mon compte →
                    </button>

                </form>

            </div>

        </div>

        <!-- Lien login -->
        <p style="text-align:center;margin-top:1.5rem;color:var(--text-dim);font-size:0.875rem;">
            Déjà un compte ?
            <a href="{{ route('login') }}" style="color:var(--accent);text-decoration:none;">
                Se connecter
            </a>
        </p>

    </div>
</body>
</html>
