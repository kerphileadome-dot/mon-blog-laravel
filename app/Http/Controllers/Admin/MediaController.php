<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\CoverImageProcessor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    public function __construct(private CoverImageProcessor $coverImages) {}

    // Afficher la bibliothèque
    public function index(Request $request)
    {
        $search = $request->get('search', '');
        $allFiles = Storage::disk('public')->files('covers');

        // Filtrer par recherche si nécessaire
        if ($search) {
            $allFiles = array_filter($allFiles, function($file) use ($search) {
                return str_contains(basename($file), $search);
            });
        }

        // Récupérer les informations des fichiers
        $media = collect($allFiles)->map(function($file) {
            return [
                'path' => $file,
                'url' => Storage::url($file),
                'name' => basename($file),
                'size' => Storage::disk('public')->size($file),
                'modified' => Storage::disk('public')->lastModified($file),
            ];
        })->sortByDesc('modified')->values();

        // Statistiques
        $stats = [
            'total_files' => count($allFiles),
            'total_size' => collect($allFiles)->sum(fn($file) => Storage::disk('public')->size($file)),
        ];

        return view('admin.media.index', compact('media', 'stats', 'search'));
    }

    // Upload de fichiers
    public function store(Request $request)
    {
        $request->validate([
            'files' => 'required',
            'files.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120', // 5MB max
        ]);

        $uploadedCount = 0;

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $this->coverImages->store($file);
                $uploadedCount++;
            }
        }

        return back()->with('success', "$uploadedCount image(s) uploadée(s) avec succès !");
    }

    // Supprimer un fichier
    public function destroy(Request $request)
    {
        $request->validate([
            'path' => 'required|string',
        ]);

        $path = $request->input('path');

        if (!Storage::disk('public')->exists($path)) {
            return back()->with('error', 'Image introuvable !');
        }

        // Vérifier si l'image est utilisée par un article publié
        $imageUrl = Storage::url($path);
        $usedByPost = \App\Models\Post::where('cover_image', $path)
            ->where('published', true)
            ->exists();

        if ($usedByPost) {
            return back()->with('error', 'Cette image est utilisée par un article publié. Impossible de la supprimer.');
        }

        Storage::disk('public')->delete($path);
        return back()->with('success', 'Image supprimée avec succès !');
    }

    // Supprimer plusieurs fichiers
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'files' => 'required|array|max:50', // Limite à 50 fichiers
            'files.*' => 'string',
        ]);

        $deletedCount = 0;
        $skippedCount = 0;

        foreach ($request->input('files') as $file) {
            if (!Storage::disk('public')->exists($file)) {
                continue;
            }

            // Vérifier si utilisé par un article publié
            $usedByPost = \App\Models\Post::where('cover_image', $file)
                ->where('published', true)
                ->exists();

            if ($usedByPost) {
                $skippedCount++;
                continue;
            }

            Storage::disk('public')->delete($file);
            $deletedCount++;
        }

        $message = "$deletedCount image(s) supprimée(s)";
        if ($skippedCount > 0) {
            $message .= " ($skippedCount ignorée(s) car utilisée(s))";
        }

        return back()->with('success', $message);
    }
}
