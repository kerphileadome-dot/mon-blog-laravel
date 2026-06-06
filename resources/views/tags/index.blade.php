@extends('layouts.app')

@section('title', 'Tags · KerpheX')

@section('content')
<div class="page-header">
    <h1 class="page-title">Explorer par tags</h1>
    <p class="page-desc">Mots-clés pour naviguer rapidement dans les sujets du blog.</p>
</div>

@if(count($tagCounts) > 0)
    <div class="tags-cloud">
        @foreach($tagCounts as $tag => $count)
            <a href="{{ route('tags.show', $tag) }}" class="tag-cloud-item">
                #{{ $tag }}
                <span class="tag-cloud-count">{{ $count }}</span>
            </a>
        @endforeach
    </div>
@else
    <div class="empty-state">
        <div class="empty-icon">#</div>
        <h2 class="empty-title">Aucun tag</h2>
        <p class="empty-desc">Les tags apparaîtront quand des articles en auront.</p>
    </div>
@endif
@endsection
