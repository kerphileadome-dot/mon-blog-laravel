@extends('layouts.app')

@section('content')

<div class="write-form">

    <h1 class="write-title">✍️ Nouvel article</h1>

    <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data">
        @csrf

        {{-- Titre --}}
        <div style="margin-bottom:1.75rem;">
            <label class="field-label">Titre *</label>
            <input type="text" name="title"
                class="form-input"
                style="font-size:1.1rem;padding:1rem 1.25rem;"
                placeholder="Donnez un titre accrocheur..."
                value="{{ old('title') }}" required>
            @error('title') <p class="field-error">{{ $message }}</p> @enderror
        </div>

        {{-- Catégorie --}}
        <div style="margin-bottom:1.75rem;">
            <label class="field-label">Catégorie</label>
            <input type="text" name="category"
                class="form-input"
                placeholder="Ex : Tech, Sport, Lifestyle..."
                value="{{ old('category') }}">
        </div>

        {{-- Image de couverture --}}
        <div style="margin-bottom:1.75rem;">
            <label class="field-label">Image de couverture</label>
            <div style="border:2px dashed var(--border-light);border-radius:12px;padding:2rem;text-align:center;cursor:pointer;transition:border-color 0.2s;"
                onclick="document.getElementById('cover_image').click()"
                id="dropZone">
                <div id="preview" style="display:none;margin-bottom:1rem;">
                    <img id="previewImg" src="" alt="Preview"
                        style="max-height:200px;border-radius:8px;object-fit:cover;">
                </div>
                <div id="uploadPrompt">
                    <p style="font-size:2rem;margin-bottom:0.5rem;">🖼️</p>
                    <p style="color:var(--text-muted);font-size:0.9rem;">Cliquez pour choisir une image</p>
                    <p style="color:var(--text-dim);font-size:0.8rem;margin-top:0.25rem;">JPG, PNG, GIF, WEBP · Max 4MB</p>
                </div>
                <input type="file" id="cover_image" name="cover_image"
                    accept="image/*" style="display:none;"
                    onchange="previewImage(this)">
            </div>
            @error('cover_image') <p class="field-error">{{ $message }}</p> @enderror
        </div>

        {{-- Extrait --}}
        <div style="margin-bottom:1.75rem;">
            <label class="field-label">Extrait</label>
            <textarea name="excerpt" rows="2" class="form-input"
                style="resize:vertical;"
                placeholder="Courte description...">{{ old('excerpt') }}</textarea>
        </div>

        {{-- Contenu --}}
        <div style="margin-bottom:2rem;">
            <label class="field-label">Contenu *</label>
            <textarea name="content" rows="14" class="form-input"
                style="resize:vertical;line-height:1.75;"
                placeholder="Écrivez votre article ici..." required>{{ old('content') }}</textarea>
            @error('content') <p class="field-error">{{ $message }}</p> @enderror
        </div>

        {{-- Footer --}}
        <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem;">
            <label class="publish-checkbox">
                <input type="checkbox" name="published" value="1"
                    style="width:1rem;height:1rem;accent-color:var(--accent);"
                    {{ old('published') ? 'checked' : '' }}>
                Publier immédiatement
            </label>
            <div style="display:flex;gap:0.75rem;">
                <a href="{{ route('posts.index') }}" class="btn-ghost">Annuler</a>
                <button type="submit" class="btn-primary">🚀 Publier l'article</button>
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
        };
        reader.readAsDataURL(input.files[0]);
        document.getElementById('dropZone').style.borderColor = 'var(--accent)';
    }
}
</script>

@endsection