<div class="flex h-[32rem] flex-col rounded-lg border border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-800">
    <div class="flex items-center justify-between border-b border-gray-200 px-4 py-3 dark:border-gray-700">
        <p class="text-sm font-medium text-gray-700 dark:text-gray-200">
            {{ __('Chat with') }} {{ $otherParticipant->handle }}
        </p>

        @if ($activeSwap)
            <span class="inline-flex items-center rounded-full bg-indigo-100 px-2.5 py-0.5 text-xs font-medium text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200">
                {{ __('Swap requested') }}: {{ __(str($activeSwap->status)->headline()->toString()) }}
            </span>
        @elseif ($isInitiator)
            <button type="button" wire:click="toggleSwapPicker" class="inline-flex items-center rounded-md bg-gray-800 px-3 py-1.5 text-xs font-semibold uppercase tracking-widest text-white hover:bg-gray-700 dark:bg-gray-200 dark:text-gray-800">
                {{ __('Request swap') }}
            </button>
        @endif
    </div>

    @if ($showSwapPicker && ! $activeSwap)
        <div class="border-b border-gray-200 bg-gray-50 px-4 py-3 dark:border-gray-700 dark:bg-gray-900">
            <form wire:submit="requestSwap" class="flex flex-wrap items-end gap-3">
                <div class="grow">
                    <x-input-label for="requesterPostId" :value="__('Offer one of your posts in return')" />
                    <select wire:model="requesterPostId" id="requesterPostId" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                        <option value="">{{ __('Select a post…') }}</option>
                        @foreach ($myPosts as $myPost)
                            <option value="{{ $myPost->id }}">{{ $myPost->current_job_title }} &rarr; {{ $myPost->desired_job_title }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('requesterPostId')" class="mt-2" />
                </div>
                <x-primary-button>{{ __('Send request') }}</x-primary-button>
            </form>
        </div>
    @endif

    <div class="flex-1 space-y-3 overflow-y-auto px-4 py-4">
        @forelse ($messages as $message)
            <div @class(['flex', 'justify-end' => $message->sender_id === auth()->id()])>
                <div @class([
                    'max-w-xs rounded-lg px-3 py-2 text-sm sm:max-w-sm',
                    'bg-gray-800 text-white dark:bg-gray-200 dark:text-gray-900' => $message->sender_id === auth()->id(),
                    'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200' => $message->sender_id !== auth()->id(),
                ])>
                    {{ $message->body }}
                </div>
            </div>
        @empty
            <p class="text-center text-sm text-gray-400">{{ __('No messages yet.') }}</p>
        @endforelse
    </div>

    <form wire:submit="sendMessage" class="flex items-center gap-2 border-t border-gray-200 p-3 dark:border-gray-700">
        <input wire:model="newMessage" type="text" placeholder="{{ __('Write a message…') }}" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300" />
        <button type="submit" class="shrink-0 rounded-md bg-gray-800 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white hover:bg-gray-700 dark:bg-gray-200 dark:text-gray-800">
            {{ __('Send') }}
        </button>
    </form>
</div>
