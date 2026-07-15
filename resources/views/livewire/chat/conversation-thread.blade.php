<div class="flex h-[32rem] flex-col overflow-hidden rounded-2xl border border-zinc-200 bg-white shadow-card dark:border-zinc-800 dark:bg-zinc-900">
    <div class="flex items-center justify-between gap-2 border-b border-zinc-200 px-4 py-3 dark:border-zinc-800">
        <p class="text-sm font-semibold text-zinc-800 dark:text-zinc-200">
            {{ __('Chat with') }} {{ $otherParticipant->handle }}
        </p>

        @if ($activeSwap)
            <span class="inline-flex items-center gap-1.5 rounded-full bg-brand-50 px-2.5 py-1 text-xs font-semibold text-brand-800 dark:bg-brand-950 dark:text-brand-200">
                <svg class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-13a.75.75 0 00-1.5 0v5c0 .414.336.75.75.75h4a.75.75 0 000-1.5h-3.25V5z" clip-rule="evenodd"/></svg>
                {{ __(str($activeSwap->status)->headline()->toString()) }}
            </span>
        @elseif ($isInitiator)
            <button type="button" wire:click="toggleSwapPicker" class="inline-flex items-center gap-1.5 rounded-lg bg-brand-600 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-brand-700">
                {{ __('Request swap') }}
            </button>
        @endif
    </div>

    @if ($showSwapPicker && ! $activeSwap)
        <div class="border-b border-zinc-200 bg-zinc-50 px-4 py-3 dark:border-zinc-800 dark:bg-zinc-800/50">
            <form wire:submit="requestSwap" class="flex flex-wrap items-end gap-3">
                <div class="grow">
                    <x-input-label for="requesterPostId" :value="__('Offer one of your posts in return')" />
                    <x-select-input wire:model="requesterPostId" id="requesterPostId" class="mt-1 block w-full text-sm">
                        <option value="">{{ __('Select a post…') }}</option>
                        @foreach ($myPosts as $myPost)
                            <option value="{{ $myPost->id }}">{{ $myPost->current_job_title }} → {{ $myPost->desired_job_title }}</option>
                        @endforeach
                    </x-select-input>
                    <x-input-error :messages="$errors->get('requesterPostId')" class="mt-2" />
                </div>
                <x-primary-button>{{ __('Send request') }}</x-primary-button>
            </form>
            <p class="mt-2 text-xs text-zinc-400">{{ __('The other worker must approve before anything else happens.') }}</p>
        </div>
    @endif

    <div class="flex-1 space-y-3 overflow-y-auto bg-zinc-50/50 px-4 py-4 dark:bg-zinc-950/30">
        @forelse ($messages as $message)
            <div @class(['flex', 'justify-end' => $message->sender_id === auth()->id()])>
                <div @class([
                    'max-w-xs rounded-2xl px-3.5 py-2 text-sm leading-relaxed sm:max-w-sm',
                    'rounded-br-md bg-ink text-white dark:bg-zinc-100 dark:text-zinc-900' => $message->sender_id === auth()->id(),
                    'rounded-bl-md border border-zinc-200 bg-white text-zinc-800 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-200' => $message->sender_id !== auth()->id(),
                ])>
                    {{ $message->body }}
                </div>
            </div>
        @empty
            <p class="pt-8 text-center text-sm text-zinc-400">{{ __('No messages yet.') }}</p>
        @endforelse
    </div>

    <form wire:submit="sendMessage" class="flex items-center gap-2 border-t border-zinc-200 p-3 dark:border-zinc-800">
        <x-text-input wire:model="newMessage" type="text" :placeholder="__('Write a message…')" class="block w-full text-sm" />
        <button type="submit" class="shrink-0 rounded-lg bg-ink p-2.5 text-white transition hover:bg-zinc-700 dark:bg-zinc-100 dark:text-zinc-900" aria-label="{{ __('Send') }}">
            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path d="M3.105 2.288a.75.75 0 00-.826.95l1.414 4.926A1.5 1.5 0 005.135 9.25h6.115a.75.75 0 010 1.5H5.135a1.5 1.5 0 00-1.442 1.086l-1.414 4.926a.75.75 0 00.826.95 28.897 28.897 0 0015.293-7.155.75.75 0 000-1.114A28.897 28.897 0 003.105 2.288z"/></svg>
        </button>
    </form>
</div>
