<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class Users extends Component
{
    use WithPagination;

    public string $search = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function ban(User $user): void
    {
        abort_if($user->isAdmin(), 403);

        $user->update(['is_banned' => true, 'banned_at' => now()]);
    }

    public function unban(User $user): void
    {
        $user->update(['is_banned' => false, 'banned_at' => null]);
    }

    public function delete(User $user): void
    {
        abort_if($user->isAdmin(), 403);
        abort_if($user->id === Auth::id(), 403);

        $user->delete();
    }

    public function render()
    {
        $users = User::query()
            ->when($this->search, function ($q) {
                $q->where(function ($q) {
                    $q->where('email', 'like', "%{$this->search}%")
                        ->orWhere('handle', 'like', "%{$this->search}%")
                        ->orWhere('name', 'like', "%{$this->search}%");
                });
            })
            ->withCount('posts')
            ->latest()
            ->paginate(20);

        return view('livewire.admin.users', ['users' => $users]);
    }
}
