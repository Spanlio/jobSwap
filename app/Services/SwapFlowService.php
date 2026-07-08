<?php

namespace App\Services;

use App\Exceptions\PaymentMethodMissingException;
use App\Exceptions\PaymentReservationFailedException;
use App\Mail\EmployerApprovalRequested;
use App\Mail\PaymentFailed;
use App\Mail\SwapConfirmed;
use App\Mail\SwapDeclinedByEmployer;
use App\Mail\SwapRequestReceived;
use App\Models\Conversation;
use App\Models\EmployerApproval;
use App\Models\Post;
use App\Models\SwapActionLog;
use App\Models\SwapPayment;
use App\Models\SwapRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Laravel\Cashier\Cashier;
use Laravel\Cashier\Exceptions\IncompletePayment;

class SwapFlowService
{
    public function requestSwap(Post $targetPost, User $requester, Post $requesterPost, ?Conversation $conversation = null): SwapRequest
    {
        $swapRequest = SwapRequest::create([
            'post_id' => $targetPost->id,
            'post_owner_id' => $targetPost->user_id,
            'requester_post_id' => $requesterPost->id,
            'requester_id' => $requester->id,
            'conversation_id' => $conversation?->id,
            'status' => SwapRequest::STATUS_PENDING,
        ]);

        $this->log($swapRequest, $requester, 'requested', null, SwapRequest::STATUS_PENDING);

        Mail::to($targetPost->user->email)->send(new SwapRequestReceived($swapRequest));

        return $swapRequest;
    }

    public function workerDecline(SwapRequest $swapRequest, User $actor): SwapRequest
    {
        abort_unless($swapRequest->post_owner_id === $actor->id, 403);
        abort_unless($swapRequest->status === SwapRequest::STATUS_PENDING, 422, 'This request has already been handled.');

        $from = $swapRequest->status;
        $swapRequest->update([
            'status' => SwapRequest::STATUS_DECLINED_BY_WORKER,
            'worker_responded_at' => now(),
        ]);

        $this->log($swapRequest, $actor, 'declined_by_worker', $from, $swapRequest->status);

        return $swapRequest;
    }

    /**
     * @throws PaymentMethodMissingException
     */
    public function workerApprove(SwapRequest $swapRequest, User $actor): SwapRequest
    {
        abort_unless($swapRequest->post_owner_id === $actor->id, 403);
        abort_unless($swapRequest->status === SwapRequest::STATUS_PENDING, 422, 'This request has already been handled.');

        $owner = $swapRequest->postOwner;
        $requester = $swapRequest->requester;

        foreach ([$owner, $requester] as $worker) {
            if (! $worker->hasDefaultPaymentMethod()) {
                throw new PaymentMethodMissingException($worker);
            }
        }

        return DB::transaction(function () use ($swapRequest, $actor, $owner, $requester) {
            $from = $swapRequest->status;

            $swapRequest->update([
                'status' => SwapRequest::STATUS_AWAITING_EMPLOYERS,
                'worker_responded_at' => now(),
            ]);

            $this->log($swapRequest, $actor, 'approved_by_worker', $from, $swapRequest->status);

            $employerA = EmployerApproval::create([
                'swap_request_id' => $swapRequest->id,
                'post_id' => $swapRequest->post_id,
                'worker_id' => $owner->id,
                'role' => EmployerApproval::ROLE_EMPLOYER_A,
                'employer_email' => $swapRequest->post->employer_email,
                'token' => Str::random(48),
                'status' => EmployerApproval::STATUS_PENDING,
            ]);

            $employerB = EmployerApproval::create([
                'swap_request_id' => $swapRequest->id,
                'post_id' => $swapRequest->requester_post_id,
                'worker_id' => $requester->id,
                'role' => EmployerApproval::ROLE_EMPLOYER_B,
                'employer_email' => $swapRequest->requesterPost->employer_email,
                'token' => Str::random(48),
                'status' => EmployerApproval::STATUS_PENDING,
            ]);

            try {
                $this->reservePayment($swapRequest, $owner);
                $this->reservePayment($swapRequest, $requester);
            } catch (PaymentReservationFailedException $e) {
                $this->releaseAllPayments($swapRequest);
                $swapRequest->update(['status' => SwapRequest::STATUS_PAYMENT_FAILED]);
                $this->log($swapRequest, null, 'payment_failed', SwapRequest::STATUS_AWAITING_EMPLOYERS, SwapRequest::STATUS_PAYMENT_FAILED, ['reason' => $e->getMessage()]);

                Mail::to($owner->email)->send(new PaymentFailed($swapRequest));
                Mail::to($requester->email)->send(new PaymentFailed($swapRequest));

                return $swapRequest->fresh();
            }

            foreach ([$employerA, $employerB] as $approval) {
                $approval->update(['notified_at' => now()]);
                Mail::to($approval->employer_email)->send(new EmployerApprovalRequested($approval));
            }

            $this->log($swapRequest, null, 'employers_notified', $swapRequest->status, $swapRequest->status);

            return $swapRequest->fresh();
        });
    }

    public function employerRespond(EmployerApproval $approval, bool $approved, ?string $question = null): EmployerApproval
    {
        abort_unless($approval->isPending(), 422, 'This request has already been answered.');

        $swapRequest = $approval->swapRequest;

        return DB::transaction(function () use ($approval, $approved, $question, $swapRequest) {
            $approval->update([
                'status' => $approved ? EmployerApproval::STATUS_APPROVED : EmployerApproval::STATUS_DECLINED,
                'question' => $question,
                'responded_at' => now(),
                'responded_ip' => request()->ip(),
            ]);

            $this->log($swapRequest, null, $approved ? 'employer_approved' : 'employer_declined', $swapRequest->status, $swapRequest->status, ['role' => $approval->role]);

            if (! $approved) {
                $this->declineByEmployer($swapRequest);

                return $approval->fresh();
            }

            $allApproved = $swapRequest->employerApprovals()
                ->where('status', '!=', EmployerApproval::STATUS_APPROVED)
                ->doesntExist();

            if ($allApproved) {
                $this->confirmSwap($swapRequest);
            }

            return $approval->fresh();
        });
    }

