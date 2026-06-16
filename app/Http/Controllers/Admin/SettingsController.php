<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\User;
use App\Services\BlogSettings;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index(BlogSettings $settings)
    {
        return view('admin.settings.index', ['settings' => $settings->all()]);
    }

    public function update(Request $request, BlogSettings $settings)
    {
        $request->validate([
            'blog_name' => 'required|max:255',
            'blog_description' => 'nullable|max:500',
            'blog_keywords' => 'nullable|max:255',
            'comments_auto_approve' => 'boolean',
            'posts_per_page' => 'required|integer|min:1|max:50',
            'email_notifications' => 'boolean',
        ]);

        $settings->update([
            'blog_name' => $request->blog_name,
            'blog_description' => $request->blog_description,
            'blog_keywords' => $request->blog_keywords,
            'comments_auto_approve' => $request->has('comments_auto_approve'),
            'posts_per_page' => (int) $request->posts_per_page,
            'email_notifications' => $request->has('email_notifications'),
        ]);

        return back()->with('success', 'Paramètres mis à jour avec succès !');
    }

    public function exportUsers()
    {
        $users = User::where('role', 'visitor')
            ->withCount(['posts', 'comments', 'favorites'])
            ->get();

        $filename = 'users_'.now()->format('Y-m-d_His').'.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ];

        $callback = function () use ($users) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Nom', 'Email', 'Articles', 'Commentaires', 'Favoris', 'Inscrit le']);

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

    public function exportStats()
    {
        $posts = Post::with(['likes', 'comments'])
            ->withCount(['likes', 'comments'])
            ->orderBy('views', 'desc')
            ->get();

        $filename = 'stats_'.now()->format('Y-m-d_His').'.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ];

        $callback = function () use ($posts) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Titre', 'Statut', 'Vues', 'Likes', 'Commentaires', 'Publié le']);

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
