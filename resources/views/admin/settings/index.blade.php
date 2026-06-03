@extends('layouts.app')

@section('content')
<div style="max-width:1000px;margin:0 auto;padding:2rem;">
    <h1 style="font-size:2rem;font-weight:700;margin-bottom:2rem;">⚙️ Paramètres du Blog</h1>

    @if(session('success'))
        <div style="background:#10b981;color:white;padding:1rem;border-radius:0.5rem;margin-bottom:1.5rem;">
            ✓ {{ session('success') }}
        </div>
    @endif

    <div style="background:white;border-radius:1rem;padding:2rem;box-shadow:0 1px 3px rgba(0,0,0,0.1);margin-bottom:2rem;">
        <h2 style="font-size:1.5rem;font-weight:700;margin-bottom:1.5rem;">📝 Informations du Blog</h2>

        <form method="POST" action="{{ route('admin.settings.update') }}">
            @csrf

            <div style="margin-bottom:1.5rem;">
                <label style="display:block;font-weight:600;margin-bottom:0.5rem;">Nom du Blog</label>
                <input type="text" name="blog_name" value="{{ old('blog_name', $settings['blog_name']) }}"
                    style="width:100%;padding:0.75rem;border:1px solid #d1d5db;border-radius:0.5rem;" required>
            </div>

            <div style="margin-bottom:1.5rem;">
                <label style="display:block;font-weight:600;margin-bottom:0.5rem;">Description</label>
                <textarea name="blog_description" rows="3"
                    style="width:100%;padding:0.75rem;border:1px solid #d1d5db;border-radius:0.5rem;">{{ old('blog_description', $settings['blog_description']) }}</textarea>
                <p style="font-size:0.875rem;color:#6b7280;margin-top:0.25rem;">Décrivez votre blog en quelques mots</p>
            </div>

            <div style="margin-bottom:1.5rem;">
                <label style="display:block;font-weight:600;margin-bottom:0.5rem;">Mots-clés SEO</label>
                <input type="text" name="blog_keywords" value="{{ old('blog_keywords', $settings['blog_keywords']) }}"
                    style="width:100%;padding:0.75rem;border:1px solid #d1d5db;border-radius:0.5rem;">
                <p style="font-size:0.875rem;color:#6b7280;margin-top:0.25rem;">Séparez les mots-clés par des virgules</p>
            </div>

            <h3 style="font-size:1.25rem;font-weight:700;margin:2rem 0 1rem;border-top:1px solid #e5e7eb;padding-top:2rem;">💬 Commentaires</h3>

            <div style="margin-bottom:1.5rem;">
                <label style="display:flex;align-items:center;gap:0.75rem;cursor:pointer;">
                    <input type="checkbox" name="comments_auto_approve" value="1"
                        {{ $settings['comments_auto_approve'] ? 'checked' : '' }}
                        style="width:1.25rem;height:1.25rem;">
                    <span style="font-weight:600;">Approuver automatiquement les commentaires</span>
                </label>
                <p style="font-size:0.875rem;color:#6b7280;margin-top:0.25rem;margin-left:2rem;">
                    Si coché, les commentaires seront publiés sans modération
                </p>
            </div>

            <h3 style="font-size:1.25rem;font-weight:700;margin:2rem 0 1rem;border-top:1px solid #e5e7eb;padding-top:2rem;">📊 Affichage</h3>

            <div style="margin-bottom:1.5rem;">
                <label style="display:block;font-weight:600;margin-bottom:0.5rem;">Articles par page</label>
                <input type="number" name="posts_per_page" value="{{ old('posts_per_page', $settings['posts_per_page']) }}"
                    min="1" max="50"
                    style="width:200px;padding:0.75rem;border:1px solid #d1d5db;border-radius:0.5rem;" required>
            </div>

            <h3 style="font-size:1.25rem;font-weight:700;margin:2rem 0 1rem;border-top:1px solid #e5e7eb;padding-top:2rem;">📧 Notifications</h3>

            <div style="margin-bottom:2rem;">
                <label style="display:flex;align-items:center;gap:0.75rem;cursor:pointer;">
                    <input type="checkbox" name="email_notifications" value="1"
                        {{ $settings['email_notifications'] ? 'checked' : '' }}
                        style="width:1.25rem;height:1.25rem;">
                    <span style="font-weight:600;">Recevoir des notifications par email</span>
                </label>
                <p style="font-size:0.875rem;color:#6b7280;margin-top:0.25rem;margin-left:2rem;">
                    Notifications pour nouveaux commentaires, utilisateurs, etc.
                </p>
            </div>

            <button type="submit" style="background:#00bf72;color:white;padding:0.75rem 2rem;border-radius:0.5rem;border:none;font-weight:600;cursor:pointer;font-size:1rem;">
                💾 Enregistrer les paramètres
            </button>
        </form>
    </div>

    <div style="background:white;border-radius:1rem;padding:2rem;box-shadow:0 1px 3px rgba(0,0,0,0.1);">
        <h2 style="font-size:1.5rem;font-weight:700;margin-bottom:1.5rem;">📥 Export de Données</h2>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;">
            <div style="background:#f9fafb;padding:1.5rem;border-radius:0.75rem;">
                <h3 style="font-weight:600;margin-bottom:0.5rem;">Utilisateurs</h3>
                <p style="color:#6b7280;font-size:0.875rem;margin-bottom:1rem;">
                    Exporter la liste complète des utilisateurs inscrits
                </p>
                <a href="{{ route('admin.settings.export-users') }}"
                    style="background:#3b82f6;color:white;padding:0.5rem 1.5rem;border-radius:0.5rem;text-decoration:none;display:inline-block;font-weight:600;">
                    📊 Exporter (CSV)
                </a>
            </div>

            <div style="background:#f9fafb;padding:1.5rem;border-radius:0.75rem;">
                <h3 style="font-weight:600;margin-bottom:0.5rem;">Statistiques Articles</h3>
                <p style="color:#6b7280;font-size:0.875rem;margin-bottom:1rem;">
                    Exporter les statistiques de tous les articles
                </p>
                <a href="{{ route('admin.settings.export-stats') }}"
                    style="background:#3b82f6;color:white;padding:0.5rem 1.5rem;border-radius:0.5rem;text-decoration:none;display:inline-block;font-weight:600;">
                    📈 Exporter (CSV)
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
