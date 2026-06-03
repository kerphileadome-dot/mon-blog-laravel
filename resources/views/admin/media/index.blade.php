@extends('layouts.app')

@section('content')
<div style="max-width:1400px;margin:0 auto;padding:2rem;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:2rem;">
        <h1 style="font-size:2rem;font-weight:700;">🖼️ Bibliothèque de Médias</h1>
        <button onclick="document.getElementById('uploadModal').style.display='block'"
            style="background:#00bf72;color:white;padding:0.75rem 1.5rem;border-radius:0.5rem;border:none;font-weight:600;cursor:pointer;">
            ⬆️ Uploader des images
        </button>
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

    <!-- Statistiques -->
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1.5rem;margin-bottom:2rem;">
        <div style="background:white;padding:1.5rem;border-radius:0.75rem;box-shadow:0 1px 3px rgba(0,0,0,0.1);">
            <div style="font-size:0.875rem;color:#6b7280;margin-bottom:0.5rem;">Total d'images</div>
            <div style="font-size:2rem;font-weight:700;color:#3b82f6;">{{ $stats['total_files'] }}</div>
        </div>
        <div style="background:white;padding:1.5rem;border-radius:0.75rem;box-shadow:0 1px 3px rgba(0,0,0,0.1);">
            <div style="font-size:0.875rem;color:#6b7280;margin-bottom:0.5rem;">Espace utilisé</div>
            <div style="font-size:2rem;font-weight:700;color:#10b981;">{{ number_format($stats['total_size'] / 1024 / 1024, 2) }} MB</div>
        </div>
        <div style="background:white;padding:1.5rem;border-radius:0.75rem;box-shadow:0 1px 3px rgba(0,0,0,0.1);">
            <div style="font-size:0.875rem;color:#6b7280;margin-bottom:0.5rem;">Rechercher</div>
            <form method="GET" style="margin-top:0.5rem;">
                <input type="text" name="search" value="{{ $search }}" placeholder="Rechercher une image..."
                    style="width:100%;padding:0.5rem;border:1px solid #d1d5db;border-radius:0.5rem;font-size:0.875rem;">
            </form>
        </div>
    </div>

    <!-- Grille d'images -->
    @if($media->count() > 0)
        <form method="POST" action="{{ route('admin.media.bulk-delete') }}" id="bulkDeleteForm">
            @csrf
            @method('DELETE')

            <div style="background:white;border-radius:1rem;padding:1.5rem;box-shadow:0 1px 3px rgba(0,0,0,0.1);margin-bottom:1rem;">
                <div style="display:flex;gap:1rem;margin-bottom:1rem;">
                    <button type="button" onclick="selectAll()"
                        style="background:#6b7280;color:white;padding:0.5rem 1rem;border-radius:0.5rem;border:none;cursor:pointer;font-size:0.875rem;">
                        Tout sélectionner
                    </button>
                    <button type="button" onclick="deselectAll()"
                        style="background:#6b7280;color:white;padding:0.5rem 1rem;border-radius:0.5rem;border:none;cursor:pointer;font-size:0.875rem;">
                        Tout désélectionner
                    </button>
                    <button type="submit" onclick="return confirm('Supprimer les images sélectionnées ?')"
                        style="background:#ef4444;color:white;padding:0.5rem 1rem;border-radius:0.5rem;border:none;cursor:pointer;font-size:0.875rem;">
                        🗑️ Supprimer la sélection
                    </button>
                </div>

                <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:1.5rem;">
                    @foreach($media as $item)
                        <div class="media-item" style="position:relative;border:2px solid #e5e7eb;border-radius:0.75rem;overflow:hidden;background:#f9fafb;">
                            <input type="checkbox" name="files[]" value="{{ $item['path'] }}" class="media-checkbox"
                                style="position:absolute;top:0.5rem;left:0.5rem;width:1.25rem;height:1.25rem;cursor:pointer;z-index:10;">

                            <img src="{{ $item['url'] }}" alt="{{ $item['name'] }}"
                                style="width:100%;height:200px;object-fit:cover;cursor:pointer;"
                                onclick="showImageModal('{{ $item['url'] }}', '{{ $item['name'] }}', '{{ $item['path'] }}')">

                            <div style="padding:1rem;">
                                <div style="font-size:0.75rem;font-weight:600;margin-bottom:0.5rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"
                                    title="{{ $item['name'] }}">
                                    {{ $item['name'] }}
                                </div>
                                <div style="font-size:0.75rem;color:#6b7280;margin-bottom:0.5rem;">
                                    {{ number_format($item['size'] / 1024, 2) }} KB
                                </div>
                                <div style="font-size:0.75rem;color:#6b7280;margin-bottom:0.75rem;">
                                    {{ date('d/m/Y H:i', $item['modified']) }}
                                </div>
                                <button type="button" onclick="copyUrl('{{ $item['url'] }}')"
                                    style="width:100%;background:#3b82f6;color:white;padding:0.5rem;border-radius:0.5rem;border:none;cursor:pointer;font-size:0.75rem;margin-bottom:0.5rem;">
                                    📋 Copier l'URL
                                </button>
                                <form method="POST" action="{{ route('admin.media.destroy') }}" style="display:inline;width:100%;" onsubmit="return confirm('Supprimer cette image ?')">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="path" value="{{ $item['path'] }}">
                                    <button type="submit" style="width:100%;background:#ef4444;color:white;padding:0.5rem;border-radius:0.5rem;border:none;cursor:pointer;font-size:0.75rem;">
                                        🗑️ Supprimer
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </form>
    @else
        <div style="background:white;border-radius:1rem;padding:4rem;text-align:center;box-shadow:0 1px 3px rgba(0,0,0,0.1);">
            <div style="font-size:4rem;margin-bottom:1rem;">📭</div>
            <h3 style="font-size:1.5rem;font-weight:600;margin-bottom:0.5rem;">Aucune image</h3>
            <p style="color:#6b7280;margin-bottom:2rem;">Commencez par uploader des images pour votre blog</p>
            <button onclick="document.getElementById('uploadModal').style.display='block'"
                style="background:#00bf72;color:white;padding:0.75rem 2rem;border-radius:0.5rem;border:none;font-weight:600;cursor:pointer;">
                ⬆️ Uploader des images
            </button>
        </div>
    @endif
