@extends('layouts.app')

@section('title', 'Page introuvable · KerpheX')

@section('content')
<div class="empty-state" style="margin-top:4rem;">
    <div class="empty-icon" style="font-family:var(--font-display);font-size:3rem;font-weight:600;color:var(--accent);">404</div>
    <h1 class="empty-title">Page introuvable</h1>
    <p class="empty-desc">La page que vous cherchez n'existe pas ou a été déplacée.</p>
    <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;">
        <a href="{{ route('posts.index') }}" class="btn-primary btn-accent">Retour à l'accueil</a>
        <a href="{{ route('search') }}" class="btn-ghost">Rechercher</a>
    </div>
</div>
@endsection
