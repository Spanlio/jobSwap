<?php

namespace App\Livewire\Admin;

use App\Models\SwapActionLog;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class ActionLog extends Component
{
    use WithPagination;

    public function render()
    {
        $logs = SwapActionLog::query()
            ->with(['swapRequest', 'actor'])
            ->latest()
            ->paginate(30);

        return view('livewire.admin.action-log', ['logs' => $logs]);
    }
}
