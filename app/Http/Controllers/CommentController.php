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
            'name' => 'required|max:100',
            'email' => 'nullable|email',
            'body' => 'required|max:1000',
        ]);

        $post->comments()->create([
            'name'  => $request->name,
            'email' => $request->email,
            'body'  => $request->body,
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