</div>

<!-- Modal Upload -->
<div id="uploadModal" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.7);z-index:1000;padding:2rem;">
    <div style="max-width:600px;margin:4rem auto;background:white;border-radius:1rem;padding:2rem;position:relative;">
        <button onclick="document.getElementById('uploadModal').style.display='none'"
            style="position:absolute;top:1rem;right:1rem;background:transparent;border:none;font-size:1.5rem;cursor:pointer;color:#6b7280;">
            ✕
        </button>

        <h2 style="font-size:1.5rem;font-weight:700;margin-bottom:1.5rem;">📤 Uploader des images</h2>

        <form method="POST" action="{{ route('admin.media.store') }}" enctype="multipart/form-data">
            @csrf

            <div style="border:2px dashed #d1d5db;border-radius:0.75rem;padding:3rem;text-align:center;margin-bottom:1.5rem;cursor:pointer;"
                onclick="document.getElementById('fileInput').click()">
                <div style="font-size:3rem;margin-bottom:1rem;">📁</div>
                <p style="font-weight:600;margin-bottom:0.5rem;">Cliquez pour sélectionner des images</p>
                <p style="color:#6b7280;font-size:0.875rem;">ou glissez-déposez vos fichiers ici</p>
                <p style="color:#6b7280;font-size:0.75rem;margin-top:1rem;">JPG, PNG, GIF, WEBP - Max 5MB par image</p>
            </div>

            <input type="file" id="fileInput" name="files[]" multiple accept="image/*"
                style="display:none;" onchange="showSelectedFiles(this)">

            <div id="selectedFiles" style="margin-bottom:1.5rem;"></div>

            <button type="submit" style="width:100%;background:#00bf72;color:white;padding:0.75rem;border-radius:0.5rem;border:none;font-weight:600;cursor:pointer;">
                ⬆️ Uploader
            </button>
        </form>
    </div>
</div>

<!-- Modal Prévisualisation -->
<div id="imageModal" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.9);z-index:1001;padding:2rem;" onclick="this.style.display='none'">
    <button onclick="document.getElementById('imageModal').style.display='none'"
        style="position:absolute;top:2rem;right:2rem;background:white;border:none;font-size:2rem;cursor:pointer;color:#111;border-radius:50%;width:3rem;height:3rem;">
        ✕
    </button>
    <div style="max-width:90%;max-height:90%;margin:auto;position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);" onclick="event.stopPropagation()">
        <img id="modalImage" src="" alt="" style="max-width:100%;max-height:80vh;border-radius:1rem;">
        <div style="background:white;padding:1rem;border-radius:0 0 1rem 1rem;text-align:center;">
            <p id="modalImageName" style="font-weight:600;margin-bottom:0.5rem;"></p>
            <button onclick="copyUrl(document.getElementById('modalImageUrl').value)"
                style="background:#3b82f6;color:white;padding:0.5rem 1.5rem;border-radius:0.5rem;border:none;cursor:pointer;">
                📋 Copier l'URL
            </button>
            <input type="hidden" id="modalImageUrl">
        </div>
    </div>
</div>

<script>
function showSelectedFiles(input) {
    const container = document.getElementById('selectedFiles');
    container.innerHTML = '';

    if (input.files.length > 0) {
        container.innerHTML = `<p style="font-weight:600;margin-bottom:0.5rem;">${input.files.length} fichier(s) sélectionné(s) :</p>`;
        for (let i = 0; i < input.files.length; i++) {
            container.innerHTML += `<p style="font-size:0.875rem;color:#6b7280;">• ${input.files[i].name}</p>`;
        }
    }
}

function copyUrl(url) {
    navigator.clipboard.writeText(url).then(() => {
        alert('✓ URL copiée dans le presse-papier !');
    });
}

function showImageModal(url, name, path) {
    document.getElementById('modalImage').src = url;
    document.getElementById('modalImageName').textContent = name;
    document.getElementById('modalImageUrl').value = url;
    document.getElementById('imageModal').style.display = 'block';
}

function selectAll() {
    document.querySelectorAll('.media-checkbox').forEach(cb => cb.checked = true);
}

function deselectAll() {
    document.querySelectorAll('.media-checkbox').forEach(cb => cb.checked = false);
}
</script>
@endsection
