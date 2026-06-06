@extends('layouts.app')

@section('title', 'Mes favoris · ' . config('app.name'))

@section('content')

<div class="page-header">
    <h1 class="page-title">Articles <em style="font-style:italic;color:var(--accent);">sauvegardés</em></h1>
    <p class="page-desc">Tous vos articles préférés, réunis en un seul endroit.</p>
</div>

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
        <div class="empty-icon">☆</div>
        <h2 class="empty-title">Aucun favori pour l'instant</h2>
        <p class="empty-desc">Ajoutez des articles en favoris pour les retrouver facilement ici.</p>
        <a href="{{ route('posts.index') }}" class="btn-primary btn-accent">Découvrir les articles</a>
    </div>
@endif

@endsection
