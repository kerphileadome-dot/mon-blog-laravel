<?php

namespace App\Models;

use App\Services\CommentMentionService;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'post_id',
        'parent_id',
        'user_id',
        'name',
        'email',
        'body',
        'approved',
        'mentions_notified_at',
    ];

    protected $casts = [
        'approved' => 'boolean',
        'mentions_notified_at' => 'datetime',
    ];

    // Relation avec le post
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    // Relation avec l'utilisateur (si connecté)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Commentaire parent
    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    // Réponses au commentaire
    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id')->where('approved', true);
    }

    // Vérifier si c'est une réponse
    public function isReply()
    {
        return ! is_null($this->parent_id);
    }

    public function mentionedUsers()
    {
        return $this->belongsToMany(User::class, 'comment_user_mentions')
            ->withPivot('handle')
            ->withTimestamps();
    }

    public function formattedBody(): string
    {
        return app(CommentMentionService::class)->formatBody($this->body);
    }
}
