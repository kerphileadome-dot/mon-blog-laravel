<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} · Blog</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="blog-body">

    <div class="noise-overlay"></div>
    <div class="progress-bar" id="progressBar"></div>

    <nav class="blog-nav">
        <div class="nav-inner">
            <a href="{{ route('posts.index') }}" style="display:flex;align-items:center;gap:0.75rem;text-decoration:none;">
                <div style="width:36px;height:36px;background:linear-gradient(135deg, #00bf72 0%, #00a862 100%);border-radius:8px;display:flex;align-items:center;justify-content:center;font-weight:700;color:white;font-size:1.1rem;box-shadow:0 2px 8px rgba(0,191,114,0.25);">
                    K
                </div>
                <span style="font-size:1.25rem;font-weight:700;color:var(--text-primary);">{{ config('app.name') }}</span>
            </a>
            <div class="nav-links">
                @auth
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="btn-ghost">📊 Dashboard</a>
                        <a href="{{ route('admin.posts') }}" class="btn-ghost">📝 Articles</a>
                        <a href="{{ route('admin.comments') }}" class="btn-ghost">💬 Commentaires</a>
                        <a href="{{ route('admin.users.index') }}" class="btn-ghost">👥 Utilisateurs</a>
                        <a href="{{ route('admin.media.index') }}" class="btn-ghost">🖼️ Médias</a>
                        <a href="{{ route('admin.settings.index') }}" class="btn-ghost">⚙️ Paramètres</a>
                        <a href="{{ route('admin.posts.create') }}" class="btn-primary">+ Nouvel article</a>
                    @else
                        <a href="{{ route('favorites.index') }}" class="btn-ghost">⭐ Mes Favoris</a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button class="btn-ghost">Déconnexion</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn-ghost">Se connecter</a>
                    <a href="{{ route('register') }}" class="btn-primary">S'inscrire</a>
                @endauth
            </div>
        </div>
    </nav>

    @if(session('success'))
        <div class="flash-msg">
            ✓ {{ session('success') }}
        </div>
    @endif

    <main class="blog-main">
        @yield('content')
    </main>

    <footer class="blog-footer">
        <div class="footer-inner">
            <p class="footer-brand">{{ config('app.name') }}</p>
            <p class="footer-copy">© {{ date('Y') }} · Tous droits réservés</p>
        </div>
    </footer>

    <script>
        window.addEventListener('scroll', () => {
            const winScroll = document.body.scrollTop || document.documentElement.scrollTop;
            const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            const scrolled = (winScroll / height) * 100;
            const bar = document.getElementById('progressBar');
            if (bar) bar.style.width = scrolled + '%';
        });

        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry, i) => {
                if (entry.isIntersecting) {
                    setTimeout(() => entry.target.classList.add('visible'), i * 100);
                }
            });
        }, { threshold: 0.1 });

        document.querySelectorAll('.post-card').forEach(card => observer.observe(card));
    </script>

</body>
</html>
