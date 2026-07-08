<?php

namespace App\Livewire\Posts;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Show extends Component
{
    public Post $post;

    public string $newMessage = '';

    public function mount(Post $post): void
    {
        $this->post = $post;
    }

    public function getConversationProperty(): ?Conversation
    {
        if (! Auth::check() || Auth::id() === $this->post->user_id) {
            return null;
        }

        return Conversation::where('post_id', $this->post->id)
            ->where('initiator_id', Auth::id())
            ->first();
    }

    public function startConversation(): void
    {
        $this->validate(['newMessage' => 'required|string|max:2000']);

        abort_if(Auth::id() === $this->post->user_id, 403);

        $conversation = Conversation::firstOrCreate([
            'post_id' => $this->post->id,
            'initiator_id' => Auth::id(),
        ], [
            'post_owner_id' => $this->post->user_id,
            'last_message_at' => now(),
        ]);

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
        return view('livewire.posts.show', [
            'conversation' => $this->conversation,
            'isOwner' => Auth::id() === $this->post->user_id,
        ]);
    }
}
