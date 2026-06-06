@extends('layouts.app')

@section('title', ($query ? "Recherche : {$query}" : 'Recherche') . ' · KerpheX')

@section('content')
<div class="page-header">
    <h1 class="page-title">Rechercher</h1>
    <p class="page-desc">Trouvez des articles par titre, contenu, catégorie ou tag.</p>
</div>

<form method="GET" action="{{ route('search') }}" class="search-form">
    <input type="search" name="q" value="{{ $query }}" placeholder="Que cherchez-vous ?" class="search-input" autofocus>
    <button type="submit" class="btn-primary btn-accent">Rechercher</button>
</form>

@if($query !== '')
    <div class="section-header" style="margin-top:2.5rem;">
        <div>
            <h2 class="section-title">{{ $posts->total() }} résultat{{ $posts->total() !== 1 ? 's' : '' }}</h2>
            <p class="section-subtitle">pour « {{ $query }} »</p>
        </div>
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
            <div class="empty-icon">🔍</div>
            <h2 class="empty-title">Aucun résultat</h2>
            <p class="empty-desc">Essayez d'autres mots-clés ou parcourez les catégories.</p>
            <a href="{{ route('categories.index') }}" class="btn-ghost">Voir les catégories</a>
        </div>
    @endif
@endif
@endsection
