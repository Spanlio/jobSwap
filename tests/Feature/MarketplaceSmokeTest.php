<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MarketplaceSmokeTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_can_browse_the_feed(): void
    {
        $post = Post::factory()->for(User::factory())->create();

        $this->get('/')->assertOk()->assertSee($post->current_job_title);
    }

    public function test_guests_can_view_a_post_without_seeing_employer_details(): void
    {
        $post = Post::factory()->for(User::factory())->create(['employer_email' => 'secret@employer.test']);

        $this->get(route('posts.show', $post))
            ->assertOk()
            ->assertDontSee('secret@employer.test');
    }

    public function test_worker_can_create_and_list_their_own_post(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('posts.create'))
            ->assertOk();

        $post = Post::factory()->for($user)->create();

        $this->actingAs($user)
            ->get(route('posts.mine'))
            ->assertOk()
            ->assertSee($post->current_job_title);
    }

    public function test_non_admin_cannot_access_admin_panel(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get(route('admin.dashboard'))->assertForbidden();
    }

    public function test_admin_can_access_admin_panel(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->get(route('admin.dashboard'))->assertOk();
    }

    public function test_banned_user_is_logged_out_and_forbidden(): void
    {
        $user = User::factory()->create(['is_banned' => true]);

        $this->actingAs($user)->get(route('dashboard'))->assertForbidden();
    }

    public function test_unknown_employer_token_returns_404(): void
    {
        $this->get(route('employer.respond', 'does-not-exist'))->assertNotFound();
    }
}
