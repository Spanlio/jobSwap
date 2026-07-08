<?php

namespace App\Http\Controllers;

use App\Models\EmployerApproval;
use App\Services\SwapFlowService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmployerApprovalController extends Controller
{
    public function show(string $token): View
    {
        $approval = EmployerApproval::where('token', $token)->firstOrFail();

        $expired = $approval->notified_at
            && $approval->notified_at->addDays(config('jobswap.employer_link_lifetime_days'))->isPast();

        return view('employer.respond', [
            'approval' => $approval,
            'post' => $approval->post,
            'expired' => $expired,
        ]);
    }

    public function respond(Request $request, string $token, SwapFlowService $service): RedirectResponse
    {
        $approval = EmployerApproval::where('token', $token)->firstOrFail();

        $data = $request->validate([
            'decision' => 'required|in:approve,decline',
            'question' => 'nullable|string|max:2000',
        ]);

        $service->employerRespond($approval, $data['decision'] === 'approve', $data['question'] ?? null);

        return redirect()->route('employer.respond', $token)->with('status', 'submitted');
    }
}
