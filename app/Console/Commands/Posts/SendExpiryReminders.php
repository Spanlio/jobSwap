<?php

namespace App\Console\Commands\Posts;

use App\Mail\PostExpiringReminder;
use App\Models\Post;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

#[Signature('app:send-expiry-reminders')]
#[Description('Email workers whose posts expire in N days (see config jobswap.expiry_reminder_days_before)')]
class SendExpiryReminders extends Command
{
    public function handle(): void
    {
        $days = config('jobswap.expiry_reminder_days_before');

        $posts = Post::where('status', Post::STATUS_ACTIVE)
            ->whereNull('expiry_reminder_sent_at')
            ->whereBetween('expires_at', [now()->addDays($days)->startOfDay(), now()->addDays($days)->endOfDay()])
            ->with('user')
            ->get();

        foreach ($posts as $post) {
            Mail::to($post->user->email)->send(new PostExpiringReminder($post));
            $post->update(['expiry_reminder_sent_at' => now()]);
        }

        $this->info("Sent {$posts->count()} expiry reminder(s).");
    }
}
