<div class="mx-auto max-w-4xl px-4 py-10 sm:px-6 lg:px-8">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-zinc-900 dark:text-zinc-100">{{ __('My posts') }}</h1>
            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">{{ __('Your anonymous swap offers. Posts expire after 30 days.') }}</p>
        </div>
        <a href="{{ route('posts.create') }}" wire:navigate class="inline-flex items-center gap-1.5 rounded-lg bg-brand-600 px-3.5 py-2 text-sm font-semibold text-white transition hover:bg-brand-700">
            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z"/></svg>
            {{ __('Post a swap') }}
        </a>
    </div>

    <div class="mt-8 space-y-4">
        @forelse ($posts as $post)
            <div class="flex flex-col gap-4 rounded-2xl border border-zinc-200 bg-white p-6 shadow-card sm:flex-row sm:items-center sm:justify-between dark:border-zinc-800 dark:bg-zinc-900">
                <div>
                    <div class="flex flex-wrap items-center gap-2">
                        <p class="font-bold text-zinc-900 dark:text-zinc-100">
                            {{ $post->current_job_title }} <span class="text-brand-500">→</span> {{ $post->desired_job_title }}
                        </p>
                        <span @class([
                            'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold',
                            'bg-emerald-50 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300' => $post->status === 'active',
                            'bg-blue-50 text-blue-700 dark:bg-blue-950 dark:text-blue-300' => $post->status === 'swapped',
                            'bg-zinc-100 text-zinc-600 dark:bg-zinc-800 dark:text-zinc-400' => in_array($post->status, ['expired', 'removed']),
                        ])>
                            {{ __(ucfirst($post->status)) }}
                        </span>
                    </div>
                    <p class="mt-1.5 text-sm text-zinc-500 dark:text-zinc-400">
                        {{ $post->regionLabel() }} &middot; {{ $post->years_experience }} {{ __('yrs exp.') }} &middot;
                        {{ __('Expires') }} {{ $post->expires_at->translatedFormat('j M Y') }}
                    </p>
                </div>

                <div class="flex shrink-0 items-center gap-4 text-sm font-medium">
                    <a href="{{ route('posts.show', $post) }}" wire:navigate class="text-zinc-500 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-zinc-100">{{ __('View') }}</a>
                    @if ($post->status === 'active')
                        <a href="{{ route('posts.edit', $post) }}" wire:navigate class="text-zinc-500 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-zinc-100">{{ __('Edit') }}</a>
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
            <x-empty-state :title="__('You haven\'t posted a swap yet.')" :description="__('Your post is anonymous — other workers only see your roles, region and experience.')">
                <a href="{{ route('posts.create') }}" wire:navigate class="inline-flex items-center rounded-lg bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-brand-700">
                    {{ __('Post a swap') }}
                </a>
            </x-empty-state>
        @endforelse
    </div>
</div>
