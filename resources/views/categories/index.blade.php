@extends('layouts.app')

@section('title', 'Catégories · ' . config('app.name'))

@section('content')

<div class="page-header">
    <h1 class="page-title">Explorer par catégorie</h1>
    <p class="page-desc">Parcourez les articles classés par thème pour trouver ce qui vous intéresse.</p>
</div>

@if($categories->count() > 0)
    <div class="categories-grid">
        @foreach($categories as $cat)
            <a href="{{ route('categories.show', $cat->category) }}" class="category-card">
                <span class="category-card-name">{{ $cat->category }}</span>
                <span class="category-card-count">{{ $cat->count }} article{{ $cat->count > 1 ? 's' : '' }}</span>
                <span class="category-card-arrow">Explorer →</span>
            </a>
        @endforeach
    </div>
@else
    <div class="empty-state">
        <div class="empty-icon">📂</div>
        <h2 class="empty-title">Aucune catégorie</h2>
        <p class="empty-desc">Les catégories apparaîtront dès que des articles seront publiés avec une catégorie.</p>
        <a href="{{ route('posts.index') }}" class="btn-ghost">Retour à l'accueil</a>
    </div>
@endif

@endsection