    protected function declineByEmployer(SwapRequest $swapRequest): void
    {
        $this->releaseAllPayments($swapRequest);

        $from = $swapRequest->status;
        $swapRequest->update(['status' => SwapRequest::STATUS_DECLINED_BY_EMPLOYER]);
        $this->log($swapRequest, null, 'declined_by_employer', $from, $swapRequest->status);

        Mail::to($swapRequest->postOwner->email)->send(new SwapDeclinedByEmployer($swapRequest));
        Mail::to($swapRequest->requester->email)->send(new SwapDeclinedByEmployer($swapRequest));
    }

    protected function confirmSwap(SwapRequest $swapRequest): void
    {
        try {
            foreach ($swapRequest->payments as $payment) {
                $this->capturePayment($payment);
            }
        } catch (\Throwable $e) {
            Log::error('Swap payment capture failed', ['swap_request_id' => $swapRequest->id, 'error' => $e->getMessage()]);
            $this->releaseAllPayments($swapRequest);
            $swapRequest->update(['status' => SwapRequest::STATUS_PAYMENT_FAILED]);
            $this->log($swapRequest, null, 'payment_failed', SwapRequest::STATUS_AWAITING_EMPLOYERS, SwapRequest::STATUS_PAYMENT_FAILED, ['reason' => $e->getMessage()]);

            return;
        }

        $from = $swapRequest->status;
        $swapRequest->update(['status' => SwapRequest::STATUS_CONFIRMED, 'confirmed_at' => now()]);
        $this->log($swapRequest, null, 'confirmed', $from, $swapRequest->status);

        $swapRequest->post->update(['status' => Post::STATUS_SWAPPED]);
        $swapRequest->requesterPost->update(['status' => Post::STATUS_SWAPPED]);

        Mail::to($swapRequest->postOwner->email)->send(new SwapConfirmed($swapRequest, $swapRequest->postOwner, $swapRequest->requester));
        Mail::to($swapRequest->requester->email)->send(new SwapConfirmed($swapRequest, $swapRequest->requester, $swapRequest->postOwner));
    }

    /**
     * @throws PaymentReservationFailedException
     */
    protected function reservePayment(SwapRequest $swapRequest, User $worker): SwapPayment
    {
        $amount = config('jobswap.swap_fee_cents');

        $payment = SwapPayment::create([
            'swap_request_id' => $swapRequest->id,
            'user_id' => $worker->id,
            'amount_cents' => $amount,
            'currency' => config('cashier.currency', 'eur'),
            'status' => SwapPayment::STATUS_PENDING,
        ]);

        try {
            $stripePayment = $worker->charge($amount, $worker->defaultPaymentMethod()->id, [
                'capture_method' => 'manual',
                'off_session' => true,
            ]);

            $payment->update([
                'stripe_payment_intent_id' => $stripePayment->id,
                'status' => SwapPayment::STATUS_RESERVED,
                'reserved_at' => now(),
            ]);

            $this->log($swapRequest, null, 'payment_reserved', null, null, ['user_id' => $worker->id]);

            return $payment;
        } catch (IncompletePayment|\Throwable $e) {
            $payment->update([
                'status' => SwapPayment::STATUS_FAILED,
                'failure_reason' => $e->getMessage(),
            ]);

            $this->log($swapRequest, null, 'payment_reservation_failed', null, null, ['user_id' => $worker->id, 'error' => $e->getMessage()]);

            throw new PaymentReservationFailedException("Could not reserve payment for user #{$worker->id}: {$e->getMessage()}");
        }
    }

    protected function capturePayment(SwapPayment $payment): void
    {
        if ($payment->status !== SwapPayment::STATUS_RESERVED) {
            return;
        }

        Cashier::stripe()->paymentIntents->capture($payment->stripe_payment_intent_id);

        $payment->update(['status' => SwapPayment::STATUS_CAPTURED, 'captured_at' => now()]);
        $this->log($payment->swapRequest, null, 'payment_captured', null, null, ['user_id' => $payment->user_id]);
    }

    protected function releaseAllPayments(SwapRequest $swapRequest): void
    {
        foreach ($swapRequest->payments as $payment) {
            $this->releasePayment($payment);
        }
    }

    protected function releasePayment(SwapPayment $payment): void
    {
        if (! in_array($payment->status, [SwapPayment::STATUS_RESERVED])) {
            return;
        }

        try {
            Cashier::stripe()->paymentIntents->cancel($payment->stripe_payment_intent_id);
        } catch (\Throwable $e) {
            Log::warning('Failed to cancel Stripe payment intent', ['payment_id' => $payment->id, 'error' => $e->getMessage()]);
        }

        $payment->update(['status' => SwapPayment::STATUS_RELEASED, 'released_at' => now()]);
        $this->log($payment->swapRequest, null, 'payment_released', null, null, ['user_id' => $payment->user_id]);
    }

    protected function log(SwapRequest $swapRequest, ?User $actor, string $event, ?string $from, ?string $to, array $meta = []): void
    {
        SwapActionLog::create([
            'swap_request_id' => $swapRequest->id,
            'actor_id' => $actor?->id,
            'event' => $event,
            'from_status' => $from,
            'to_status' => $to,
            'meta' => $meta,
        ]);
    }
}
