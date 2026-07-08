<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'post_id',
    'post_owner_id',
    'requester_post_id',
    'requester_id',
    'conversation_id',
    'status',
    'worker_responded_at',
    'confirmed_at',
    'cancel_reason',
])]
class SwapRequest extends Model
{
    const STATUS_PENDING = 'pending';

    const STATUS_DECLINED_BY_WORKER = 'declined_by_worker';

    const STATUS_AWAITING_EMPLOYERS = 'awaiting_employers';

    const STATUS_DECLINED_BY_EMPLOYER = 'declined_by_employer';

    const STATUS_CONFIRMED = 'confirmed';

    const STATUS_PAYMENT_FAILED = 'payment_failed';

    const STATUS_CANCELLED = 'cancelled';

    protected function casts(): array
    {
        return [
            'worker_responded_at' => 'datetime',
            'confirmed_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<Post, $this>
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * @return BelongsTo<Post, $this>
     */
    public function requesterPost(): BelongsTo
    {
        return $this->belongsTo(Post::class, 'requester_post_id');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function postOwner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'post_owner_id');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    /**
     * @return BelongsTo<Conversation, $this>
     */
    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    /**
     * @return HasMany<EmployerApproval, $this>
     */
    public function employerApprovals(): HasMany
    {
        return $this->hasMany(EmployerApproval::class);
    }

    /**
     * @return HasMany<SwapPayment, $this>
     */
    public function payments(): HasMany
    {
        return $this->hasMany(SwapPayment::class);
    }

    /**
     * @return HasMany<SwapActionLog, $this>
     */
    public function actionLogs(): HasMany
    {
        return $this->hasMany(SwapActionLog::class);
    }

    public function isOpen(): bool
    {
        return in_array($this->status, [
            self::STATUS_PENDING,
            self::STATUS_AWAITING_EMPLOYERS,
        ]);
    }
}
