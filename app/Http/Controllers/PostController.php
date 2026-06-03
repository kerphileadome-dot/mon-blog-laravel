<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::where('published', true)
                     ->with(['user', 'comments', 'likes'])
                     ->latest()
                     ->paginate(6);
        return view('posts.index', compact('posts'));
    }

    public function show(Post $post)
    {
        // Seuls les utilisateurs connectés peuvent lire les articles
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Vous devez créer un compte ou vous connecter pour lire les articles.');
        }

        $post->increment('views');
        $comments = $post->comments()->where('approved', true)->whereNull('parent_id')->with('replies')->latest()->get();
        $likesCount = $post->likes()->count();
        $isLiked = $post->isLikedBy(auth()->user());
        $isFavorited = $post->isFavoritedBy(auth()->user());

        return view('posts.show', compact('post', 'comments', 'likesCount', 'isLiked', 'isFavorited'));
    }

    public function create()
    {
        // Pas d'autorisation ici, déjà géré par le middleware admin
        return view('posts.create');
    }

    public function store(Request $request)
    {
        // Pas d'autorisation ici, déjà géré par le middleware admin

        $request->validate([
            'title'       => 'required|max:255',
            'content'     => 'required',
            'excerpt'     => 'nullable|max:500',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
        ]);

        $coverImagePath = null;
        if ($request->hasFile('cover_image')) {
            $coverImagePath = $request->file('cover_image')->store('covers', 'public');
        }

        Post::create([
            'user_id'     => auth()->id(),
            'title'       => $request->title,
            'slug'        => Str::slug($request->title) . '-' . time(),
            'excerpt'     => $request->excerpt,
            'content'     => $request->content,
            'category'    => $request->category,
            'cover_image' => $coverImagePath,
            'published'   => $request->has('published'),
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Article publié avec succès !');
    }

    public function edit(Post $post)
    {
        // Pas d'autorisation ici, déjà géré par le middleware admin
        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        // Pas d'autorisation ici, déjà géré par le middleware admin

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
            $coverImagePath = $request->file('cover_image')->store('covers', 'public');
        }

        $post->update([
            'title'       => $request->title,
            'slug'        => Str::slug($request->title) . '-' . time(),
            'excerpt'     => $request->excerpt,
            'content'     => $request->content,
            'category'    => $request->category,
            'cover_image' => $coverImagePath,
            'published'   => $request->has('published'),
        ]);

        return redirect()->route('posts.show', $post)->with('success', 'Article mis à jour !');
    }

    public function destroy(Post $post)
    {
        // Pas d'autorisation ici, déjà géré par le middleware admin

        if ($post->cover_image) {
            Storage::disk('public')->delete($post->cover_image);
        }
        $post->delete();
        return redirect()->route('admin.dashboard')->with('success', 'Article supprimé !');
    }
}
