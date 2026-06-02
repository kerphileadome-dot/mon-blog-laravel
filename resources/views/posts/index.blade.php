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