<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    // Ajouter ou retirer un article des favoris
    public function toggle(Post $post)
    {
        $user = auth()->user();

        if ($user->hasFavorited($post)) {
            $user->favoritePosts()->detach($post->id);
            return back()->with('success', 'Article retiré des favoris');
        } else {
            $user->favoritePosts()->attach($post->id);
            return back()->with('success', 'Article ajouté aux favoris');
        }
    }

    // Page des favoris
    public function index()
    {
        $posts = auth()->user()->favoritePosts()
                    ->where('published', true)
                    ->with(['user', 'comments', 'likes'])
                    ->latest('favorites.created_at')
                    ->paginate(6);

        return view('favorites.index', compact('posts'));
    }
}
