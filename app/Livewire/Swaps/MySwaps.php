<?php

namespace App\Livewire\Swaps;

use App\Exceptions\PaymentMethodMissingException;
use App\Models\SwapRequest;
use App\Services\SwapFlowService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class MySwaps extends Component
{
    public ?string $paymentMethodError = null;

    public function approve(int $swapRequestId, SwapFlowService $service): void
    {
        $swapRequest = SwapRequest::findOrFail($swapRequestId);

        try {
            $service->workerApprove($swapRequest, Auth::user());
            $this->paymentMethodError = null;
        } catch (PaymentMethodMissingException $e) {
            $this->paymentMethodError = $e->user->id === Auth::id()
                ? __('Add a payment method before approving swaps.')
                : __('The other worker still needs to add a payment method. We\'ll notify you once they do.');
        }
    }

    public function decline(int $swapRequestId, SwapFlowService $service): void
    {
        $swapRequest = SwapRequest::findOrFail($swapRequestId);
        $service->workerDecline($swapRequest, Auth::user());
    }

    public function render()
    {
        $received = Auth::user()->swapRequestsReceived()
            ->with(['requester', 'requesterPost', 'post', 'employerApprovals'])
            ->latest()
            ->get();

        $made = Auth::user()->swapRequestsMade()
            ->with(['postOwner', 'requesterPost', 'post', 'employerApprovals'])
            ->latest()
            ->get();

        return view('livewire.swaps.my-swaps', [
            'received' => $received,
            'made' => $made,
            'hasPaymentMethod' => Auth::user()->hasDefaultPaymentMethod(),
        ]);
    }
}
