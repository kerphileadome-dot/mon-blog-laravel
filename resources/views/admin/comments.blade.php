@extends('layouts.admin')

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
                            <td>
                                @if($comment->parent_id)
                                    <small style="color:#666;display:block;margin-bottom:0.25rem;">
                                        ↳ Réponse à {{ $comment->parent?->name ?? 'un commentaire' }}
                                    </small>
                                @endif
                                {{ Str::limit($comment->body, 100) }}
                            </td>
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
                                <button type="button" class="action-btn" title="Répondre"
                                    onclick="toggleAdminReply({{ $comment->id }})">💬</button>
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
                                <form method="POST" action="{{ route('admin.comments.delete', $comment) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="action-btn" onclick="return confirm('Supprimer ce commentaire ?')" title="Supprimer">🗑️</button>
                                </form>
                            </td>
                        </tr>
                        <tr id="reply-row-{{ $comment->id }}" style="display:none;">
                            <td colspan="6" style="background:#f8faf8;padding:1rem 1.25rem;">
                                <form method="POST" action="{{ route('admin.comments.reply', $comment) }}">
                                    @csrf
                                    <label style="display:block;font-size:0.8rem;font-weight:600;color:#555;margin-bottom:0.5rem;">
                                        Répondre à {{ $comment->name }}
                                    </label>
                                    <textarea name="body" rows="3" class="form-input"
                                        placeholder="Votre réponse en tant qu'administrateur…"
                                        style="width:100%;margin-bottom:0.75rem;resize:vertical;" required></textarea>
                                    <div style="display:flex;gap:0.5rem;">
                                        <button type="submit" class="btn-primary" style="padding:0.5rem 1rem;font-size:0.875rem;">
                                            Publier la réponse
                                        </button>
                                        <button type="button" class="btn-ghost" style="padding:0.5rem 1rem;font-size:0.875rem;"
                                            onclick="toggleAdminReply({{ $comment->id }})">
                                            Annuler
                                        </button>
                                    </div>
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

@push('scripts')
<script>
function toggleAdminReply(id) {
    const row = document.getElementById('reply-row-' + id);
    const isHidden = row.style.display === 'none';
    document.querySelectorAll('[id^="reply-row-"]').forEach(el => {
        el.style.display = 'none';
    });
    row.style.display = isHidden ? 'table-row' : 'none';
    if (isHidden) {
        row.querySelector('textarea')?.focus();
    }
}
</script>
@endpush
