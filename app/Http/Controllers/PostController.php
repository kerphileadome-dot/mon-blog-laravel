<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Services\BlogSettings;
use App\Services\CoverImageProcessor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function __construct(private CoverImageProcessor $coverImages) {}

    public function index(BlogSettings $settings)
    {
        $perPage = $settings->postsPerPage();

        $featured = Post::where('published', true)
            ->forList()
            ->latest()
            ->first();

        $posts = Post::where('published', true)
            ->forList()
            ->when($featured, fn ($q) => $q->where('id', '!=', $featured->id))
            ->latest()
            ->paginate($perPage);

        $categories = Post::where('published', true)
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->selectRaw('category, COUNT(*) as count')
            ->groupBy('category')
            ->orderByDesc('count')
            ->get();

        $stats = Post::where('published', true)
            ->selectRaw('COUNT(*) as total_posts, COALESCE(SUM(views), 0) as total_views')
            ->first();

        $totalPosts = (int) $stats->total_posts;
        $totalViews = (int) $stats->total_views;

        return view('posts.index', compact('posts', 'featured', 'categories', 'totalPosts', 'totalViews'));
    }

    public function show(Post $post)
    {
        if (!$post->published && !\Illuminate\Support\Facades\Auth::guard('admin')->check()) {
            abort(404);
        }

        $post->load('user')->loadCount('likes');
        $post->increment('views');

        $allComments = $post->comments()
            ->where('approved', true)
            ->orderBy('created_at')
            ->get();

        $comments = $allComments->whereNull('parent_id')->values();

        $likesCount = $post->likes_count;
        $isLiked = false;
        $isFavorited = false;

        if (\Illuminate\Support\Facades\Auth::guard('web')->check()) {
            $userId = \Illuminate\Support\Facades\Auth::guard('web')->id();
            $isLiked = $post->likes()->where('user_id', $userId)->exists();
            $isFavorited = DB::table('favorites')
                ->where('user_id', $userId)
                ->where('post_id', $post->id)
                ->exists();
        }

        $relatedPosts = Post::where('published', true)
            ->where('id', '!=', $post->id)
            ->when($post->category, fn ($q) => $q->where('category', $post->category))
            ->forList()
            ->latest()
            ->take(2)
            ->get();

        if ($relatedPosts->count() < 2) {
            $relatedPosts = Post::where('published', true)
                ->where('id', '!=', $post->id)
                ->forList()
                ->latest()
                ->take(2)
                ->get();
        }

        return view('posts.show', compact(
            'post', 'comments', 'allComments', 'likesCount', 'isLiked', 'isFavorited', 'relatedPosts'
        ));
    }

    public function create()
    {
        $this->authorize('create', Post::class);

        return view('posts.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Post::class);
        $request->validate([
            'title'       => 'required|max:255',
            'content'     => 'required',
            'excerpt'     => 'nullable|max:500',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
        ]);

        $coverImagePath = null;
        if ($request->hasFile('cover_image')) {
            $coverImagePath = $this->coverImages->store($request->file('cover_image'));
        }

        Post::create([
            'user_id'     => auth()->id(),
            'title'       => $request->title,
            'excerpt'     => $request->excerpt,
            'content'     => $request->content,
            'category'    => $request->category,
            'tags'        => $request->tags,
            'cover_image' => $coverImagePath,
            'published'   => $request->has('published'),
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Article publié avec succès !');
    }

    public function edit(Post $post)
    {
        $this->authorize('update', $post);

        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        $this->authorize('update', $post);
        $request->validate([
            'title'       => 'required|max:255',
            'content'     => 'required',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
        ]);

        $coverImagePath = $post->cover_image;
        if ($request->hasFile('cover_image')) {
            if ($post->cover_image) {
                Storage::disk('public')->delete($post->cover_image);
            }
            $coverImagePath = $this->coverImages->store($request->file('cover_image'));
        }

        $post->update([
            'title'       => $request->title,
            'excerpt'     => $request->excerpt,
            'content'     => $request->content,
            'category'    => $request->category,
            'tags'        => $request->tags,
            'cover_image' => $coverImagePath,
            'published'   => $request->has('published'),
        ]);

        return redirect()->route('posts.show', $post)->with('success', 'Article mis à jour !');
    }

    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);

        if ($post->cover_image) {
            Storage::disk('public')->delete($post->cover_image);
        }
        $post->delete();
        return redirect()->route('admin.dashboard')->with('success', 'Article supprimé !');
    }
}
