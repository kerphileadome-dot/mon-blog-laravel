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
        $userId = auth()->id();

        $like = $post->likes()->where('user_id', $userId)->first();

        if ($like) {
            $like->delete();
            $message = 'Like retiré !';
        } else {
            $post->likes()->create([
                'user_id' => $userId,
                'ip_address' => request()->ip()
            ]);
            $message = 'Article liké !';
        }

        return back()->with('success', $message);
    }
}
