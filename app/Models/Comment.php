<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'post_id',
        'name',
        'email',
        'body',
        'approved',
    ];

    protected $casts = [
        'approved' => 'boolean',
    ];

    // Relation avec le post
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}