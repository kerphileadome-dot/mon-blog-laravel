<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use App\Services\BlogSettings;
use App\Services\CommentMentionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function __construct(
        protected CommentMentionService $mentions,
    ) {}

    public function store(Request $request, Post $post, BlogSettings $settings)
    {
        $request->validate([
            'body' => 'required|max:1000',
        ]);

        $userId = Auth::guard('web')->id();
        $body = trim($request->body);

        if ($this->isDuplicate($userId, $post->id, $body)) {
            return $this->redirectAfterComment($post, 'Commentaire déjà publié.');
        }

        $approved = $settings->commentsAutoApprove();

        $comment = $post->comments()->create([
            'user_id' => $userId,
            'name' => Auth::guard('web')->user()->name,
            'email' => Auth::guard('web')->user()->email,
            'body' => $body,
            'approved' => $approved,
        ]);

        $this->mentions->processNewComment($comment, $body, $approved);

        $message = $approved
            ? 'Commentaire publié !'
            : 'Commentaire envoyé — en attente de modération.';

        return $this->redirectAfterComment($post, $message);
    }

    public function reply(Request $request, Post $post, Comment $comment, BlogSettings $settings)
    {
        if ($comment->post_id !== $post->id) {
            abort(404);
        }

        $request->validate([
            'body' => 'required|max:1000',
        ]);

        $body = trim($request->body);

        $fromAdmin = Auth::guard('admin')->check();

        if (! $fromAdmin && ! Auth::guard('web')->check()) {
            abort(403, 'Accès non autorisé.');
        }

        $author = $fromAdmin
            ? Auth::guard('admin')->user()
            : Auth::guard('web')->user();

        if ($this->isDuplicate($author->id, $post->id, $body, $comment->id)) {
            return $this->redirectAfterComment($post, 'Réponse déjà publiée.', ! $fromAdmin);
        }

        $autoApprove = $fromAdmin || $settings->commentsAutoApprove();

        $reply = $post->comments()->create([
            'parent_id' => $comment->id,
            'user_id' => $author->id,
            'name' => $author->name,
            'email' => $author->email,
            'body' => $body,
            'approved' => $autoApprove,
        ]);

        $this->mentions->processNewComment($reply, $body, $autoApprove);

        $message = $autoApprove
            ? 'Réponse publiée !'
            : 'Réponse envoyée — en attente de modération.';

        return $this->redirectAfterComment($post, $message, ! $fromAdmin);
    }

    public function update(Request $request, Post $post, Comment $comment, BlogSettings $settings)
    {
        $this->authorizeCommentOwner($post, $comment);

        $request->validate([
            'body' => 'required|max:1000',
        ]);

        $body = trim($request->body);

        $comment->update(['body' => $body]);
        $this->mentions->syncMentions($comment, $body);

        if ($comment->approved) {
            $comment->forceFill(['mentions_notified_at' => null])->save();
            $this->mentions->notifyMentionedUsers($comment->fresh());
        }

        return $this->redirectAfterComment($post, 'Commentaire modifié.');
    }

    public function destroyVisitor(Post $post, Comment $comment)
    {
        $this->authorizeCommentOwner($post, $comment);

        $comment->delete();

        return $this->redirectAfterComment($post, 'Commentaire supprimé.');
    }

    public function destroy(Comment $comment)
    {
        if (! Auth::guard('admin')->check()) {
            abort(403, 'Accès non autorisé.');
        }

        $comment->delete();

        return back()->with('success', 'Commentaire supprimé !');
    }

    protected function authorizeCommentOwner(Post $post, Comment $comment): void
    {
        if ($comment->post_id !== $post->id) {
            abort(404);
        }

        if ($comment->user_id !== Auth::guard('web')->id()) {
            abort(403, 'Vous ne pouvez modifier que vos propres commentaires.');
        }
    }

    protected function isDuplicate(int $userId, int $postId, string $body, ?int $parentId = null): bool
    {
        return Comment::query()
            ->where('user_id', $userId)
            ->where('post_id', $postId)
            ->where('body', $body)
            ->when($parentId !== null, fn ($q) => $q->where('parent_id', $parentId), fn ($q) => $q->whereNull('parent_id'))
            ->where('created_at', '>=', now()->subMinute())
            ->exists();
    }

    protected function redirectAfterComment(Post $post, string $message, bool $withFragment = true)
    {
        $redirect = redirect()
            ->route('posts.show', $post)
            ->with('success', $message);

        return $withFragment ? $redirect->withFragment('comments') : $redirect;
    }
}
