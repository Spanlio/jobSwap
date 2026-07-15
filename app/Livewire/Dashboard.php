<?php

namespace App\Livewire;

use App\Models\Conversation;
use App\Models\SwapRequest;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Dashboard extends Component
{
    public function render()
    {
        $user = Auth::user();

        return view('livewire.dashboard', [
            'pendingReceived' => $user->swapRequestsReceived()
                ->where('status', SwapRequest::STATUS_PENDING)
                ->with(['requester', 'requesterPost'])
                ->latest()
                ->get(),
            'activePosts' => $user->posts()->active()->latest()->get(),
            'recentConversations' => Conversation::query()
                ->where(fn ($q) => $q->where('post_owner_id', $user->id)->orWhere('initiator_id', $user->id))
                ->with(['post', 'postOwner', 'initiator'])
                ->latest('last_message_at')
                ->limit(3)
                ->get(),
            'openSwapCount' => $user->swapRequestsMade()
                ->whereIn('status', [SwapRequest::STATUS_PENDING, SwapRequest::STATUS_AWAITING_EMPLOYERS])
                ->count(),
        ]);
    }
}
