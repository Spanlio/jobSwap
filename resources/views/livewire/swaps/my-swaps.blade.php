<div class="mx-auto max-w-4xl px-4 py-10 sm:px-6 lg:px-8">
    <h1 class="text-2xl font-bold tracking-tight text-zinc-900 dark:text-zinc-100">{{ __('My swaps') }}</h1>
    <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">{{ __('Approve, decline and follow every swap from request to confirmation.') }}</p>

    <div class="mt-8 space-y-10">
        @if (! $hasPaymentMethod)
            <div class="flex flex-wrap items-center justify-between gap-3 rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-800 dark:border-amber-900 dark:bg-amber-950 dark:text-amber-200">
                <p>{{ __('Add a payment method now so swaps you approve are not delayed.') }}</p>
                <a href="{{ route('payment-method.edit') }}" wire:navigate class="shrink-0 rounded-lg bg-amber-600 px-3 py-1.5 font-semibold text-white transition hover:bg-amber-700">{{ __('Add card') }}</a>
            </div>
        @endif

        @if ($paymentMethodError)
            <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700 dark:border-red-900 dark:bg-red-950 dark:text-red-300">
                {{ $paymentMethodError }}
            </div>
        @endif

        <section>
            <h2 class="mb-4 text-lg font-bold text-zinc-800 dark:text-zinc-200">{{ __('Requests received') }}</h2>
            <div class="space-y-4">
                @forelse ($received as $swap)
                    <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-card dark:border-zinc-800 dark:bg-zinc-900">
                        <div class="flex flex-wrap items-start justify-between gap-3">
                            <div>
                                <p class="font-bold text-zinc-900 dark:text-zinc-100">
                                    {{ $swap->requester->handle }} {{ __('wants to swap with you') }}
                                </p>
                                <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                                    {{ __('Their post') }}: {{ $swap->requesterPost->current_job_title }} → {{ $swap->requesterPost->desired_job_title }}
                                </p>
                            </div>
                            <p class="text-xs text-zinc-400">{{ $swap->created_at->diffForHumans() }}</p>
                        </div>

                        <div class="mt-5 border-t border-zinc-100 pt-5 dark:border-zinc-800">
                            <x-swap-progress :swap="$swap" perspective="owner" />
                        </div>

                        @if ($swap->status === 'pending')
                            <div class="mt-5 rounded-xl bg-zinc-50 p-4 dark:bg-zinc-800/50">
                                <p class="text-xs leading-relaxed text-zinc-500 dark:text-zinc-400">
                                    {{ __('When you approve, €5 is reserved on each worker\'s card (not charged) and both employers get an approval email. Money only moves if everyone says yes.') }}
                                </p>
                                <div class="mt-3 flex flex-wrap gap-3">
                                    <x-primary-button type="button" wire:click="approve({{ $swap->id }})">
                                        {{ __('Approve') }}
                                    </x-primary-button>
                                    <x-danger-button type="button" wire:click="decline({{ $swap->id }})" wire:confirm="{{ __('Decline this swap request?') }}">
                                        {{ __('Decline') }}
                                    </x-danger-button>
                                </div>
                            </div>
                        @elseif ($swap->status === 'confirmed')
                            <div class="mt-5 rounded-xl bg-emerald-50 p-4 text-sm text-emerald-800 dark:bg-emerald-950 dark:text-emerald-200">
                                <p class="font-semibold">{{ __('Swap confirmed! Here is how to reach them:') }}</p>
                                <p class="mt-1">{{ $swap->requester->email }}</p>
                            </div>
                        @endif
                    </div>
                @empty
                    <x-empty-state :title="__('No requests yet.')" :description="__('When another worker asks to swap with one of your posts, it appears here.')" />
                @endforelse
            </div>
        </section>

        <section>
            <h2 class="mb-4 text-lg font-bold text-zinc-800 dark:text-zinc-200">{{ __('Requests I made') }}</h2>
            <div class="space-y-4">
                @forelse ($made as $swap)
                    <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-card dark:border-zinc-800 dark:bg-zinc-900">
                        <div class="flex flex-wrap items-start justify-between gap-3">
                            <div>
                                <p class="font-bold text-zinc-900 dark:text-zinc-100">
                                    {{ __('Request to') }} {{ $swap->postOwner->handle }}
                                </p>
                                <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                                    {{ $swap->post->current_job_title }} → {{ $swap->post->desired_job_title }}
                                </p>
                            </div>
                            <p class="text-xs text-zinc-400">{{ $swap->created_at->diffForHumans() }}</p>
                        </div>

                        <div class="mt-5 border-t border-zinc-100 pt-5 dark:border-zinc-800">
                            <x-swap-progress :swap="$swap" perspective="requester" />
                        </div>

                        @if ($swap->status === 'confirmed')
                            <div class="mt-5 rounded-xl bg-emerald-50 p-4 text-sm text-emerald-800 dark:bg-emerald-950 dark:text-emerald-200">
                                <p class="font-semibold">{{ __('Swap confirmed! Here is how to reach them:') }}</p>
                                <p class="mt-1">{{ $swap->postOwner->email }}</p>
                            </div>
                        @endif
                    </div>
                @empty
                    <x-empty-state :title="__('You haven\'t requested a swap yet.')" :description="__('Find a matching offer in the browse feed, chat with the worker, then request the swap from the chat.')">
                        <a href="{{ route('home') }}" wire:navigate class="inline-flex items-center rounded-lg bg-ink px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-zinc-700 dark:bg-zinc-100 dark:text-zinc-900">{{ __('Browse offers') }}</a>
                    </x-empty-state>
                @endforelse
            </div>
        </section>
    </div>
</div>
