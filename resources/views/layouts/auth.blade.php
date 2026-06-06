<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'KerpheX')</title>
    <x-fonts />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="blog-body auth-page">

    <div class="noise-overlay"></div>
    <div class="auth-bg-glow"></div>

    <div class="auth-wrapper">
        <div class="auth-header">
            <a href="{{ route('posts.index') }}" style="text-decoration:none;">
                <x-kerphex-logo size="lg" />
            </a>
            @hasSection('subtitle')
                <p class="auth-subtitle">@yield('subtitle')</p>
            @endif
        </div>

        @if(session('success'))
            <div class="flash-msg auth-flash">✓ {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="flash-msg auth-flash" style="background:rgba(220,38,38,0.08);border-color:rgba(220,38,38,0.25);color:#dc2626;">
                ✕ {{ session('error') }}
            </div>
        @endif
        @if(session('status'))
            <div class="flash-msg auth-flash">✓ {{ session('status') }}</div>
        @endif

        <div class="auth-card">
            @yield('content')
        </div>

        @hasSection('footer')
            <div class="auth-footer">@yield('footer')</div>
        @endif
    </div>

</body>
</html>
