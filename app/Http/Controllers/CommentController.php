<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    // Ajouter un commentaire
    public function store(Request $request, Post $post)
    {
        $request->validate([
            'body' => 'required|max:1000',
        ]);

        $post->comments()->create([
            'user_id' => auth()->id(),
            'name'  => auth()->user()->name,
            'email' => auth()->user()->email,
            'body'  => $request->body,
            'approved' => true, // Auto-approuvé pour les utilisateurs connectés
        ]);

        return back()->with('success', 'Commentaire ajouté !');
    }

    // Supprimer un commentaire
    public function destroy(Comment $comment)
    {
        // Seul l'admin peut supprimer
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            abort(403, 'Accès non autorisé.');
        }

        $comment->delete();
        return back()->with('success', 'Commentaire supprimé !');
    }
}
