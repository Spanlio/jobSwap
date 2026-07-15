<?php

namespace App\Livewire\Chat;

use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

/**
 * Facebook-style floating chat dock, fixed to the bottom-right corner.
 * Lists existing conversations and opens a mini thread without leaving
 * the page. Persisted across wire:navigate page changes via @persist.
 */
class ChatDock extends Component
{
    public bool $open = false;

    public ?int $activeConversationId = null;

    public string $newMessage = '';

    public function toggle(): void
    {
        $this->open = ! $this->open;

        if (! $this->open) {
            $this->activeConversationId = null;
        }
    }

    public function openConversation(int $conversationId): void
    {
        $conversation = Conversation::findOrFail($conversationId);

        abort_unless(in_array(Auth::id(), $conversation->participants()), 403);

        $this->activeConversationId = $conversationId;
        $this->open = true;
    }

    public function closeConversation(): void
    {
        $this->activeConversationId = null;
    }

    public function send(): void
    {
        $this->validate(['newMessage' => 'required|string|max:2000']);

        $conversation = Conversation::findOrFail($this->activeConversationId);

        abort_unless(in_array(Auth::id(), $conversation->participants()), 403);

        Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => Auth::id(),
            'body' => $this->newMessage,
        ]);

        $conversation->update(['last_message_at' => now()]);
        $this->newMessage = '';
    }

    public function render()
    {
        $conversations = Conversation::query()
            ->where(function ($q) {
                $q->where('post_owner_id', Auth::id())
                    ->orWhere('initiator_id', Auth::id());
            })
            ->with(['post', 'postOwner', 'initiator'])
            ->latest('last_message_at')
            ->limit(8)
            ->get();

        $active = $this->activeConversationId
            ? $conversations->firstWhere('id', $this->activeConversationId)
            : null;

        return view('livewire.chat.chat-dock', [
            'conversations' => $conversations,
            'active' => $active,
            'activeMessages' => $active
                ? $active->messages()->with('sender')->latest()->limit(30)->get()->reverse()->values()
                : collect(),
        ]);
    }
}
