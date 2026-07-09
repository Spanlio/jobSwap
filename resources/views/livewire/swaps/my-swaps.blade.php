<div class="space-y-10">
    @if (! $hasPaymentMethod)
        <div class="rounded-lg border border-amber-200 bg-amber-50 p-4 text-sm text-amber-800 dark:border-amber-900 dark:bg-amber-950 dark:text-amber-200">
            {{ __('Add a payment method now so swaps you approve are not delayed.') }}
            <a href="{{ route('payment-method.edit') }}" wire:navigate class="ml-1 font-medium underline">{{ __('Add card') }}</a>
        </div>
    @endif

    @if ($paymentMethodError)
        <div class="rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700 dark:border-red-900 dark:bg-red-950 dark:text-red-300">
            {{ $paymentMethodError }}
        </div>
    @endif

    <section>
        <h2 class="mb-4 text-lg font-semibold text-gray-800 dark:text-gray-200">{{ __('Requests received') }}</h2>
        <div class="space-y-3">
            @forelse ($received as $swap)
                <div class="rounded-lg border border-gray-200 bg-white p-5 dark:border-gray-700 dark:bg-gray-800">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <div>
                            <p class="font-medium text-gray-800 dark:text-gray-200">
                                {{ $swap->requester->handle }} {{ __('wants to swap with you') }}
                            </p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ __('Their post') }}: {{ $swap->requesterPost->current_job_title }} &rarr; {{ $swap->requesterPost->desired_job_title }}
                            </p>
                        </div>
                        <x-swap-status-badge :status="$swap->status" />
                    </div>

                    @if ($swap->status === 'pending')
                        <div class="mt-4 flex gap-3">
                            <button type="button" wire:click="approve({{ $swap->id }})" class="rounded-md bg-gray-800 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white hover:bg-gray-700 dark:bg-gray-200 dark:text-gray-800">
                                {{ __('Approve') }}
                            </button>
                            <button type="button" wire:click="decline({{ $swap->id }})" wire:confirm="{{ __('Decline this swap request?') }}" class="rounded-md border border-gray-300 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">
                                {{ __('Decline') }}
                            </button>
                        </div>
                    @elseif ($swap->status === 'confirmed')
                        <p class="mt-4 rounded-md bg-green-50 p-3 text-sm text-green-800 dark:bg-green-950 dark:text-green-200">
                            {{ __('Swap confirmed! Contact') }}: {{ $swap->requester->email }}
                        </p>
                    @endif
                </div>
            @empty
                <p class="text-sm text-gray-400">{{ __('No requests yet.') }}</p>
            @endforelse
        </div>
    </section>

    <section>
        <h2 class="mb-4 text-lg font-semibold text-gray-800 dark:text-gray-200">{{ __('Requests I made') }}</h2>
        <div class="space-y-3">
            @forelse ($made as $swap)
                <div class="rounded-lg border border-gray-200 bg-white p-5 dark:border-gray-700 dark:bg-gray-800">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <div>
                            <p class="font-medium text-gray-800 dark:text-gray-200">
                                {{ __('Request to') }} {{ $swap->postOwner->handle }}
                            </p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $swap->post->current_job_title }} &rarr; {{ $swap->post->desired_job_title }}
                            </p>
                        </div>
                        <x-swap-status-badge :status="$swap->status" />
                    </div>

                    @if ($swap->status === 'confirmed')
                        <p class="mt-4 rounded-md bg-green-50 p-3 text-sm text-green-800 dark:bg-green-950 dark:text-green-200">
                            {{ __('Swap confirmed! Contact') }}: {{ $swap->postOwner->email }}
                        </p>
                    @endif
                </div>
            @empty
                <p class="text-sm text-gray-400">{{ __("You haven't requested a swap yet.") }}</p>
            @endforelse
        </div>
    </section>
</div>
