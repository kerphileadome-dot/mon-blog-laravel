@extends('layouts.admin')

@section('content')

<div class="admin-container">
    <div class="admin-header">
        <h1 class="admin-title">👤 Détails de l'utilisateur</h1>
    </div>

    <div class="admin-section">
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(250px,1fr));gap:1.5rem;margin-bottom:2rem;">
            <div style="background:white;padding:1.5rem;border-radius:12px;border:1px solid #e5e7eb;">
                <div style="font-size:0.875rem;color:#666;margin-bottom:0.5rem;">Nom</div>
                <div style="font-size:1.25rem;font-weight:600;">{{ $user->name }}</div>
            </div>
            <div style="background:white;padding:1.5rem;border-radius:12px;border:1px solid #e5e7eb;">
                <div style="font-size:0.875rem;color:#666;margin-bottom:0.5rem;">Email</div>
                <div style="font-size:1.25rem;font-weight:600;">{{ $user->email }}</div>
            </div>
            <div style="background:white;padding:1.5rem;border-radius:12px;border:1px solid #e5e7eb;">
                <div style="font-size:0.875rem;color:#666;margin-bottom:0.5rem;">Rôle</div>
                <div style="font-size:1.25rem;font-weight:600;">
                    @if($user->role === 'admin')
                        👑 Admin
                    @else
                        👤 Visiteur
                    @endif
                </div>
            </div>
            <div style="background:white;padding:1.5rem;border-radius:12px;border:1px solid #e5e7eb;">
                <div style="font-size:0.875rem;color:#666;margin-bottom:0.5rem;">Statut</div>
                <div style="font-size:1.25rem;font-weight:600;">
                    @if($user->blocked)
                        🚫 Bloqué
                    @else
                        ✅ Actif
                    @endif
                </div>
            </div>
        </div>

        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:1.5rem;margin-bottom:2rem;">
            <div style="background:#f9fafb;padding:1.25rem;border-radius:12px;">
                <div style="font-size:2rem;margin-bottom:0.5rem;">📝</div>
                <div style="font-size:1.5rem;font-weight:700;color:#333;">{{ $user->posts->count() }}</div>
                <div style="font-size:0.875rem;color:#666;">Articles</div>
            </div>
            <div style="background:#f9fafb;padding:1.25rem;border-radius:12px;">
                <div style="font-size:2rem;margin-bottom:0.5rem;">💬</div>
                <div style="font-size:1.5rem;font-weight:700;color:#333;">{{ $user->comments->count() }}</div>
                <div style="font-size:0.875rem;color:#666;">Commentaires</div>
            </div>
            <div style="background:#f9fafb;padding:1.25rem;border-radius:12px;">
                <div style="font-size:2rem;margin-bottom:0.5rem;">⭐</div>
                <div style="font-size:1.5rem;font-weight:700;color:#333;">{{ $user->favorites->count() }}</div>
                <div style="font-size:0.875rem;color:#666;">Favoris</div>
            </div>
            <div style="background:#f9fafb;padding:1.25rem;border-radius:12px;">
                <div style="font-size:2rem;margin-bottom:0.5rem;">📅</div>
                <div style="font-size:1.5rem;font-weight:700;color:#333;">{{ $user->created_at->format('d/m/Y') }}</div>
                <div style="font-size:0.875rem;color:#666;">Inscription</div>
            </div>
        </div>

        @if($user->posts->count() > 0)
        <h3 style="font-size:1.25rem;font-weight:700;margin-bottom:1rem;">📝 Articles de {{ $user->name }}</h3>
        <div class="admin-table-container" style="margin-bottom:2rem;">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>Statut</th>
                        <th>Vues</th>
                        <th>Likes</th>
                        <th>Commentaires</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($user->posts as $post)
                        <tr>
                            <td>
                                <a href="{{ route('posts.show', $post) }}" class="table-link" target="_blank">
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
                            <td>{{ $post->likes_count }}</td>
                            <td>{{ $post->comments_count }}</td>
                            <td>{{ $post->created_at->format('d/m/Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        @if($user->id !== auth()->id())
        <div style="display:flex;gap:1rem;margin-top:2rem;">
            <form method="POST" action="{{ route('admin.users.toggle-block', $user) }}">
                @csrf
                @if($user->blocked)
                    <button class="admin-btn admin-btn-primary">✅ Débloquer l'utilisateur</button>
                @else
                    <button class="admin-btn" style="background:#fee2e2;color:#991b1b;border:2px solid #fecaca;">🚫 Bloquer l'utilisateur</button>
                @endif
            </form>
            @if(!$user->isAdmin())
                <form method="POST" action="{{ route('admin.users.destroy', $user)}}" onsubmit="return confirm('Supprimer définitivement cet utilisateur ?')">
                    @csrf
                    @method('DELETE')
                    <button class="admin-btn" style="background:#fee2e2;color:#991b1b;border:2px solid #fecaca;">🗑️ Supprimer l'utilisateur</button>
                </form>
            @endif
        </div>
        @endif
    </div>

    <div class="back-link">
        <a href="{{ route('admin.users.index') }}">← Retour à la liste des utilisateurs</a>
    </div>
</div>

@endsection
