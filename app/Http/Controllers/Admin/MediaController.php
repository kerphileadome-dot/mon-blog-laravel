<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
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
                $file->store('covers', 'public');
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

        $path = $request->path;

        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
            return back()->with('success', 'Image supprimée avec succès !');
        }

        return back()->with('error', 'Image introuvable !');
    }

    // Supprimer plusieurs fichiers
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'files' => 'required|array',
            'files.*' => 'string',
        ]);

        $deletedCount = 0;

        foreach ($request->files as $file) {
            if (Storage::disk('public')->exists($file)) {
                Storage::disk('public')->delete($file);
                $deletedCount++;
            }
        }

        return back()->with('success', "$deletedCount image(s) supprimée(s) avec succès !");
    }
}
