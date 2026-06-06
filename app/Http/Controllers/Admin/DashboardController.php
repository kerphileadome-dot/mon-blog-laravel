<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\CommentController;
use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Comment;
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
            'total_users' => \App\Models\User::where('role', 'visitor')->count(),
            'total_likes' => \App\Models\Like::count(),
            'total_favorites' => \App\Models\Favorite::count(),
        ];

        $recentPosts = Post::withCount(['comments', 'likes'])
                           ->latest()
                           ->take(5)
                           ->get();

        $popularPosts = Post::where('published', true)
                           ->orderBy('views', 'desc')
                           ->take(5)
                           ->get();

        $recentUsers = \App\Models\User::where('role', 'visitor')
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
        $comments = Comment::with('post')
                           ->latest()
                           ->paginate(20);

        return view('admin.comments', compact('comments'));
    }

    public function approveComment(Comment $comment)
    {
        $comment->update(['approved' => true]);
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
            app(\App\Services\BlogSettings::class)
        );
    }
}
