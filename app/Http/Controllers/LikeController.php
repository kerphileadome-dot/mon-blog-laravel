<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    // Ajouter ou retirer un like
    public function toggle(Post $post)
    {
        $ip = request()->ip();

        $like = $post->likes()->where('ip_address', $ip)->first();

        if ($like) {
            $like->delete();
            $message = 'Like retiré !';
        } else {
            $post->likes()->create(['ip_address' => $ip]);
            $message = 'Article liké !';
        }

        return back()->with('success', $message);
    }
}