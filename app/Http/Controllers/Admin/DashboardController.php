<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\CommentController;
use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Favorite;
use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use App\Services\BlogSettings;
use App\Services\CommentMentionService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_posts' => Post::count(),
            'published_posts' => Post::where('published', true)->count(),
            'draft_posts' => Post::where('published', false)->count(),
            'total_comments' => Comment::count(),
            'pending_comments' => Comment::where('approved', false)->count(),
            'total_views' => Post::sum('views'),
            'total_users' => User::where('role', 'visitor')->count(),
            'total_likes' => Like::count(),
            'total_favorites' => Favorite::count(),
        ];

        $recentPosts = Post::withCount(['comments', 'likes'])
            ->latest()
            ->take(5)
            ->get();

        $popularPosts = Post::where('published', true)
            ->orderBy('views', 'desc')
            ->take(5)
            ->get();

        $recentUsers = User::where('role', 'visitor')
            ->latest()
            ->take(5)
            ->get();

        $pendingComments = Comment::where('approved', false)
            ->with('post')
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentPosts', 'popularPosts', 'recentUsers', 'pendingComments'));
    }

    public function posts()
    {
        $posts = Post::withCount(['comments', 'likes'])
            ->latest()
            ->paginate(10);

        return view('admin.posts', compact('posts'));
    }

    public function comments()
    {
        $comments = Comment::with(['post', 'parent'])
            ->latest()
            ->paginate(20);

        $commentStats = [
            'total' => Comment::count(),
            'approved' => Comment::where('approved', true)->count(),
            'pending' => Comment::where('approved', false)->count(),
            'replies' => Comment::whereNotNull('parent_id')->count(),
        ];

        return view('admin.comments', compact('comments', 'commentStats'));
    }

    public function approveComment(Comment $comment)
    {
        $comment->update(['approved' => true]);

        app(CommentMentionService::class)->notifyMentionedUsers($comment->fresh());

        return back()->with('success', 'Commentaire approuvé !');
    }

    public function rejectComment(Comment $comment)
    {
        $comment->update(['approved' => false]);

        return back()->with('success', 'Commentaire rejeté !');
    }

    // Répondre à un commentaire (délègue au contrôleur public)
    public function replyToComment(Request $request, Comment $comment)
    {
        return app(CommentController::class)->reply(
            $request,
            $comment->post,
            $comment,
            app(BlogSettings::class)
        );
    }
}
