<div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
    <x-admin-nav />

    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-lg border border-gray-200 bg-white p-5 dark:border-gray-700 dark:bg-gray-800">
            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Revenue') }}</p>
            <p class="mt-2 text-3xl font-semibold text-gray-900 dark:text-gray-100">€{{ number_format($revenue, 2) }}</p>
        </div>
        <div class="rounded-lg border border-gray-200 bg-white p-5 dark:border-gray-700 dark:bg-gray-800">
            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Workers') }}</p>
            <p class="mt-2 text-3xl font-semibold text-gray-900 dark:text-gray-100">{{ $workerCount }}</p>
            <p class="mt-1 text-xs text-gray-400">{{ $bannedCount }} {{ __('banned') }}</p>
        </div>
        <div class="rounded-lg border border-gray-200 bg-white p-5 dark:border-gray-700 dark:bg-gray-800">
            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Active posts') }}</p>
            <p class="mt-2 text-3xl font-semibold text-gray-900 dark:text-gray-100">{{ $activePostCount }}</p>
            <p class="mt-1 text-xs text-gray-400">{{ $totalPostCount }} {{ __('total') }}</p>
        </div>
        <div class="rounded-lg border border-gray-200 bg-white p-5 dark:border-gray-700 dark:bg-gray-800">
            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Confirmed swaps') }}</p>
            <p class="mt-2 text-3xl font-semibold text-gray-900 dark:text-gray-100">{{ $swapCounts['confirmed'] ?? 0 }}</p>
        </div>
    </div>

    <div class="mt-8 rounded-lg border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-800">
        <h2 class="mb-4 text-sm font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">{{ __('Swap status overview') }}</h2>
        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-6">
            @foreach (['pending', 'awaiting_employers', 'confirmed', 'declined_by_worker', 'declined_by_employer', 'payment_failed', 'cancelled'] as $status)
                <div>
                    <x-swap-status-badge :status="$status" />
                    <p class="mt-2 text-xl font-semibold text-gray-800 dark:text-gray-200">{{ $swapCounts[$status] ?? 0 }}</p>
                </div>
            @endforeach
        </div>
    </div>
</div>
