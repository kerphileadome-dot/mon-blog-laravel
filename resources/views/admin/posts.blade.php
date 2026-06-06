@extends('layouts.admin')

@section('content')

<div class="admin-container">
    <div class="admin-header">
        <h1 class="admin-title">📝 Gestion des articles</h1>
        <a href="{{ route('admin.posts.create') }}" class="admin-btn admin-btn-primary">➕ Nouvel article</a>
    </div>

    <div class="admin-section">
        <div class="admin-table-container">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>Catégorie</th>
                        <th>Statut</th>
                        <th>Vues</th>
                        <th>Likes</th>
                        <th>Commentaires</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($posts as $post)
                        <tr>
                            <td>
                                <a href="{{ route('posts.show', $post) }}" class="table-link" target="_blank">
                                    {{ Str::limit($post->title, 50) }}
                                </a>
                            </td>
                            <td>{{ $post->category ?? '-' }}</td>
                            <td>
                                @if($post->published)
                                    <span class="badge badge-success">✅ Publié</span>
                                @else
                                    <span class="badge badge-warning">📋 Brouillon</span>
                                @endif
                            </td>
                            <td>{{ $post->views }}</td>
                            <td>{{ $post->likes_count }}</td>
                            <td>{{ $post->comments_count }}</td>
                            <td>{{ $post->created_at->format('d/m/Y') }}</td>
                            <td class="action-buttons">
                                <a href="{{ route('admin.posts.edit', $post) }}" class="action-btn" title="Modifier">✏️</a>
                                <form method="POST" action="{{ route('admin.posts.destroy', $post) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="action-btn" onclick="return confirm('Supprimer cet article ?')" title="Supprimer">🗑️</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">Aucun article</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 2rem;">
            {{ $posts->links() }}
        </div>
    </div>

    <div class="back-link">
        <a href="{{ route('admin.dashboard') }}">← Retour au dashboard</a>
    </div>
</div>

<style>
.admin-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.action-buttons {
    display: flex;
    gap: 0.5rem;
}

.action-btn {
    background: none;
    border: none;
    cursor: pointer;
    font-size: 1.2rem;
    text-decoration: none;
}

.back-link {
    margin-top: 2rem;
    text-align: center;
}

.back-link a {
    color: #667eea;
    text-decoration: none;
    font-weight: 600;
}

.back-link a:hover {
    text-decoration: underline;
}
</style>

@endsection
