<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KerpheX · Connexion</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="blog-body" style="display:flex;align-items:center;justify-content:center;min-height:100vh;">

    <div class="noise-overlay"></div>

    <div style="width:100%;max-width:420px;padding:2rem;">

        <!-- Logo -->
        <div style="text-align:center;margin-bottom:2.5rem;">
            <a href="{{ route('posts.index') }}" class="blog-logo" style="justify-content:center;">
                <span class="logo-dot"></span>
                <span>KerpheX</span>
            </a>
            <p style="color:var(--text-muted);margin-top:0.75rem;font-size:0.9rem;">
                Bienvenue ! Connectez-vous pour continuer.
            </p>
        </div>

        <!-- Card -->
        <div style="background:var(--card);border:1px solid var(--border);border-radius:20px;padding:2rem;">

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email -->
                <div style="margin-bottom:1.25rem;">
                    <label class="field-label">Email</label>
                    <input type="email" name="email"
                        class="form-input"
                        value="{{ old('email') }}"
                        placeholder="votre@email.com"
                        required autofocus>
                    @error('email')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Mot de passe -->
                <div style="margin-bottom:1.5rem;">
                    <label class="field-label">Mot de passe</label>
                    <input type="password" name="password"
                        class="form-input"
                        placeholder="••••••••"
                        required>
                    @error('password')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Se souvenir -->
                <div style="margin-bottom:1.5rem;">
                    <label class="publish-checkbox">
                        <input type="checkbox" name="remember"
                            style="width:1rem;height:1rem;accent-color:var(--accent);">
                        Se souvenir de moi
                    </label>
                </div>

                <!-- Bouton -->
                <button type="submit" class="btn-primary" style="width:100%;justify-content:center;padding:0.75rem;">
                    Se connecter →
                </button>

            </form>

        </div>

        <!-- Message blog personnel -->
        <p style="text-align:center;margin-top:1.5rem;color:var(--text-dim);font-size:0.875rem;">
            Blog personnel · Connexion administrateur uniquement
        </p>

    </div>
</body>
</html>
