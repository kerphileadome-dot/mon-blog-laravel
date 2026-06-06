<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Services\BlogSettings;

class TagController extends Controller
{
    public function index()
    {
        $posts = Post::where('published', true)
            ->whereNotNull('tags')
            ->where('tags', '!=', '')
            ->get();

        $tagCounts = [];
        foreach ($posts as $post) {
            foreach ($post->tags_list as $tag) {
                $tagCounts[$tag] = ($tagCounts[$tag] ?? 0) + 1;
            }
        }
        arsort($tagCounts);

        return view('tags.index', compact('tagCounts'));
    }

    public function show(string $tag, BlogSettings $settings)
    {
        $tagName = urldecode($tag);

        $posts = Post::where('published', true)
            ->where(function ($q) use ($tagName) {
                $q->where('tags', $tagName)
                  ->orWhere('tags', 'like', $tagName . ',%')
                  ->orWhere('tags', 'like', '%,' . $tagName . ',%')
                  ->orWhere('tags', 'like', '%,' . $tagName);
            })
            ->forList()
            ->latest()
            ->paginate($settings->postsPerPage());

        return view('tags.show', compact('posts', 'tagName'));
    }
}
