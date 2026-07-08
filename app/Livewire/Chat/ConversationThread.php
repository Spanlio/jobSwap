<?php

namespace App\Livewire\Chat;

use App\Models\Conversation;
use App\Models\Message;
use App\Services\SwapFlowService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class ConversationThread extends Component
{
    public Conversation $conversation;

    public string $newMessage = '';

    public string $requesterPostId = '';

    public bool $showSwapPicker = false;

    public function mount(Conversation $conversation): void
    {
        abort_unless(in_array(Auth::id(), $conversation->participants()), 403);

        $this->conversation = $conversation;
    }

    public function sendMessage(): void
    {
        $this->validate(['newMessage' => 'required|string|max:2000']);

        Message::create([
            'conversation_id' => $this->conversation->id,
            'sender_id' => Auth::id(),
            'body' => $this->newMessage,
        ]);

        $this->conversation->update(['last_message_at' => now()]);
        $this->newMessage = '';
    }

    public function toggleSwapPicker(): void
    {
        $this->showSwapPicker = ! $this->showSwapPicker;
    }

    public function requestSwap(SwapFlowService $service): void
    {
        abort_unless(Auth::id() === $this->conversation->initiator_id, 403);

        $this->validate(['requesterPostId' => 'required|exists:posts,id']);

        $requesterPost = Auth::user()->posts()->active()->findOrFail($this->requesterPostId);

        $service->requestSwap($this->conversation->post, Auth::user(), $requesterPost, $this->conversation);

        $this->showSwapPicker = false;
    }

    #[On('swap-updated')]
    public function refresh(): void
    {
        //
    }

    public function render()
    {
        $activeSwap = $this->conversation->post
            ->swapRequestsReceived()
            ->where('requester_id', $this->conversation->initiator_id)
            ->whereIn('status', ['pending', 'awaiting_employers'])
            ->latest()
            ->first();

        return view('livewire.chat.conversation-thread', [
            'messages' => $this->conversation->messages()->with('sender')->orderBy('created_at')->get(),
            'otherParticipant' => $this->conversation->otherParticipant(Auth::user()),
            'isInitiator' => Auth::id() === $this->conversation->initiator_id,
            'activeSwap' => $activeSwap,
            'myPosts' => Auth::id() === $this->conversation->initiator_id
                ? Auth::user()->posts()->active()->get()
                : collect(),
        ]);
    }
}
