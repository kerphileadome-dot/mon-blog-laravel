@extends('layouts.app')

@section('content')
<div style="max-width:1200px;margin:0 auto;padding:2rem;">
    <a href="{{ route('admin.users.index') }}" style="color:var(--accent);text-decoration:none;margin-bottom:2rem;display:inline-block;">
        ← Retour à la liste
    </a>

    <div style="background:white;border-radius:1rem;padding:2rem;box-shadow:0 1px 3px rgba(0,0,0,0.1);margin-bottom:2rem;">
        <div style="display:flex;justify-content:space-between;align-items:start;margin-bottom:2rem;">
            <div>
                <h1 style="font-size:2rem;font-weight:700;margin-bottom:0.5rem;">{{ $user->name }}</h1>
                <p style="color:#6b7280;">{{ $user->email }}</p>
            </div>
            <div>
                @if($user->isAdmin())
                    <span style="background:#3b82f6;color:white;padding:0.5rem 1rem;border-radius:9999px;font-weight:600;">👑 Administrateur</span>
                @else
                    <span style="background:#10b981;color:white;padding:0.5rem 1rem;border-radius:9999px;font-weight:600;">👤 Visiteur</span>
                @endif
            </div>
        </div>

        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1.5rem;margin-bottom:2rem;">
            <div style="background:#f9fafb;padding:1.5rem;border-radius:0.75rem;text-align:center;">
                <div style="font-size:2rem;font-weight:700;color:#3b82f6;">{{ $user->posts->count() }}</div>
                <div style="color:#6b7280;font-size:0.875rem;margin-top:0.5rem;">Articles publiés</div>
            </div>
            <div style="background:#f9fafb;padding:1.5rem;border-radius:0.75rem;text-align:center;">
                <div style="font-size:2rem;font-weight:700;color:#10b981;">{{ $user->comments->count() }}</div>
                <div style="color:#6b7280;font-size:0.875rem;margin-top:0.5rem;">Commentaires</div>
            </div>
            <div style="background:#f9fafb;padding:1.5rem;border-radius:0.75rem;text-align:center;">
                <div style="font-size:2rem;font-weight:700;color:#f59e0b;">{{ $user->favorites->count() }}</div>
                <div style="color:#6b7280;font-size:0.875rem;margin-top:0.5rem;">Favoris</div>
            </div>
            <div style="background:#f9fafb;padding:1.5rem;border-radius:0.75rem;text-align:center;">
                <div style="font-size:1rem;font-weight:700;color:#6b7280;">{{ $user->created_at->format('d/m/Y') }}</div>
                <div style="color:#6b7280;font-size:0.875rem;margin-top:0.5rem;">Inscrit le</div>
            </div>
        </div>
    </div>

    @if($user->comments->count() > 0)
        <div style="background:white;border-radius:1rem;padding:2rem;box-shadow:0 1px 3px rgba(0,0,0,0.1);margin-bottom:2rem;">
            <h2 style="font-size:1.5rem;font-weight:700;margin-bottom:1.5rem;">💬 Derniers commentaires</h2>
            @foreach($user->comments()->latest()->take(5)->get() as $comment)
                <div style="border-bottom:1px solid #e5e7eb;padding:1rem 0;">
                    <div style="display:flex;justify-content:space-between;align-items:start;margin-bottom:0.5rem;">
                        <a href="{{ route('posts.show', $comment->post) }}" style="color:#3b82f6;text-decoration:none;font-weight:600;">
                            {{ $comment->post->title }}
                        </a>
                        <span style="color:#6b7280;font-size:0.875rem;">{{ $comment->created_at->diffForHumans() }}</span>
                    </div>
                    <p style="color:#374151;">{{ Str::limit($comment->body, 150) }}</p>
                </div>
            @endforeach
        </div>
    @endif

    @if($user->favorites->count() > 0)
        <div style="background:white;border-radius:1rem;padding:2rem;box-shadow:0 1px 3px rgba(0,0,0,0.1);">
            <h2 style="font-size:1.5rem;font-weight:700;margin-bottom:1.5rem;">⭐ Articles favoris</h2>
            <div style="display:grid;gap:1rem;">
                @foreach($user->favoritePosts as $post)
                    <a href="{{ route('posts.show', $post) }}" style="display:flex;justify-content:space-between;padding:1rem;background:#f9fafb;border-radius:0.5rem;text-decoration:none;color:inherit;">
                        <span style="font-weight:600;">{{ $post->title }}</span>
                        <span style="color:#6b7280;">👁 {{ $post->views }} vues</span>
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
