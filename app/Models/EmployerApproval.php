<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'swap_request_id', 'post_id', 'worker_id', 'role', 'employer_email', 'token',
    'question', 'answer', 'status', 'notified_at', 'responded_at', 'responded_ip',
])]
class EmployerApproval extends Model
{
    const ROLE_EMPLOYER_A = 'employer_a';

    const ROLE_EMPLOYER_B = 'employer_b';

    const STATUS_PENDING = 'pending';

    const STATUS_APPROVED = 'approved';

    const STATUS_DECLINED = 'declined';

    protected function casts(): array
    {
        return [
            'notified_at' => 'datetime',
            'responded_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<SwapRequest, $this>
     */
    public function swapRequest(): BelongsTo
    {
        return $this->belongsTo(SwapRequest::class);
    }

    /**
     * @return BelongsTo<Post, $this>
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function worker(): BelongsTo
    {
        return $this->belongsTo(User::class, 'worker_id');
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }
}
