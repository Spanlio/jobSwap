<?php

namespace App\Console\Commands\Posts;

use App\Models\Post;
use App\Models\PostLog;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:expire-posts')]
#[Description('Mark active posts past their expiry date as expired')]
class ExpirePosts extends Command
{
    public function handle(): void
    {
        $posts = Post::where('status', Post::STATUS_ACTIVE)
            ->where('expires_at', '<=', now())
            ->get();

        foreach ($posts as $post) {
            $post->update(['status' => Post::STATUS_EXPIRED]);

            PostLog::create([
                'post_id' => $post->id,
                'user_id' => $post->user_id,
                'event' => PostLog::EVENT_EXPIRED,
            ]);
        }

        $this->info("Expired {$posts->count()} post(s).");
    }
}
