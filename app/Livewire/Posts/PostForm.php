<?php

namespace App\Livewire\Posts;

use App\Models\Post;
use App\Models\PostLog;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('layouts.app')]
class PostForm extends Component
{
    public ?Post $post = null;

    #[Validate('required|string|max:150')]
    public string $current_job_title = '';

    #[Validate('required|string|max:150')]
    public string $desired_job_title = '';

    #[Validate('nullable|string|max:1000')]
    public string $licenses = '';

    #[Validate('required|integer|min:0|max:60')]
    public ?int $years_experience = null;

    #[Validate('required|string')]
    public string $region = '';

    #[Validate('required|string')]
    public string $availability = '';

    #[Validate('required|email|max:255')]
    public string $employer_email = '';

    #[Validate('nullable|string|max:150')]
    public string $employer_name = '';

    public function mount(?Post $post = null): void
    {
        if ($post?->exists) {
            abort_unless($post->user_id === Auth::id(), 403);

            $this->post = $post;
            $this->current_job_title = $post->current_job_title;
            $this->desired_job_title = $post->desired_job_title;
            $this->licenses = (string) $post->licenses;
            $this->years_experience = $post->years_experience;
            $this->region = $post->region;
            $this->availability = $post->availability;
            $this->employer_email = $post->employer_email;
            $this->employer_name = (string) $post->employer_name;
        }
    }

    public function save(): void
    {
        $data = $this->validate();

        if ($this->post) {
            $this->post->update($data);

            PostLog::create([
                'post_id' => $this->post->id,
                'user_id' => $this->post->user_id,
                'actor_id' => Auth::id(),
                'event' => PostLog::EVENT_EDITED,
            ]);

            $this->redirect(route('posts.mine'), navigate: true);

            return;
        }

        $data['user_id'] = Auth::id();
        $data['status'] = Post::STATUS_ACTIVE;
        $data['expires_at'] = now()->addDays(config('jobswap.post_lifetime_days'));

        $post = Post::create($data);

        PostLog::create([
            'post_id' => $post->id,
            'user_id' => $post->user_id,
            'actor_id' => Auth::id(),
            'event' => PostLog::EVENT_CREATED,
        ]);

        $this->redirect(route('posts.mine'), navigate: true);
    }

    public function render()
    {
        return view('livewire.posts.post-form', [
            'regions' => config('jobswap.regions'),
            'availabilityOptions' => config('jobswap.availability'),
        ]);
    }
}
