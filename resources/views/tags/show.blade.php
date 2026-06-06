@extends('layouts.app')

@section('title', "#{$tagName} · KerpheX")

@section('content')
<div class="page-header">
    <nav class="article-breadcrumb" style="justify-content:center;margin-bottom:1rem;color:var(--ink-faint);">
        <a href="{{ route('posts.index') }}">Accueil</a><span>›</span>
        <a href="{{ route('tags.index') }}">Tags</a><span>›</span>
        <span style="color:var(--ink);">#{{ $tagName }}</span>
    </nav>
    <h1 class="page-title">#{{ $tagName }}</h1>
    <p class="page-desc">{{ $posts->total() }} article{{ $posts->total() > 1 ? 's' : '' }}</p>
</div>

@if($posts->count() > 0)
    <div class="posts-grid">
        @foreach($posts as $index => $post)
            <x-post-card :post="$post" :index="$index" />
        @endforeach
    </div>
    <div class="pagination-wrap">{{ $posts->links('vendor.pagination.blog') }}</div>
@else
    <div class="empty-state">
        <h2 class="empty-title">Aucun article avec ce tag</h2>
        <a href="{{ route('tags.index') }}" class="btn-ghost">Voir tous les tags</a>
    </div>
@endif
@endsection
