@extends('layouts.admin')

@section('content')

<div class="admin-container">
    <div class="admin-header">
        <h1 class="admin-title">🖼️ Bibliothèque de médias</h1>
    </div>

    <!-- Statistiques -->
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:1.5rem;margin-bottom:2rem;">
        <div style="background:white;padding:1.5rem;border-radius:12px;box-shadow:0 2px 8px rgba(0,0,0,0.1);">
            <div style="font-size:2rem;margin-bottom:0.5rem;">📁</div>
            <div style="font-size:1.75rem;font-weight:700;color:#333;">{{ $stats['total_files'] }}</div>
            <div style="font-size:0.875rem;color:#666;">Fichiers</div>
        </div>
        <div style="background:white;padding:1.5rem;border-radius:12px;box-shadow:0 2px 8px rgba(0,0,0,0.1);">
            <div style="font-size:2rem;margin-bottom:0.5rem;">💾</div>
            <div style="font-size:1.75rem;font-weight:700;color:#333;">{{ number_format($stats['total_size'] / 1024 / 1024, 2) }} MB</div>
            <div style="font-size:0.875rem;color:#666;">Espace utilisé</div>
        </div>
    </div>

    <!-- Upload de fichiers -->
    <div class="admin-section" style="margin-bottom:2rem;">
        <h3 style="font-size:1.25rem;font-weight:700;margin-bottom:1rem;">📤 Upload d'images</h3>
        <form method="POST" action="{{ route('admin.media.store') }}" enctype="multipart/form-data">
            @csrf
            <div style="border:2px dashed #d1d5db;border-radius:12px;padding:2rem;text-align:center;cursor:pointer;"
                 onclick="document.getElementById('fileInput').click()">
                <input type="file" id="fileInput" name="files[]" multiple accept="image/*" style="display:none;"
                       onchange="this.form.submit()">
                <div style="font-size:3rem;margin-bottom:0.5rem;">📁</div>
                <p style="font-weight:600;margin-bottom:0.25rem;">Cliquez pour sélectionner des images</p>
                <p style="font-size:0.875rem;color:#666;">JPG, PNG, GIF, WEBP · Max 5MB par fichier</p>
            </div>
            @error('files.*')
                <p class="field-error">{{ $message }}</p>
            @enderror
        </form>
    </div>

    <!-- Liste des médias -->
    <div class="admin-section">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;">
            <h3 style="font-size:1.25rem;font-weight:700;">🖼️ Vos images ({{ $media->count() }})</h3>
            @if($media->count() > 0)
                <button onclick="toggleBulkMode()" id="bulkBtn" class="admin-btn admin-btn-secondary" style="font-size:0.875rem;">
                    ✓ Sélection multiple
                </button>
            @endif
        </div>

        @if($media->count() > 0)
            <form method="POST" action="{{ route('admin.media.bulk-delete') }}" id="bulkForm" style="display:none;">
                @csrf
                @method('DELETE')
                <div style="margin-bottom:1rem;display:flex;gap:0.5rem;">
                    <button type="submit" class="admin-btn" style="background:#fee2e2;color:#991b1b;border:2px solid #fecaca;font-size:0.875rem;"
                            onclick="return confirm('Supprimer les images sélectionnées ?')">
                        🗑️ Supprimer la sélection
                    </button>
                    <button type="button" onclick="toggleBulkMode()" class="admin-btn admin-btn-secondary" style="font-size:0.875rem;">
                        Annuler
                    </button>
                </div>
            </form>

            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:1rem;">
                @foreach($media as $item)
                    <div class="media-card" style="background:white;border-radius:12px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,0.1);">
                        <div style="position:relative;">
                            <img src="{{ $item['url'] }}" alt="{{ $item['name'] }}"
                                 style="width:100%;height:150px;object-fit:cover;">
                            <div class="bulk-checkbox" style="display:none;position:absolute;top:0.5rem;left:0.5rem;">
                                <input type="checkbox" name="files[]" value="{{ $item['path'] }}" form="bulkForm"
                                       style="width:1.25rem;height:1.25rem;">
                            </div>
                        </div>
                        <div style="padding:1rem;">
                            <p style="font-size:0.75rem;color:#666;margin-bottom:0.5rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"
                               title="{{ $item['name'] }}">
                                {{ $item['name'] }}
                            </p>
                            <p style="font-size:0.75rem;color:#999;margin-bottom:0.75rem;">
                                {{ number_format($item['size'] / 1024, 2) }} KB
                            </p>
                            <div style="display:flex;gap:0.5rem;flex-wrap:wrap;">
                                <button onclick="copyUrl('{{ $item['url'] }}')"
                                        class="admin-btn" style="flex:1;font-size:0.75rem;padding:0.5rem;">
                                    📋 Copier URL
                                </button>
                                <form method="POST" action="{{ route('admin.media.destroy') }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="path" value="{{ $item['path'] }}">
                                    <button class="admin-btn" style="background:#fee2e2;color:#991b1b;border:2px solid #fecaca;font-size:0.75rem;padding:0.5rem;"
                                            onclick="return confirm('Supprimer cette image ?')">
                                        🗑️
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div style="text-align:center;padding:3rem;color:#666;">
                <div style="font-size:3rem;margin-bottom:1rem;">📭</div>
                <p>Aucune image pour l'instant.</p>
                <p style="font-size:0.875rem;margin-top:0.5rem;">Uploadez vos premières images ci-dessus.</p>
            </div>
        @endif
    </div>

    <div class="back-link">
        <a href="{{ route('admin.dashboard') }}">← Retour au dashboard</a>
    </div>
</div>

<script>
function copyUrl(url) {
    const fullUrl = window.location.origin + url;
    navigator.clipboard.writeText(fullUrl).then(() => {
        alert('URL copiée : ' + fullUrl);
    });
}

function toggleBulkMode() {
    const bulkForm = document.getElementById('bulkForm');
    const bulkBtn = document.getElementById('bulkBtn');
    const checkboxes = document.querySelectorAll('.bulk-checkbox');

    if (bulkForm.style.display === 'none') {
        bulkForm.style.display = 'block';
        bulkBtn.style.display = 'none';
        checkboxes.forEach(cb => cb.style.display = 'block');
    } else {
        bulkForm.style.display = 'none';
        bulkBtn.style.display = 'inline-block';
        checkboxes.forEach(cb => {
            cb.style.display = 'none';
            cb.querySelector('input').checked = false;
        });
    }
}
</script>

@endsection
