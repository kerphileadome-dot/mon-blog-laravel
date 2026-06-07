<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="@yield('meta_description', 'KerpheX — Blog professionnel. Idées, explorations et réflexions.')">
    <meta name="author" content="KerpheX">
    <title>@yield('title', 'KerpheX · Blog professionnel')</title>
    <x-fonts />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="blog-body">

    <div class="noise-overlay"></div>
    <div class="progress-bar" id="progressBar"></div>

    <nav class="blog-nav">
        <div class="nav-inner">
            <a href="{{ route('posts.index') }}" class="blog-logo">
                <x-kerphex-logo size="sm" />
            </a>

            <form method="GET" action="{{ route('search') }}" class="nav-search nav-desktop-only">
                <input type="search" name="q" value="{{ request('q') }}" placeholder="Rechercher…" aria-label="Rechercher">
                <button type="submit" aria-label="Lancer la recherche">→</button>
            </form>

            <div class="nav-center nav-desktop-only">
                <a href="{{ route('posts.index') }}" class="nav-link {{ request()->routeIs('posts.index') ? 'active' : '' }}">Accueil</a>
                <a href="{{ route('categories.index') }}" class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">Catégories</a>
                <a href="{{ route('tags.index') }}" class="nav-link {{ request()->routeIs('tags.*') ? 'active' : '' }}">Tags</a>
                <a href="{{ route('about') }}" class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}">À propos</a>
            </div>

            <div class="nav-links">
                @auth('web')
                    <a href="{{ route('favorites.index') }}" class="btn-ghost nav-desktop-only">Favoris</a>
                    <a href="{{ route('profile.edit') }}" class="btn-ghost nav-desktop-only">Profil</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline nav-desktop-only">
                        @csrf
                        <button class="btn-ghost">Déconnexion</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn-ghost nav-desktop-only">Connexion</a>
                    <a href="{{ route('register') }}" class="btn-primary btn-accent nav-desktop-only">S'inscrire</a>
                @endauth
                @auth('admin')
                    <a href="{{ route('admin.dashboard') }}" class="btn-ghost nav-desktop-only">Administration</a>
                @endauth
                <button class="nav-toggle" id="navToggle" aria-label="Menu"><span></span><span></span><span></span></button>
            </div>
        </div>
    </nav>

    <div class="mobile-menu" id="mobileMenu">
        <form method="GET" action="{{ route('search') }}" class="nav-search" style="margin-bottom:0.5rem;">
            <input type="search" name="q" placeholder="Rechercher…">
            <button type="submit">→</button>
        </form>
        <a href="{{ route('posts.index') }}">Accueil</a>
        <a href="{{ route('categories.index') }}">Catégories</a>
        <a href="{{ route('tags.index') }}">Tags</a>
        <a href="{{ route('about') }}">À propos</a>
        @auth('web')
            <a href="{{ route('favorites.index') }}">Mes favoris</a>
            <a href="{{ route('profile.edit') }}">Mon profil</a>
            <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit">Déconnexion</button></form>
        @else
            <a href="{{ route('login') }}">Connexion</a>
            <a href="{{ route('register') }}">S'inscrire</a>
        @endauth
        @auth('admin')
            <a href="{{ route('admin.dashboard') }}">Administration</a>
        @endauth
    </div>

    @if(session('success'))
        <div class="flash-msg">✓ {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="flash-msg flash-error">✕ {{ session('error') }}</div>
    @endif

    <main class="blog-main @yield('main_class')">
        @yield('content')
    </main>

    <footer class="blog-footer">
        <div class="footer-inner">
            <div>
                <x-kerphex-logo size="sm" style="margin-bottom:1rem;" />
                <p class="footer-desc">Blog professionnel — idées, explorations et réflexions partagées avec passion.</p>
            </div>
            <div class="footer-links">
                <a href="{{ route('posts.index') }}">Articles</a>
                <a href="{{ route('categories.index') }}">Catégories</a>
                <a href="{{ route('tags.index') }}">Tags</a>
                <a href="{{ route('search') }}">Recherche</a>
                <a href="{{ route('about') }}">À propos</a>
                @auth('admin')
                    <a href="{{ route('admin.dashboard') }}">Administration</a>
                @endauth
            </div>
            <div class="footer-copy">
                <span>© {{ date('Y') }} KerpheX · Tous droits réservés</span>
                <span>Propulsé par Laravel</span>
            </div>
        </div>
    </footer>

    <script>
        const progressBar = document.getElementById('progressBar');
        window.addEventListener('scroll', () => {
            const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            if (progressBar) progressBar.style.width = (height > 0 ? (window.scrollY / height) * 100 : 0) + '%';
        });
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry, i) => {
                if (entry.isIntersecting) setTimeout(() => entry.target.classList.add('visible'), i * 80);
            });
        }, { threshold: 0.08 });
        document.querySelectorAll('.post-card, .category-card, .about-stat-card').forEach(el => observer.observe(el));
        const navToggle = document.getElementById('navToggle');
        const mobileMenu = document.getElementById('mobileMenu');
        if (navToggle && mobileMenu) {
            navToggle.addEventListener('click', () => mobileMenu.classList.toggle('open'));
            document.addEventListener('click', (e) => {
                if (!navToggle.contains(e.target) && !mobileMenu.contains(e.target)) mobileMenu.classList.remove('open');
            });
        }
    </script>
    @stack('scripts')
</body>
</html>
