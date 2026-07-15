<div>
    @guest
        <!-- Hero -->
        <section class="border-b border-zinc-200 bg-white dark:border-zinc-800 dark:bg-zinc-900">
            <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8 lg:py-20">
                <div class="max-w-2xl">
                    <p class="text-sm font-semibold uppercase tracking-widest text-brand-600">JobSwap.lv</p>
                    <h1 class="mt-3 text-4xl font-extrabold tracking-tight text-zinc-900 sm:text-5xl dark:text-zinc-50">
                        {{ __('Swap your job.') }}<br>{{ __('Keep your career.') }}
                    </h1>
                    <p class="mt-4 max-w-xl text-lg leading-relaxed text-zinc-500 dark:text-zinc-400">
                        {{ __('An anonymous marketplace where workers across Latvia trade jobs. Chat first, decide together — and nothing changes until both employers say yes.') }}
                    </p>
                    <div class="mt-8 flex flex-wrap items-center gap-4">
                        <a href="{{ route('register') }}" wire:navigate class="inline-flex items-center rounded-lg bg-brand-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-brand-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-600 focus-visible:ring-offset-2">
                            {{ __('Create a free account') }}
                        </a>
                        <span class="text-sm text-zinc-400 dark:text-zinc-500">{{ __('Posting is free — you only pay €5 when a swap is fully confirmed.') }}</span>
                    </div>
                </div>

                <!-- How it works -->
                <ol class="mt-12 grid grid-cols-1 gap-4 sm:grid-cols-3">
                    @foreach ([
                        ['n' => 1, 't' => __('Post anonymously'), 'd' => __('Only your job title, region and experience are public — never your name or employer.')],
                        ['n' => 2, 't' => __('Chat and agree'), 'd' => __('Message other workers freely. Request a swap only when you are both ready.')],
                        ['n' => 3, 't' => __('Employers approve'), 'd' => __('Both employers confirm by email. Only then is the €5 fee charged and details shared.')],
                    ] as $step)
                        <li class="flex gap-4 rounded-xl border border-zinc-200 bg-zinc-50 p-5 dark:border-zinc-700 dark:bg-zinc-800/50">
                            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-brand-600 text-sm font-bold text-white">{{ $step['n'] }}</span>
                            <div>
                                <p class="font-semibold text-zinc-900 dark:text-zinc-100">{{ $step['t'] }}</p>
                                <p class="mt-1 text-sm leading-relaxed text-zinc-500 dark:text-zinc-400">{{ $step['d'] }}</p>
                            </div>
                        </li>
                    @endforeach
                </ol>
            </div>
        </section>
    @endguest

    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold tracking-tight text-zinc-900 dark:text-zinc-100">{{ __('Open swap offers') }}</h2>
                <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">{{ trans_choice(':count offer|:count offers', $posts->total(), ['count' => $posts->total()]) }}</p>
            </div>
        </div>

        <!-- Filters -->
        <div class="mt-5 grid grid-cols-1 gap-4 rounded-xl border border-zinc-200 bg-white p-4 shadow-card sm:grid-cols-4 dark:border-zinc-800 dark:bg-zinc-900">
            <div>
                <x-input-label for="filter-job" :value="__('Job title')" />
                <x-text-input wire:model.live.debounce.400ms="job_title" id="filter-job" class="mt-1 block w-full" type="text" :placeholder="__('e.g. Electrician')" />
            </div>

            <div>
                <x-input-label for="filter-region" :value="__('Region')" />
                <x-select-input wire:model.live="region" id="filter-region" class="mt-1 block w-full">
                    <option value="">{{ __('All regions') }}</option>
                    @foreach ($regions as $value => $labels)
                        <option value="{{ $value }}">{{ $labels[app()->getLocale()] ?? $labels['en'] }}</option>
                    @endforeach
                </x-select-input>
            </div>

            <div>
                <x-input-label for="filter-licenses" :value="__('Licenses / certificates')" />
                <x-text-input wire:model.live.debounce.400ms="licenses" id="filter-licenses" class="mt-1 block w-full" type="text" :placeholder="__('e.g. B category')" />
            </div>

            <div class="flex items-end">
                @if ($region || $job_title || $licenses)
                    <button type="button" wire:click="resetFilters" class="inline-flex items-center gap-1 text-sm font-medium text-brand-700 hover:text-brand-800 dark:text-brand-400">
                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z"/></svg>
                        {{ __('Clear filters') }}
                    </button>
                @endif
            </div>
        </div>

        <!-- Post grid -->
        <div wire:loading.class="opacity-50" class="mt-6 grid grid-cols-1 gap-5 transition-opacity sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($posts as $post)
                <a href="{{ route('posts.show', $post) }}" wire:navigate class="group flex flex-col rounded-xl border border-zinc-200 bg-white p-5 shadow-card transition hover:-translate-y-0.5 hover:border-zinc-300 hover:shadow-card-hover focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-600 dark:border-zinc-800 dark:bg-zinc-900 dark:hover:border-zinc-600">
                    <p class="text-[11px] font-semibold uppercase tracking-widest text-zinc-400">{{ $post->user->handle }}</p>

                    <p class="mt-3 text-lg font-bold leading-snug text-zinc-900 group-hover:text-brand-700 dark:text-zinc-100 dark:group-hover:text-brand-400">{{ $post->current_job_title }}</p>
                    <p class="mt-1 flex items-center gap-1.5 text-sm text-zinc-500 dark:text-zinc-400">
                        <svg class="h-4 w-4 text-brand-500" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M2 10a.75.75 0 01.75-.75h12.59l-2.1-1.95a.75.75 0 111.02-1.1l3.5 3.25a.75.75 0 010 1.1l-3.5 3.25a.75.75 0 11-1.02-1.1l2.1-1.95H2.75A.75.75 0 012 10z" clip-rule="evenodd"/></svg>
                        {{ $post->desired_job_title }}
                    </p>

                    <div class="mt-4 flex flex-wrap gap-1.5 text-xs font-medium text-zinc-600 dark:text-zinc-300">
                        <span class="rounded-md bg-zinc-100 px-2 py-1 dark:bg-zinc-800">{{ $post->regionLabel() }}</span>
                        <span class="rounded-md bg-zinc-100 px-2 py-1 dark:bg-zinc-800">{{ $post->years_experience }} {{ __('yrs exp.') }}</span>
                        <span class="rounded-md bg-zinc-100 px-2 py-1 dark:bg-zinc-800">{{ $post->availabilityLabel() }}</span>
                    </div>

                    @if ($post->licenses)
                        <p class="mt-3 truncate text-xs text-zinc-400 dark:text-zinc-500">{{ $post->licenses }}</p>
                    @endif

                    <p class="mt-auto pt-4 text-xs text-zinc-400 dark:text-zinc-500">{{ __('Posted') }} {{ $post->created_at->diffForHumans() }}</p>
                </a>
            @empty
                <div class="col-span-full">
                    <x-empty-state :title="__('No posts match your filters yet.')" :description="__('Try a broader search, or check back soon — new offers appear every day.')">
                        @if ($region || $job_title || $licenses)
                            <x-secondary-button wire:click="resetFilters">{{ __('Clear filters') }}</x-secondary-button>
                        @endif
                    </x-empty-state>
                </div>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $posts->links() }}
        </div>
    </div>
</div>
