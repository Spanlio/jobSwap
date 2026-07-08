<?php

namespace App\Models;

use Database\Factories\PostFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'user_id',
    'current_job_title',
    'desired_job_title',
    'licenses',
    'years_experience',
    'region',
    'availability',
    'employer_email',
    'employer_name',
    'status',
    'expires_at',
    'expiry_reminder_sent_at',
])]
#[Hidden(['employer_email', 'employer_name'])]
class Post extends Model
{
    /** @use HasFactory<PostFactory> */
    use HasFactory, SoftDeletes;

    const STATUS_ACTIVE = 'active';

    const STATUS_SWAPPED = 'swapped';

    const STATUS_REMOVED = 'removed';

    const STATUS_EXPIRED = 'expired';

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'expiry_reminder_sent_at' => 'datetime',
            'years_experience' => 'integer',
        ];
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasMany<Conversation, $this>
     */
    public function conversations(): HasMany
    {
        return $this->hasMany(Conversation::class);
    }

    /**
     * @return HasMany<SwapRequest, $this>
     */
    public function swapRequestsReceived(): HasMany
    {
        return $this->hasMany(SwapRequest::class, 'post_id');
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    /**
     * @param  Builder<Post>  $query
     * @return Builder<Post>
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_ACTIVE)
            ->where('expires_at', '>', now());
    }

    public function regionLabel(): string
    {
        $locale = app()->getLocale();

        return config("jobswap.regions.{$this->region}.{$locale}", $this->region);
    }

    public function availabilityLabel(): string
    {
        $locale = app()->getLocale();

        return config("jobswap.availability.{$this->availability}.{$locale}", $this->availability);
    }
}
