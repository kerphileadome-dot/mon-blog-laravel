@extends('layouts.admin')

@section('content')

<div class="admin-container">
    <div class="admin-header">
        <h1 class="admin-title">⚙️ Paramètres</h1>
    </div>

    <!-- Exports -->
    <div class="admin-section" style="margin-bottom:2rem;">
        <h3 style="font-size:1.25rem;font-weight:700;margin-bottom:1rem;">📊 Exports</h3>
        <div style="display:flex;gap:1rem;flex-wrap:wrap;">
            <a href="{{ route('admin.settings.export-users') }}" class="admin-btn admin-btn-primary">
                📥 Exporter les utilisateurs (CSV)
            </a>
            <a href="{{ route('admin.settings.export-stats') }}" class="admin-btn admin-btn-primary">
                📥 Exporter les statistiques (CSV)
            </a>
        </div>
        <p style="font-size:0.875rem;color:#666;margin-top:1rem;">
            Les fichiers CSV peuvent être ouverts avec Excel, Google Sheets, ou tout autre tableur.
        </p>
    </div>

    <!-- Paramètres du blog -->
    <div class="admin-section">
        <h3 style="font-size:1.25rem;font-weight:700;margin-bottom:1rem;">🎨 Paramètres du blog</h3>
        <form method="POST" action="{{ route('admin.settings.update') }}">
            @csrf

            <div style="margin-bottom:1.5rem;">
                <label class="field-label">Nom du blog</label>
                <input type="text" name="blog_name" class="form-input"
                       value="{{ old('blog_name', $settings['blog_name']) }}" required>
            </div>

            <div style="margin-bottom:1.5rem;">
                <label class="field-label">Description</label>
                <textarea name="blog_description" class="form-input" rows="3">{{ old('blog_description', $settings['blog_description']) }}</textarea>
            </div>

            <div style="margin-bottom:1.5rem;">
                <label class="field-label">Mots-clés (SEO)</label>
                <input type="text" name="blog_keywords" class="form-input"
                       value="{{ old('blog_keywords', $settings['blog_keywords']) }}"
                       placeholder="blog, articles, tutoriels">
            </div>

            <div style="margin-bottom:1.5rem;">
                <label class="field-label">Articles par page</label>
                <input type="number" name="posts_per_page" class="form-input"
                       value="{{ old('posts_per_page', $settings['posts_per_page']) }}"
                       min="1" max="50" required>
            </div>

            <div style="margin-bottom:1.5rem;">
                <label class="publish-checkbox">
                    <input type="checkbox" name="comments_auto_approve" value="1"
                           style="width:1rem;height:1rem;accent-color:var(--accent);"
                           {{ $settings['comments_auto_approve'] ? 'checked' : '' }}>
                    Approuver automatiquement les commentaires
                </label>
            </div>

            <div style="margin-bottom:1.5rem;">
                <label class="publish-checkbox">
                    <input type="checkbox" name="email_notifications" value="1"
                           style="width:1rem;height:1rem;accent-color:var(--accent);"
                           {{ $settings['email_notifications'] ? 'checked' : '' }}>
                    Activer les notifications par email
                </label>
            </div>

            <div style="display:flex;gap:1rem;">
                <button type="submit" class="admin-btn admin-btn-primary">💾 Sauvegarder</button>
                <a href="{{ route('admin.dashboard') }}" class="admin-btn admin-btn-secondary">Annuler</a>
            </div>
        </form>
    </div>

    <div class="back-link">
        <a href="{{ route('admin.dashboard') }}">← Retour au dashboard</a>
    </div>
</div>

@endsection
