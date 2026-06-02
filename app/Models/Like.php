<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    protected $fillable = [
        'post_id',
        'ip_address',
    ];

    // Relation avec le post
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}