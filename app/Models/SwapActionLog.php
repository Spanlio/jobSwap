<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['swap_request_id', 'actor_id', 'event', 'from_status', 'to_status', 'meta'])]
class SwapActionLog extends Model
{
    protected function casts(): array
    {
        return [
            'meta' => 'array',
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
    public function actor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'actor_id');
    }
}
