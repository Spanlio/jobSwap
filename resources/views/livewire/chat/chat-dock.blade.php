<div class="fixed bottom-4 right-4 z-50 flex flex-col items-end gap-3 sm:bottom-6 sm:right-6">
    @if ($open)
        @if ($active)
            <!-- Active mini conversation -->
            @php $other = $active->otherParticipant(auth()->user()); @endphp
            <div class="flex h-96 w-80 flex-col overflow-hidden rounded-2xl border border-zinc-200 bg-white shadow-2xl dark:border-zinc-700 dark:bg-zinc-900">
                <div class="flex items-center justify-between gap-2 border-b border-zinc-200 bg-zinc-50 px-3 py-2.5 dark:border-zinc-700 dark:bg-zinc-800">
                    <button type="button" wire:click="closeConversation" class="rounded p-1 text-zinc-400 transition hover:text-zinc-700 dark:hover:text-zinc-200" aria-label="{{ __('Back') }}">
                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 10a.75.75 0 01-.75.75H4.66l2.1 1.95a.75.75 0 11-1.02 1.1l-3.5-3.25a.75.75 0 010-1.1l3.5-3.25a.75.75 0 111.02 1.1l-2.1 1.95h12.59A.75.75 0 0118 10z" clip-rule="evenodd"/></svg>
                    </button>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-bold text-zinc-900 dark:text-zinc-100">{{ $other->handle }}</p>
                        <p class="truncate text-[11px] text-zinc-400">{{ $active->post->current_job_title }} → {{ $active->post->desired_job_title }}</p>
                    </div>
                    <a href="{{ route('messages.show', $active) }}" wire:navigate class="rounded p-1 text-zinc-400 transition hover:text-zinc-700 dark:hover:text-zinc-200" title="{{ __('Open full chat') }}">
                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.25 5.5a.75.75 0 00-.75.75v8.5c0 .414.336.75.75.75h8.5a.75.75 0 00.75-.75v-4a.75.75 0 011.5 0v4A2.25 2.25 0 0112.75 17h-8.5A2.25 2.25 0 012 14.75v-8.5A2.25 2.25 0 014.25 4h5a.75.75 0 010 1.5h-5z" clip-rule="evenodd"/><path fill-rule="evenodd" d="M6.194 12.753a.75.75 0 001.06.053L16.5 4.44v2.81a.75.75 0 001.5 0v-4.5a.75.75 0 00-.75-.75h-4.5a.75.75 0 000 1.5h2.553l-9.056 8.194a.75.75 0 00-.053 1.06z" clip-rule="evenodd"/></svg>
                    </a>
                    <button type="button" wire:click="toggle" class="rounded p-1 text-zinc-400 transition hover:text-zinc-700 dark:hover:text-zinc-200" aria-label="{{ __('Close') }}">
                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z"/></svg>
                    </button>
                </div>

                <div wire:poll.7s class="flex-1 space-y-2 overflow-y-auto bg-zinc-50/50 px-3 py-3 dark:bg-zinc-950/30"
                     x-data x-init="$el.scrollTop = $el.scrollHeight" x-effect="$el.scrollTop = $el.scrollHeight">
                    @forelse ($activeMessages as $message)
                        <div @class(['flex', 'justify-end' => $message->sender_id === auth()->id()])>
                            <div @class([
                                'max-w-[85%] rounded-2xl px-3 py-1.5 text-sm leading-relaxed',
                                'rounded-br-md bg-ink text-white dark:bg-zinc-100 dark:text-zinc-900' => $message->sender_id === auth()->id(),
                                'rounded-bl-md border border-zinc-200 bg-white text-zinc-800 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-200' => $message->sender_id !== auth()->id(),
                            ])>
                                {{ $message->body }}
                            </div>
                        </div>
                    @empty
                        <p class="pt-6 text-center text-xs text-zinc-400">{{ __('No messages yet.') }}</p>
                    @endforelse
                </div>

                <form wire:submit="send" class="flex items-center gap-2 border-t border-zinc-200 p-2 dark:border-zinc-700">
                    <x-text-input wire:model="newMessage" type="text" :placeholder="__('Write a message…')" class="block w-full !py-1.5 text-sm" autocomplete="off" />
                    <button type="submit" class="shrink-0 rounded-lg bg-brand-600 p-2 text-white transition hover:bg-brand-700" aria-label="{{ __('Send') }}">
                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path d="M3.105 2.288a.75.75 0 00-.826.95l1.414 4.926A1.5 1.5 0 005.135 9.25h6.115a.75.75 0 010 1.5H5.135a1.5 1.5 0 00-1.442 1.086l-1.414 4.926a.75.75 0 00.826.95 28.897 28.897 0 0015.293-7.155.75.75 0 000-1.114A28.897 28.897 0 003.105 2.288z"/></svg>
                    </button>
                </form>
            </div>
        @else
            <!-- Conversation list -->
            <div class="flex max-h-96 w-80 flex-col overflow-hidden rounded-2xl border border-zinc-200 bg-white shadow-2xl dark:border-zinc-700 dark:bg-zinc-900">
                <div class="flex items-center justify-between border-b border-zinc-200 bg-zinc-50 px-4 py-3 dark:border-zinc-700 dark:bg-zinc-800">
                    <p class="text-sm font-bold text-zinc-900 dark:text-zinc-100">{{ __('Messages') }}</p>
                    <button type="button" wire:click="toggle" class="rounded p-1 text-zinc-400 transition hover:text-zinc-700 dark:hover:text-zinc-200" aria-label="{{ __('Close') }}">
                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z"/></svg>
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto">
                    @forelse ($conversations as $conversation)
                        @php $other = $conversation->otherParticipant(auth()->user()); @endphp
                        <button type="button" wire:click="openConversation({{ $conversation->id }})" class="flex w-full items-center gap-3 px-4 py-3 text-left transition hover:bg-zinc-50 dark:hover:bg-zinc-800">
                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-brand-50 text-xs font-bold text-brand-700 dark:bg-brand-950 dark:text-brand-300">
                                {{ mb_substr(str_replace('Worker #', '', $other->handle), 0, 2) }}
                            </span>
                            <span class="min-w-0 flex-1">
                                <span class="block truncate text-sm font-semibold text-zinc-900 dark:text-zinc-100">{{ $other->handle }}</span>
                                <span class="block truncate text-xs text-zinc-400">{{ $conversation->post->current_job_title }}</span>
                            </span>
                            <span class="shrink-0 text-[11px] text-zinc-400">{{ $conversation->last_message_at?->shortAbsoluteDiffForHumans() }}</span>
                        </button>
                    @empty
                        <p class="px-4 py-8 text-center text-sm text-zinc-400">{{ __('No conversations yet.') }}</p>
                    @endforelse
                </div>
            </div>
        @endif
    @endif

    <!-- Launcher -->
    <button
        type="button"
        wire:click="toggle"
        class="flex items-center gap-2 rounded-full bg-ink px-4 py-3 text-sm font-semibold text-white shadow-2xl transition hover:bg-zinc-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-600 focus-visible:ring-offset-2 dark:bg-zinc-100 dark:text-zinc-900 dark:hover:bg-white"
        aria-label="{{ __('Messages') }}"
    >
        @if ($open)
            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M14.77 12.79a.75.75 0 01-1.06-.02L10 8.832 6.29 12.77a.75.75 0 11-1.08-1.04l4.25-4.5a.75.75 0 011.08 0l4.25 4.5a.75.75 0 01-.02 1.06z" clip-rule="evenodd" transform="rotate(180 10 10)"/></svg>
        @else
            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 2c-2.236 0-4.43.18-6.57.524C1.993 2.755 1 4.014 1 5.426v5.148c0 1.413.993 2.67 2.43 2.902.848.137 1.705.248 2.57.331v3.443a.75.75 0 001.28.53l3.58-3.579a.78.78 0 01.527-.224 41.202 41.202 0 005.183-.5c1.437-.232 2.43-1.49 2.43-2.903V5.426c0-1.413-.993-2.67-2.43-2.902A41.289 41.289 0 0010 2z" clip-rule="evenodd"/></svg>
        @endif
        {{ __('Messages') }}
    </button>
</div>
