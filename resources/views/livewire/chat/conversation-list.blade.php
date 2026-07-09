<div class="space-y-3">
    @forelse ($conversations as $conversation)
        @php $other = $conversation->otherParticipant(auth()->user()); @endphp
        <a href="{{ route('messages.show', $conversation) }}" wire:navigate class="flex items-center justify-between rounded-lg border border-gray-200 bg-white p-4 hover:border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:hover:border-gray-600">
            <div>
                <p class="font-medium text-gray-800 dark:text-gray-200">{{ $other->handle }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $conversation->post->current_job_title }} &rarr; {{ $conversation->post->desired_job_title }}</p>
            </div>
            <p class="text-xs text-gray-400">{{ $conversation->last_message_at?->diffForHumans() }}</p>
        </a>
    @empty
        <div class="rounded-lg border border-dashed border-gray-300 p-10 text-center dark:border-gray-700">
            <p class="text-gray-500 dark:text-gray-400">{{ __('No conversations yet.') }}</p>
        </div>
    @endforelse
</div>
