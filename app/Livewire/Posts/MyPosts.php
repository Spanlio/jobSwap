<?php

namespace App\Livewire\Posts;

use App\Models\Post;
use App\Models\PostLog;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class MyPosts extends Component
{
    public function remove(Post $post): void
    {
        abort_unless($post->user_id === Auth::id(), 403);

        $post->update(['status' => Post::STATUS_REMOVED]);
        $post->delete();

        PostLog::create([
            'post_id' => $post->id,
            'user_id' => $post->user_id,
            'actor_id' => Auth::id(),
            'event' => PostLog::EVENT_REMOVED_BY_OWNER,
        ]);
    }

    public function render()
    {
        return view('livewire.posts.my-posts', [
            'posts' => Auth::user()->posts()->latest()->get(),
        ]);
    }
}
