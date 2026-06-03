@extends('layouts.admin')

@section('content')

<div class="admin-container">
    <div class="admin-header">
        <h1 class="admin-title">📊 Dashboard Admin</h1>
        <p class="admin-subtitle">Gérez votre blog personnel</p>
    </div>

    <!-- Statistiques -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">📝</div>
            <div class="stat-content">
                <div class="stat-value">{{ $stats['total_posts'] }}</div>
                <div class="stat-label">Articles totaux</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">✅</div>
            <div class="stat-content">
                <div class="stat-value">{{ $stats['published_posts'] }}</div>
                <div class="stat-label">Publiés</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">📋</div>
            <div class="stat-content">
                <div class="stat-value">{{ $stats['draft_posts'] }}</div>
                <div class="stat-label">Brouillons</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">👥</div>
            <div class="stat-content">
                <div class="stat-value">{{ $stats['total_users'] }}</div>
                <div class="stat-label">Utilisateurs</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">💬</div>
            <div class="stat-content">
                <div class="stat-value">{{ $stats['total_comments'] }}</div>
                <div class="stat-label">Commentaires</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">⏳</div>
            <div class="stat-content">
                <div class="stat-value">{{ $stats['pending_comments'] }}</div>
                <div class="stat-label">En attente</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">👁</div>
            <div class="stat-content">
                <div class="stat-value">{{ number_format($stats['total_views']) }}</div>
                <div class="stat-label">Vues totales</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">❤️</div>
            <div class="stat-content">
                <div class="stat-value">{{ $stats['total_likes'] }}</div>
                <div class="stat-label">Likes</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">⭐</div>
            <div class="stat-content">
                <div class="stat-value">{{ $stats['total_favorites'] }}</div>
                <div class="stat-label">Favoris</div>
            </div>
        </div>
    </div>

    <!-- Navigation rapide -->
    <div class="admin-actions">
        <a href="{{ route('admin.posts.create') }}" class="admin-btn admin-btn-primary">
            ➕ Nouvel article
        </a>
        <a href="{{ route('admin.posts') }}" class="admin-btn admin-btn-secondary">
            📝 Gérer les articles
        </a>
        <a href="{{ route('admin.comments') }}" class="admin-btn admin-btn-secondary">
            💬 Gérer les commentaires
        </a>
        <a href="{{ route('admin.users.index') }}" class="admin-btn admin-btn-secondary">
            👥 Gérer les utilisateurs
        </a>
    </div>

    <!-- Articles récents -->
    <div class="admin-section">
        <h2 class="section-title">📌 Articles récents</h2>
        <div class="admin-table-container">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>Statut</th>
                        <th>Vues</th>
                        <th>Likes</th>
                        <th>Commentaires</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentPosts as $post)
                        <tr>
                            <td>
                                <a href="{{ route('posts.show', $post) }}" class="table-link">
                                    {{ Str::limit($post->title, 50) }}
                                </a>
                            </td>
                            <td>
                                @if($post->published)
                                    <span class="badge badge-success">✅ Publié</span>
                                @else
                                    <span class="badge badge-warning">📋 Brouillon</span>
                                @endif
                            </td>
                            <td>{{ $post->views }}</td>
                            <td>{{ $post->likes->count() }}</td>
                            <td>{{ $post->comments->count() }}</td>
                            <td>{{ $post->created_at->format('d/m/Y') }}</td>
                            <td>
                                <a href="{{ route('admin.posts.edit', $post) }}" class="action-btn">✏️</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Aucun article</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Commentaires en attente -->
    @if($pendingComments->count() > 0)
        <div class="admin-section">
            <h2 class="section-title">⏳ Commentaires en attente de modération</h2>
            <div class="comments-list">
                @foreach($pendingComments as $comment)
                    <div class="comment-card">
                        <div class="comment-header">
                            <strong>{{ $comment->name }}</strong>
                            <span class="comment-date">{{ $comment->created_at->diffForHumans() }}</span>
                        </div>
                        <div class="comment-body">
                            {{ Str::limit($comment->body, 150) }}
                        </div>
                        <div class="comment-meta">
                            Sur : <a href="{{ route('posts.show', $comment->post) }}">{{ $comment->post->title }}</a>
                        </div>
                        <div class="comment-actions">
                            <form method="POST" action="{{ route('admin.comments.approve', $comment) }}" class="inline">
                                @csrf
                                <button class="btn-approve">✅ Approuver</button>
                            </form>
                            <form method="POST" action="{{ route('comments.destroy', $comment) }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn-delete" onclick="return confirm('Supprimer ce commentaire ?')">🗑️ Supprimer</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>

<style>
.admin-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem 1rem;
}

.admin-header {
    text-align: center;
    margin-bottom: 3rem;
}

.admin-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.admin-subtitle {
    color: #666;
    font-size: 1.1rem;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 1.5rem;
    margin-bottom: 3rem;
}

.stat-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    display: flex;
    align-items: center;
    gap: 1rem;
}

.stat-icon {
    font-size: 2rem;
}

.stat-value {
    font-size: 2rem;
    font-weight: 700;
    color: #333;
}

.stat-label {
    font-size: 0.875rem;
    color: #666;
}

.admin-actions {
    display: flex;
    gap: 1rem;
    margin-bottom: 3rem;
    flex-wrap: wrap;
}

.admin-btn {
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.2s;
}

.admin-btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.admin-btn-secondary {
    background: white;
    color: #333;
    border: 2px solid #e5e7eb;
}

.admin-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.admin-section {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    margin-bottom: 2rem;
}

.section-title {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 1.5rem;
}

.admin-table-container {
    overflow-x: auto;
}

.admin-table {
    width: 100%;
    border-collapse: collapse;
}

.admin-table th {
    background: #f9fafb;
    padding: 0.75rem;
    text-align: left;
    font-weight: 600;
    border-bottom: 2px solid #e5e7eb;
}

.admin-table td {
    padding: 0.75rem;
    border-bottom: 1px solid #e5e7eb;
}

.table-link {
    color: #667eea;
    text-decoration: none;
}

.table-link:hover {
    text-decoration: underline;
}

.badge {
    padding: 0.25rem 0.75rem;
    border-radius: 12px;
    font-size: 0.875rem;
    font-weight: 600;
}

.badge-success {
    background: #d1fae5;
    color: #065f46;
}

.badge-warning {
    background: #fef3c7;
    color: #92400e;
}

.action-btn {
    text-decoration: none;
    font-size: 1.2rem;
}

.comments-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.comment-card {
    background: #f9fafb;
    border-radius: 8px;
    padding: 1rem;
}

.comment-header {
    display: flex;
    justify-content: space-between;
    margin-bottom: 0.5rem;
}

.comment-date {
    color: #666;
    font-size: 0.875rem;
}

.comment-body {
    margin-bottom: 0.5rem;
    color: #333;
}

.comment-meta {
    font-size: 0.875rem;
    color: #666;
    margin-bottom: 1rem;
}

.comment-meta a {
    color: #667eea;
    text-decoration: none;
}

.comment-actions {
    display: flex;
    gap: 0.5rem;
}

.inline {
    display: inline;
}

.btn-approve, .btn-delete {
    padding: 0.5rem 1rem;
    border-radius: 6px;
    border: none;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-approve {
    background: #d1fae5;
    color: #065f46;
}

.btn-delete {
    background: #fee2e2;
    color: #991b1b;
}

.btn-approve:hover, .btn-delete:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.text-center {
    text-align: center;
    padding: 2rem;
    color: #666;
}
</style>

@endsection
