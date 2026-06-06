@extends('layouts.app')

@section('title', $categoryName . ' · ' . config('app.name'))

@section('content')

<div class="page-header">
    <nav class="article-breadcrumb" style="justify-content:center;margin-bottom:1rem;color:var(--ink-faint);">
        <a href="{{ route('posts.index') }}" style="color:var(--ink-faint);">Accueil</a>
        <span>›</span>
        <a href="{{ route('categories.index') }}" style="color:var(--ink-faint);">Catégories</a>
        <span>›</span>
        <span style="color:var(--ink);">{{ $categoryName }}</span>
    </nav>
    <h1 class="page-title">{{ $categoryName }}</h1>
    <p class="page-desc">{{ $posts->total() }} article{{ $posts->total() > 1 ? 's' : '' }} dans cette catégorie</p>
</div>

@if($categories->count() > 0)
    <div class="category-bar">
        <a href="{{ route('posts.index') }}" class="category-pill">Tous</a>
        @foreach($categories as $cat)
            <a href="{{ route('categories.show', $cat) }}"
               class="category-pill {{ $cat === $categoryName ? 'active' : '' }}">
                {{ $cat }}
            </a>
        @endforeach
    </div>
@endif

@if($posts->count() > 0)
    <div class="posts-grid">
        @foreach($posts as $index => $post)
            <x-post-card :post="$post" :index="$index" />
        @endforeach
    </div>
    <div class="pagination-wrap">
        {{ $posts->links('vendor.pagination.blog') }}
    </div>
@else
    <div class="empty-state">
        <div class="empty-icon">📭</div>
        <h2 class="empty-title">Aucun article dans cette catégorie</h2>
        <p class="empty-desc">Revenez bientôt ou explorez d'autres catégories.</p>
        <a href="{{ route('categories.index') }}" class="btn-ghost">Voir toutes les catégories</a>
    </div>
@endif

@endsection
