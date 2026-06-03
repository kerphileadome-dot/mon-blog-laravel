<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Post extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'cover_image',
        'category',
        'views',
        'published',
    ];

    protected $casts = [
        'published' => 'boolean',
        'views' => 'integer',
    ];

    // Relation avec l'auteur
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relation avec les commentaires
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // Relation avec les likes
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    // Relation avec les favoris
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    // Relation avec les utilisateurs qui ont mis en favori
    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
    }

    // Vérifier si un utilisateur a mis cet article en favori
    public function isFavoritedBy($user)
    {
        return $this->favoritedBy()->where('user_id', $user->id)->exists();
    }

    // Vérifier si un utilisateur a liké cet article
    public function isLikedBy($user)
    {
        if (!$user) return false;
        return $this->likes()->where('user_id', $user->id)->exists();
    }

    // Générer le slug automatiquement
    public static function boot()
    {
        parent::boot();
        static::creating(function ($post) {
            $post->slug = Str::slug($post->title);
        });
    }
}
