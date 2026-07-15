<div class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8">
    <h1 class="text-2xl font-bold tracking-tight text-zinc-900 dark:text-zinc-100">
        {{ __('Hi,') }} {{ auth()->user()->handle }}
    </h1>
    <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">{{ __('Here is where your swaps stand today.') }}</p>

    <!-- Needs your attention -->
    <section class="mt-8">
        <h2 class="text-lg font-bold text-zinc-800 dark:text-zinc-200">{{ __('Needs your attention') }}</h2>
        <div class="mt-4 space-y-3">
            @forelse ($pendingReceived as $swap)
                <div class="flex flex-wrap items-center justify-between gap-4 rounded-2xl border-l-4 border-brand-600 bg-white p-5 shadow-card dark:bg-zinc-900">
                    <div>
                        <p class="font-semibold text-zinc-900 dark:text-zinc-100">
                            {{ $swap->requester->handle }} {{ __('wants to swap with you') }}
                        </p>
                        <p class="mt-0.5 text-sm text-zinc-500 dark:text-zinc-400">
                            {{ $swap->requesterPost->current_job_title }} → {{ $swap->requesterPost->desired_job_title }} &middot; {{ $swap->created_at->diffForHumans() }}
                        </p>
                    </div>
                    <a href="{{ route('swaps.mine') }}" wire:navigate class="inline-flex items-center rounded-lg bg-ink px-4 py-2 text-sm font-semibold text-white transition hover:bg-zinc-700 dark:bg-zinc-100 dark:text-zinc-900">
                        {{ __('Review') }}
                    </a>
                </div>
            @empty
                <div class="rounded-2xl border border-zinc-200 bg-white p-5 text-sm text-zinc-500 shadow-card dark:border-zinc-800 dark:bg-zinc-900 dark:text-zinc-400">
                    {{ __("You're all caught up — no swap requests waiting on you.") }}
                    @if ($openSwapCount > 0)
                        {{ trans_choice('You have :count swap in progress.|You have :count swaps in progress.', $openSwapCount, ['count' => $openSwapCount]) }}
                        <a href="{{ route('swaps.mine') }}" wire:navigate class="font-semibold text-brand-700 hover:underline dark:text-brand-400">{{ __('Track it') }}</a>
                    @endif
                </div>
            @endforelse
        </div>
    </section>

    <div class="mt-10 grid grid-cols-1 gap-8 lg:grid-cols-2">
        <!-- Active posts -->
        <section>
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-bold text-zinc-800 dark:text-zinc-200">{{ __('Active posts') }}</h2>
                <a href="{{ route('posts.mine') }}" wire:navigate class="text-sm font-medium text-zinc-500 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-zinc-100">{{ __('View all') }}</a>
            </div>
            <div class="mt-4 space-y-3">
                @forelse ($activePosts->take(3) as $post)
                    <a href="{{ route('posts.show', $post) }}" wire:navigate class="block rounded-2xl border border-zinc-200 bg-white p-5 shadow-card transition hover:shadow-card-hover dark:border-zinc-800 dark:bg-zinc-900">
                        <p class="font-semibold text-zinc-900 dark:text-zinc-100">{{ $post->current_job_title }} <span class="text-brand-500">→</span> {{ $post->desired_job_title }}</p>
                        <p @class([
                            'mt-1 text-sm',
                            'text-amber-600 dark:text-amber-400 font-medium' => $post->expires_at->diffInDays() <= 5,
                            'text-zinc-500 dark:text-zinc-400' => $post->expires_at->diffInDays() > 5,
                        ])>
                            {{ __('Expires') }} {{ $post->expires_at->diffForHumans() }}
                        </p>
                    </a>
                @empty
                    <x-empty-state :title="__('No active posts.')" :description="__('Post your offer so matching workers can find you.')">
                        <a href="{{ route('posts.create') }}" wire:navigate class="inline-flex items-center rounded-lg bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-brand-700">{{ __('Post a swap') }}</a>
                    </x-empty-state>
                @endforelse
            </div>
        </section>

        <!-- Recent messages -->
        <section>
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-bold text-zinc-800 dark:text-zinc-200">{{ __('Recent messages') }}</h2>
                <a href="{{ route('messages.index') }}" wire:navigate class="text-sm font-medium text-zinc-500 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-zinc-100">{{ __('View all') }}</a>
            </div>
            <div class="mt-4 space-y-3">
                @forelse ($recentConversations as $conversation)
                    @php $other = $conversation->otherParticipant(auth()->user()); @endphp
                    <a href="{{ route('messages.show', $conversation) }}" wire:navigate class="flex items-center justify-between gap-3 rounded-2xl border border-zinc-200 bg-white p-5 shadow-card transition hover:shadow-card-hover dark:border-zinc-800 dark:bg-zinc-900">
                        <div>
                            <p class="font-semibold text-zinc-900 dark:text-zinc-100">{{ $other->handle }}</p>
                            <p class="mt-0.5 text-sm text-zinc-500 dark:text-zinc-400">{{ $conversation->post->current_job_title }}</p>
                        </div>
                        <p class="shrink-0 text-xs text-zinc-400">{{ $conversation->last_message_at?->diffForHumans() }}</p>
                    </a>
                @empty
                    <x-empty-state :title="__('No conversations yet.')" :description="__('Open any offer in the browse feed and send the first message.')" />
                @endforelse
            </div>
        </section>
    </div>
</div>
