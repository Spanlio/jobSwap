<?php

namespace App\Livewire\Admin;

use App\Models\SwapRequest;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class Swaps extends Component
{
    use WithPagination;

    public string $status = '';

    public function updatingStatus(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $swaps = SwapRequest::query()
            ->with(['postOwner', 'requester', 'post', 'requesterPost', 'payments'])
            ->when($this->status, fn ($q) => $q->where('status', $this->status))
            ->latest()
            ->paginate(20);

        return view('livewire.admin.swaps', ['swaps' => $swaps]);
    }
}
