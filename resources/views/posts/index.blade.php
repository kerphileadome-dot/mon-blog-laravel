@extends('layouts.app')

@section('content')

<div class="hero">
    <span class="hero-label">✦ Blog Personnel</span>
    <h1 class="hero-title">Mes <em>pensées</em><br>& explorations</h1>
    <p class="hero-subtitle">Tutoriels, expériences et réflexions partagés avec le monde.</p>
</div>

@if($posts->count() > 0)
    <div class="posts-grid">
        @foreach($posts as $index => $post)
            @php
                $grads = ['grad-1','grad-2','grad-3','grad-4','grad-5'];
                $grad = $grads[$index % 5];
            @endphp
            <article class="post-card">
                <div class="card-image">
                    @if($post->cover_image)
                        <img src="{{ Storage::url($post->cover_image) }}" alt="{{ $post->title }}">
                    @else
                        <div class="card-gradient {{ $grad }}">📝</div>
                    @endif
                    @guest
                        <div style="position:absolute;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.7);display:flex;align-items:center;justify-content:center;border-radius:1rem 1rem 0 0;">
                            <div style="text-align:center;color:white;">
                                <div style="font-size:3rem;margin-bottom:0.5rem;">🔒</div>
                                <p style="font-size:0.9rem;font-weight:500;">Connexion requise</p>
                            </div>
                        </div>
                    @endguest
                </div>
                <div class="card-body">
                    @if($post->category)
                        <div class="card-category">{{ $post->category }}</div>
                    @endif
                    @auth
                        <a href="{{ route('posts.show', $post) }}" class="card-title">
                            {{ $post->title }}
                        </a>
                    @else
                        <div class="card-title" style="cursor:not-allowed;opacity:0.6;">
                            {{ $post->title }}
                        </div>
                    @endauth
                    <p class="card-excerpt">
                        {{ $post->excerpt ?? Str::limit(strip_tags($post->content), 100) }}
                    </p>
                    <div class="card-meta">
                        <span class="card-date">{{ $post->created_at->diffForHumans() }}</span>
                        <div class="card-stats">
                            <span>👁 {{ $post->views }}</span>
                            <span>❤️ {{ $post->likes->count() }}</span>
                            <span>💬 {{ $post->comments->count() }}</span>
                        </div>
                    </div>
                    @guest
                        <div style="margin-top:1rem;padding-top:1rem;border-top:1px solid var(--border);">
                            <a href="{{ route('register') }}" class="btn-primary" style="width:100%;text-align:center;display:block;font-size:0.875rem;">
                                Créer un compte pour lire
                            </a>
                        </div>
                    @endguest
                </div>
            </article>
        @endforeach
    </div>

    <div style="margin-top:2.5rem;">
        {{ $posts->links() }}
    </div>

@else
    <div class="empty-state">
        <div class="empty-icon">📭</div>
        <p class="empty-title">Aucun article pour l'instant.</p>
        @auth
            <a href="{{ route('posts.create') }}" class="btn-primary" style="margin-top:1rem;">
                + Écrire le premier article
            </a>
        @endauth
    </div>
@endif

@endsection
