<?php

namespace App\Http\Controllers\Admin;

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
        ];

        $recentPosts = Post::with(['comments', 'likes'])
                           ->latest()
                           ->take(5)
                           ->get();

        $pendingComments = Comment::where('approved', false)
                                  ->with('post')
                                  ->latest()
                                  ->take(10)
                                  ->get();

        return view('admin.dashboard', compact('stats', 'recentPosts', 'pendingComments'));
    }

    public function posts()
    {
        $posts = Post::with(['comments', 'likes'])
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
}
