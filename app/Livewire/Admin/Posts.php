<?php

namespace App\Livewire\Admin;

use App\Models\Post;
use App\Models\PostLog;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class Posts extends Component
{
    use WithPagination;

    public string $status = '';

    public function updatingStatus(): void
    {
        $this->resetPage();
    }

    public function remove(Post $post): void
    {
        $post->update(['status' => Post::STATUS_REMOVED]);
        $post->delete();

        PostLog::create([
            'post_id' => $post->id,
            'user_id' => $post->user_id,
            'actor_id' => Auth::id(),
            'event' => PostLog::EVENT_REMOVED_BY_ADMIN,
        ]);
    }

    public function render()
    {
        $posts = Post::query()
            ->with('user')
            ->when($this->status, fn ($q) => $q->where('status', $this->status))
            ->latest()
            ->paginate(20);

        return view('livewire.admin.posts', ['posts' => $posts]);
    }
}
