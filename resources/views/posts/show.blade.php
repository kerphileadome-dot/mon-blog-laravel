@extends('layouts.app')

@section('title', $post->title . ' · ' . config('app.name'))
@section('meta_description', $post->excerpt ?? Str::limit(strip_tags($post->content), 160))
@section('main_class', 'blog-main--article')

@section('content')

@php
    $grads = ['grad-1','grad-2','grad-3','grad-4','grad-5'];
    $grad = $grads[$post->id % 5];
    $initial = strtoupper(substr($post->user->name, 0, 1));
@endphp

<div class="article-hero">
    @if($post->cover_image)
        <img src="{{ Storage::url($post->cover_image) }}" alt="{{ $post->title }}">
    @else
        <div class="article-hero-gradient {{ $grad }}"></div>
    @endif
    <div class="article-hero-overlay">
        <div class="article-hero-content">
            <nav class="article-breadcrumb">
                <a href="{{ route('posts.index') }}">Accueil</a>
                <span>›</span>
                @if($post->category)
                    <a href="{{ route('categories.show', $post->category) }}">{{ $post->category }}</a>
                    <span>›</span>
                @endif
                <span>Article</span>
            </nav>
            @if($post->category)
                <a href="{{ route('categories.show', $post->category) }}" class="article-category">
                    {{ $post->category }}
                </a>
            @endif
            <h1 class="article-title">{{ $post->title }}</h1>
            <div class="article-meta">
                <span class="article-meta-item">
                    <span class="card-avatar" style="width:32px;height:32px;font-size:0.75rem;">{{ $initial }}</span>
                    {{ $post->user->name }}
                </span>
                <span class="article-meta-item">{{ $post->created_at->format('d M Y') }}</span>
                <span class="article-meta-item">{{ $post->views }} vues</span>
                <span class="article-meta-item">{{ $post->readingTime() }} min de lecture</span>
            </div>
            @if(count($post->tags_list) > 0)
                <div class="article-tags">
                    @foreach($post->tags_list as $tag)
                        <a href="{{ route('tags.show', $tag) }}" class="article-tag">#{{ $tag }}</a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

<div class="article-layout">
    <div class="article-main">
        <article class="article-prose">
            <div class="article-body">{{ $post->content }}</div>

            <div class="author-card">
                <div class="author-avatar">{{ $initial }}</div>
                <div class="author-info">
                    <div class="author-name">Écrit par {{ $post->user->name }}</div>
                    <div class="author-role">Publié le {{ $post->created_at->format('d F Y') }}</div>
                </div>
            </div>
        </article>

        <section class="comments-section" id="comments">
            <h2 class="comments-title">{{ $allComments->count() }} commentaire{{ $allComments->count() !== 1 ? 's' : '' }}</h2>

            @forelse($comments as $comment)
                <x-comment-item :comment="$comment" :post="$post" :all-comments="$allComments" />
            @empty
                <p style="color:var(--ink-faint);font-size:0.9rem;">Aucun commentaire pour l'instant. Soyez le premier à réagir !</p>
            @endforelse

            @auth
                <div class="comment-form-card">
                    <h3 class="form-title">Laisser un commentaire</h3>
                    <p style="font-size:0.85rem;color:var(--ink-muted);margin-bottom:1rem;">
                        Commenter en tant que <strong>{{ auth()->user()->name }}</strong>
                    </p>
                    <form method="POST" action="{{ route('comments.store', $post) }}">
                        @csrf
                        <textarea name="body" rows="4" placeholder="Votre commentaire *"
                            class="form-input" style="margin-bottom:1rem;resize:vertical;" required>{{ old('body') }}</textarea>
                        <button type="submit" class="btn-primary">Publier le commentaire</button>
                    </form>
                </div>
            @else
                <div class="comment-cta">
                    <p>Connectez-vous pour rejoindre la conversation</p>
                    <a href="{{ route('login') }}" class="btn-primary">Se connecter</a>
                </div>
            @endauth
        </section>

        @if($relatedPosts->count() > 0)
            <section class="related-posts">
                <div class="section-header">
                    <h2 class="section-title">Articles similaires</h2>
                </div>
                <div class="posts-grid">
                    @foreach($relatedPosts as $index => $related)
                        <x-post-card :post="$related" :index="$index" />
                    @endforeach
                </div>
            </section>
        @endif
    </div>

    <aside class="article-sidebar">
        @auth
            <div class="sidebar-card">
                <p class="sidebar-title">Favoris</p>
                <form method="POST" action="{{ route('posts.favorite', $post) }}">
                    @csrf
                    <button class="like-btn fav-btn {{ $isFavorited ? 'active' : '' }}">
                        {{ $isFavorited ? '★ Retirer des favoris' : '☆ Ajouter aux favoris' }}
                    </button>
                </form>
            </div>
        @endauth

        <div class="sidebar-card">
            <p class="sidebar-title">Réactions</p>
            @auth
                <form method="POST" action="{{ route('posts.like', $post) }}">
                    @csrf
                    <button class="like-btn {{ $isLiked ? 'active' : '' }}">
                        ♥ J'aime · {{ $likesCount }}
                    </button>
                </form>
            @else
                <p style="font-size:0.85rem;color:var(--ink-muted);text-align:center;">
                    <a href="{{ route('login') }}" style="color:var(--accent);font-weight:600;">Connectez-vous</a> pour liker
                </p>
            @endauth
        </div>

        <div class="sidebar-card">
            <p class="sidebar-title">À propos de cet article</p>
            <div class="sidebar-stat">
                <span class="sidebar-stat-label">Vues</span>
                <span class="sidebar-stat-value">{{ $post->views }}</span>
            </div>
            <div class="sidebar-stat">
                <span class="sidebar-stat-label">Likes</span>
                <span class="sidebar-stat-value">{{ $likesCount }}</span>
            </div>
            <div class="sidebar-stat">
                <span class="sidebar-stat-label">Commentaires</span>
                <span class="sidebar-stat-value">{{ $allComments->count() }}</span>
            </div>
            <div class="sidebar-stat">
                <span class="sidebar-stat-label">Lecture</span>
                <span class="sidebar-stat-value">{{ $post->readingTime() }} min</span>
            </div>
        </div>

        @auth
            @if(auth()->user()->isAdmin())
                <div class="sidebar-card">
                    <p class="sidebar-title">Administration</p>
                    <a href="{{ route('admin.posts.edit', $post) }}" class="action-link">Modifier l'article</a>
                    <form method="POST" action="{{ route('admin.posts.destroy', $post) }}">
                        @csrf
                        @method('DELETE')
                        <button class="action-link danger" onclick="return confirm('Supprimer cet article définitivement ?')">
                            Supprimer l'article
                        </button>
                    </form>
                </div>
            @endif
        @endauth
    </aside>
</div>

<a href="{{ route('posts.index') }}" class="back-link">← Retour aux articles</a>

@endsection

@push('scripts')
<script>
function toggleReply(id) {
    document.getElementById('reply-' + id).classList.toggle('open');
}
</script>
@endpush
