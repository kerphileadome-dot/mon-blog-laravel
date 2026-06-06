<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Services\BlogSettings;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Post::where('published', true)
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->selectRaw('category, COUNT(*) as count')
            ->groupBy('category')
            ->orderByDesc('count')
            ->get();

        return view('categories.index', compact('categories'));
    }

    public function show(string $category, BlogSettings $settings)
    {
        $categoryName = urldecode($category);

        $posts = Post::where('published', true)
            ->where('category', $categoryName)
            ->forList()
            ->latest()
            ->paginate($settings->postsPerPage());

        $categories = Post::where('published', true)
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->pluck('category');

        return view('categories.show', compact('posts', 'categoryName', 'categories'));
    }
}
