<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class SettingsController extends Controller
{
    protected $settingsFile;

    public function __construct()
    {
        $this->settingsFile = storage_path('app/blog_settings.json');
    }

    // Afficher les paramètres
    public function index()
    {
        $settings = $this->getSettings();
        return view('admin.settings.index', compact('settings'));
    }

    // Sauvegarder les paramètres
    public function update(Request $request)
    {
        $request->validate([
            'blog_name' => 'required|max:255',
            'blog_description' => 'nullable|max:500',
            'blog_keywords' => 'nullable|max:255',
            'comments_auto_approve' => 'boolean',
            'posts_per_page' => 'required|integer|min:1|max:50',
            'email_notifications' => 'boolean',
        ]);

        $settings = [
            'blog_name' => $request->blog_name,
            'blog_description' => $request->blog_description,
            'blog_keywords' => $request->blog_keywords,
            'comments_auto_approve' => $request->has('comments_auto_approve'),
            'posts_per_page' => $request->posts_per_page,
            'email_notifications' => $request->has('email_notifications'),
            'updated_at' => now()->toDateTimeString(),
        ];

        File::put($this->settingsFile, json_encode($settings, JSON_PRETTY_PRINT));

        return back()->with('success', 'Paramètres mis à jour avec succès !');
    }

    // Obtenir les paramètres
    protected function getSettings()
    {
        if (!File::exists($this->settingsFile)) {
            return [
                'blog_name' => config('app.name'),
                'blog_description' => 'Mon blog personnel',
                'blog_keywords' => 'blog, articles, tutoriels',
                'comments_auto_approve' => false,
                'posts_per_page' => 6,
                'email_notifications' => true,
            ];
        }

        return json_decode(File::get($this->settingsFile), true);
    }

    // Export des utilisateurs en CSV
    public function exportUsers()
    {
        $users = \App\Models\User::where('role', 'visitor')
            ->withCount(['posts', 'comments', 'favorites'])
            ->get();

        $filename = 'users_' . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($users) {
            $file = fopen('php://output', 'w');

            // En-têtes
            fputcsv($file, ['Nom', 'Email', 'Articles', 'Commentaires', 'Favoris', 'Inscrit le']);

            // Données
            foreach ($users as $user) {
                fputcsv($file, [
                    $user->name,
                    $user->email,
                    $user->posts_count,
                    $user->comments_count,
                    $user->favorites_count,
                    $user->created_at->format('d/m/Y H:i'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // Export des statistiques en CSV
    public function exportStats()
    {
        $posts = \App\Models\Post::with(['likes', 'comments'])
            ->withCount(['likes', 'comments'])
            ->orderBy('views', 'desc')
            ->get();

        $filename = 'stats_' . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($posts) {
            $file = fopen('php://output', 'w');

            // En-têtes
            fputcsv($file, ['Titre', 'Statut', 'Vues', 'Likes', 'Commentaires', 'Publié le']);

            // Données
            foreach ($posts as $post) {
                fputcsv($file, [
                    $post->title,
                    $post->published ? 'Publié' : 'Brouillon',
                    $post->views,
                    $post->likes_count,
                    $post->comments_count,
                    $post->created_at->format('d/m/Y H:i'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
