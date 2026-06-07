@extends('layouts.admin')

@section('content')

<div class="admin-container">
    <div class="admin-header">
        <h1 class="admin-title">👥 Gestion des utilisateurs</h1>
    </div>

    <div class="admin-section">
        <div class="admin-table-container">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Rôle</th>
                        <th>Statut</th>
                        <th>Articles</th>
                        <th>Commentaires</th>
                        <th>Favoris</th>
                        <th>Inscription</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td><strong>{{ $user->name }}</strong></td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if($user->role === 'admin')
                                    <span class="badge" style="background:#fef3c7;color:#92400e;">👑 Admin</span>
                                @else
                                    <span class="badge" style="background:#dbeafe;color:#1e40af;">👤 Visiteur</span>
                                @endif
                            </td>
                            <td>
                                @if($user->blocked)
                                    <span class="badge" style="background:#fee2e2;color:#991b1b;">🚫 Bloqué</span>
                                @else
                                    <span class="badge badge-success">✅ Actif</span>
                                @endif
                            </td>
                            <td>{{ $user->posts_count }}</td>
                            <td>{{ $user->comments_count }}</td>
                            <td>{{ $user->favorites_count }}</td>
                            <td>{{ $user->created_at->format('d/m/Y') }}</td>
                            <td class="action-buttons">
                                <a href="{{ route('admin.users.show', $user) }}" class="action-btn" title="Voir">👁️</a>
                                @if($user->id !== auth('admin')->id())
                                    <form method="POST" action="{{ route('admin.users.toggle-block', $user) }}" class="inline">
                                        @csrf
                                        @if($user->blocked)
                                            <button class="action-btn" title="Débloquer">✅</button>
                                        @else
                                            <button class="action-btn" title="Bloquer">🚫</button>
                                        @endif
                                    </form>
                                    @if(!$user->isAdmin())
                                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button class="action-btn" onclick="return confirm('Supprimer cet utilisateur ?')" title="Supprimer">🗑️</button>
                                        </form>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">Aucun utilisateur</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 2rem;">
            {{ $users->links() }}
        </div>
    </div>

    <div class="back-link">
        <a href="{{ route('admin.dashboard') }}">← Retour au dashboard</a>
    </div>
</div>

@endsection
