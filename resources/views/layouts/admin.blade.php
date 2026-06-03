<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body style="background:#f3f4f6;min-height:100vh;">

    <!-- Navigation Admin -->
    <nav style="background:white;border-bottom:1px solid #e5e7eb;padding:1rem 0;">
        <div style="max-width:1400px;margin:0 auto;padding:0 2rem;display:flex;justify-content:space-between;align-items:center;">
            <div style="display:flex;align-items:center;gap:3rem;">
                <a href="{{ route('posts.index') }}" style="font-size:1.5rem;font-weight:700;color:#111827;text-decoration:none;">
                    {{ config('app.name') }}
                </a>
                <div style="display:flex;gap:1.5rem;">
                    <a href="{{ route('admin.dashboard') }}" style="color:#6b7280;text-decoration:none;font-weight:500;padding:0.5rem 1rem;border-radius:0.5rem;transition:all 0.2s;{{ request()->routeIs('admin.dashboard') ? 'background:#f3f4f6;color:#111827;' : '' }}">
                        📊 Dashboard
                    </a>
                    <a href="{{ route('admin.posts') }}" style="color:#6b7280;text-decoration:none;font-weight:500;padding:0.5rem 1rem;border-radius:0.5rem;transition:all 0.2s;{{ request()->routeIs('admin.posts') ? 'background:#f3f4f6;color:#111827;' : '' }}">
                        📝 Articles
                    </a>
                    <a href="{{ route('admin.comments') }}" style="color:#6b7280;text-decoration:none;font-weight:500;padding:0.5rem 1rem;border-radius:0.5rem;transition:all 0.2s;{{ request()->routeIs('admin.comments') ? 'background:#f3f4f6;color:#111827;' : '' }}">
                        💬 Commentaires
                    </a>
                    <a href="{{ route('admin.users.index') }}" style="color:#6b7280;text-decoration:none;font-weight:500;padding:0.5rem 1rem;border-radius:0.5rem;transition:all 0.2s;{{ request()->routeIs('admin.users.*') ? 'background:#f3f4f6;color:#111827;' : '' }}">
                        👥 Utilisateurs
                    </a>
                    <a href="{{ route('admin.settings.index') }}" style="color:#6b7280;text-decoration:none;font-weight:500;padding:0.5rem 1rem;border-radius:0.5rem;transition:all 0.2s;{{ request()->routeIs('admin.settings.*') ? 'background:#f3f4f6;color:#111827;' : '' }}">
                        ⚙️ Paramètres
                    </a>
                </div>
            </div>
            <div style="display:flex;gap:1rem;align-items:center;">
                <a href="{{ route('posts.create') }}" style="background:#00bf72;color:white;padding:0.5rem 1.5rem;border-radius:0.5rem;text-decoration:none;font-weight:600;">
                    + Nouvel article
                </a>
                <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                    @csrf
                    <button style="background:transparent;border:none;color:#6b7280;cursor:pointer;font-weight:500;padding:0.5rem 1rem;">
                        Déconnexion
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Contenu -->
    <main>
        @yield('content')
    </main>

</body>
</html>
