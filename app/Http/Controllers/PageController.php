<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Services\BlogSettings;

class PageController extends Controller
{
    public function about(BlogSettings $settings)
    {
        $stats = [
            'posts' => Post::where('published', true)->count(),
            'views' => Post::where('published', true)->sum('views'),
            'readers' => User::where('role', 'visitor')->count(),
            'categories' => Post::where('published', true)->whereNotNull('category')->where('category', '!=', '')->distinct()->count('category'),
        ];

        $settings = $settings->all();

        return view('pages.about', compact('stats', 'settings'));
    }
}
