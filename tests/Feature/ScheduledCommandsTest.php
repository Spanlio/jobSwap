<?php

namespace Tests\Feature;

use App\Mail\PostExpiringReminder;
use App\Models\Post;
use App\Models\PostLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ScheduledCommandsTest extends TestCase
{
    use RefreshDatabase;

    public function test_expire_posts_marks_only_overdue_active_posts(): void
    {
        $overdue = Post::factory()->create(['expires_at' => now()->subDay()]);
        $current = Post::factory()->create(['expires_at' => now()->addDays(10)]);
        $alreadyRemoved = Post::factory()->create([
            'status' => Post::STATUS_REMOVED,
            'expires_at' => now()->subDay(),
        ]);

        $this->artisan('app:expire-posts')->assertSuccessful();

        $this->assertSame(Post::STATUS_EXPIRED, $overdue->fresh()->status);
        $this->assertSame(Post::STATUS_ACTIVE, $current->fresh()->status);
        $this->assertSame(Post::STATUS_REMOVED, $alreadyRemoved->fresh()->status);

        $this->assertDatabaseHas('post_logs', [
            'post_id' => $overdue->id,
            'event' => PostLog::EVENT_EXPIRED,
        ]);
    }

    public function test_expiry_reminder_goes_out_once_at_the_configured_offset(): void
    {
        Mail::fake();

        $days = config('jobswap.expiry_reminder_days_before');

        $dueForReminder = Post::factory()->create(['expires_at' => now()->addDays($days)->midDay()]);
        $notYetDue = Post::factory()->create(['expires_at' => now()->addDays($days + 5)]);

        $this->artisan('app:send-expiry-reminders')->assertSuccessful();

        Mail::assertQueued(PostExpiringReminder::class, 1);
        Mail::assertQueued(
            PostExpiringReminder::class,
            fn (PostExpiringReminder $mail) => $mail->hasTo($dueForReminder->user->email)
        );
        $this->assertNotNull($dueForReminder->fresh()->expiry_reminder_sent_at);
        $this->assertNull($notYetDue->fresh()->expiry_reminder_sent_at);

        // Running again must not re-send.
        $this->artisan('app:send-expiry-reminders')->assertSuccessful();
        Mail::assertQueued(PostExpiringReminder::class, 1);
    }
}
