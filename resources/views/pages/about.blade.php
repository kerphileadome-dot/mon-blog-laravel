@extends('layouts.app')

@section('title', 'À propos · KerpheX')

@section('content')
<section class="about-hero">
    <x-kerphex-logo size="lg" style="justify-content:center;margin-bottom:2rem;" />
    <h1 class="hero-title" style="font-size:clamp(2rem,5vw,3.5rem);">Bienvenue sur <em>KerpheX</em></h1>
    <p class="hero-subtitle" style="max-width:600px;">
        {{ $settings['blog_description'] ?? 'Un espace dédié au partage d\'idées, d\'expériences et de réflexions.' }}
    </p>
</section>

<div class="about-stats">
    <div class="about-stat-card">
        <div class="about-stat-value">{{ $stats['posts'] }}</div>
        <div class="about-stat-label">Articles publiés</div>
    </div>
    <div class="about-stat-card">
        <div class="about-stat-value">{{ number_format($stats['views']) }}</div>
        <div class="about-stat-label">Lectures totales</div>
    </div>
    <div class="about-stat-card">
        <div class="about-stat-value">{{ $stats['categories'] }}</div>
        <div class="about-stat-label">Catégories</div>
    </div>
    <div class="about-stat-card">
        <div class="about-stat-value">{{ $stats['readers'] }}</div>
        <div class="about-stat-label">Lecteurs inscrits</div>
    </div>
</div>

<div class="about-content write-form" style="max-width:720px;margin:0 auto;">
    <h2 class="section-title" style="margin-bottom:1rem;">Notre mission</h2>
    <p style="color:var(--ink-soft);line-height:1.85;font-size:1.05rem;margin-bottom:1.5rem;">
        KerpheX est un blog professionnel conçu pour partager du contenu de qualité : tutoriels, réflexions et explorations.
        Chaque article est rédigé avec soin pour informer, inspirer et créer du dialogue avec la communauté.
    </p>
    <h2 class="section-title" style="margin-bottom:1rem;">Fonctionnalités</h2>
    <ul class="about-features">
        <li>Articles classés par catégories et tags</li>
        <li>Recherche full-text dans tout le contenu</li>
        <li>Commentaires, likes et favoris pour les membres</li>
        <li>Interface responsive et moderne</li>
    </ul>
    <div style="margin-top:2rem;display:flex;gap:1rem;flex-wrap:wrap;">
        <a href="{{ route('posts.index') }}" class="btn-primary btn-accent">Lire les articles</a>
        @guest
            <a href="{{ route('register') }}" class="btn-ghost">Rejoindre la communauté</a>
        @endguest
    </div>
</div>
@endsection
