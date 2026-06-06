<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Services\BlogSettings;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request, BlogSettings $settings)
    {
        $query = trim($request->get('q', ''));

        $posts = collect();
        if ($query !== '') {
            $posts = Post::where('published', true)
                ->where(function ($q) use ($query) {
                    $q->where('title', 'like', "%{$query}%")
                      ->orWhere('excerpt', 'like', "%{$query}%")
                      ->orWhere('content', 'like', "%{$query}%")
                      ->orWhere('category', 'like', "%{$query}%")
                      ->orWhere('tags', 'like', "%{$query}%");
                })
                ->with(['user', 'comments', 'likes'])
                ->latest()
                ->paginate($settings->postsPerPage())
                ->withQueryString();
        }

        return view('search.index', compact('query', 'posts'));
    }
}
