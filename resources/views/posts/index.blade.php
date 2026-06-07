@extends('layouts.app')

@section('title', config('app.name') . ' · Accueil')

@section('content')

<section class="home-hero">
    <div class="hero-eyebrow">
        <span class="hero-eyebrow-dot"></span>
        KerpheX · Blog professionnel
    </div>
    <h1 class="hero-title">Des idées qui <em>inspirent</em><br>des histoires qui <em>comptent</em></h1>
    <p class="hero-subtitle">
        Tutoriels, expériences et réflexions — un blog moderne pour explorer, apprendre et partager.
    </p>
    @if($totalPosts > 0)
        <div class="hero-stats">
            <div class="hero-stat">
                <div class="hero-stat-value">{{ $totalPosts }}</div>
                <div class="hero-stat-label">Articles</div>
            </div>
            <div class="hero-stat">
                <div class="hero-stat-value">{{ $categories->count() }}</div>
                <div class="hero-stat-label">Catégories</div>
            </div>
            <div class="hero-stat">
                <div class="hero-stat-value">{{ number_format($totalViews) }}</div>
                <div class="hero-stat-label">Lectures</div>
            </div>
        </div>
    @endif
</section>

@if($featured)
    @php $grads = ['grad-1','grad-2','grad-3','grad-4','grad-5']; @endphp
    <a href="{{ route('posts.show', $featured) }}" class="featured-post">
        <div class="featured-post-image">
            @if($featured->cover_image)
                <img src="{{ Storage::url($featured->cover_image) }}" alt="{{ $featured->title }}">
            @else
                <div class="featured-post-gradient {{ $grads[$featured->id % 5] }}"></div>
            @endif
        </div>
        <div class="featured-post-body">
            <span class="featured-label">✦ Article à la une</span>
            @if($featured->category)
                <span class="featured-label" style="color:var(--accent-bright);margin-left:0.5rem;">{{ $featured->category }}</span>
            @endif
            <h2 class="featured-title">{{ $featured->title }}</h2>
            <p class="featured-excerpt">
                {{ $featured->excerpt ?? Str::limit(strip_tags($featured->content), 180) }}
            </p>
            <div class="featured-meta">
                <span>{{ $featured->user->name }}</span>
                <span>{{ $featured->created_at->format('d M Y') }}</span>
                <span>{{ $featured->readingTime() }} min de lecture</span>
            </div>
            <span class="featured-read">Lire l'article →</span>
        </div>
    </a>
@endif

@if($categories->count() > 0)
    <div class="category-bar">
        <a href="{{ route('posts.index') }}" class="category-pill active">Tous</a>
        @foreach($categories as $cat)
            <a href="{{ route('categories.show', $cat->category) }}" class="category-pill">
                {{ $cat->category }}
                <span class="category-pill-count">{{ $cat->count }}</span>
            </a>
        @endforeach
        <a href="{{ route('categories.index') }}" class="category-pill">Voir tout →</a>
    </div>
@endif

@if($posts->count() > 0)
    <div class="section-header">
        <div>
            <h2 class="section-title">Derniers articles</h2>
            <p class="section-subtitle">Les publications les plus récentes</p>
        </div>
    </div>

    <div class="posts-grid">
        @foreach($posts as $index => $post)
            <x-post-card :post="$post" :index="$index" />
        @endforeach
    </div>

    <div class="pagination-wrap">
        {{ $posts->links('vendor.pagination.blog') }}
    </div>

@elseif(!$featured)
    <div class="empty-state">
        <div class="empty-icon">✍️</div>
        <h2 class="empty-title">Le blog prend vie bientôt</h2>
        <p class="empty-desc">Aucun article publié pour l'instant. Revenez très prochainement pour découvrir du contenu inspirant.</p>
        @auth('admin')
            <a href="{{ route('admin.posts.create') }}" class="btn-primary btn-accent">Écrire le premier article</a>
        @endauth
    </div>
@endif

@endsection
