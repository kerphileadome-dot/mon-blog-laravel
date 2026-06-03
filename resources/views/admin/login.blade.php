<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KerpheX · Connexion Admin</title>
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
                🔐 Connexion Administrateur
            </p>
        </div>

        <!-- Card -->
        <div style="background:var(--card);border:1px solid var(--border);border-radius:20px;padding:2rem;">

            @if(session('error'))
                <div style="background:#ef4444;color:white;padding:0.75rem;border-radius:0.5rem;margin-bottom:1.5rem;font-size:0.875rem;">
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login.submit') }}">
                @csrf

                <!-- Email -->
                <div style="margin-bottom:1.25rem;">
                    <label class="field-label">Email administrateur</label>
                    <input type="email" name="email"
                        class="form-input"
                        value="{{ old('email') }}"
                        placeholder="admin@exemple.com"
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
                    Se connecter au panel admin →
                </button>

            </form>

        </div>

        <!-- Lien visiteur -->
        <p style="text-align:center;margin-top:1.5rem;color:var(--text-dim);font-size:0.875rem;">
            Vous êtes visiteur ?
            <a href="{{ route('login') }}" style="color:var(--accent);text-decoration:none;">
                Connexion visiteur
            </a>
        </p>

    </div>
</body>
</html>
