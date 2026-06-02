<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PostPolicy
{
    /**
     * Seul l'admin peut créer des posts
     */
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Seul l'admin peut modifier des posts
     */
    public function update(User $user, Post $post): bool
    {
        return $user->isAdmin();
    }

    /**
     * Seul l'admin peut supprimer des posts
     */
    public function delete(User $user, Post $post): bool
    {
        return $user->isAdmin();
    }
}
