<?php

namespace App\Livewire\Admin;

use App\Models\PostLog;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class PostLogList extends Component
{
    use WithPagination;

    public function render()
    {
        $logs = PostLog::query()
            ->with(['post', 'user', 'actor'])
            ->latest()
            ->paginate(30);

        return view('livewire.admin.post-log-list', ['logs' => $logs]);
    }
}
