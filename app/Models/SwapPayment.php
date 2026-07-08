<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'swap_request_id', 'user_id', 'stripe_payment_intent_id', 'amount_cents', 'currency',
    'status', 'failure_reason', 'reserved_at', 'captured_at', 'released_at',
])]
class SwapPayment extends Model
{
    const STATUS_PENDING = 'pending';

    const STATUS_RESERVED = 'reserved';

    const STATUS_CAPTURED = 'captured';

    const STATUS_RELEASED = 'released';

    const STATUS_FAILED = 'failed';

    protected function casts(): array
    {
        return [
            'reserved_at' => 'datetime',
            'captured_at' => 'datetime',
            'released_at' => 'datetime',
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
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
