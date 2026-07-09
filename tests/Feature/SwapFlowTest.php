<?php

namespace Tests\Feature;

use App\Exceptions\PaymentMethodMissingException;
use App\Models\Conversation;
use App\Models\EmployerApproval;
use App\Models\Post;
use App\Models\SwapRequest;
use App\Models\User;
use App\Services\SwapFlowService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class SwapFlowTest extends TestCase
{
    use RefreshDatabase;

    private SwapFlowService $service;

    protected function setUp(): void
    {
        parent::setUp();

        Mail::fake();
        $this->service = app(SwapFlowService::class);
    }

    private function makeConversation(Post $targetPost, User $requester): Conversation
    {
        return Conversation::create([
            'post_id' => $targetPost->id,
            'post_owner_id' => $targetPost->user_id,
            'initiator_id' => $requester->id,
            'last_message_at' => now(),
        ]);
    }

    public function test_requesting_a_swap_creates_a_pending_request(): void
    {
        $workerA = User::factory()->create();
        $workerB = User::factory()->create();
        $postA = Post::factory()->for($workerA)->create();
        $postB = Post::factory()->for($workerB)->create();
        $conversation = $this->makeConversation($postA, $workerB);

        $swapRequest = $this->service->requestSwap($postA, $workerB, $postB, $conversation);

        $this->assertSame(SwapRequest::STATUS_PENDING, $swapRequest->status);
        $this->assertDatabaseHas('swap_action_logs', ['swap_request_id' => $swapRequest->id, 'event' => 'requested']);
    }

    public function test_worker_can_decline_a_pending_request(): void
    {
        $workerA = User::factory()->create();
        $workerB = User::factory()->create();
        $postA = Post::factory()->for($workerA)->create();
        $postB = Post::factory()->for($workerB)->create();

        $swapRequest = $this->service->requestSwap($postA, $workerB, $postB);

        $this->service->workerDecline($swapRequest, $workerA);

        $this->assertSame(SwapRequest::STATUS_DECLINED_BY_WORKER, $swapRequest->fresh()->status);
    }

    public function test_worker_cannot_decline_someone_elses_request(): void
    {
        $workerA = User::factory()->create();
        $workerB = User::factory()->create();
        $stranger = User::factory()->create();
        $postA = Post::factory()->for($workerA)->create();
        $postB = Post::factory()->for($workerB)->create();

        $swapRequest = $this->service->requestSwap($postA, $workerB, $postB);

        $this->expectException(\Symfony\Component\HttpKernel\Exception\HttpException::class);
        $this->service->workerDecline($swapRequest, $stranger);
    }

    public function test_approving_without_a_payment_method_throws(): void
    {
        $workerA = User::factory()->create();
        $workerB = User::factory()->create();
        $postA = Post::factory()->for($workerA)->create();
        $postB = Post::factory()->for($workerB)->create();

        $swapRequest = $this->service->requestSwap($postA, $workerB, $postB);

        $this->expectException(PaymentMethodMissingException::class);
        $this->service->workerApprove($swapRequest, $workerA);
    }

    public function test_employer_decline_releases_swap_and_keeps_post_active(): void
    {
        $workerA = User::factory()->create();
        $workerB = User::factory()->create();
        $postA = Post::factory()->for($workerA)->create();
        $postB = Post::factory()->for($workerB)->create();

        $swapRequest = SwapRequest::create([
            'post_id' => $postA->id,
            'post_owner_id' => $workerA->id,
            'requester_post_id' => $postB->id,
            'requester_id' => $workerB->id,
            'status' => SwapRequest::STATUS_AWAITING_EMPLOYERS,
        ]);

        $approvalA = EmployerApproval::create([
            'swap_request_id' => $swapRequest->id,
            'post_id' => $postA->id,
            'worker_id' => $workerA->id,
            'role' => EmployerApproval::ROLE_EMPLOYER_A,
            'employer_email' => $postA->employer_email,
            'token' => 'token-a',
            'notified_at' => now(),
            'status' => EmployerApproval::STATUS_PENDING,
        ]);

        EmployerApproval::create([
            'swap_request_id' => $swapRequest->id,
            'post_id' => $postB->id,
            'worker_id' => $workerB->id,
            'role' => EmployerApproval::ROLE_EMPLOYER_B,
            'employer_email' => $postB->employer_email,
            'token' => 'token-b',
            'notified_at' => now(),
            'status' => EmployerApproval::STATUS_PENDING,
        ]);

        $this->service->employerRespond($approvalA, false, 'Not comfortable with this.');

        $this->assertSame(SwapRequest::STATUS_DECLINED_BY_EMPLOYER, $swapRequest->fresh()->status);
        $this->assertSame(Post::STATUS_ACTIVE, $postA->fresh()->status);
        $this->assertSame(Post::STATUS_ACTIVE, $postB->fresh()->status);
    }

    public function test_both_employers_approving_confirms_the_swap_and_marks_posts_swapped(): void
    {
        $workerA = User::factory()->create();
        $workerB = User::factory()->create();
        $postA = Post::factory()->for($workerA)->create();
        $postB = Post::factory()->for($workerB)->create();

        $swapRequest = SwapRequest::create([
            'post_id' => $postA->id,
            'post_owner_id' => $workerA->id,
            'requester_post_id' => $postB->id,
            'requester_id' => $workerB->id,
            'status' => SwapRequest::STATUS_AWAITING_EMPLOYERS,
        ]);

        $approvalA = EmployerApproval::create([
            'swap_request_id' => $swapRequest->id,
            'post_id' => $postA->id,
            'worker_id' => $workerA->id,
            'role' => EmployerApproval::ROLE_EMPLOYER_A,
            'employer_email' => $postA->employer_email,
            'token' => 'token-a2',
            'notified_at' => now(),
            'status' => EmployerApproval::STATUS_PENDING,
        ]);

        $approvalB = EmployerApproval::create([
            'swap_request_id' => $swapRequest->id,
            'post_id' => $postB->id,
            'worker_id' => $workerB->id,
            'role' => EmployerApproval::ROLE_EMPLOYER_B,
            'employer_email' => $postB->employer_email,
            'token' => 'token-b2',
            'notified_at' => now(),
            'status' => EmployerApproval::STATUS_PENDING,
        ]);

        $this->service->employerRespond($approvalA, true);
        $this->assertSame(SwapRequest::STATUS_AWAITING_EMPLOYERS, $swapRequest->fresh()->status);

        $this->service->employerRespond($approvalB, true);

        $swapRequest->refresh();
        $this->assertSame(SwapRequest::STATUS_CONFIRMED, $swapRequest->status);
        $this->assertSame(Post::STATUS_SWAPPED, $postA->fresh()->status);
        $this->assertSame(Post::STATUS_SWAPPED, $postB->fresh()->status);
    }

    public function test_employer_cannot_respond_twice(): void
    {
        $workerA = User::factory()->create();
        $postA = Post::factory()->for($workerA)->create();

        $swapRequest = SwapRequest::create([
            'post_id' => $postA->id,
            'post_owner_id' => $workerA->id,
            'requester_post_id' => $postA->id,
            'requester_id' => $workerA->id,
            'status' => SwapRequest::STATUS_AWAITING_EMPLOYERS,
        ]);

        $approval = EmployerApproval::create([
            'swap_request_id' => $swapRequest->id,
            'post_id' => $postA->id,
            'worker_id' => $workerA->id,
            'role' => EmployerApproval::ROLE_EMPLOYER_A,
            'employer_email' => $postA->employer_email,
            'token' => 'token-once',
            'status' => EmployerApproval::STATUS_APPROVED,
            'responded_at' => now(),
        ]);

        $this->expectException(\Symfony\Component\HttpKernel\Exception\HttpException::class);
        $this->service->employerRespond($approval, true);
    }
}
