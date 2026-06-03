<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin · {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="blog-body">

    <div class="noise-overlay"></div>

    <nav class="blog-nav" style="border-bottom:1px solid var(--border);">
        <div class="nav-inner" style="max-width:100%;padding:0 2rem;">
            <!-- Logo professionnel séparé -->
            <div style="display:flex;align-items:center;gap:3rem;">
                <a href="{{ route('admin.dashboard') }}" style="display:flex;align-items:center;gap:0.75rem;text-decoration:none;">
                    <div style="width:40px;height:40px;background:linear-gradient(135deg, #00bf72 0%, #00a862 100%);border-radius:10px;display:flex;align-items:center;justify-content:center;font-weight:700;color:white;font-size:1.25rem;box-shadow:0 4px 6px rgba(0,191,114,0.3);">
                        K
                    </div>
                    <div>
                        <div style="font-size:1.1rem;font-weight:700;color:var(--text-primary);line-height:1.2;">{{ config('app.name') }}</div>
                        <div style="font-size:0.7rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.05em;">Admin Panel</div>
                    </div>
                </a>

                <!-- Menu admin - caché sur mobile -->
                <div style="display:flex;align-items:center;gap:0.5rem;" class="desktop-menu">
                    <a href="{{ route('admin.dashboard') }}" class="btn-ghost">📊 Dashboard</a>
                    <a href="{{ route('admin.posts') }}" class="btn-ghost">📝 Articles</a>
                    <a href="{{ route('admin.comments') }}" class="btn-ghost">💬 Commentaires</a>
                    <a href="{{ route('admin.users.index') }}" class="btn-ghost">👥 Utilisateurs</a>
                    <a href="{{ route('admin.media.index') }}" class="btn-ghost">🖼️ Médias</a>
                    <a href="{{ route('admin.settings.index') }}" class="btn-ghost">⚙️ Paramètres</a>
                </div>
            </div>

            <!-- Actions droite -->
            <div style="display:flex;align-items:center;gap:1rem;">
                <a href="{{ route('admin.posts.create') }}" class="btn-primary">+ Nouvel article</a>
                <form method="POST" action="{{ route('admin.logout') }}" class="inline">
                    @csrf
                    <button class="btn-ghost">Déconnexion</button>
                </form>
            </div>
        </div>
    </nav>

    <style>
        @media (max-width: 1024px) {
            .desktop-menu { display: none !important; }
        }
    </style>

    @if(session('success'))
        <div class="flash-msg">
            ✓ {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="position:fixed;top:5rem;right:2rem;background:#ef4444;color:white;padding:1rem 1.5rem;border-radius:12px;box-shadow:0 4px 12px rgba(239,68,68,0.3);z-index:1000;animation:slideIn 0.3s ease;">
            ✕ {{ session('error') }}
        </div>
    @endif

    <main class="blog-main" style="padding-top:1rem;">
        @yield('content')
    </main>

    <footer class="blog-footer">
        <div class="footer-inner">
            <p class="footer-brand">{{ config('app.name') }} Admin</p>
            <p class="footer-copy">© {{ date('Y') }} · Panneau d'administration</p>
        </div>
    </footer>

</body>
</html>
