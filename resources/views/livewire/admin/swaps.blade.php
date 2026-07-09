<div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
    <x-admin-nav />

    <div class="mb-4">
        <select wire:model.live="status" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
            <option value="">{{ __('All statuses') }}</option>
            <option value="pending">{{ __('Pending') }}</option>
            <option value="awaiting_employers">{{ __('Awaiting employers') }}</option>
            <option value="confirmed">{{ __('Confirmed') }}</option>
            <option value="declined_by_worker">{{ __('Declined by worker') }}</option>
            <option value="declined_by_employer">{{ __('Declined by employer') }}</option>
            <option value="payment_failed">{{ __('Payment failed') }}</option>
            <option value="cancelled">{{ __('Cancelled') }}</option>
        </select>
    </div>

    <div class="overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-900">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-gray-500">#</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-gray-500">{{ __('Post owner') }}</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-gray-500">{{ __('Requester') }}</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-gray-500">{{ __('Status') }}</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-gray-500">{{ __('Payments') }}</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-gray-500">{{ __('Created') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-800">
                @foreach ($swaps as $swap)
                    <tr>
                        <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $swap->id }}</td>
                        <td class="px-4 py-3 text-sm font-medium text-gray-800 dark:text-gray-200">{{ $swap->postOwner->handle }}</td>
                        <td class="px-4 py-3 text-sm font-medium text-gray-800 dark:text-gray-200">{{ $swap->requester->handle }}</td>
                        <td class="px-4 py-3 text-sm"><x-swap-status-badge :status="$swap->status" /></td>
                        <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">
                            @foreach ($swap->payments as $payment)
                                <span class="mr-2">€{{ number_format($payment->amount_cents / 100, 2) }} ({{ $payment->status }})</span>
                            @endforeach
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $swap->created_at->diffForHumans() }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $swaps->links() }}</div>
</div>
