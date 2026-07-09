<div>
    <div class="mb-6 grid grid-cols-1 gap-4 rounded-lg border border-gray-200 bg-white p-4 sm:grid-cols-4 dark:border-gray-700 dark:bg-gray-800">
        <div>
            <x-input-label for="filter-job" :value="__('Job title')" />
            <x-text-input wire:model.live.debounce.400ms="job_title" id="filter-job" class="mt-1 block w-full" type="text" :placeholder="__('e.g. Electrician')" />
        </div>

        <div>
            <x-input-label for="filter-region" :value="__('Region')" />
            <select wire:model.live="region" id="filter-region" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                <option value="">{{ __('All regions') }}</option>
                @foreach ($regions as $value => $labels)
                    <option value="{{ $value }}">{{ $labels[app()->getLocale()] ?? $labels['en'] }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <x-input-label for="filter-licenses" :value="__('Licenses / certificates')" />
            <x-text-input wire:model.live.debounce.400ms="licenses" id="filter-licenses" class="mt-1 block w-full" type="text" :placeholder="__('e.g. B category')" />
        </div>

        <div class="flex items-end">
            <button type="button" wire:click="resetFilters" class="text-sm text-gray-500 underline hover:text-gray-800 dark:text-gray-400">
                {{ __('Clear filters') }}
            </button>
        </div>
    </div>

    <div wire:loading.class="opacity-50" class="grid grid-cols-1 gap-5 transition-opacity sm:grid-cols-2 lg:grid-cols-3">
        @forelse ($posts as $post)
            <a href="{{ route('posts.show', $post) }}" wire:navigate class="block rounded-lg border border-gray-200 bg-white p-5 transition hover:border-gray-300 hover:shadow-sm dark:border-gray-700 dark:bg-gray-800 dark:hover:border-gray-600">
                <p class="text-xs font-medium uppercase tracking-wide text-gray-400">{{ $post->user->handle }}</p>
                <p class="mt-2 font-semibold text-gray-900 dark:text-gray-100">{{ $post->current_job_title }}</p>
                <p class="mt-0.5 flex items-center gap-1 text-sm text-gray-500 dark:text-gray-400">
                    <span>&rarr;</span> {{ $post->desired_job_title }}
                </p>

                <div class="mt-4 flex flex-wrap gap-2 text-xs text-gray-500 dark:text-gray-400">
                    <span class="rounded-full bg-gray-100 px-2 py-1 dark:bg-gray-700">{{ $post->regionLabel() }}</span>
                    <span class="rounded-full bg-gray-100 px-2 py-1 dark:bg-gray-700">{{ $post->years_experience }} {{ __('yrs exp.') }}</span>
                    <span class="rounded-full bg-gray-100 px-2 py-1 dark:bg-gray-700">{{ $post->availabilityLabel() }}</span>
                </div>

                @if ($post->licenses)
                    <p class="mt-3 truncate text-xs text-gray-400">{{ $post->licenses }}</p>
                @endif
            </a>
        @empty
            <div class="col-span-full rounded-lg border border-dashed border-gray-300 p-12 text-center dark:border-gray-700">
                <p class="text-gray-500 dark:text-gray-400">{{ __('No posts match your filters yet.') }}</p>
            </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $posts->links() }}
    </div>
</div>
