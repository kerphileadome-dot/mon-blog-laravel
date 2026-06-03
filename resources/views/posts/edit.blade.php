@extends('layouts.admin')

@section('content')

<div class="write-form">

    <h1 class="write-title">✏️ Modifier l'article</h1>

    <form method="POST" action="{{ route('admin.posts.update', $post) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Titre --}}
        <div style="margin-bottom:1.75rem;">
            <label class="field-label">Titre *</label>
            <input type="text" name="title"
                class="form-input"
                style="font-size:1.1rem;padding:1rem 1.25rem;"
                value="{{ old('title', $post->title) }}" required>
            @error('title') <p class="field-error">{{ $message }}</p> @enderror
        </div>

        {{-- Catégorie --}}
        <div style="margin-bottom:1.75rem;">
            <label class="field-label">Catégorie</label>
            <input type="text" name="category"
                class="form-input"
                placeholder="Ex : Tech, Sport, Lifestyle..."
                value="{{ old('category', $post->category) }}">
        </div>

        {{-- Image de couverture --}}
        <div style="margin-bottom:1.75rem;">
            <label class="field-label">Image de couverture</label>

            @if($post->cover_image)
                <div style="margin-bottom:1rem;">
                    <img src="{{ Storage::url($post->cover_image) }}"
                        alt="Image actuelle"
                        style="max-height:200px;border-radius:12px;object-fit:cover;">
                    <p style="color:var(--text-dim);font-size:0.8rem;margin-top:0.5rem;">
                        Image actuelle — choisissez-en une nouvelle pour la remplacer
                    </p>
                </div>
            @endif

            <div style="border:2px dashed var(--border-light);border-radius:12px;padding:2rem;text-align:center;cursor:pointer;transition:border-color 0.2s;"
                onclick="document.getElementById('cover_image').click()"
                id="dropZone">
                <div id="preview" style="display:none;margin-bottom:1rem;">
                    <img id="previewImg" src="" alt="Preview"
                        style="max-height:200px;border-radius:8px;object-fit:cover;">
                </div>
                <div id="uploadPrompt">
                    <p style="font-size:2rem;margin-bottom:0.5rem;">🖼️</p>
                    <p style="color:var(--text-muted);font-size:0.9rem;">
                        Cliquez pour choisir une nouvelle image
                    </p>
                    <p style="color:var(--text-dim);font-size:0.8rem;margin-top:0.25rem;">
                        JPG, PNG, GIF, WEBP · Max 4MB
                    </p>
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
                style="resize:vertical;">{{ old('excerpt', $post->excerpt) }}</textarea>
        </div>

        {{-- Contenu --}}
        <div style="margin-bottom:2rem;">
            <label class="field-label">Contenu *</label>
            <textarea name="content" rows="14" class="form-input"
                style="resize:vertical;line-height:1.75;"
                required>{{ old('content', $post->content) }}</textarea>
            @error('content') <p class="field-error">{{ $message }}</p> @enderror
        </div>

        {{-- Statut --}}
        <div style="background:var(--surface);border:1px solid var(--border);border-radius:12px;padding:1rem 1.25rem;margin-bottom:2rem;display:flex;align-items:center;justify-content:space-between;">
            <div style="font-size:0.875rem;color:var(--text-muted);">
                Statut :
                @if($post->published)
                    <span style="color:var(--accent-3);font-weight:600;">● Publié</span>
                @else
                    <span style="color:var(--accent);font-weight:600;">● Brouillon</span>
                @endif
            </div>
            <div style="font-size:0.8rem;color:var(--text-dim);">
                Créé {{ $post->created_at->diffForHumans() }}
            </div>
        </div>

        {{-- Footer --}}
        <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem;">
            <label class="publish-checkbox">
                <input type="checkbox" name="published" value="1"
                    style="width:1rem;height:1rem;accent-color:var(--accent);"
                    {{ $post->published ? 'checked' : '' }}>
                Publié
            </label>
            <div style="display:flex;gap:0.75rem;">
                <a href="{{ route('posts.show', $post) }}" class="btn-ghost">Annuler</a>
                <button type="submit" class="btn-primary">💾 Mettre à jour</button>
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
