@extends('layouts.admin')

@section('content')
<div style="max-width:1400px;margin:0 auto;padding:2rem;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:2rem;">
        <h1 style="font-size:2rem;font-weight:700;">👥 Gestion des Utilisateurs</h1>
    </div>

    @if(session('success'))
        <div style="background:#10b981;color:white;padding:1rem;border-radius:0.5rem;margin-bottom:1.5rem;">
            ✓ {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="background:#ef4444;color:white;padding:1rem;border-radius:0.5rem;margin-bottom:1.5rem;">
            ✕ {{ session('error') }}
        </div>
    @endif

    <div style="background:white;border-radius:1rem;box-shadow:0 1px 3px rgba(0,0,0,0.1);overflow-x:auto;">
        <table style="width:100%;min-width:1200px;border-collapse:collapse;">
            <thead style="background:#f9fafb;">
                <tr>
                    <th style="padding:1rem;text-align:left;font-weight:600;color:#374151;white-space:nowrap;">Nom</th>
                    <th style="padding:1rem;text-align:left;font-weight:600;color:#374151;white-space:nowrap;">Email</th>
                    <th style="padding:1rem;text-align:center;font-weight:600;color:#374151;white-space:nowrap;">Rôle</th>
                    <th style="padding:1rem;text-align:center;font-weight:600;color:#374151;white-space:nowrap;">Articles</th>
                    <th style="padding:1rem;text-align:center;font-weight:600;color:#374151;white-space:nowrap;">Commentaires</th>
                    <th style="padding:1rem;text-align:center;font-weight:600;color:#374151;white-space:nowrap;">Favoris</th>
                    <th style="padding:1rem;text-align:center;font-weight:600;color:#374151;white-space:nowrap;">Inscription</th>
                    <th style="padding:1rem;text-align:center;font-weight:600;color:#374151;white-space:nowrap;">Statut</th>
                    <th style="padding:1rem;text-align:center;font-weight:600;color:#374151;white-space:nowrap;min-width:280px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr style="border-top:1px solid #e5e7eb;">
                        <td style="padding:1rem;">
                            <div style="font-weight:600;">{{ $user->name }}</div>
                        </td>
                        <td style="padding:1rem;color:#6b7280;">{{ $user->email }}</td>
                        <td style="padding:1rem;">
                            @if($user->isAdmin())
                                <span style="background:#3b82f6;color:white;padding:0.25rem 0.75rem;border-radius:9999px;font-size:0.75rem;font-weight:600;">Admin</span>
                            @else
                                <span style="background:#10b981;color:white;padding:0.25rem 0.75rem;border-radius:9999px;font-size:0.75rem;font-weight:600;">Visiteur</span>
                            @endif
                        </td>
                        <td style="padding:1rem;text-align:center;">{{ $user->posts_count }}</td>
                        <td style="padding:1rem;text-align:center;">{{ $user->comments_count }}</td>
                        <td style="padding:1rem;text-align:center;">{{ $user->favorites_count }}</td>
                        <td style="padding:1rem;color:#6b7280;font-size:0.875rem;white-space:nowrap;">{{ $user->created_at->format('d/m/Y') }}</td>
                        <td style="padding:1rem;text-align:center;white-space:nowrap;">
                            @if($user->blocked)
                                <span style="background:#ef4444;color:white;padding:0.25rem 0.75rem;border-radius:9999px;font-size:0.75rem;font-weight:600;">Bloqué</span>
                            @else
                                <span style="background:#10b981;color:white;padding:0.25rem 0.75rem;border-radius:9999px;font-size:0.75rem;font-weight:600;">Actif</span>
                            @endif
                        </td>
                        <td style="padding:1rem;white-space:nowrap;">
                            <div style="display:flex;gap:0.5rem;justify-content:center;flex-wrap:nowrap;">
                                <a href="{{ route('admin.users.show', $user) }}" style="background:#3b82f6;color:white;padding:0.5rem 1rem;border-radius:0.5rem;text-decoration:none;font-size:0.875rem;">
                                    👁️ Voir
                                </a>
                                @if(!$user->isAdmin())
                                    <form method="POST" action="{{ route('admin.users.toggle-block', $user) }}" style="display:inline;">
                                        @csrf
                                        <button style="background:{{ $user->blocked ? '#10b981' : '#f59e0b' }};color:white;padding:0.5rem 1rem;border-radius:0.5rem;border:none;cursor:pointer;font-size:0.875rem;">
                                            {{ $user->blocked ? '✓ Débloquer' : '🚫 Bloquer' }}
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" style="display:inline;" onsubmit="return confirm('Supprimer cet utilisateur ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button style="background:#ef4444;color:white;padding:0.5rem 1rem;border-radius:0.5rem;border:none;cursor:pointer;font-size:0.875rem;">
                                            🗑️
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" style="padding:3rem;text-align:center;color:#9ca3af;">
                            Aucun utilisateur inscrit pour l'instant.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:2rem;">
        {{ $users->links() }}
    </div>
</div>
@endsection
