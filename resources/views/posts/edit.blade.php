@extends('layouts.admin')

@section('content')

<div class="write-form">
    <h1 class="write-title">Modifier l'article</h1>

    <form method="POST" action="{{ route('admin.posts.update', $post) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div style="margin-bottom:1.75rem;">
            <label class="field-label">Titre *</label>
            <input type="text" name="title" class="form-input"
                style="font-size:1.1rem;padding:1rem 1.25rem;"
                value="{{ old('title', $post->title) }}" required>
            @error('title') <p class="field-error">{{ $message }}</p> @enderror
        </div>

        <div class="form-grid" style="margin-bottom:1.75rem;">
            <div>
                <label class="field-label">Catégorie</label>
                <input type="text" name="category" class="form-input"
                    placeholder="Tech, Sport, Lifestyle…"
                    value="{{ old('category', $post->category) }}">
            </div>
            <div>
                <label class="field-label">Tags</label>
                <input type="text" name="tags" class="form-input"
                    placeholder="laravel, php, tutoriel"
                    value="{{ old('tags', $post->tags) }}">
            </div>
        </div>

        <div style="margin-bottom:1.75rem;">
            <label class="field-label">Image de couverture</label>
            @if($post->cover_image)
                <div style="margin-bottom:1rem;">
                    <img src="{{ Storage::url($post->cover_image) }}" alt="Image actuelle"
                        style="max-height:200px;border-radius:12px;object-fit:cover;">
                    <p style="color:var(--ink-faint);font-size:0.8rem;margin-top:0.5rem;">Image actuelle — choisissez-en une nouvelle pour la remplacer</p>
                </div>
            @endif
            <div class="drop-zone" onclick="document.getElementById('cover_image').click()" id="dropZone">
                <div id="preview" style="display:none;margin-bottom:1rem;">
                    <img id="previewImg" src="" alt="Aperçu" style="max-height:200px;border-radius:8px;object-fit:cover;">
                </div>
                <div id="uploadPrompt">
                    <p style="color:var(--ink-muted);font-size:0.9rem;">Cliquez pour choisir une nouvelle image</p>
                    <p style="color:var(--ink-faint);font-size:0.8rem;margin-top:0.25rem;">JPG, PNG, GIF, WEBP · Max 4 Mo</p>
                </div>
                <input type="file" id="cover_image" name="cover_image" accept="image/*" style="display:none;" onchange="previewImage(this)">
            </div>
            @error('cover_image') <p class="field-error">{{ $message }}</p> @enderror
        </div>

        <div style="margin-bottom:1.75rem;">
            <label class="field-label">Extrait</label>
            <textarea name="excerpt" rows="2" class="form-input" style="resize:vertical;">{{ old('excerpt', $post->excerpt) }}</textarea>
        </div>

        <div style="margin-bottom:2rem;">
            <label class="field-label">Contenu *</label>
            <textarea name="content" rows="16" class="form-input" style="resize:vertical;line-height:1.75;" required>{{ old('content', $post->content) }}</textarea>
            @error('content') <p class="field-error">{{ $message }}</p> @enderror
        </div>

        <div style="background:var(--bg-warm);border:1px solid var(--border);border-radius:12px;padding:1rem 1.25rem;margin-bottom:2rem;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:0.5rem;">
            <div style="font-size:0.875rem;color:var(--ink-muted);">
                Statut :
                @if($post->published)
                    <span style="color:var(--accent);font-weight:600;">Publié</span>
                @else
                    <span style="color:var(--gold);font-weight:600;">Brouillon</span>
                @endif
            </div>
            <div style="font-size:0.8rem;color:var(--ink-faint);">Créé {{ $post->created_at->diffForHumans() }}</div>
        </div>

        <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem;">
            <label class="publish-checkbox">
                <input type="checkbox" name="published" value="1"
                    style="width:1rem;height:1rem;accent-color:var(--accent);"
                    {{ $post->published ? 'checked' : '' }}>
                Publié
            </label>
            <div style="display:flex;gap:0.75rem;">
                <a href="{{ route('posts.show', $post) }}" class="btn-ghost">Annuler</a>
                <button type="submit" class="btn-primary btn-accent">Mettre à jour</button>
            </div>
        </div>
    </form>
</div>

<a href="{{ route('posts.index') }}" class="back-link">← Retour aux articles</a>

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = (e) => {
            document.getElementById('previewImg').src = e.target.result;
            document.getElementById('preview').style.display = 'block';
            document.getElementById('uploadPrompt').style.display = 'none';
            document.getElementById('dropZone').classList.add('has-file');
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

@endsection
