<?php

namespace App\Livewire\Chat;

use App\Models\Conversation;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class ConversationList extends Component
{
    public function render()
    {
        $conversations = Conversation::query()
            ->where(function ($q) {
                $q->where('post_owner_id', Auth::id())
                    ->orWhere('initiator_id', Auth::id());
            })
            ->with(['post', 'postOwner', 'initiator'])
            ->latest('last_message_at')
            ->get();

        return view('livewire.chat.conversation-list', [
            'conversations' => $conversations,
        ]);
    }
}
