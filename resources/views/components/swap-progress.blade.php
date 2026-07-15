@props(['swap', 'perspective' => 'owner'])

@php
    $failedStatuses = [
        'declined_by_worker' => ['step' => 2, 'label' => __('Declined by worker')],
        'declined_by_employer' => ['step' => 3, 'label' => __('Declined by employer')],
        'payment_failed' => ['step' => 3, 'label' => __('Payment failed')],
        'cancelled' => ['step' => 2, 'label' => __('Cancelled')],
    ];

    $failure = $failedStatuses[$swap->status] ?? null;

    $currentStep = match ($swap->status) {
        'pending' => 2,
        'awaiting_employers' => 3,
        'confirmed' => 5,
        default => $failure['step'] ?? 2,
    };

    $steps = [
        1 => __('Requested'),
        2 => __('Worker approval'),
        3 => __('Employers'),
        4 => __('Confirmed'),
    ];
@endphp

<div {{ $attributes->merge(['class' => 'space-y-3']) }}>
    <ol class="flex items-center gap-0">
        @foreach ($steps as $index => $label)
            @php
                $done = ! $failure && $currentStep > $index;
                $current = ! $failure && $currentStep === $index;
                $failedHere = $failure && $failure['step'] === $index;
            @endphp
            <li class="flex items-center {{ $index < count($steps) ? 'flex-1' : '' }}">
                <div class="flex flex-col items-center gap-1.5 sm:flex-row sm:gap-2">
                    <span @class([
                        'flex h-6 w-6 shrink-0 items-center justify-center rounded-full text-[11px] font-bold',
                        'bg-emerald-600 text-white' => $done,
                        'bg-ink text-white ring-4 ring-zinc-200 dark:bg-zinc-100 dark:text-zinc-900 dark:ring-zinc-700' => $current,
                        'bg-red-600 text-white' => $failedHere,
                        'border-2 border-zinc-300 bg-white text-zinc-400 dark:border-zinc-600 dark:bg-zinc-800' => ! $done && ! $current && ! $failedHere,
                    ])>
                        @if ($done)
                            <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd"/></svg>
                        @elseif ($failedHere)
                            <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor"><path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z"/></svg>
                        @else
                            {{ $index }}
                        @endif
                    </span>
                    <span @class([
                        'text-[11px] font-medium sm:text-xs',
                        'text-emerald-700 dark:text-emerald-400' => $done,
                        'text-zinc-900 dark:text-zinc-100' => $current,
                        'text-red-700 dark:text-red-400' => $failedHere,
                        'text-zinc-400 dark:text-zinc-500' => ! $done && ! $current && ! $failedHere,
                    ])>
                        {{ $failedHere ? $failure['label'] : $label }}
                    </span>
                </div>
                @if ($index < count($steps))
                    <div @class([
                        'mx-2 h-px flex-1 sm:mx-3',
                        'bg-emerald-500' => ! $failure && $currentStep > $index,
                        'bg-zinc-200 dark:bg-zinc-700' => $failure || $currentStep <= $index,
                    ])></div>
                @endif
            </li>
        @endforeach
    </ol>

    @if ($swap->status === 'awaiting_employers' && $swap->relationLoaded('employerApprovals') && $swap->employerApprovals->isNotEmpty())
        <div class="flex flex-wrap gap-2">
            @foreach ($swap->employerApprovals as $approval)
                @php
                    $isMine = ($perspective === 'owner') === ($approval->role === 'employer_a');
                @endphp
                <span @class([
                    'inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium',
                    'bg-emerald-50 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300' => $approval->status === 'approved',
                    'bg-zinc-100 text-zinc-600 dark:bg-zinc-800 dark:text-zinc-400' => $approval->status === 'pending',
                    'bg-red-50 text-red-800 dark:bg-red-950 dark:text-red-300' => $approval->status === 'declined',
                ])>
                    @if ($approval->status === 'approved')
                        <svg class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd"/></svg>
                    @elseif ($approval->status === 'pending')
                        <svg class="h-3 w-3 animate-pulse" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-13a.75.75 0 00-1.5 0v5c0 .414.336.75.75.75h4a.75.75 0 000-1.5h-3.25V5z" clip-rule="evenodd"/></svg>
                    @endif
                    {{ $isMine ? __('Your employer') : __('Their employer') }}:
                    {{ $approval->status === 'pending' ? __('awaiting reply') : __($approval->status === 'approved' ? 'approved' : 'declined') }}
                </span>
            @endforeach
        </div>
    @endif
</div>
