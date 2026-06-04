@extends('layouts.app')

@section('content')

@php
    $grads = ['grad-1','grad-2','grad-3','grad-4','grad-5'];
    $grad = $grads[$post->id % 5];
@endphp

{{-- En-tête de l'article --}}
<div class="article-header">
    @if($post->cover_image)
        <img src="{{ Storage::url($post->cover_image) }}" alt="{{ $post->title }}">
    @else
        <div class="article-header-gradient {{ $grad }}">📝</div>
    @endif
    <div class="article-header-overlay">
        <div class="article-header-content">
            @if($post->category)
                <span class="article-category">{{ $post->category }}</span>
            @endif
            <h1 class="article-title">{{ $post->title }}</h1>
            <div class="article-meta">
                <span>✍️ {{ $post->user->name }}</span>
                <span>📅 {{ $post->created_at->format('d M Y') }}</span>
                <span>👁 {{ $post->views }} vues</span>
                <span>⏱ {{ max(1, (int)(str_word_count(strip_tags($post->content)) / 200)) }} min de lecture</span>
            </div>
        </div>
    </div>
</div>

{{-- Layout deux colonnes --}}
<div class="article-layout">

    {{-- Colonne principale --}}
    <div>
        <div class="article-content">
            <div class="article-body" style="white-space: pre-line; color: #1a1a1a; font-size: 1.05rem; line-height: 1.8;">
                {{ $post->content }}
            </div>
        </div>

        {{-- Commentaires --}}
        <div class="comments-section">
            <h2 class="comments-title">💬 {{ $comments->count() }} Commentaire(s)</h2>

            @forelse($comments as $comment)
                <div class="comment-item">
                    <div class="comment-header">
                        <div>
                            <span class="comment-author">{{ $comment->name }}</span>
                            <span class="comment-time" style="margin-left:0.75rem;">
                                {{ $comment->created_at->diffForHumans() }}
                            </span>
                        </div>
                        @auth
                            @if(auth()->user()->role === 'admin')
                            <form method="POST" action="{{ route('admin.comments.delete', $comment) }}">
                                @csrf
                                @method('DELETE')
                                <button class="action-link danger" style="font-size:0.8rem;">✕</button>
                            </form>
                            @endif
                        @endauth
                    </div>
                    <p class="comment-body" style="color: #2d2d2d; font-size: 0.95rem;">{{ $comment->body }}</p>

                    {{-- Bouton répondre pour admin --}}
                    @auth
                        @if(auth()->user()->role === 'admin')
                        <button onclick="toggleReplyForm{{ $comment->id }}()"
                                style="color:var(--accent);background:none;border:none;cursor:pointer;font-size:0.85rem;margin-top:0.5rem;">
                            💬 Répondre
                        </button>
                        <div id="replyForm{{ $comment->id }}" style="display:none;margin-top:1rem;padding:1rem;background:#f9fafb;border-radius:8px;">
                            <form method="POST" action="{{ route('admin.comments.reply', $comment) }}">
                                @csrf
                                <textarea name="body" rows="3" placeholder="Votre réponse *"
                                    class="form-input" style="margin-bottom:0.75rem;resize:vertical;"
                                    required></textarea>
                                <div style="display:flex;gap:0.5rem;">
                                    <button type="submit" class="btn-primary" style="padding:0.5rem 1rem;font-size:0.875rem;">
                                        Envoyer
                                    </button>
                                    <button type="button" onclick="toggleReplyForm{{ $comment->id }}()"
                                            class="btn-ghost" style="padding:0.5rem 1rem;font-size:0.875rem;">
                                        Annuler
                                    </button>
                                </div>
                            </form>
                        </div>
                        <script>
                            function toggleReplyForm{{ $comment->id }}() {
                                const form = document.getElementById('replyForm{{ $comment->id }}');
                                form.style.display = form.style.display === 'none' ? 'block' : 'none';
                            }
                        </script>
                        @endif
                    @endauth

                    {{-- Afficher les réponses --}}
                    @if($comment->replies->count() > 0)
                        <div style="margin-left:2rem;margin-top:1rem;padding-left:1rem;border-left:3px solid var(--border);">
                            @foreach($comment->replies as $reply)
                                <div style="margin-bottom:1rem;">
                                    <div style="display:flex;align-items:center;gap:0.5rem;margin-bottom:0.25rem;">
                                        <span style="font-weight:600;color:var(--accent);font-size:0.875rem;">{{ $reply->name }}</span>
                                        <span style="color:var(--text-dim);font-size:0.75rem;">{{ $reply->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p style="color:#2d2d2d;font-size:0.875rem;">{{ $reply->body }}</p>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @empty
                <p style="color:var(--text-dim);font-size:0.9rem;margin-bottom:1.5rem;">
                    Aucun commentaire pour l'instant. Soyez le premier !
                </p>
            @endforelse

            {{-- Formulaire commentaire --}}
            @auth
                <div class="comment-form-card">
                    <h3 class="form-title">Laisser un commentaire</h3>
                    <form method="POST" action="{{ route('comments.store', $post) }}">
                        @csrf
                        <div class="form-grid">
                            <input type="text" name="name" placeholder="Votre nom *"
                                class="form-input" value="{{ old('name', auth()->user()->name) }}" required>
                            <input type="email" name="email" placeholder="Email (optionnel)"
                                class="form-input" value="{{ old('email', auth()->user()->email) }}">
                        </div>
                        <textarea name="body" rows="4" placeholder="Votre commentaire *"
                            class="form-input" style="margin-bottom:1rem;resize:vertical;"
                            required>{{ old('body') }}</textarea>
                        <button type="submit" class="btn-primary">
                            Publier le commentaire
                        </button>
                    </form>
                </div>
            @else
                <div style="text-align:center;padding:2rem;background:var(--card);border-radius:0.75rem;border:1px solid var(--border);">
                    <div style="font-size:2rem;margin-bottom:1rem;">💬</div>
                    <p style="color:var(--text-muted);margin-bottom:1.5rem;">Connectez-vous pour laisser un commentaire</p>
                    <a href="{{ route('login') }}" class="btn-primary">Se connecter</a>
                </div>
            @endauth
        </div>
    </div>

    {{-- Sidebar --}}
    <aside class="article-sidebar">

        {{-- Favoris --}}
        @auth
            <div class="sidebar-card">
                <p class="sidebar-title">Favoris</p>
                <form method="POST" action="{{ route('posts.favorite', $post) }}">
                    @csrf
                    <button class="like-btn" style="background:{{ $isFavorited ? 'var(--accent)' : 'var(--card)' }};">
                        {{ $isFavorited ? '⭐ Retiré des favoris' : '☆ Ajouter aux favoris' }}
                    </button>
                </form>
            </div>
        @endauth

        {{-- Like --}}
        <div class="sidebar-card">
            <p class="sidebar-title">Vous avez aimé ?</p>
            @auth
                <form method="POST" action="{{ route('posts.like', $post) }}">
                    @csrf
                    <button class="like-btn">
                        ❤️ J'aime &nbsp;·&nbsp; {{ $likesCount }}
                    </button>
                </form>
            @else
                <div style="text-align:center;padding:1rem;color:var(--text-muted);font-size:0.875rem;">
                    <a href="{{ route('login') }}" style="color:var(--accent);">Connectez-vous</a> pour liker
                </div>
            @endauth
        </div>

        {{-- Stats (admin uniquement) --}}
        @auth
            @if(auth()->user()->role === 'admin')
            <div class="sidebar-card">
                <p class="sidebar-title">Statistiques</p>
                <div style="display:flex;flex-direction:column;gap:0.75rem;">
                    <div style="display:flex;justify-content:space-between;font-size:0.875rem;">
                        <span style="color:var(--text-muted);">👁 Vues</span>
                        <span style="color:var(--text);font-weight:600;">{{ $post->views }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;font-size:0.875rem;">
                        <span style="color:var(--text-muted);">❤️ Likes</span>
                        <span style="color:var(--text);font-weight:600;">{{ $likesCount }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;font-size:0.875rem;">
                        <span style="color:var(--text-muted);">💬 Commentaires</span>
                        <span style="color:var(--text);font-weight:600;">{{ $comments->count() }}</span>
                    </div>
                </div>
            </div>
            @endif
        @endauth

        {{-- Actions admin --}}
        @auth
            @if(auth()->user()->role === 'admin')
            <div class="sidebar-card">
                <p class="sidebar-title">Administration</p>
                <a href="{{ route('admin.posts.edit', $post) }}" class="action-link">
                    ✏️ Modifier l'article
                </a>
                <form method="POST" action="{{ route('admin.posts.destroy', $post) }}">
                    @csrf
                    @method('DELETE')
                    <button class="action-link danger"
                        onclick="return confirm('Supprimer cet article définitivement ?')">
                        🗑️ Supprimer l'article
                    </button>
                </form>
            </div>
            @endif
        @endauth

    </aside>
</div>

<a href="{{ route('posts.index') }}" class="back-link">← Retour aux articles</a>

@endsection
