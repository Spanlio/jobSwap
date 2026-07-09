<div class="space-y-4">
    @forelse ($posts as $post)
        <div class="flex flex-col gap-3 rounded-lg border border-gray-200 bg-white p-5 sm:flex-row sm:items-center sm:justify-between dark:border-gray-700 dark:bg-gray-800">
            <div>
                <div class="flex items-center gap-2">
                    <p class="font-medium text-gray-900 dark:text-gray-100">
                        {{ $post->current_job_title }} <span class="text-gray-400">&rarr;</span> {{ $post->desired_job_title }}
                    </p>
                    <span @class([
                        'inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium',
                        'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' => $post->status === 'active',
                        'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' => $post->status === 'swapped',
                        'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' => in_array($post->status, ['expired', 'removed']),
                    ])>
                        {{ ucfirst($post->status) }}
                    </span>
                </div>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    {{ $post->regionLabel() }} &middot; {{ $post->years_experience }} {{ __('yrs exp.') }} &middot;
                    {{ __('Expires') }} {{ $post->expires_at->translatedFormat('j M Y') }}
                </p>
            </div>

            <div class="flex shrink-0 items-center gap-4 text-sm">
                <a href="{{ route('posts.show', $post) }}" wire:navigate class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-100">{{ __('View') }}</a>
                @if ($post->status === 'active')
                    <a href="{{ route('posts.edit', $post) }}" wire:navigate class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-100">{{ __('Edit') }}</a>
                    <button
                        type="button"
                        wire:click="remove({{ $post->id }})"
                        wire:confirm="{{ __('Remove this post? This cannot be undone.') }}"
                        class="text-red-600 hover:text-red-800 dark:text-red-400"
                    >
                        {{ __('Remove') }}
                    </button>
                @endif
            </div>
        </div>
    @empty
        <div class="rounded-lg border border-dashed border-gray-300 p-10 text-center dark:border-gray-700">
            <p class="text-gray-500 dark:text-gray-400">{{ __("You haven't posted a swap yet.") }}</p>
            <a href="{{ route('posts.create') }}" wire:navigate class="mt-4 inline-flex items-center rounded-md bg-gray-800 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white hover:bg-gray-700 dark:bg-gray-200 dark:text-gray-800">
                {{ __('Post a swap') }}
            </a>
        </div>
    @endforelse
</div>
