@props(['post', 'index' => 0])

@php
    $grads = ['grad-1', 'grad-2', 'grad-3', 'grad-4', 'grad-5'];
    $grad = $grads[$index % 5];
    $initial = strtoupper(substr($post->user->name ?? 'A', 0, 1));
    $likesCount = $post->likes_count ?? $post->likes()->count();
    $commentsCount = $post->comments_count ?? $post->comments()->count();
@endphp

<article class="post-card">
    <div class="card-image">
        @if($post->cover_image)
            <img src="{{ Storage::url($post->cover_image) }}" alt="{{ $post->title }}" loading="lazy" decoding="async" width="400" height="225">
        @else
            <div class="card-gradient {{ $grad }}">
                <span class="card-gradient-icon">{{ strtoupper(substr($post->title, 0, 1)) }}</span>
            </div>
        @endif
    </div>
    <div class="card-body">
        <div class="card-top">
            @if($post->category)
                <a href="{{ route('categories.show', $post->category) }}" class="card-category">
                    {{ $post->category }}
                </a>
            @endif
            @foreach(array_slice($post->tags_list, 0, 2) as $tag)
                <a href="{{ route('tags.show', $tag) }}" class="card-tag">#{{ $tag }}</a>
            @endforeach
        </div>
        <a href="{{ route('posts.show', $post) }}" class="card-title">{{ $post->title }}</a>
        <p class="card-excerpt">
            {{ $post->excerpt ?? Str::limit(strip_tags($post->content), 120) }}
        </p>
        <div class="card-footer">
            <div class="card-author">
                <div class="card-avatar">{{ $initial }}</div>
                <div>
                    <div class="card-author-name">{{ $post->user->name }}</div>
                    <div class="card-date">{{ $post->created_at->format('d M Y') }}</div>
                </div>
            </div>
            <span class="card-views">{{ $post->views }} vues</span>
        </div>
        <div class="card-actions">
            @auth
                <a href="{{ route('posts.show', $post) }}" class="card-action-btn" title="J'aime cet article">
                    <svg class="card-action-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                    </svg>
                    <span>{{ $likesCount }}</span>
                </a>
                <a href="{{ route('posts.show', $post) }}#comments" class="card-action-btn" title="Commenter">
                    <svg class="card-action-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                    </svg>
                    <span>{{ $commentsCount }}</span>
                </a>
            @else
                <a href="{{ route('login') }}" class="card-action-btn" title="Connectez-vous pour liker">
                    <svg class="card-action-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                    </svg>
                    <span>{{ $likesCount }}</span>
                </a>
                <a href="{{ route('login') }}" class="card-action-btn" title="Connectez-vous pour commenter">
                    <svg class="card-action-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                    </svg>
                    <span>{{ $commentsCount }}</span>
                </a>
            @endauth
        </div>
    </div>
</article>
