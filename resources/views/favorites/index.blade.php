@extends('layouts.app')

@section('content')

<div class="hero" style="padding:4rem 0 3rem;">
    <span class="hero-label">⭐ Mes Favoris</span>
    <h1 class="hero-title">Articles <em>sauvegardés</em></h1>
    <p class="hero-subtitle">Tous vos articles préférés en un seul endroit.</p>
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
                </div>
                <div class="card-body">
                    @if($post->category)
                        <div class="card-category">{{ $post->category }}</div>
                    @endif
                    <a href="{{ route('posts.show', $post) }}" class="card-title">
                        {{ $post->title }}
                    </a>
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
                    <div style="margin-top:1rem;padding-top:1rem;border-top:1px solid var(--border);">
                        <form method="POST" action="{{ route('posts.favorite', $post) }}" style="display:inline;">
                            @csrf
                            <button class="action-link" style="color:var(--accent);font-size:0.875rem;">
                                ⭐ Retirer des favoris
                            </button>
                        </form>
                    </div>
                </div>
            </article>
        @endforeach
    </div>

    <div style="margin-top:2.5rem;">
        {{ $posts->links() }}
    </div>

@else
    <div class="empty-state">
        <div class="empty-icon">⭐</div>
        <p class="empty-title">Aucun article favori pour l'instant.</p>
        <p style="color:var(--text-muted);margin-top:0.5rem;">Ajoutez des articles en favoris pour les retrouver facilement ici.</p>
        <a href="{{ route('posts.index') }}" class="btn-primary" style="margin-top:1.5rem;">
            Découvrir les articles
        </a>
    </div>
@endif

@endsection
