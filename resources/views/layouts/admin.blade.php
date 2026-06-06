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

    <nav class="blog-nav">
        <div class="nav-inner" style="max-width:100%;">
            <a href="{{ route('admin.dashboard') }}" class="blog-logo">
                <x-kerphex-logo size="sm" />
            </a>

            <div class="nav-center desktop-menu">
                <a href="{{ route('admin.dashboard') }}" class="nav-link">Dashboard</a>
                <a href="{{ route('admin.posts') }}" class="nav-link">Articles</a>
                <a href="{{ route('admin.comments') }}" class="nav-link">Commentaires</a>
                <a href="{{ route('admin.users.index') }}" class="nav-link">Utilisateurs</a>
                <a href="{{ route('admin.media.index') }}" class="nav-link">Médias</a>
                <a href="{{ route('admin.settings.index') }}" class="nav-link">Paramètres</a>
            </div>

            <div class="nav-links">
                <a href="{{ route('posts.index') }}" class="btn-ghost nav-desktop-only">Voir le blog</a>
                <a href="{{ route('admin.posts.create') }}" class="btn-primary btn-accent nav-desktop-only">Écrire</a>
                <form method="POST" action="{{ route('admin.logout') }}" class="inline nav-desktop-only">
                    @csrf
                    <button class="btn-ghost">Déconnexion</button>
                </form>
                <button class="nav-toggle" id="navToggle" aria-label="Menu">
                    <span></span><span></span><span></span>
                </button>
            </div>
        </div>
    </nav>

    <div class="mobile-menu" id="mobileMenu">
        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        <a href="{{ route('admin.posts') }}">Articles</a>
        <a href="{{ route('admin.comments') }}">Commentaires</a>
        <a href="{{ route('admin.users.index') }}">Utilisateurs</a>
        <a href="{{ route('admin.media.index') }}">Médias</a>
        <a href="{{ route('admin.settings.index') }}">Paramètres</a>
        <a href="{{ route('admin.posts.create') }}">Nouvel article</a>
        <a href="{{ route('posts.index') }}">Voir le blog</a>
        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button type="submit">Déconnexion</button>
        </form>
    </div>

    @if(session('success'))
        <div class="flash-msg">✓ {{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="flash-msg" style="background:rgba(220,38,38,0.08);border-color:rgba(220,38,38,0.25);color:#dc2626;">
            ✕ {{ session('error') }}
        </div>
    @endif

    <main class="blog-main" style="padding-top:1rem;">
        @yield('content')
    </main>

    <footer class="blog-footer">
        <div class="footer-inner">
            <div>
                <p class="footer-brand">{{ config('app.name') }} Admin</p>
                <p class="footer-desc">Panneau d'administration du blog.</p>
            </div>
            <div class="footer-copy">
                <span>© {{ date('Y') }} · Administration</span>
                <a href="{{ route('posts.index') }}" style="color:rgba(255,255,255,0.5);text-decoration:none;">Retour au blog →</a>
            </div>
        </div>
    </footer>

    <script>
        const navToggle = document.getElementById('navToggle');
        const mobileMenu = document.getElementById('mobileMenu');
        if (navToggle && mobileMenu) {
            navToggle.addEventListener('click', () => mobileMenu.classList.toggle('open'));
        }
    </script>

</body>
</html>
