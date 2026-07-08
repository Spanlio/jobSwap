<?php

namespace App\Livewire\Admin;

use App\Models\Post;
use App\Models\SwapPayment;
use App\Models\SwapRequest;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Dashboard extends Component
{
    public function render()
    {
        $revenueCents = SwapPayment::where('status', SwapPayment::STATUS_CAPTURED)->sum('amount_cents');

        return view('livewire.admin.dashboard', [
            'workerCount' => User::where('role', User::ROLE_WORKER)->count(),
            'bannedCount' => User::where('is_banned', true)->count(),
            'activePostCount' => Post::active()->count(),
            'totalPostCount' => Post::count(),
            'swapCounts' => SwapRequest::query()
                ->selectRaw('status, count(*) as total')
                ->groupBy('status')
                ->pluck('total', 'status'),
            'revenue' => $revenueCents / 100,
        ]);
    }
}
