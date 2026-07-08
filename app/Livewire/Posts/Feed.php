<?php

namespace App\Livewire\Posts;

use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class Feed extends Component
{
    use WithPagination;

    #[Url]
    public string $region = '';

    #[Url]
    public string $job_title = '';

    #[Url]
    public string $licenses = '';

    public function updating($property): void
    {
        if (in_array($property, ['region', 'job_title', 'licenses'])) {
            $this->resetPage();
        }
    }

    public function resetFilters(): void
    {
        $this->reset('region', 'job_title', 'licenses');
    }

    public function render()
    {
        $posts = Post::query()
            ->active()
            ->when(Auth::check(), fn ($q) => $q->where('user_id', '!=', Auth::id()))
            ->when($this->region, fn ($q) => $q->where('region', $this->region))
            ->when($this->job_title, function ($q) {
                $q->where(function ($q) {
                    $q->where('current_job_title', 'like', "%{$this->job_title}%")
                        ->orWhere('desired_job_title', 'like', "%{$this->job_title}%");
                });
            })
            ->when($this->licenses, fn ($q) => $q->where('licenses', 'like', "%{$this->licenses}%"))
            ->latest()
            ->paginate(12);

        return view('livewire.posts.feed', [
            'posts' => $posts,
            'regions' => config('jobswap.regions'),
        ]);
    }
}
