<div class="mx-auto max-w-3xl px-4 py-10 sm:px-6 lg:px-8">
    <h1 class="text-2xl font-bold tracking-tight text-zinc-900 dark:text-zinc-100">{{ __('Messages') }}</h1>
    <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">{{ __('Chats stay open with no commitment — request a swap only when you are ready.') }}</p>

    <div class="mt-8 space-y-3">
        @forelse ($conversations as $conversation)
            @php $other = $conversation->otherParticipant(auth()->user()); @endphp
            <a href="{{ route('messages.show', $conversation) }}" wire:navigate class="flex items-center justify-between gap-4 rounded-2xl border border-zinc-200 bg-white p-5 shadow-card transition hover:-translate-y-0.5 hover:shadow-card-hover dark:border-zinc-800 dark:bg-zinc-900">
                <div class="flex items-center gap-4">
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-brand-50 text-sm font-bold text-brand-700 dark:bg-brand-950 dark:text-brand-300">
                        {{ mb_substr(str_replace('Worker #', '', $other->handle), 0, 2) }}
                    </span>
                    <div>
                        <p class="font-semibold text-zinc-900 dark:text-zinc-100">{{ $other->handle }}</p>
                        <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ $conversation->post->current_job_title }} → {{ $conversation->post->desired_job_title }}</p>
                    </div>
                </div>
                <p class="shrink-0 text-xs text-zinc-400">{{ $conversation->last_message_at?->diffForHumans() }}</p>
            </a>
        @empty
            <x-empty-state :title="__('No conversations yet.')" :description="__('Open any offer in the browse feed and send the first message.')">
                <a href="{{ route('home') }}" wire:navigate class="inline-flex items-center rounded-lg bg-ink px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-zinc-700 dark:bg-zinc-100 dark:text-zinc-900">{{ __('Browse offers') }}</a>
            </x-empty-state>
        @endforelse
    </div>
</div>
