<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use App\Services\BlogSettings;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Post $post, BlogSettings $settings)
    {
        $request->validate([
            'body' => 'required|max:1000',
        ]);

        $post->comments()->create([
            'user_id'  => auth()->id(),
            'name'     => auth()->user()->name,
            'email'    => auth()->user()->email,
            'body'     => $request->body,
            'approved' => $settings->commentsAutoApprove(),
        ]);

        $message = $settings->commentsAutoApprove()
            ? 'Commentaire publié !'
            : 'Commentaire envoyé — en attente de modération.';

        return back()->with('success', $message);
    }

    public function reply(Request $request, Post $post, Comment $comment, BlogSettings $settings)
    {
        if ($comment->post_id !== $post->id) {
            abort(404);
        }

        $request->validate([
            'body' => 'required|max:1000',
        ]);

        $autoApprove = auth()->user()->isAdmin() || $settings->commentsAutoApprove();

        $post->comments()->create([
            'parent_id' => $comment->id,
            'user_id'   => auth()->id(),
            'name'      => auth()->user()->name,
            'email'     => auth()->user()->email,
            'body'      => $request->body,
            'approved'  => $autoApprove,
        ]);

        $message = $autoApprove
            ? 'Réponse publiée !'
            : 'Réponse envoyée — en attente de modération.';

        return back()->with('success', $message)->withFragment('comments');
    }

    public function destroy(Comment $comment)
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            abort(403, 'Accès non autorisé.');
        }

        $comment->delete();
        return back()->with('success', 'Commentaire supprimé !');
    }
}
