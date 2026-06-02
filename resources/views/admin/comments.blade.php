@extends('layouts.app')

@section('content')

<div class="admin-container">
    <div class="admin-header">
        <h1 class="admin-title">💬 Gestion des commentaires</h1>
    </div>

    <div class="admin-section">
        <div class="admin-table-container">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Auteur</th>
                        <th>Commentaire</th>
                        <th>Article</th>
                        <th>Statut</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($comments as $comment)
                        <tr>
                            <td>
                                <strong>{{ $comment->name }}</strong>
                                @if($comment->email)
                                    <br><small style="color: #666;">{{ $comment->email }}</small>
                                @endif
                            </td>
                            <td>{{ Str::limit($comment->body, 100) }}</td>
                            <td>
                                <a href="{{ route('posts.show', $comment->post) }}" class="table-link" target="_blank">
                                    {{ Str::limit($comment->post->title, 40) }}
                                </a>
                            </td>
                            <td>
                                @if($comment->approved)
                                    <span class="badge badge-success">✅ Approuvé</span>
                                @else
                                    <span class="badge badge-warning">⏳ En attente</span>
                                @endif
                            </td>
                            <td>{{ $comment->created_at->format('d/m/Y H:i') }}</td>
                            <td class="action-buttons">
                                @if(!$comment->approved)
                                    <form method="POST" action="{{ route('admin.comments.approve', $comment) }}" class="inline">
                                        @csrf
                                        <button class="action-btn" title="Approuver">✅</button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('admin.comments.reject', $comment) }}" class="inline">
                                        @csrf
                                        <button class="action-btn" title="Rejeter">❌</button>
                                    </form>
                                @endif
                                <form method="POST" action="{{ route('comments.destroy', $comment) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="action-btn" onclick="return confirm('Supprimer ce commentaire ?')" title="Supprimer">🗑️</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Aucun commentaire</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 2rem;">
            {{ $comments->links() }}
        </div>
    </div>

    <div class="back-link">
        <a href="{{ route('admin.dashboard') }}">← Retour au dashboard</a>
    </div>
</div>

@endsection
