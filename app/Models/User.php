<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Cashier\Billable;

#[Fillable(['name', 'email', 'password', 'is_banned', 'banned_at', 'locale'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use Billable, HasFactory, Notifiable;

    const ROLE_WORKER = 'worker';

    const ROLE_ADMIN = 'admin';

    protected static function booted(): void
    {
        static::creating(function (User $user) {
            if (empty($user->handle)) {
                $user->handle = 'Worker #'.Str::upper(Str::random(6));
            }
        });
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_banned' => 'boolean',
            'banned_at' => 'datetime',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isBanned(): bool
    {
        return (bool) $this->is_banned;
    }

    /**
     * @return HasMany<Post, $this>
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    /**
     * @return HasMany<SwapRequest, $this>
     */
    public function swapRequestsReceived(): HasMany
    {
        return $this->hasMany(SwapRequest::class, 'post_owner_id');
    }

    /**
     * @return HasMany<SwapRequest, $this>
     */
    public function swapRequestsMade(): HasMany
    {
        return $this->hasMany(SwapRequest::class, 'requester_id');
    }

    /**
     * @return HasMany<Message, $this>
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'sender_id');
    }
}
