<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['post_id', 'user_id', 'actor_id', 'event', 'meta'])]
class PostLog extends Model
{
    const EVENT_CREATED = 'created';

    const EVENT_EDITED = 'edited';

    const EVENT_REMOVED_BY_OWNER = 'removed_by_owner';

    const EVENT_REMOVED_BY_ADMIN = 'removed_by_admin';

    const EVENT_EXPIRED = 'expired';

    const EVENT_SWAPPED = 'swapped';

    protected function casts(): array
    {
        return [
            'meta' => 'array',
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
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function actor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'actor_id');
    }
}
