<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use App\Services\BlogSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, Post $post, BlogSettings $settings)
    {
        $request->validate([
            'body' => 'required|max:1000',
        ]);

        $post->comments()->create([
            'user_id'  => Auth::guard('web')->id(),
            'name'     => Auth::guard('web')->user()->name,
            'email'    => Auth::guard('web')->user()->email,
            'body'     => $request->body,
            'approved' => $settings->commentsAutoApprove(),
        ]);

        $message = $settings->commentsAutoApprove()
            ? 'Commentaire publié !'
            : 'Commentaire envoyé — en attente de modération.';

        return back()->with('success', $message)->withFragment('comments');
    }

    public function reply(Request $request, Post $post, Comment $comment, BlogSettings $settings)
    {
        if ($comment->post_id !== $post->id) {
            abort(404);
        }

        $request->validate([
            'body' => 'required|max:1000',
        ]);

        $fromAdmin = Auth::guard('admin')->check();

        if (!$fromAdmin && !Auth::guard('web')->check()) {
            abort(403, 'Accès non autorisé.');
        }

        $author = $fromAdmin
            ? Auth::guard('admin')->user()
            : Auth::guard('web')->user();

        $autoApprove = $fromAdmin || $settings->commentsAutoApprove();

        $post->comments()->create([
            'parent_id' => $comment->id,
            'user_id'   => $author->id,
            'name'      => $author->name,
            'email'     => $author->email,
            'body'      => $request->body,
            'approved'  => $autoApprove,
        ]);

        $message = $autoApprove
            ? 'Réponse publiée !'
            : 'Réponse envoyée — en attente de modération.';

        $redirect = back()->with('success', $message);

        return $fromAdmin ? $redirect : $redirect->withFragment('comments');
    }

    public function destroy(Comment $comment)
    {
        if (!Auth::guard('admin')->check()) {
            abort(403, 'Accès non autorisé.');
        }

        $comment->delete();
        return back()->with('success', 'Commentaire supprimé !');
    }
}
