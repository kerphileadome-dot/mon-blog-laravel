<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\ResetPasswordNotification;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string|null $remember_token
 * @property string $role
 * @property bool $blocked
 * @property Carbon|null $email_verified_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @method bool isAdmin()
 */
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'blocked',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'blocked' => 'boolean',
        ];
    }

    /**
     * Relation avec les posts
     */
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Relation avec les commentaires
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Relation avec les likes
     */
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    /**
     * Relation avec les favoris
     */
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    /**
     * Articles favoris
     */
    public function favoritePosts()
    {
        return $this->belongsToMany(Post::class, 'favorites')->withTimestamps();
    }

    /**
     * Vérifier si un article est en favoris
     */
    public function hasFavorited(Post $post): bool
    {
        return $this->favoritePosts()->where('post_id', $post->id)->exists();
    }

    /**
     * Vérifier si l'utilisateur est admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPasswordNotification($token));
    }
}
